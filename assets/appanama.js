var map = L.map('map').setView([12.07085000,-1.20077222], 15);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

fetch('../data/panama/panama.geojson')
  .then(response => response.json())
  .then(data => {
    var geojsonLayer = L.geoJSON(data, {
        style: {
            color: "blue",
            weight: 2,
            fillColor: "lightblue",
            fillOpacity: 0.5
        }
      }).addTo(map);

    map.fitBounds(geojsonLayer.getBounds());

  });

