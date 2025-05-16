@extends('layout.app')

@section('content')
<div class="bg-transparent w-full ">
    <h1 class="px-10 pt-4 text-[#0E5509] font-bold text-[clamp(1.4rem,2vw,3rem)]">Profil</h1>
    <div class="w-full mt-1 h-2 bg-[#DDFFAC] mb-2"></div>

    @php
        $dataUser = session('dataUser') ?? [];
    @endphp

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-center w-full">
        <form id="akunForm" action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5 w-[60%] ">
            @csrf

            {{-- Foto Profil --}}
            <div class="flex flex-col items-center">
                <label for="path_img" class="relative cursor-pointer group">
                    <img src="{{ $dataUser['path_img'] ?? asset('default-profile.png') }}"
                         alt="Foto Profil"
                         class="w-40 h-40 sm:w-48 sm:h-48 rounded-full object-cover border-4 border-gray-300 shadow-xl transition duration-300 group-hover:opacity-80">
                    <input type="file" name="path_img" id="path_img"
                           class="absolute top-0 left-0 w-full h-full opacity-0 cursor-pointer rounded-full">
                </label>
                <p class="text-sm text-gray-500 mt-2">Klik gambar untuk mengganti foto</p>
            </div>

            {{-- Nama --}}
            <div>
                <label class="block text-sm text-gray-700 mb-1">Nama</label>
                <input type="text" name="name" id="name" readonly
                       class="editable w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-green-600"
                       style="background-color: #DDFFAC; border-color: #727272;"
                       value="{{ $dataUser['nama'] ?? '' }}">
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email" readonly
                       class="editable w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-green-600"
                       style="background-color: #DDFFAC; border-color: #727272;"
                       value="{{ $dataUser['email'] ?? '' }}">
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm text-gray-700 mb-1">Password</label>
              <input type="password" name="password" id="password" readonly
       class="editable w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-green-600"
       style="background-color: #DDFFAC; border-color: #727272;"
       placeholder="Isi jika ingin ganti password">
            </div>

            {{-- Nomor Telepon --}}
            <div>
                <label class="block text-sm text-gray-700 mb-1">Nomor Telepon</label>
                <input type="text" name="nmr_telpon" id="nmr_telpon" readonly
                       class="editable w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-green-600"
                       style="background-color: #DDFFAC; border-color: #727272;"
                       value="{{ $dataUser['nmr_telpon'] ?? '' }}">
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm text-gray-700 mb-1">Status</label>
                <input type="text" name="status" id="status" readonly
                       class="w-full px-4 py-2 rounded-lg bg-[#DDFFAC] text-gray-600 border border-gray-300 cursor-not-allowed"
                       value="{{ $dataUser['status'] ?? '' }}">
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex flex-wrap justify-end space-x-3 pt-4">
                <button type="button" id="editBtn"
                        class="bg-[#0E5509] hover:bg-green-800 text-white px-6 py-2 rounded-lg transition duration-200">
                    Edit
                </button>
                <button type="submit" id="saveBtn"
                        class="hidden bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition duration-200">
                    Save
                </button>
                <button type="button" id="cancelBtn"
                        class="hidden bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition duration-200">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Script --}}
<script>
    const formInputs = ['name', 'path_img', 'password', 'nmr_telpon'];
    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const form = document.getElementById('akunForm');

    const originalData = {};
    formInputs.forEach(id => {
        originalData[id] = document.getElementById(id)?.value || '';
    });

    editBtn.addEventListener('click', () => {
        formInputs.forEach(id => {
            const input = document.getElementById(id);
            if (input) input.removeAttribute('readonly');
        });
        editBtn.classList.add('hidden');
        saveBtn.classList.remove('hidden');
        cancelBtn.classList.remove('hidden');
    });

    cancelBtn.addEventListener('click', () => {
        formInputs.forEach(id => {
            const input = document.getElementById(id);
            if (input) {
                input.setAttribute('readonly', true);
                input.value = originalData[id];
            }
        });
        editBtn.classList.remove('hidden');
        saveBtn.classList.add('hidden');
        cancelBtn.classList.add('hidden');
    });

    form.addEventListener('submit', function (e) {
        const confirmation = confirm('Apakah data yang diubah sudah sesuai?');
        if (!confirmation) {
            e.preventDefault();
            return;
        }

        setTimeout(() => {
            window.location.href = "{{ route('profile') }}";
        }, 500);
    });
</script>
@endsection
