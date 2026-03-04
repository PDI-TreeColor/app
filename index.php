<?php

declare(strict_types=1);

require_once 'flight/Flight.php';

session_start();

Flight::set('connexion_db', $connexion_db);

// route par défaut, va sur le menu
Flight::route('/', function() {
    Flight::render('menu');
});

// route de la carte du jeu
Flight::route('/burkina', function() {
    Flight::render('burkina');
});

Flight::start();

?>