<?php

namespace SpencerMortensen\Parser;

// Test
$exception = new ParserException($input, null);
$output = $exception->getRule();


// Input
$input = null;

// Output
$output = null;

// Input
$input = 'a';

// Output
$output = 'a';

// Input
$input = 'name';

// Output
$output = 'name';


// Test
$exception = new ParserException(null, $input);
$output = $exception->getState();

// Input
$input = null;

// Output
$output = null;

// Input
$input = 'a';

// Output
$output = 'a';

// Input
$input = array('abc', 'ABC');

// Output
$output = array('abc', 'ABC');
