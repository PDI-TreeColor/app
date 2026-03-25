<?php
// actions/get_project_data.php
/**
 * API Endpoint: Récupération des zones géographiques.
 */
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../config/database.php';
$db = getPdoConnection();

$idProjet = $_GET['projet'] ?? null;
$params = [];
$sql_where = "";

if ($idProjet) {
    $sql_where = "WHERE id = :id_param";
    $params['id_param'] = $idProjet;
}

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
    SELECT id, nom, image, geom, 
           ROUND(ST_Area(geom::geography)::numeric, 2) as surface
    FROM zones 
    $sql_where
  ) inputs
) features";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$result = $stmt->fetchColumn();

echo $result ? $result : json_encode(['type' => 'FeatureCollection', 'features' => []]);
