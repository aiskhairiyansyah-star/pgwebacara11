<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- leaflet css link  -->
    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    />

    <title>Web-GIS with Geoserver and Leaflet</title>

    <style>
      body {
        margin: 0;
        padding: 0;
      }
      #map {
        width: 100%;
        height: 100vh;
      }

      /* ======================= */
      /*   LEGEND SCROLLABLE     */
      /* ======================= */
      .legend {
        padding: 4px 6px;
        font: 13px Arial, sans-serif;
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        border-radius: 6px;
        border: 1px solid #ccc;

        width: 160px;      
        max-height: 180px; 
        overflow-y: auto;  
      }

      .legend h4 {
        text-align: center;
        margin: 4px 0 6px;
        font-size: 14px;
      }

      .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 4px;
      }

      .legend i {
        width: 14px;
        height: 14px;
        margin-right: 6px;
        border: 1px solid #555;
      }
    </style>
  </head>

  <body>
    <div id="map"></div>

    <!-- leaflet js link  -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <script>
      // ===============================
      // MAP DASAR
      // ===============================
      var map = L.map("map").setView([-7.732521, 110.402376], 11);

      var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution: "© OpenStreetMap contributors",
      }).addTo(map);

      // ===============================
      // LAYER WMS DARI GEOSERVER
      // ===============================
      var desa = L.tileLayer.wms(
        "http://localhost:8080/geoserver/pgwebacara10/wms",
        {
          layers: "pgwebacara10:ADMINISTRASIDESA_AR_25K",
          format: "image/png",
          transparent: true,
        }
      ).addTo(map);

      var jalan = L.tileLayer.wms(
        "http://localhost:8080/geoserver/pgwebacara10/wms",
        {
          layers: "pgwebacara10:JALAN_LN_25K",
          format: "image/png",
          transparent: true,
        }
      ).addTo(map);

      var kecamatan = L.tileLayer.wms(
        "http://localhost:8080/geoserver/pgweb/wms",
        {
          layers: "pgweb:penduduk_sleman",
          format: "image/png",
          transparent: true,
        }
      ).addTo(map);

      // ===============================
      // LAYER CONTROL
      // ===============================
      var overlayLayers = {
        "Administrasi Desa (AR_25K)": desa,
        "Jalan 25K": jalan,
        "Data Kecamatan": kecamatan,
      };

      L.control.layers(null, overlayLayers).addTo(map);

      // ===============================
      //   LEGEND SCROLLABLE
      // ===============================
      var legend = L.control({ position: "bottomleft" });

      legend.onAdd = function () {
        var div = L.DomUtil.create("div", "legend");

        div.innerHTML = `
          <h4>Legenda</h4>

          <b>Jaringan Jalan</b><br>
          <div class="legend-item"><i style="background:#ff0000"></i>Jalan Arteri</div>
          <div class="legend-item"><i style="background:#ff8800"></i>Jalan Kolektor</div>
          <div class="legend-item"><i style="background:#ffaa00"></i>Jalan Lokal</div>

          <br><b>Administrasi Desa</b><br>
          <div class="legend-item"><i style="background:#0088ff"></i>Umbulharjo</div>
          <div class="legend-item"><i style="background:#0066cc"></i>Condongcatur</div>
          <div class="legend-item"><i style="background:#0044aa"></i>Minomartani</div>
          <div class="legend-item"><i style="background:#003388"></i>Caturtunggal</div>
          <div class="legend-item"><i style="background:#002266"></i>Sinduadi</div>
          <div class="legend-item"><i style="background:#001144"></i>Maguwoharjo</div>

          <br><b>Kecamatan</b><br>
          <div class="legend-item"><i style="background:#22aa22"></i>Depok</div>
          <div class="legend-item"><i style="background:#118811"></i>Mlati</div>
          <div class="legend-item"><i style="background:#116611"></i>Gamping</div>
        `;

        return div;
      };

      legend.addTo(map);

      // =========================================
      // iframe peta dari Sleman Geoportal
      // =========================================
      var iframe = L.control({ position: "topright" });

      iframe.onAdd = function () {
        var div = L.DomUtil.create("div");
        div.innerHTML = `
          <iframe 
            src="https://geoportal.slemankab.go.id/datasets/geonode:kasus_leptosirosis_2025_semester1/embed"
            width="350"
            height="250"
            frameborder="0">
          </iframe>
        `;
        return div;
      };

      iframe.addTo(map);
    </script>
  </body>
</html>
