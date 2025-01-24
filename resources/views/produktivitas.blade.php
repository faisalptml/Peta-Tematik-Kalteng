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

        .text {
          text-align: justify;
        }

        .info {
            padding: 6px 8px;
            font: 14px/16px Arial, Helvetica, sans-serif;
            background: white;
            background: rgba(255,255,255,1.0);
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            border-radius: 5px;
        }
        .info h4 {
            margin: 0 0 5px;
            color: #777;
        }

        .legend {
            line-height: 18px;
            color: #555;
        }
        .legend i {
            width: 18px;
            height: 18px;
            float: left;
            margin-right: 8px;
            opacity: 0.7;
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
          <a class="nav-link active dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Peta Tematik
          </a>
          <ul class="dropdown-menu">
            <li><a class="active dropdown-item" href="/produktivitas">Produktivitas</a></li>
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

    <h2 id="title" class="mt-3">Produktivitas Padi Menurut Kabupaten/Kota di Provinsi Kalimantan Tengah</h2>
    <div class="container">
    <p class="col mt-1 text-center">Produktivitas padi di Provinsi Kalimantan Tengah tahun 2024 menunjukkan variasi antar kabupaten/kota, 
    mencerminkan perbedaan kondisi lahan, teknologi pertanian, dan praktik budidaya yang diterapkan di masing-masing wilayah.</p>
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

            var geojson; 

            function getColor(nilai) {
                return nilai > 1000 ? '#1b4d3e' : // Hijau gelap pekat
                        nilai > 500  ? '#355E3B' : // Hijau lumut
                        nilai > 200  ? '#228B22' : // Hijau hutan alami
                        nilai > 100  ? '#006d2c' : // Hijau tua
                        nilai > 50   ? '#31a354' : // Hijau sedang
                        nilai > 20   ? '#74c476' : // Hijau segar
                        nilai > 10   ? '#bae4b3' : // Hijau pucat
                                    '#edf8e9';  // Hijau terang
            }

            function style(feature) {
                return {
                    fillColor: getColor(feature.properties.produktivitas_ku_ha),
                    weight: 2,
                    opacity: 1,
                    color: '#898121',
                    dashArray: '4',
                    fillOpacity: 0.7
                };
            }

            function highlightFeature(e) {
                var layer = e.target;

                layer.setStyle({
                    weight: 5,
                    color: '#F87A53',
                    dashArray: '',
                    fillOpacity: 0.7
                });

                layer.bringToFront();
                info.update(layer.feature.properties);
            }

            function resetHighlight(e) {
                if (geojson) {
                    geojson.resetStyle(e.target); // Reset style jika geojson sudah tersedia
                } else {
                    console.error('GeoJSON layer is not yet initialized.');
                }
                info.update();
            }

            function zoomToFeature(e) {
                map.fitBounds(e.target.getBounds());
            }

            function onEachFeature(feature, layer) {
                layer.on({
                    mouseover: highlightFeature,
                    mouseout: resetHighlight,
                    click: zoomToFeature
                });
            }

            // Kontrol Informasi
            var info = L.control();

            info.onAdd = function (map) {
                this._div = L.DomUtil.create('div', 'info'); // Membuat elemen div dengan class "info"
                this.update();
                return this._div;
            };

            info.update = function (props) {
                this._div.innerHTML = '<h4>Produktivitas Padi</h4>' + 
                    (props
                        ? '<b>' + props.name + '</b><br />Produktivitas: ' + props.produktivitas_ku_ha + ' ku/ha'
                        : 'Arahkan mouse ke area');
            };

            info.addTo(map);

            // legend
            var legend = L.control({position: 'bottomright'});

            legend.onAdd = function (map) {

                var div = L.DomUtil.create('div', 'info legend'),
                    grades = [0, 10, 20, 50, 100, 200, 500, 1000],
                    labels = [];

                for (var i = 0; i < grades.length; i++) {
                    div.innerHTML +=
                        '<i style="background:' + getColor(grades[i] + 1) + '"></i> ' +
                        grades[i] + (grades[i + 1] ? '&ndash;' + grades[i + 1] + '<br>' : '+');
                }

                return div;
            };

            legend.addTo(map);

            fetch('/DataKalteng.geojson')
                .then(response => response.json())
                .then(data => {
                    geojson = L.geoJson(data, {
                        style: style,
                        onEachFeature: onEachFeature
                    }).addTo(map);
                })
                .catch(error => console.error('Error loading the geojson file:', error));

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