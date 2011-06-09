<?php
$routes = array();

// About Match
$routes['/about\.html'] = 'general/about';
$routes['/advertising\.html'] = 'general/advertising';
// Page Match
$routes['/:any/:any/(:any)\.html\?id=(:num)'] = 'page/view/$2/$1';
