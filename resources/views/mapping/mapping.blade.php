@extends('layout.app')
@section('title','Mapping')

@section('content')
@php
$dataUser=session('dataUser')
@endphp
        <h1 class="px-10 pt-4 text-[#0E5509] font-bold text-[clamp(1.4rem,2vw,3rem)]">Mapping</h1>
        <div class="w-full mt-1 h-2 bg-[#DDFFAC]"></div>

        <!-- Tombol tambah -->
        @if ($dataUser['status']=='Staf')
        
        <button id="addBtn" class="z-2 absolute top-25 cursor-pointer right-10 bg-[#DDFFAC] text-[#554009] p-2 rounded-lg shadow-lg hover:bg-[#eaffcd] transition duration-200">
            Tambah Titik
        </button>
        @else
        <div id="addBtn"></div>
        @endif
        <div id="map" class="mt-4 lg:m-4 m-0 rounded-2xl w-ful h-113 z-1 border-2 border-[#0E5509]"></div>
  <!-- Form Input -->

  <form id="formBox" action="{{ route('mapping.store') }}" method="POST"
      class="z-5 hidden absolute top-23 ml-10 bg-[#F1FFDE] lg:text-[1rem] text-[0.6rem] p-2 border rounded-lg w-fit">
    @csrf
    <table class="table-auto text-left w-full">

        {{-- Petani --}}
        <tr class="border-b border-gray-500">
            <td><label for="namaPetani" class="p-1">Nama Petani</label></td>
            <td>:</td>
            <td><input type="text" name="namaPetani" id="namaPetani" class="ml-2  px-2 py-1 rounded" required></td>
        </tr>

        {{-- Nomor Telepon --}}
        <tr class="border-b border-gray-500">
            <td><label for="nmr_telpon" class="p-1">Nomor Telepon</label></td>
            <td>:</td>
            <td><input type="text" name="nmr_telpon" id="nmr_telpon" class="ml-2 px-2 py-1 rounded" required></td>
        </tr>

        {{-- NIK --}}
        <tr class="border-b border-gray-500">
            <td><label for="nik" class="p-1">NIK</label></td>
            <td>:</td>
            <td><input type="text" name="nik" id="nik" class="ml-2 px-2 py-1 rounded" required></td>
        </tr>


        {{-- Alamat --}}
        <tr class="border-b border-gray-500">
            <td><label for="alamat" class="p-1">Alamat</label></td>
            <td>:</td>
            <td><input type="text" name="alamat" id="alamat" class="ml-2 px-2 py-1 rounded" required></td>
        </tr>

        {{-- Jenis Tanaman --}}

        <tr class="border-b border-gray-500">
            <td><label for="namaTanaman" class="p-1">Nama Tanaman</label></td>
            <td>:</td>
            <td><input type="text" name="namaTanaman" id="nama_tanaman" class="ml-2 px-2 py-1 rounded" required></td>
        </tr>

        {{-- Luas Lahan --}}
        <tr class="border-b border-gray-500">
            <td><label for="luasLahan" class="p-1">Luas Lahan</label></td>
            <td>:</td>
            <td><input type="number" name="luasLahan" id="luas_lahan" class="ml-2 px-2 py-1 rounded" required></td>
        </tr>


        {{-- Latitude --}}
        <tr class="border-b border-gray-500">
            <td><label for="lat" class="p-1">Latitude</label></td>
            <td>:</td>
            <td><input type="number" name="lat" id="lat" step="any" class="ml-2 px-2 py-1 rounded" required></td>
        </tr>

        {{-- Longitude --}}
        <tr class="border-b border-gray-500">
            <td><label for="lng" class="p-1">Longitude</label></td>
            <td>:</td>
            <td><input type="number" name="lng" id="lng" step="any" class="ml-2 px-2 py-1 rounded" required></td>
        </tr>

        {{-- Jenis Lahan --}}
        <tr class="border-b border-gray-500">
            <td><label for="statusLahan" class="p-1">Status Lahan</label></td>
            <td>:</td>
            <td>
                <select name="statusLahan" id="jenis_lahan_id" class="ml-2 w-full px-2 py-1 rounded" required>
                    <option disabled selected>Pilih Status</option>
                    @foreach($jenisLahanList as $lahan)
                        <option value="{{$lahan->id}}">{{$lahan->jenis_lahan}}</option>
                    @endforeach
                </select>
            </td>
        </tr>


        {{-- Status Tanam --}}
        <tr class="border-b border-gray-500">
            <td><label for="statusPanen" class="p-1">Status Tanam</label></td>
            <td>:</td>
            <td>
                <select name="statusPanen" id="status_tanam" class="ml-2 w-full px-2 py-1 rounded" required>
                    <option disabled selected>Pilih Status</option>
                    <option value="Tanam">Tanam</option>
                    <option value="Panen">Panen</option>
                </select>
            </td>
        </tr>
    </table>

    <div class="mt-4 text-right flex">
        <div class="flex-1 p-2 justify-start flex">
            <button type="submit" id="submitButton" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Simpan</button>
        </div>
        <div class="flex-1 p-2 justify-start">
            <button id="closeBtn"  onclick="closeForm()" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Batal</button>

        </div>
    </div>
