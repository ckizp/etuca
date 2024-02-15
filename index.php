<?php

use controller\Routeur;
use data_base\DataBase;

require __DIR__ . "/controller/Routeur.php";
require_once __DIR__ . "/data_base/DataBase.php";
session_start();

//Creation du routeur pour rediriger vers la bonne page
if (DataBase::connect() == null)
    die("Unable to connect to database");

$routeur = new Routeur();
$routeur->handleRequest();
?>