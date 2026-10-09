<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Geografis Wilayah Kalimantan Tengah</title>
    <link rel="icon" type="image/png" href="{{ asset('img/icon.png') }}" alt="Logo">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
   
    <style>
         body {
          font-family: Arial, sans-serif;
          margin: 20px;
        }
        #map {
          height: 60vh; 
          width: 100%;  
          min-height: 300px; 
        }

        .text-justify {
          text-align: justify;
        }

        /*  perangkat besar */
        @media (min-width: 992px) {
          #map {
            height: 75vh; 
          }
        }

        /* perangkat layar kecil (ponsel) */
        @media (max-width: 768px) {
          #map {
            height: 50vh;
          }

          h2#title {
            font-size: 1.5rem; 
          }
        }
        .legend {
            background: white;
            padding: 10px;
            line-height: 1.4;
        }
        .legend i {
            width: 20px;
            height: 20px;
            float: left;
            margin-right: 8px;
            opacity: 0.7;
        }
        #title, #sub { text-align: center; }
        nav ul li a {
        text-decoration: none;
        color: #007bff;
        }

        nav ul li a:hover {
            text-decoration: underline;
            color: #0056b3;
        }

        .text {
          text-align: justify;
        }
        .card {
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
          transition: transform 0.2s ease;
        }
        .card:hover {
          transform: scale(1.05);
        }
        .card-img-top {
          height: 400px;
          object-fit: cover;
        }
    </style>

    <!-- JS Leaflet -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

</head>
<body>
<nav class="navbar navbar-expand-lg bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="/kalteng">
      <img src="{{ asset('img/LOGO_kalteng.png') }}" alt="Logo" width="50" height="24" class="d-inline-block align-text-top">
      KALTENG
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
      <ul class="navbar-nav me-4 mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/kalteng">Home</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Peta Tematik
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/produktivitas">Produktivitas</a></li>
            <li><a class="dropdown-item" href="/luasPanen">Luas Panen</a></li>
            <li><a class="dropdown-item" href="/padi">Produksi Padi</a></li>
            <li><a class="dropdown-item" href="/beras">Produksi Beras</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/Analisis">Analisis</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/interaktif">Luas Wilayah</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<h2 id="title" class="mt-3 text-center">PETA GEOGRAFIS KALIMANTAN TENGAH</h2>
<div class="container">
  <div class="row">
    <div class="col-lg-6 col-md-12">
      <div id="map" class="mt-4"></div>
      <script>
        var map = L.map('map').setView([-1.0088123, 113.3351748], 6.7);
        var tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 18,
          attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        }).addTo(map);

        console.log('Fetching GeoJSON from:', '/kalteng.json');

        // Fetch GeoJSON Data
        fetch('/kalteng.json')
          .then((response) => {
            console.log('Response from server:', response);
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.json();
          })
          .then((data) => {
            console.log('GeoJSON Data:', data);
            L.geoJSON(data).addTo(map);
          })
          .catch((error) => {
            console.error('Error loading the GeoJSON file:', error);
          });

        // resize peta
        window.addEventListener('resize', () => {
          map.invalidateSize();
        });
      </script>
    </div>

    <div class="col-lg-6 col-md-12 mt-4 lh-1">
      <h2>Deskripsi Singkat Peta Tematik Provinsi Kalimantan Tengah</h2>
      <p class="text-justify">
        Peta tematik ini menampilkan informasi mengenai Luas Panen, Produksi, dan Produktivitas Padi serta Produksi Padi 
        dan Beras di setiap kabupaten/kota di Provinsi Kalimantan Tengah. Data non-spasial yang digunakan diperoleh dari sumber resmi, 
        seperti Badan Pusat Statistik (BPS), dan dirancang untuk memberikan gambaran visual yang informatif tentang sektor pertanian 
        di wilayah tersebut.
        <br><br>
        Peta ini menyoroti distribusi luas panen, jumlah hasil produksi padi, serta produktivitas per hektar di berbagai wilayah. 
        Selain itu, peta juga menunjukkan perbandingan produksi padi dan beras dalam ton di masing-masing kabupaten/kota. Informasi ini 
        divisualisasikan dengan warna dan simbol tematik.
        <br><br>
        Peta ini bertujuan untuk memudahkan analisis mengenai potensi pertanian di Kalimantan Tengah, mengidentifikasi wilayah dengan hasil 
        produksi yang unggul, serta memahami pola distribusi produktivitas pertanian di tingkat kabupaten/kota. Peta ini juga berguna untuk 
        perencanaan pembangunan sektor pertanian, pengambilan keputusan kebijakan pangan, dan mendukung program ketahanan pangan daerah.
        <br><br>
        Peta interaktif ini dibuat menggunakan pustaka JavaScript Leaflet untuk menampilkan informasi yang mudah diakses dan ramah pengguna.
      </p>
    </div>
  </div>
</div>
<div class="container">
  <h1 class="text-center mt-5">OUR TEAM</h1>
  <div class="row mt-4 mb-5 justify-content-center">
    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
      <div class="card">
        <img src="{{ asset('img/Pisal.jpg') }}" class="card-img-top" alt="Paisal Patmal Jaelani">
        <div class="card-body">
          <p class="card-text text-center">
            <b>Paisal Patmal Jaelani</b>
          </p>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
      <div class="card">
        <img src="{{ asset('img/Dims.jpg') }}" class="card-img-top" alt="Dimas Anggoro Harahap">
        <div class="card-body">
          <p class="card-text text-center">
            <b>Dimas Anggoro Harahap</b>
          </p>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
      <div class="card">
        <img src="{{ asset('img/Reva.jpg') }}" class="card-img-top" alt="Reva Diaz">
        <div class="card-body">
          <p class="card-text text-center">
            <b>Reva Diaz</b>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

        <!-- Botstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
