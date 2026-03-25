<?php
// config/database.php

/**
 * Retourne une connexion PostgreSQL classique (pg_connect)
 */
function getDbConnection() {
    $conn = pg_connect("host=db dbname=mydb user=treecolor password=treecolor");
    if (!$conn) {
        die("Erreur de connexion à la base de données.");
    }
    return $conn;
}

/**
 * Retourne une connexion PDO pour PostgreSQL
 */
function getPdoConnection() {
    $host = "db";
    $port = "5432";
    $dbname = "mydb";
    $user = "treecolor";
    $password = "treecolor";
    try {
        return new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    } catch (PDOException $e) {
        die(json_encode(['error' => 'Erreur de connexion PDO: ' . $e->getMessage()]));
    }
}
