@extends('layout.app')
@section('title','Tambah Akun')
@section('content')

<h1 class="px-10 pt-4 text-[#0E5509] font-bold text-[clamp(1.4rem,2vw,3rem)]">Tambah Akun</h1>
<div class="w-full mt-1 h-2 bg-[#DDFFAC] mb-2"></div>
<div class="flex justify-center ">
    <div class="bg-white w-[60%] shadow-lg rounded-lg p-8">
        
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    
    @else
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            {{ "Email udah ada, ganti email yang baru!" }}
        </div>

    @endif

    <form id="akunForm" action="{{ route('userss.store') }}" method="POST" class="space-y-5 ">
        @csrf

        <div>
            <label class="block text-sm text-gray-600 mb-1">Email</label>
            <input type="email" name="email" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400">
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Password</label>
            <input type="password" name="password" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400">
        </div>
            
        <div>
            <label class="block text-sm text-gray-600 mb-1">Status</label>
            <select name="status_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400">
               
            @foreach ($statusPekerjaan as $status )
                <option value="{{ $status['id'] }}">{{ $status['status'] }}</option>
            @endforeach

            </select>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="bg-gray-700 hover:bg-gray-800 text-white px-5 py-2 rounded-lg transition duration-200">
                Simpan
            </button>
        </div>
    </form>
</div>

</div>
<script>
    document.getElementById('akunForm').addEventListener('submit', function (e) {
        const confirmation = confirm('Apakah data yang ditambahkan sudah sesuai?');
        if (!confirmation) {
            e.preventDefault(); 
        }
    });
</script>

@endsection
