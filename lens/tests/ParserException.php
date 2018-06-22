<?php

namespace SpencerMortensen\Parser;

// Test
$exception = new ParserException($input, null);
$output = $exception->getRule();


// Cause
$input = null;

// Effect
$output = null;

// Cause
$input = 'a';

// Effect
$output = 'a';

// Cause
$input = 'name';

// Effect
$output = 'name';


// Test
$exception = new ParserException(null, $input);
$output = $exception->getState();

// Cause
$input = null;

// Effect
$output = null;

// Cause
$input = 'a';

// Effect
$output = 'a';

// Cause
$input = ['abc', 'ABC'];

// Effect
$output = ['abc', 'ABC'];
