<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$host = "localhost";
$port = "5432";
$dbname = "zones";
$user = "treecolor";
$password = "treecolor";

try {
    $db = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

$idProjet = $_GET['id_projet'] ?? null;
$params = [];

// 1. On prépare la base de la requête
$sql_where = "";

// 2. Si un nom est fourni, on ajoute le filtre WHERE
if ($idProjet) {
    $sql_where = "WHERE id = :id_param";
    $params['id_param'] = $idProjet;
}

// 3. Construction de la requête globale
$sql = "SELECT jsonb_build_object(
    'type',     'FeatureCollection',
    'features', COALESCE(jsonb_agg(feature), '[]'::jsonb)
)
FROM (
  SELECT jsonb_build_object(
    'type',       'Feature',
    'geometry',   ST_AsGeoJSON(geom)::jsonb,
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