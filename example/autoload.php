<?php

namespace SpencerMortensen\Autoloader;

$project = dirname(__DIR__);

$classes = array(
	'Example' => 'example/src',
	'SpencerMortensen\\Parser' => 'src',
	'SpencerMortensen\\RegularExpressions' => 'vendor/spencer-mortensen/regular-expressions/src'
);

require "{$project}/vendor/spencer-mortensen/autoloader/src/Autoloader.php";

new Autoloader($project, $classes);
