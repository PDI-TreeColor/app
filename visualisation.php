<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['projet'];

    // 1. Ici, vous faites vos traitements (Base de données, validation, etc.)
    
    // 2. Redirection vers la page de visualisation avec un paramètre
    header("Location: visualisation.php?status=success&nom=" . urlencode($nom));
    
    // 3. Toujours quitter le script après un header pour stopper l'exécution
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de l'API Copernicus</title>
    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>
    <style>
        #map {
            height: 100em;
            z-index: 0;
        }

        #calendrier {
            position: absolute;
            top: 100px;
            left: 20px;
            z-index: 1;
        }

        #PLUS {
            position: absolute;
            top: 80px;
            right: 20px;
            z-index: 1000;
        }

        
        #formulaire{
            position:absolute;
            top:80px;
            right:20px;
            background:white;
            padding:20px;
            border-radius:10px;
            box-shadow:0 0 10px rgba(0,0,0,0.3);
            display:none;
            z-index:1000;
        }


    </style>
</head>
<body>
    <div id="map"></div>
    <div id="calendrier">
        <select name="year" id="year-select"></select>
        <select name="month" id="month-select"></select>
        <script>
            const yearSelect = document.getElementById("year-select")
            const monthSelect = document.getElementById("month-select")

            var today = new Date().toJSON().slice(0,7)
            var todayYear = Number(today.slice(0,4))
            var todayMonth = Number(today.slice(5,7))

            function removeSelectOptions(selectElement) {
                var i, L = selectElement.options.length - 1;
                for(i = L; i >= 0; i--) {
                    selectElement.remove(i);
                }
            }

            function updateAvailableMonths(year) {
                removeSelectOptions(monthSelect);
                var lastMonthAvailable = 12
                if (year == todayYear) {
                    lastMonthAvailable = todayMonth;
                }
                for (let i = 1; i<=lastMonthAvailable; i++) {
                    var opt = document.createElement("option");
                    opt.value = i.toString()
                    opt.text = i.toString()
                    monthSelect.add(opt, null)
                }
            }

            for (let i = 2016; i<=todayYear; i++) {
                var opt = document.createElement("option");
                opt.value = i.toString()
                opt.text = i.toString()
                yearSelect.add(opt, null)
            }

            yearSelect.value = todayYear;

            updateAvailableMonths(todayYear)

            yearSelect.addEventListener("change", (event) => {
                year = Number(event.target.value);
                updateAvailableMonths(year);
            });
        </script>
    </div>
    
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
    
    <script src="js/map.js"></script>
</body>
</html>