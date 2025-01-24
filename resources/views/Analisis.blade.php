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
        #chartContainer {
            width: 100%;
            height: 400px;
        }
    </style>

    <!-- JS Leaflet -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


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
          <a class="nav-link" aria-current="page" href="/kalteng">Home</a>
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
          <a class="nav-link active" href="/Analisis">Analisis</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/interaktif">Luas Wilayah</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="container mt-4">
    <h3 class="text-center">Perbandingan Antar Kabupaten/Kota</h3>
    <p class="text-center">Berikut adalah grafik perbandingan antar kabupaten/kota di Provinsi Kalimantan Tengah tahun 2024.</p>

    <div id="chartContainer">
        <canvas id="produksiPadiChart"></canvas>
    </div>
    <div id="chartContainer">
        <canvas id="luasPanenChart"></canvas>
    </div>
    <div id="chartContainer">
        <canvas id="produksiBerasChart"></canvas>
    </div>
    <div id="chartContainer">
        <canvas id="produktivitasChart"></canvas>
    </div>
</div>

<script>

fetch('/DataKalteng.geojson')
    .then(response => response.json())
    .then(data => {

        var kabupatenData = [];
        var produksiPadi = [];
        var produksiBeras = [];
        var luasPanen = [];
        var produktif = [];

        data.features.forEach(function(feature) {
            kabupatenData.push(feature.properties.name);
            produksiPadi.push(feature.properties.produksi_padiTon);
            produksiBeras.push(feature.properties.produksi_berasTon);
            luasPanen.push(feature.properties.luas_panen_ha);
            produktif.push(feature.properties.produktivitas_ku_ha);
        });

        // Grafik Produksi Padi
        var ctx = document.getElementById('produksiPadiChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: kabupatenData,
                datasets: [{
                    label: 'Produksi Padi (ton)',
                    data: produksiPadi,
                    backgroundColor: '#FFD700',
                    borderColor: '#B8860B',
                    borderWidth: 1
                }]
            }
        });

        // Grafik Luas Panen
        var ctx = document.getElementById('luasPanenChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: kabupatenData,
                datasets: [{
                    label: 'Luas Panen (Ha)',
                    data: luasPanen,
                    backgroundColor: '#FF6347',
                    borderColor: '#FF4500',
                    borderWidth: 1
                }]
            }
        });

        // Grafik Produksi Beras
        var ctx = document.getElementById('produksiBerasChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: kabupatenData,
                datasets: [{
                    label: 'Produksi Beras (ton)',
                    data: produksiBeras,
                    backgroundColor: '#32CD32',
                    borderColor: '#228B22',
                    borderWidth: 1
                }]
            }
        });

        // Grafik Produktivitas
        var ctx = document.getElementById('produktivitasChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: kabupatenData,
                datasets: [{
                    label: 'Produktivitas (Ku/Ha)',
                    data: produktif,
                    backgroundColor: '#1E90FF',
                    borderColor: '#4682B4',
                    borderWidth: 1
                }]
            }
        });
    })
    .catch(error => console.error('Error loading the GeoJSON:', error));
</script>
<div class="card-body text-center mb-2 mt-5">
        <blockquote class="blockquote mb-0">
        <footer class="blockquote-footer">Made with 🩷 and 🍵 - Pisal & team <cite title="Source Title">©2025 All Right Reserved</cite></footer>
        </blockquote>
    </div>


        <!-- Botstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>