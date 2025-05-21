<?php

namespace App\Http\Controllers;

use App\Models\LaporanMapping;
use App\Models\PemetaanLahan;
use App\Models\PoligonKomoditas;
use App\Models\Tanaman;
use App\Models\Petani;
use App\Models\TitikKomoditas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class Mapping extends Controller
{
    // --------------------------------------------------------------
    // -----------------------  Array Mapping  ----------------------
    // --------------------------------------------------------------
    
    public function arrayMapping($namaStaf,$namaPetani,$nik,$telpon,$alamat, $luasLahan, $lat, $lng, $jenisLahan, $namaTanaman,$waktuLaporan,$statusTanam,$koordinatPoligon = null)
    {
        return [
            'namaStaf'=>$namaStaf,
            'namaPetani'=>$namaPetani,
            'nik'=>$nik,
            'telpon'=>$telpon,
            'alamat' => $alamat,
            'luas_lahan' => $luasLahan,
            'lat' => $lat,
            'lng' => $lng,
            'jenis_lahan' => $jenisLahan,
            'nama_tanaman' => $namaTanaman,
            'waktu_laporan' => $waktuLaporan,
            'status_tanam' => $statusTanam,
            'koordinat_poligon' => $koordinatPoligon, 
        ];
    }
    
    

    
    // --------------------------------------------------------------
    // -----------------------  Show Mapping  -----------------------
    // --------------------------------------------------------------
    public function showMapping()
    {
        
       $latestIds = LaporanMapping::select(DB::raw('MAX(laporan_mapping.id) as id'))->join('pemetaan_lahan', 'laporan_mapping.pemetaan_lahan_id', '=', 'pemetaan_lahan.id')->join('titik_komoditas', 'pemetaan_lahan.id_titik_komoditas', '=', 'titik_komoditas.id')->groupBy('titik_komoditas.lat', 'titik_komoditas.lng')->pluck('id');

        $mapping = LaporanMapping::with([
            'akun',
            'petani',
            'pemetaanLahan',
            'pemetaanLahan.lahan',
            'pemetaanLahan.poligon',
            'pemetaanLahan.titik'
        ])->whereIn('id', $latestIds)->get();
        $result = [];

        foreach ($mapping as $item) {
            $result[] = $this->arrayMapping(
                $item->akun->nama,
                $item->petani->nama,
                $item->petani->nik,
                $item->petani->nmr_telpon,
                $item->pemetaanLahan->alamat,
                $item->pemetaanLahan->luas_lahan,
                $item->pemetaanLahan->titik->lat ?? null,
                $item->pemetaanLahan->titik->lng ?? null,
                $item->pemetaanLahan->lahan->jenis_lahan ?? null,
                $item->pemetaanLahan->tanaman->nama_tanaman ?? null,
                $item->waktu_laporan,
                $item->pemetaanLahan->status_tanam,
            );
        }

        return response()->json($result);
    }
   
    // --------------------------------------------------------------
    // -----------------------  Add Mapping  ------------------------
    // --------------------------------------------------------------
    
public function addaMapping(Request $request, $id = null)
{
    $validated = $request->validate([
        'namaPetani' => 'required|string',
        'alamat' => 'required|string',
        'luasLahan' => 'required|numeric',
        'lat' => 'required|numeric',
        'lng' => 'required|numeric',
        'statusLahan' => 'required|string',
        'namaTanaman' => 'required|string',
        'statusPanen' => 'required|string',
        'nmr_telpon' => 'required|string',
        'nik' => 'required|string',
        'koordinatPoligon' => 'nullable|array',
    ]);

    
    if ($id) {
        $pemetaanLahan = PemetaanLahan::findOrFail($id);
    } else {
        $pemetaanLahan = new PemetaanLahan;
    }

    $petani = Petani::updateOrCreate(
        ['nik' => $validated['nik']],
        ['nmr_telpon' => $validated['nmr_telpon'], 'nama' => $validated['namaPetani']]
    );
    $titikKomoditas = TitikKomoditas::updateOrCreate(
            ['id' => $pemetaanLahan->id_titik_komoditas],
            ['lat' => $validated['lat'], 'lng' => $validated['lng']]
    );
    if (!empty($validated['koordinatPoligon'])) {
        if ($pemetaanLahan->id_poligon_komoditas) {
            $poligon = PoligonKomoditas::find($pemetaanLahan->id_poligon_komoditas);
            if ($poligon) {
                $poligon->koordinat_poligon = json_encode($validated['koordinatPoligon']);
                $poligon->save();
            } else {
                $poligon = PoligonKomoditas::create([
                    'koordinat_poligon' => json_encode($validated['koordinatPoligon']),
                ]);
            }
        } else {
            $poligon = PoligonKomoditas::create([
                'koordinat_poligon' => json_encode($validated['koordinatPoligon']),
            ]);
        }
    } else {
        $poligon = null;
    }
    $tanaman = Tanaman::firstOrCreate(
        ['nama_tanaman' => $validated['namaTanaman']]
    );
    $pemetaanLahan = PemetaanLahan::create([
            'alamat' => $validated['alamat'],
            'luas_lahan' => $validated['luasLahan'],
            'id_titik_komoditas'=>$titikKomoditas?->id,
            'id_poligon_komoditas'=>$poligon?->id,
            'jenis_lahan_id' => $validated['statusLahan'],
            'jenis_tanam_id' => $tanaman->id,
            'status_tanam' => $validated['statusPanen'],
    ]); 
    if (!$id) {
        LaporanMapping::create([
            'akun_id' => session('dataUser')['id'],
            'petani_id' => $petani->id,
            'pemetaan_lahan_id' => $pemetaanLahan->id,
            'waktu_laporan' => now(),
        ]);
    }

    return redirect()->route('mapping')->with('success', $id ? 'Data berhasil diupdate' : 'Data berhasil ditambahkan');
}
}