</form>


<script>
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("formBox").style.display = 'none';
});
function closeForm() {
    document.getElementById("formBox").style.display = 'none';
}
    

let map = L.map('map').setView([-2.5, 118], 5);
let markerPreview = null;

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '© OpenStreetMap'
}).addTo(map);

document.getElementById("addBtn").onclick = () => {
  document.getElementById("formBox").style.display = "block";
    
};

if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(function(position) {
    const lat = position.coords.latitude;
    const lng = position.coords.longitude;

    document.getElementById("lat").value = lat;
    document.getElementById("lng").value = lng;

    L.marker([lat, lng]).addTo(map)
      .bindPopup("Lokasi Anda Sekarang")
      .openPopup();

    map.setView([lat, lng], 13);
  }, function(error) {
    console.error("Geolocation Error: ", error);
    alert("Tidak dapat mengakses lokasi Anda. Pastikan Anda memberikan izin lokasi pada browser.");
  });
}
document.getElementById("submitButton").addEventListener("click", function() {
    simpan();
});
function simpan() {
    //------------- Nama Petani ------------------- 
    const namaPetani = document.getElementById("namaPetani").value;

    //------------- Nama Tanaman ------------------- 
    const namaTanaman = document.getElementById("nama_tanaman").value;

    //------------- Alamat ------------------- 
    const alamat = document.getElementById("alamat").value;

    //------------- Luas Lahan ------------------- 
    const luasLahan = document.getElementById("luasLahan").value;

    //------------- latitude ------------------- 
    const lat = parseFloat(document.getElementById("lat").value);

    //------------- longitude ------------------- 
    const lng = parseFloat(document.getElementById("lng").value);

    //------------- Status Lahan ------------------- 
    const statusLahan = document.getElementById("statusLahan").value;

    //------------- Status Panen ------------------- 
    const statusPanen = document.getElementById("statusPanen").value;

    //------------- Nomor Telepon ------------------- 
    const nmr_telpon = document.getElementById("nmr_telpon").value;

    //------------- NIK ------------------- 
    const nik = document.getElementById("nik").value;

    // Validasi input
    if (!namaPetani || !namaTanaman || !alamat || !luasLahan || isNaN(lat) || isNaN(lng) || !nmr_telpon || !nik) {
        alert("Lengkapi data!");
        return;
    }

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/mapping', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({
            namaPetani, 
            namaTanaman, 
            luasLahan, 
            statusLahan, 
            alamat, 
            lat, 
            lng, 
            statusPanen, 
            nmr_telpon, 
            nik 
        })
    })
    .then(response => response.json()) 
    .then(data => {
        if (data.success) {
            alert('Data berhasil disimpan');
        } else {
           
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan dalam pengiriman data');
    });
}

function tampilkanMarkerBaru(loc) {
    const marker = L.marker([loc.lat, loc.lng]).addTo(map);

const popupContent = `
  <div class="bg-lime-100 p-4 rounded-lg text-sm font-sans text-black w-[270px]">
    <table class="w-full text-left border-collapse">
      <tbody>
        <tr class="border-b border-black">
          <td class="font-medium w-28 py-1">Nama Petani</td>
          <td class="py-1">:</td>
          <td class="py-1">${loc.namaPetani}</td>
        </tr>
        <tr class="border-b border-black">
          <td class="font-medium py-1">Nama Tanaman</td>
          <td class="py-1">:</td>
          <td class="py-1">${loc.nama_tanaman}</td>
        </tr>
        <tr class="border-b border-black">
          <td class="font-medium py-1">Alamat</td>
          <td class="py-1">:</td>
          <td class="py-1">${loc.alamat}</td>
        </tr>
        <tr class="border-b border-black">
          <td class="font-medium py-1">Luas Lahan</td>
          <td class="py-1">:</td>
          <td class="py-1">${loc.luas_lahan}</td>
        </tr>
        <tr class="border-b border-black">
          <td class="font-medium py-1">Latitude</td>
          <td class="py-1">:</td>
          <td class="py-1">${loc.lat}</td>
        </tr>
        <tr class="border-b border-black">
          <td class="font-medium py-1">Longitude</td>
          <td class="py-1">:</td>
          <td class="py-1">${loc.lng}</td>
        </tr>
        <tr class="border-b border-black">
          <td class="font-medium py-1">Status Lahan</td>
          <td class="py-1">:</td>
          <td class="py-1">${loc.jenis_lahan}</td>
        </tr>
        <tr class="border-b border-black">
          <td class="font-medium py-1">Status Panen</td>
          <td class="py-1">:</td>
          <td class="py-1">${loc.status_tanam}</td>
        </tr>
      </tbody>
    </table>

    ${
      userStatus === 'Staf'
        ? `<button id="editBtn-${loc.id}" class="mt-4 bg-green-900 text-white px-4 py-2 rounded hover:bg-green-800 w-full">
             Edit Data
           </button>`
        : ''
    }
  </div>
`;



    marker.bindPopup(popupContent);

    marker.on('popupopen', function () {
        const editButton = document.getElementById(`editBtn-${loc.id}`);
        if (editButton) {
            editButton.onclick = () => editData(loc);
        }
    });

    loc.marker = marker;
}


