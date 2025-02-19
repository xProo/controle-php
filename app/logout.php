<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Auth\Logout;

$logout = new Logout();
$logout->execute();
?>