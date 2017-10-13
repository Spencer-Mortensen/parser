<?php

namespace SpencerMortensen\Parser\String;


// Test
$isMatch = Re::match($expression, $input, $match, $flags);


// Input
$expression = 'a+';
$input = 'aaab';
$flags = 'A';

// Output
$isMatch = true;
$match = array('aaa');


// Input
$expression = 'a+';
$input = 'baaa';
$flags = 'A';

// Output
$isMatch = false;
$match = null;



// Test
$output = Re::quote($input);


// Input
$input = "x\n'\"";

// Output
$output = "x\n'\"";


// Input
$input = "^.$\x03";

// Output
$output = "\\^\\.\\$\\\x03";
