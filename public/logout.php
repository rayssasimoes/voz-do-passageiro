<?php

session_start(); 

require_once __DIR__ . '/../src/backend/controllers/LogoutController.php';

use Backend\Controllers\LogoutController;

$controller = new LogoutController();
$controller->logout();