function editData(loc) {
    document.getElementById("formBox").style.display = "block";
    document.getElementById("namaPetani").value = loc.namaPetani;
    document.getElementById("nama_tanaman").value = loc.nama_tanaman;
    document.getElementById("alamat").value = loc.alamat;
    document.getElementById("lat").value = loc.lat;
    document.getElementById("lng").value = loc.lng;
    document.getElementById("luas_lahan").value = loc.luas_lahan;
    document.getElementById("status_tanam").value = loc.status_tanam;
    document.getElementById("nmr_telpon").value = loc.telpon;
    document.getElementById("nik").value = loc.nik; 

    const select = document.getElementById("jenis_lahan_id");
    const targetText = loc.jenis_lahan;
    for (let i = 0; i < select.options.length; i++) {
        if (select.options[i].text === targetText) {
            select.selectedIndex = i;
            break;
        }
    }

    document.getElementById("submitButton").onclick = () => simpanEdit(loc.id);
}


  
function simpanEdit(loc) {
  const nama = document.getElementById("nama").value;
  const jenis = document.getElementById("jenis").value;
  const waktu = document.getElementById("waktu").value;
  const alamat = document.getElementById("alamat").value;
  const lat = parseFloat(document.getElementById("lat").value);
  const lng = parseFloat(document.getElementById("lng").value);

  if (!nama || isNaN(lat) || isNaN(lng)) {
    alert("Lengkapi data!");
    return;
  }

  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  fetch(`/mapping/${loc.id}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': token
    },
    body: JSON.stringify({ nama, jenis, waktu, alamat, lat, lng })
  })
  .then(res => res.json())
  .then(data => {
    console.log("Berhasil edit:", data);
    batal(); 
    updateMarker(loc, data);
  })
  .catch(err => {
    console.error("Gagal simpan perubahan:", err);
    alert("Gagal menyimpan perubahan.");
  });
}
function updateMarker(loc, updatedData) {
    // Hapus marker lama
    if (loc.marker) {
        map.removeLayer(loc.marker);
    }

    // Buat marker baru
    const marker = L.marker([updatedData.lat, updatedData.lng]).addTo(map)
        .bindPopup(`
            <b>${updatedData.namaPetani}</b><br>
            Jenis Tanaman: ${updatedData.namaTanaman}<br>
            Status Lahan: ${updatedData.statusLahan}<br>
            Status Panen: ${updatedData.statusPanen}<br>
            Luas Lahan: ${updatedData.luasLahan} m²<br>
            Alamat: ${updatedData.alamat}<br>
            Nomor Telepon: ${updatedData.nmr_telpon}<br>
            NIK: ${updatedData.nik}<br>
            Waktu: ${updatedData.waktu || '-'}<br>
            <button class="bg-yellow-500 text-white p-2 rounded-lg hover:bg-yellow-600 mt-2" onclick='editData(${JSON.stringify(updatedData)})'>Edit</button>
        `);

    loc.marker = marker;
}
const userStatus = "<?php echo $dataUser['status']; ?>";
map.on('click', function(e) {
if (userStatus !== 'Staf') {
    alert("Hanya staf yang dapat menambahkan data.");
    return;
  }
  const { lat, lng } = e.latlng;
  document.getElementById("lat").value = lat;
  document.getElementById("lng").value = lng;
  tampilkanMarkerPreview(lat, lng);
  document.getElementById("formBox").style.display = "block";
});

function tampilkanMarkerPreview(lat, lng) {
  if (markerPreview) map.removeLayer(markerPreview);
  markerPreview = L.marker([lat, lng], { draggable: true }).addTo(map);
  map.setView([lat, lng], 13);
  markerPreview.on('dragend', function(e) {
    const pos = e.target.getLatLng();
    document.getElementById("lat").value = pos.lat;
    document.getElementById("lng").value = pos.lng;
  });
}

fetch('/mapping/data')
  .then(res => res.json())
  .then(data => {
    data.forEach(loc => {
      tampilkanMarkerBaru(loc);
    });
  })
  .catch(err => {
    console.error("Gagal mengambil data:", err);
    alert("Gagal memuat data.");
  });

  
  document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const lat = parseFloat(urlParams.get('lat'));
    const lng = parseFloat(urlParams.get('lng'));

    if (lat && lng) {
        map.setView([lat, lng], 15); 

        L.marker([lat, lng]).addTo(map)
            .bindPopup("Lokasi yang dicari")
            .openPopup();
    } 
});
</script>
<script>
    
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const lat = urlParams.get('lat');
    const lng = urlParams.get('lng');

    if (lat && lng) {
        // Fokuskan ke marker atau tambahkan marker
        map.setView([lat, lng], 100);

        L.circle([lat, lng], {
            radius: 10,
            color: 'red'
        }).addTo(map).bindPopup("Lokasi yang dicari").openPopup();
    }
});
</script>
@endsection
