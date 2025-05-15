@extends('layout.app')
@section('title','Login')
@section('content')

<div
  class="h-[10rem] bg-cover bg-center"
  style="background-image: url('icons/tani 1 (3).png');"
></div>


<div class="text-[#021D00] flex justify-center m-[1rem]">
    <img src="https://github.com/af133/Agrigis_Dinas_Kabupaten_Banyuwangi/blob/5b7c2d4296f3d0ab280b5ae11f549bcf6758bc48/public/icons/tani%201%20(3).png" alt="#">
    <h1 class="font-bold text-[clamp(1.2rem,2.3vw,3.3rem)]">Banyuwangi AgriGIS</h1>
</div>


<div class="flex justify-center w-full items-center">
    <section class="bg-[#DDFFAC] w-[clamp(16rem,50vw,23rem)] rounded-2xl shadow-xl flex justify-center  p-5 text-[#0E5509]">
        <form action="login" method="post" class="flex flex-col w-full">
        @csrf
        <p class="text-center font-bold text-[4dvh] mb-4">Masuk</p>
        <label for="email" class="mb-1">Email</label>
        <input type="text" name="name" id="email" class="mb-3 border rounded px-2 py-1 " required>
        
        <label for="password" class="mb-1">Password</label>
        <input type="password" name="password" id="password" class="mb-3 border rounded px-2 py-1" required>

        <button type="submit" class="bg-[#0E5509] hover:bg-[#3d6d39] text-white font-bold rounded px-3 py-1 transition duration-300">
            Submit
        </button>
        @if (session('error'))
        <div class="bg-[#fd8e8e] text-white p-1 rounded mt-4">
            {{ session('error') }}
        </div>
        @endif
    </form>
</div>


@endsection
