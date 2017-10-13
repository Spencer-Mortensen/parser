<?php

namespace SpencerMortensen\Parser\String;

use SpencerMortensen\Parser\Rules\ManyRule;
use SpencerMortensen\Parser\Rules\StringRule;


// Test
$parser = new Parser($rule);
$output = $parser->parse('aa');

// Input
$aRule = new StringRule('x', 'a', 'strtoupper');
$rule = new ManyRule('xx', $aRule);

// Output
$output = array('A', 'A');
