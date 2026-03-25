<?php
// actions/delete_project.php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer_zone'])) {
    $conn = getDbConnection();
    $id = $_POST['supprimer_zone'];
    
    $query = "DELETE FROM zones WHERE id = $1";
    pg_query_params($conn, $query, [$id]);

    header('Location: ../index.php');
    exit();
}
