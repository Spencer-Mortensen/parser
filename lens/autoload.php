<?php

namespace SpencerMortensen\Autoloader;

$project = dirname(__DIR__);

$classes = [
	'SpencerMortensen\\Parser' => 'src',
	'SpencerMortensen\\RegularExpressions' => 'vendor/spencer-mortensen/regular-expressions/src'
];

require "{$project}/vendor/spencer-mortensen/autoloader/src/Autoloader.php";

new Autoloader($project, $classes);

require_once "{$project}/lens/src/TestParser.php";
