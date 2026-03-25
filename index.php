<?php
/**
 * Page d'accueil et d'administration.
 * Permet de lister, ajouter et supprimer des projets (zones).
 */

/**
 * Ajoute une nouvelle zone en base de données.
 * Traite l'upload du fichier KML et de l'image, convertit le KML en GeoJSON via geoPHP.
 */
function ajouterZone()
{
    // Chargement des dépendances via Composer (notamment geoPHP)
    require_once '/var/www/html/vendor/autoload.php';

    $conn = pg_connect("host=db dbname=mydb user=treecolor password=treecolor");
    $nom = $_POST['projet'];

    $kmlContent = file_get_contents($_FILES['kml']['tmp_name']);

    // Conversion KML -> GeoJSON
    $geometry = geoPHP::load($kmlContent, 'kml');

    $geojsonData = $geometry->out('json');
    $geojsonArray = json_decode($geojsonData, true);
    $geometryJson = json_encode($geojsonArray);

    // Création du dossier pour stocker les assets du projet
    $dossier = '/var/www/html/data/' . $nom . '/';
    if (!is_dir($dossier)) {
        mkdir($dossier, 0777, true);
    }

    // Sauvegarde d'une copie locale du GeoJSON
    file_put_contents($dossier . $nom . '.geojson', json_encode([
        'type' => 'FeatureCollection',
        'features' => [[
                'type' => 'Feature',
                'geometry' => $geojsonArray,
                'properties' => ['nom' => $nom]
            ]]
    ]));

    // Gestion de l'upload de l'image
    $nomImage = $nom . '/' . basename($_FILES['image']['name']);
    $destination = $dossier . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $destination);

    // Insertion en base de données avec conversion GeoJSON -> Geometry PostGIS
    $query = "
            INSERT INTO zones (nom, geom, image)
            VALUES (
                $1,
                ST_SetSRID(ST_GeomFromGeoJSON($2),4326),
                $3
            )
        ";

    $result = pg_query_params($conn, $query, [$nom, $geometryJson, $nomImage]);

}

/**
 * Supprime une zone de la base de données via son ID.
 */
function supprimerZone()
{
    $conn = pg_connect("host=db dbname=mydb user=treecolor password=treecolor");
    $id = $_POST['supprimer_zone'];
    $query = "DELETE FROM zones WHERE id = $1";
    pg_query_params($conn, $query, [$id]);
}

// --- Routeur simple pour traiter les actions POST ---
if (isset($_POST['ajouter_zone'])) {
    ajouterZone();
    header('Location: ' . $_SERVER['PHP_SELF']); // Redirection pour éviter la resoumission du formulaire
    exit();
}

if (isset($_POST['supprimer_zone'])) {
    supprimerZone();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Accueil TreeColor</title>
    <link href="style.css" rel="stylesheet">
    <style>



    </style>
</head>

<body>
    <h1>Bienvenue sur l'application de suivi des projets TreeColor</h1>

    <div class="projets-container">
        <?php
// Connexion pour l'affichage de la liste des projets
$conn = pg_connect("host=db dbname=mydb user=treecolor password=treecolor");

$result = pg_query($conn, "SELECT id, nom, image FROM zones");

while ($zone = pg_fetch_assoc($result)) {
?>
        <div class="projet">
            <h3>Projet
                <?php echo $zone['nom']; ?>
            </h3>
            <img src="data/<?php echo $zone['image']; ?>" class="card-img-top" alt="..." style="width: 300px;">
            <div class="card-body">
                <form action="visualisation.php" method="GET">
                    <input type="hidden" name="projet" value="<?php echo $zone['id']?>">
                    <button class="carte-bouton">Voir le projet</button>
                </form>

                <form method="POST">
                    <button class="bouton-suppr" name="supprimer_zone" value="<?php echo $zone['id']; ?>">Supprimer le
                        projet</button>
                </form>
            </div>
        </div>
        <?php
}
?>
    </div>

    <div id="PLUS">
        <button onclick="ouvrirForm()">AJOUTER UN PAYS</button>
    </div>

    <div id="formulaire">
        <form method="POST" enctype="multipart/form-data">

            <label for="projet">Nom du projet :</label><br>
            <input type="text" id="projet" name="projet" required><br><br>

            <label for="kml">Fichier KML :</label><br>
            <input type="file" id="kml" name="kml" accept=".kml" required><br><br>

            <label for="image">Image de la carte :</label><br>
            <input type="file" id="image" name="image" accept=".png,.jpg,.jpeg" required><br><br>

            <button type="submit" name="ajouter_zone">Valider</button>
            <button type="button" onclick="fermerForm()">Fermer</button>

        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="js/map.js"></script>
</body>

</html>