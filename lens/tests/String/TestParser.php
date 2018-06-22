<?php

namespace SpencerMortensen\Parser\String;

use SpencerMortensen\Parser\ParserException;
use SpencerMortensen\Parser\Test\TestParser;


// Test
$parser = new TestParser();
$output = $parser->parse($rule, $input);


// Cause
$rule = 'andandABC';
$input = '';

// Effect
throw new ParserException('a', 0);


// Cause
$rule = 'andorABC';
$input = 'c';

// Effect
throw new ParserException('orAB', 0);


// Cause
$rule = 'andorABC';
$input = 'a';

// Effect
throw new ParserException('c', 1);


// Cause
$rule = 'andorABC';
$input = 'ax';

// Effect
throw new ParserException('c', 1);


// Cause
$rule = 'andorABC';
$input = 'ac';

// Effect
$output = 'A and C';


// Cause
$rule = 'orandABC';
$input = 'abd';

// Effect
$output = 'A and B';


// Cause
$rule = 'orCandAB';
$input = 'a';

// Effect
throw new ParserException('orCandAB', 0);
