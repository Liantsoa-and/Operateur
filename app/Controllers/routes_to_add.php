<?php

// ============================================================
// À coller dans app/Config/Routes.php
// ============================================================

// Auth
$routes->get('/',       'AuthController::index');
$routes->get('login',   'AuthController::index');
$routes->post('login',  'AuthController::login');
$routes->get('logout',  'AuthController::logout');

// Client (nécessite d'être connecté — vérifié dans le controller)
$routes->get('client/solde',        'ClientController::solde');
$routes->get('client/depot',        'ClientController::depot');
$routes->post('client/depot',       'ClientController::depot');
$routes->get('client/retrait',      'ClientController::retrait');
$routes->post('client/retrait',     'ClientController::retrait');
$routes->get('client/transfert',    'ClientController::transfert');
$routes->post('client/transfert',   'ClientController::transfert');
$routes->get('client/historique',   'ClientController::historique');

// Opérateur
$routes->get('operateur',                          'OperateurController::index');
$routes->get('operateur/prefixes',                 'OperateurController::prefixes');
$routes->post('operateur/prefixes/ajouter',        'OperateurController::ajouterPrefixe');
$routes->post('operateur/prefixes/supprimer/(:num)','OperateurController::supprimerPrefixe/$1');

$routes->get('operateur/types',                    'OperateurController::typesOperations');

$routes->get('operateur/baremes/(:num)',            'OperateurController::baremes/$1');
$routes->post('operateur/baremes/ajouter',         'OperateurController::ajouterBareme');
$routes->post('operateur/baremes/modifier/(:num)', 'OperateurController::modifierBareme/$1');
$routes->post('operateur/baremes/supprimer/(:num)','OperateurController::supprimerBareme/$1');

$routes->get('operateur/comptes',                  'OperateurController::situationComptes');
$routes->get('operateur/gains',                    'OperateurController::situationGains');
