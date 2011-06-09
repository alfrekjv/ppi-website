<?php
date_default_timezone_set('Europe/London');
require_once '../ppi-framework-git/init.php';
$app = new PPI_App();
$app->setRouter(new PPI_Router());
$app->boot()->dispatch();