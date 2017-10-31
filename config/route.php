<?php
return [
    ['GET', '/', 'Home::index', 'home'],
    ['GET|POST', '/login', 'Sesi::login', 'login'],
    ['GET', '/logout', 'Sesi::logout', 'logout'],
    ['GET|POST', '/[a:controller]/?[a:action]?/[i:id]?', '', 'default-route'],
    ['GET|POST', '/[a:module]/[a:controller]/[a:action]?/[i:id]?', '', 'default-module-route'],
];
