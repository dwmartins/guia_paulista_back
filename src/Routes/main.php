<?php

$routesDirectory = scandir(__DIR__);
$routes = array_diff($routesDirectory, ['.', '..', '.gitignore']);

foreach ($routes as $route) {
    if($route === "main.php") {
        continue;
    }

    include __DIR__."/$route";
}