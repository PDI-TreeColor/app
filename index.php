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
    <?php

    function ajouterZone(){
        $conn = pg_connect("host=db dbname=mydb user=treecolor password=treecolor");

        $nom = $_POST['projet'];

        $file = $_FILES['geojson']['tmp_name'];
        $geojson = file_get_contents($file);
        $data = json_decode($geojson, true);

        $geometry = json_encode($data['features'][0]['geometry']);

        $nomImage = $nom . '/' . basename($_FILES['image']['name']);
        $dossier = '/var/www/html/data/' . $nom . '/';

        // Crée le dossier si il n'existe pas
        if(!is_dir($dossier)){
            mkdir($dossier, 0777, true);
        }

        $destination = $dossier . basename($_FILES['image']['name']);
        $moveResult = move_uploaded_file($_FILES['image']['tmp_name'], $destination);
        
        $query = "
            INSERT INTO zones (nom, geom, image)
            VALUES (
                $1,
                ST_SetSRID(ST_GeomFromGeoJSON($2),4326),
                $3
            )
        ";

        $result = pg_query_params($conn, $query, [$nom, $geometry, $nomImage]);
    }

    if(isset($_POST['ajouter_zone'])){
        ajouterZone();
    }

    ?>

    

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
        <button onclick="ouvrirForm()">AJOUTER UN PAYS</button>
    </div>

    <div id="formulaire">
        <form method="POST" enctype="multipart/form-data">

            <label for="projet">Nom du projet :</label><br>
            <input type="text" id="projet" name="projet" required><br><br>

            <label for="file">Fichier GEOJSON :</label><br>
            <input type="file" id="file" name="geojson" accept=".geojson,.json" required><br><br>

            <label for="image">Image de la carte :</label><br>
            <input type="file" id="image" name="image" accept=".png,.jpg,.jpeg" required><br><br>

            <button type="submit" name="ajouter_zone">Valider</button>
            <button type="button" onclick="fermerForm()">Fermer</button>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="js/map.js"></script>
</body>
</html>