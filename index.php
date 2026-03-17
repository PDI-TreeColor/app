<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>TreeColor</title>
    <style>
        #PLUS {
            position: absolute;
            left: 45%;
            bottom: 10%;
            font-size: 20px;
        }

        
        #formulaire{
            position: absolute;
            left: 45%;
            bottom: 10%;
            background:white;
            padding:20px;
            border-radius:10px;
            box-shadow:0 0 10px rgba(0,0,0,0.3);
            display:none;
        }


    </style>
</head>
<body>
    <div class="container mt-5">

        <div class="row">
            
            <div class="col">

                <div class="card mx-auto" style="width: fit-content;">
                    <img src="data/burkina/burkina_card.png" class="card-img-top" alt="..." style="width: 300px;">
                    <div class="card-body">
                        <h5 class="card-title">Burkina Faso</h5>
                        <form action="visualisation.php" method="GET">
                            <input type="hidden" name="projet" value="burkina">
                            <button class="btn btn-primary">Voir le projet</button>
                        </form>
                    </div>
                </div>

            </div>  

            <div class="col">
            
                <div class="card mx-auto" style="width: fit-content;">
                    <img src="data/panama/panama_card.png" class="card-img-top" alt="..." style="width: 300px;">
                    <div class="card-body">
                        <h5 class="card-title">Panama</h5>
                        <form action="visualisation.php" method="GET">
                            <input type="hidden" name="projet" value="panama">
                            <button class="btn btn-primary">Voir le projet</button>
                        </form>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <div id="PLUS">
        <button onclick="ouvrirForm()">AJOUTER UNE ZONE</button>
    </div>

    <div id="formulaire">
        <form action="visualisation.php" method="GET" enctype="multipart/form-data">

            <label for="projet">Nom de la zone :</label><br>
            <input type="text" id="projet" name="projet" required><br><br>

            <label>Type de zone :</label><br>

            <input type="radio" id="reforester" name="type_zone" value="reforester" required>
            <label for="reforester">Zone à reforester</label><br>

            <input type="radio" id="reforestee" name="type_zone" value="reforestee">
            <label for="reforestee">Zone reforestée</label><br><br>

            <label for="file">coordonnées :</label><br>
            <input type="file" id="file" name="file"><br><br>

            <button type="button" onclick="validerForm()">Valider</button>
            <button type="button" onclick="fermerForm()">Fermer</button>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="js/map.js"></script>
</body>
</html>