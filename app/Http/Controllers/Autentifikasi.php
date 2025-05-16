<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use App\Models\Akun;
use App\Models\StatusPekerjaan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Cloudinary\Cloudinary;
class Autentifikasi extends Controller
{
    public function login(Request $request){
        $users = Akun::where('email', $request->name)->first(); 

    if ($users && Hash::check($request->password, $users->password))
        {
            $dataUser=[
                'id' => $users->id,
                'nama'=>$users->nama,
                'email'=>$users->email,
                'password'=>$users->password,
                'nmr_telpon'=>$users->nmr_telpon,
                'path_img'=>$users->path_img,
                'status'=>$users->status->status,
            ];
            session(['dataUser'=>$dataUser]);
            return redirect()->route('mapping');
        } 
    
    else 
        {
            return back()->with('error','Email atau password salah');
        }

    }
    
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:akun,email',
            'password' => 'required|min:8',
            'status_id' => 'required|exists:status_pekerja,id', 
        ]);
    
        Akun::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status_id' => $request->status_id
            
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan!');
    }
    
    
  // Update Profile method with better error handling
public function updateProfile(Request $request)
{
    $userId = session('dataUser.id');
    $user = Akun::find($userId);

    if (!$user) {
        return redirect()->route('profile')->with('error', 'User tidak ditemukan.');
    }

    // Validasi input
    $request->validate([
        'name'        => 'nullable|string|max:255',
        'password'    => 'nullable|string|min:6',
        'nmr_telpon'  => 'nullable|string|max:15',
        'path_img'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validasi gambar ditambahkan
    ]);

    // Update nama jika diisi
    if ($request->filled('name')) {
        $user->nama = $request->name;
    }

    // Update nomor telepon jika diisi
    if ($request->filled('nmr_telpon')) {
        $user->nmr_telpon = $request->nmr_telpon;
    }

    // Update password jika diisi
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    // Upload gambar jika dikirim
    if ($request->hasFile('path_img')) {
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => 'ds62ywc1c',
                'api_key'    => '824819866697979',
                'api_secret' => 'mtRkUZYo8jJJ4h3-A5jbhsTa39A',
            ],
        ]);

        $file = $request->file('path_img')->getRealPath();

        try {
            $upload = $cloudinary->uploadApi()->upload($file, [
                'folder' => 'user_images' // opsional: simpan di folder khusus
            ]);
            $url = $upload['secure_url'] ?? null;
            $user->path_img = $url ?? $user->path_img;
        } catch (\Exception $e) {
            return redirect()->route('profile')->with('error', 'Upload gambar gagal: ' . $e->getMessage());
        }
    }

    // Simpan perubahan
    $user->save();

    // Perbarui data session
    session([
        'dataUser' => [
            'id'         => $user->id,
            'nama'       => $user->nama,
            'email'      => $user->email,
            'password'   => $user->password,
            'nmr_telpon' => $user->nmr_telpon,
            'path_img'   => $user->path_img,
            'status'     => $user->status->status ?? null,
        ]
    ]);

    return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui');
}

    public function Status(){
        $statusPekerjaan = StatusPekerjaan::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'status' => $item->status,
            ];
        });
    
        return view('add_edit_account.tambahAkun', [
            'statusPekerjaan' => $statusPekerjaan 
        ]);

    }
     public function logout(Request $request)
{
    Session::flush();

    return redirect()->route('login')->with('status', 'Berhasil logout.');
}

   
}
