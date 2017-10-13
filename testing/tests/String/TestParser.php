<?php

namespace SpencerMortensen\Parser\String;

use SpencerMortensen\Parser\ParserException;
use SpencerMortensen\Parser\Test\TestParser;


// Test
$parser = new TestParser();
$output = $parser->parse($rule, $input);


// Input
$rule = 'andandABC';
$input = '';

// Output
throw new ParserException('a', 0);


// Input
$rule = 'andorABC';
$input = 'c';

// Output
throw new ParserException('orAB', 0);


// Input
$rule = 'andorABC';
$input = 'a';

// Output
throw new ParserException('c', 1);


// Input
$rule = 'andorABC';
$input = 'ax';

// Output
throw new ParserException('c', 1);


// Input
$rule = 'andorABC';
$input = 'ac';

// Output
$output = 'A and C';


// Input
$rule = 'orandABC';
$input = 'abd';

// Output
throw new ParserException(null, 2);


// Input
$rule = 'orCandAB';
$input = 'a';

// Output
throw new ParserException('orCandAB', 0);
