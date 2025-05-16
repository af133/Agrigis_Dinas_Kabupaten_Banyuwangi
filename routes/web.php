<?php

use App\Http\Controllers\Autentifikasi;
use App\Http\Controllers\Mapping;
use App\Http\Controllers\Report;
use App\Http\Controllers\Notification;
use App\Models\JenisLahan;
use Illuminate\Support\Facades\Route;

// Fitur
// 1. mapping
// 2. laporan/riwayat
// 3. tambah dan edit akun



// -------------------------------------------------------------------------------
// ----------------------------- AUTENTIFIKASI/LOGIIN ----------------------------
// -------------------------------------------------------------------------------

Route::get('/', function () {
    return view('auth.login');
})->name('login'); // tetap 'login' untuk form

Route::post('login',[Autentifikasi::class,'login'])->name('login.submit'); // ubah nama



// -------------------------------------------------------------------------------
// --------------------------------- MAPPING -------------------------------------
// -------------------------------------------------------------------------------


Route::get('mapping', function () {
    $jenisLahanList = JenisLahan::all();
    return view('mapping.mapping', ['jenisLahanList'=>$jenisLahanList]);
})->name('mapping');

Route::get('/mapping/data', [Mapping::class, 'showMapping']);
Route::post('/mapping', [Mapping::class, 'addaMapping'])->name('mapping.store');

// -------------------------------------------------------------------------------
// --------------------------------- REPOT ---------------------------------------
// -------------------------------------------------------------------------------

// Route::get('laporan', [Report::class,'showReport'])->name('laporan');
Route::get('laporan', [Report::class,'showReport'])->name('laporan');

Route::get('/goto-mapping', [Report::class, 'redirectToMapping'])->name('gotoMapping');


// -------------------------------------------------------------------------------
// --------------------------------- PROFILE -------------------------------------
// -------------------------------------------------------------------------------

Route::get('profile', function () {
    return view('add_edit_account.profile');
})->name('profile');
Route::get('tambahAkun', [Autentifikasi::class,'Status'])->name('users.create');
Route::post('tambahAkun', [Autentifikasi::class, 'store'])->name('userss.store');
Route::post('/profil/update', [Autentifikasi::class, 'updateProfile'])->name('profil.update');

// -------------------------------------------------------------------------------
// --------------------------------- Notification -------------------------------------
// -------------------------------------------------------------------------------

Route::get('notification', [Notification::class,'showNotification'])->name('notifications');

// -------------------------------------------------------------------------------
// --------------------------------- Logout -------------------------------------
// -------------------------------------------------------------------------------

Route::post('/logout', [Autentifikasi::class, 'logout'])->name('logout');




Route::get('/test-upload', function () {
    $cloudinary = new \Cloudinary\Cloudinary([
        'cloud' => [
            'cloud_name' => 'ds62ywc1c',
            'api_key'    => '824819866697979',
            'api_secret' => 'mtRkUZYo8jJJ4h3-A5jbhsTa39A',
        ],
    ]);

    try {
        $upload = $cloudinary->uploadApi()->upload('https://upload.wikimedia.org/wikipedia/commons/4/47/PNG_transparency_demonstration_1.png');
        dd($upload);
    } catch (\Exception $e) {
        dd('ERROR: ' . $e->getMessage());
    }
});
