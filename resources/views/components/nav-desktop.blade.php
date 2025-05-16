<aside class="w-66 h-[100vh] bg-[#DDFFAC] z-20 fixed flex-shrink-0 hidden rounded-tr-3xl md:block">
  <div class="h-16 flex items-center justify-center  border-b-[6px] border-[#F1FFDE] font-bold text-xl text-[#0D6106]">
      MENU
  </div>

  <div class="px-4 py-6 space-y-4">
    <div class="flex items-center space-x-3">
        <img src="{{ $img }}" alt="User" class="w-10 h-10 rounded-full" />

              <div>
                  <div class="block rounded-md px-3 py-2 text-black">{{ $name }}</div>

                  <div class="block rounded-md px-3 py-1 text-black">
                      @if ($status == 'Kepala Dinas')
                          {{ "Kepala Dinas" }}
                      @elseif ($status == 'Staf')
                          {{ "Staf Lapangan" }}
                      @elseif ($status == 'Admin')
                          {{ "Admin" }}
                      @endif
                  </div>
              </div>
    </div>
<x-nav-link href="{{ route('mapping') }}" :active="request()->is('mapping')">
    <div class="flex items-center space-x-2">
        <img src="{{ asset('icons/Location.png') }}" alt="Mapping Icon" class="w-5 h-5">
        <span>Mapping</span>
    </div>
</x-nav-link>
@if ($status == 'Kepala Dinas' || $status == 'Staf')
    <?php
    $laporan = $status == "Kepala Dinas" ? "Laporan" : "Riwayat Pekerjaan";
    ?>
    <x-nav-link :href="route('laporan', ['status' => $status])" :active="request()->is('laporan')">
        <div class="flex items-center space-x-2">
            <img src="{{ asset('icons/laporan.png') }}" alt="Laporan Icon" class="w-5 h-5">
            <span>{{ $laporan }}</span>
        </div>
    </x-nav-link>
@endif


@if ($status == "Admin")
    <x-nav-link href="{{ route('users.create') }}" :active="request()->is('tambahAkun')">
        <div class="flex items-center space-x-2">
            <img src="{{ asset('icons/Add.png') }}" alt="Tambah Akun Icon" class="w-5 h-5">
            <span>Tambah Akun</span>
        </div>
    </x-nav-link>
@endif

@if ($status == 'Kepala Dinas')
    <x-nav-link href="{{ route('notifications') }}" :active="request()->is('notifications')">
        <div class="flex items-center space-x-2">
            <img src="{{ asset('icons/Notification.png') }}" alt="Notifikasi Icon" class="w-5 h-5">
            <span>Notifikasi</span>
        </div>
    </x-nav-link>
@endif

<div class="mt-3 space-y-2 text-sm">
    <x-nav-link href="{{route('profile')}}" :active="request()->is('profile')">
        <div class="flex items-center space-x-2">
            <img src="{{ asset('icons/Profile.png') }}" alt="Profil Icon" class="w-5 h-5">
            <span>Akun Profil</span>
        </div>
    </x-nav-link>
<form method="POST" action="{{ route('logout') }}" id="logout-form">
    @csrf
    <button type="submit" class="w-full text-left">
        <x-nav-link :active="request()->is('logout')">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('icons/Logout.png') }}" alt="Keluar Icon" class="w-5 h-5">
                <span>Keluar</span>
            </div>
        </x-nav-link>
    </button>
</form>
    
</div>
  </div>
</aside>
