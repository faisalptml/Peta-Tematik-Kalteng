<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title src="{'/img/LOGO_kalteng.png'}" alt="Logo">
      Informasi Geografis Wilayah Kalimantan Tengah
    </title>
    <link rel="icon" type="image/png" href="{{ asset('img/icon.png') }}" alt="Logo">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
   
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        #map { height: 500px;}
        
        #title, #sub { text-align: center; }
        nav ul li a {
        text-decoration: none;
        color: #007bff;
        }

        nav ul li a:hover {
            text-decoration: underline;
            color: #0056b3;
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
          <a class="nav-link" href="/Analisis">Analisis</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="/interaktif">Luas Wilayah</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

    <h2 id="title" class="mt-3">Luas Wilayah Kabupaten/Kota di Provinsi Kalimantan Tengah</h2>
    <div class="container">
    <p class="col mt-1 text-center">Peta Interaktif ini dirancang untuk memberikan kemudahan 
      kepada Anda dalam memahami dan mengetahui informasi mengenai luas wilayah dari setiap 
      kabupaten atau kota yang terletak di dalam provinsi Kalimantan Tengah secara lebih jelas 
      dan terperinci.</p>
    </div>
    <div class="container">
      <div class="row">
        <div class="col">
        <div id="map" class= "mt-2 mb-4"></div>
        <script>
            var map = L.map('map').setView([-1.0088123, 113.3351748], 6.7);
            var tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            function getColor(luas) {
                return luas > 1000 ? '#800026' :
                      luas > 500  ? '#BD0026' :
                      luas > 200  ? '#E31A1C' :
                      luas > 100  ? '#FC4E2A' :
                      luas > 50   ? '#FD8D3C' :
                      luas > 20   ? '#FEB24C' :
                      luas > 10   ? '#FED976' :
                                    '#FFEDA0';
            }

            fetch('/DataKalteng.geojson')
                .then(response => response.json())
                .then(data => {

                    var overlays = {};

                    // Loop setiap kabupaten/kota dalam data GeoJSON
                    data.features.forEach(function (feature) {
                        var kabupatenName = feature.properties.name;

                        var geojsonLayer = L.geoJSON(feature, {
                            style: function() {
                                return {
                                    color: "white",
                                    weight: 2,
                                    opacity: 1,
                                    fillColor: getColor(feature.properties.luas_wilayah),
                                    fillOpacity: 0.7
                                };
                            },
                            onEachFeature: function (feature, layer) {
                                var luasWilayah = feature.properties.luas_wilayah || 'Data luas tidak tersedia';
                                var popupContent = `
                                    <div style="max-width: 300px; word-wrap: break-word;">
                                        <strong>${feature.properties.name}</strong><br>
                                        Luas Wilayah: ${luasWilayah} km²
                                    </div>
                                `;
                                layer.bindPopup(popupContent, {
                                    maxWidth: 300, // Maksimum lebar popup
                                    closeButton: true, // Tampilkan tombol close
                                    autoPan: true // Otomatis pan saat popup muncul
                                });
                            }
                        });

                        // Tambahkan layer ke objek overlays
                        overlays[kabupatenName] = geojsonLayer;
                    });

                    // Menambahkan kontrol layer ke peta untuk memungkinkan pengaktifan/penonaktifan kabupaten/kota
                    L.control.layers(null, overlays, {collapsed: true}).addTo(map);

                    map.on('layeradd', function(event) {
                        var layer = event.layer;
                        if (layer.getPopup) {
                            layer.openPopup(); 
                        }
                    });

                })
                .catch(error => console.error('Error memuat GeoJSON:', error));
        </script>
        </div>
      </div>
    </div>
    <div class="card-body text-center mb-1">
        <blockquote class="blockquote mb-0">
        <p>Data ini diperoleh dari Badan Pusat Statistik (BPS) Provinsi Kalimantan Tengah.</p>
        <footer class="blockquote-footer">Made with 🩷 and 🍵 - Pisal & team <cite title="Source Title">©2025 All Right Reserved</cite></footer>
        </blockquote>
    </div>

        <!-- Botstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>