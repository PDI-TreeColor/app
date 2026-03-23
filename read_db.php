<?php
/**
 * API Endpoint: Récupération des zones géographiques.
 * Ce script interroge la base de données et renvoie les données au format GeoJSON.
 */
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$host = "db";
$port = "5432";
$dbname = "mydb";
$user = "treecolor";
$password = "treecolor";

// Connexion à la base de données PostgreSQL
try {
    $db = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

$idProjet = $_GET['projet'] ?? null;
$params = [];

// 1. On prépare la base de la requête
$sql_where = "";

// 2. Si un nom est fourni, on ajoute le filtre WHERE
if ($idProjet) {
    $sql_where = "WHERE id = :id_param";
    $params['id_param'] = $idProjet;
}

// 3. Construction de la requête globale.
// La construction du GeoJSON est déléguée à PostgreSQL pour plus de performance.
$sql = "SELECT jsonb_build_object(
    'type',     'FeatureCollection',
    'features', COALESCE(jsonb_agg(feature), '[]'::jsonb)
)
FROM (
  SELECT jsonb_build_object(
    'type',       'Feature',
    -- Conversion de la géométrie PostGIS en GeoJSON
    'geometry',   ST_AsGeoJSON(geom)::jsonb,
    -- Les autres colonnes deviennent les propriétés du GeoJSON (sauf la géométrie brute)
    'properties', to_jsonb(inputs) - 'geom'
  ) AS feature
  FROM (
    SELECT *, 
           -- Ajout de la surface en m2 arrondi à 2 décimales
           ROUND(ST_Area(geom::geography)::numeric, 2) as surface
    FROM zones 
    $sql_where
  ) inputs
) features";


$stmt = $db->prepare($sql);
$stmt->execute($params);
$result = $stmt->fetchColumn();

// Si aucun résultat n'est trouvé (base vide), on renvoie une collection vide propre
echo $result ? $result : json_encode(['type' => 'FeatureCollection', 'features' => []]);
?>