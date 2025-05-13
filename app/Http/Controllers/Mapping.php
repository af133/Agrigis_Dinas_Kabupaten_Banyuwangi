<?php

namespace App\Http\Controllers;

use App\Models\LaporanMapping;
use App\Models\PemetaanLahan;
use App\Models\Tanaman;
use App\Models\Petani;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class Mapping extends Controller
{
    // --------------------------------------------------------------
    // -----------------------  Array Mapping  ----------------------
    // --------------------------------------------------------------
    
    public function arrayMapping($namaStaf,$namaPetani,$nik,$telpon,$alamat, $luasLahan, $lat, $lng, $jenisLahan, $namaTanaman,$waktuLaporan,$statusTanam)
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
        ];
    }
    
    

    
    // --------------------------------------------------------------
    // -----------------------  Show Mapping  -----------------------
    // --------------------------------------------------------------
    public function showMapping()
    {
        
        $latestIds = LaporanMapping::select(DB::raw('MAX(laporan_mapping.id) as id'))
        ->join('pemetaan_lahan', 'laporan_mapping.pemetaan_lahan_id', '=', 'pemetaan_lahan.id')
        ->groupBy('pemetaan_lahan.lat', 'pemetaan_lahan.lng')
        ->pluck('id');
        $mapping = LaporanMapping::with(['akun', 'petani', 'pemetaanLahan', 'pemetaanLahan.lahan'])
        ->whereIn('id', $latestIds)
        ->get();
        $result = [];
        foreach ($mapping as $item) {
            $result[] = $this->arrayMapping(
                $item->akun->nama,
                $item->petani->nama,
                $item->petani->nik,
                $item->petani->nmr_telpon,
                $item->pemetaanLahan->alamat,
                $item->pemetaanLahan->luas_lahan,
                $item->pemetaanLahan->lat,
                $item->pemetaanLahan->lng,
                $item->pemetaanLahan->lahan->jenis_lahan,
                $item->pemetaanLahan->tanaman->nama_tanaman,
                $item->waktu_laporan,
                $item->pemetaanLahan->status_tanam,
            );
        }
        
        return response()->json($result);
    }
   
    // --------------------------------------------------------------
    // -----------------------  Add Mapping  ------------------------
    // --------------------------------------------------------------
    
    public function addaMapping(Request $request){
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
        ]);
    
        $petani = Petani::updateOrCreate(
            ['nik' => $validated['nik']], 
            ['nmr_telpon' => $validated['nmr_telpon'], 'nama' => $validated['namaPetani']], // 
        );
        $tanaman = Tanaman::firstOrCreate(
            ['nama_tanaman' => $validated['namaTanaman']]
    );
    
        $pemetaanLahan = PemetaanLahan::create([
            'alamat' => $validated['alamat'],
            'luas_lahan' => $validated['luasLahan'],
            'lat' => $validated['lat'],
            'lng' => $validated['lng'],
            'jenis_lahan_id' => $validated['statusLahan'],
            'jenis_tanam_id' => $tanaman->id,
            'status_tanam' => $validated['statusPanen'],
        ]);
        
        LaporanMapping::create([
            
            'akun_id' => session('dataUser')['id'],
            'petani_id' => $petani->id,
            'pemetaan_lahan_id' => $pemetaanLahan->id,
            'waktu_laporan' => now(),
        ]);
        return redirect()->route('mapping');
        
    }
    
}
