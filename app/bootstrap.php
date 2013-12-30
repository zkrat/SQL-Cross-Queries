<?php

require __DIR__ . '/../libs/Schmutzka/loader.php';

$configurator = new Schmutzka\Configurator;
return $configurator->createContainer();
