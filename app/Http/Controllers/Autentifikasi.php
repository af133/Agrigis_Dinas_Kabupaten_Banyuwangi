<?php

namespace App\Http\Controllers;

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
    
    
  public function updateProfile(Request $request)
{
    $user = Akun::find(session('dataUser.id'));

    $request->validate([
        'name' => 'required|string|max:255',
        'password' => 'nullable|string|min:6',
        'nmr_telpon' => 'required|string|max:15',
        'path_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $user->nama = $request->name;
    $user->nmr_telpon = $request->nmr_telpon;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    if ($request->hasFile('path_img')) {
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
        ]);

        $uploadedFile = $request->file('path_img')->getRealPath();
        $uploadResult = $cloudinary->uploadApi()->upload($uploadedFile);

        $user->path_img = $uploadResult['secure_url'];
    }

    $user->save();

    session(['dataUser' => [
        'id' => $user->id,
        'nama' => $user->nama,
        'email' => $user->email,
        'password' => $user->password,
        'nmr_telpon' => $user->nmr_telpon,
        'path_img' => $user->path_img,
        'status' => $user->status->status,
    ]]);

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
    
   
}
