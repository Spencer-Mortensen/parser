<?php

namespace SpencerMortensen\Parser\String;

use SpencerMortensen\Parser\ParserException;
use SpencerMortensen\Parser\Rules\AndRule;
use SpencerMortensen\Parser\Rules\CallableRule;
use SpencerMortensen\Parser\Rules\ManyRule;
use SpencerMortensen\Parser\Rules\OrRule;
use SpencerMortensen\Parser\Rules\ReRule;
use SpencerMortensen\Parser\Rules\StringRule;


// Test
$parser = new Parser($rule);
$output = $parser->parse($input);

// Input
$rule = new StringRule('x', 'a');
$input = '';

// Output
throw new ParserException('x', 0);

// Input
$rule = new StringRule('x', 'a');
$input = 'a';

// Output
$output = 'a';

// Input
$rule = new StringRule('x', 'a', 'strtoupper');
$input = 'a';

// Output
$output = 'A';

// Input
$rule = new StringRule('x', 'a');
$input = 'b';

// Output
throw new ParserException('x', 0);

// Input
$rule = new StringRule('x', 'a');
$input = 'ab';

// Output
throw new ParserException(null, 1);

// Input
$rule = new StringRule('xy', 'ab');
$input = 'ac';

// Output
throw new ParserException('xy', 0);


// Input
$rule = new ReRule('x', 'a?(b|c)');
$input = 'd';

// Output
throw new ParserException('x', 0);

// Input
$rule = new ReRule('x', 'a?(b|c)');
$input = 'ab';

// Output
$output = 'ab';

// Input
$rule = new ReRule('x', 'a?(b|c)', function ($match) { return $match; });
$input = 'ab';

// Output
$output = array('ab', 'b');


// Input
$aRule = new StringRule('x', 'a');
$bRule = new StringRule('y', 'b');
$rule = new AndRule('xy', array($aRule, $bRule));
$input = '';

// Output
throw new ParserException('x', 0);

// Input
$aRule = new StringRule('x', 'a');
$bRule = new StringRule('y', 'b');
$rule = new AndRule('xy', array($aRule, $bRule));
$input = 'a';

// Output
throw new ParserException('y', 1);

// Input
$aRule = new StringRule('x', 'a');
$bRule = new StringRule('y', 'b');
$rule = new AndRule('xy', array($aRule, $bRule));
$input = 'ab';

// Output
$output = array('a', 'b');

// Input
$aRule = new StringRule('x', 'a');
$bRule = new StringRule('y', 'b');
$rule = new AndRule('xy', array($aRule, $bRule), function ($values) { return implode('', $values); });
$input = 'ab';

// Output
$output = 'ab';

// Input
$aRule = new StringRule('x', 'a');
$bRule = new StringRule('y', 'b');
$rule = new AndRule('xy', array($aRule, $bRule));
$input = 'abc';

// Output
throw new ParserException(null, 2);


// Input
$aRule = new StringRule('x', 'a');
$bRule = new StringRule('y', 'b');
$rule = new OrRule('xy', array($aRule, $bRule));
$input = '';

// Output
throw new ParserException('xy', 0);

// Input
$aRule = new StringRule('x', 'a');
$bRule = new StringRule('y', 'b');
$rule = new OrRule('xy', array($aRule, $bRule));
$input = 'a';

// Output
$output = 'a';

// Input
$aRule = new StringRule('x', 'a');
$bRule = new StringRule('y', 'b');
$rule = new OrRule('xy', array($aRule, $bRule));
$input = 'b';

// Output
$output = 'b';

// Input
$aRule = new StringRule('x', 'a');
$bRule = new StringRule('y', 'b');
$rule = new OrRule('xy', array($aRule, $bRule));
$input = 'c';

// Output
throw new ParserException('xy', 0);

// Input
$aRule = new StringRule('x', 'a');
$bRule = new StringRule('y', 'b');
$rule = new OrRule('xy', array($aRule, $bRule));
$input = 'ab';

// Output
throw new ParserException(null, 1);


/*
// Input
$aRule = new StringRule('x', 'a');
$rule = new ManyRule('xx', $aRule);
$input = '';

// Output
$output = array();

// Input
$aRule = new StringRule('x', 'a');
$rule = new ManyRule('xx', $aRule);
$input = 'a';

// Output
$output = array('a');

// Input
$aRule = new StringRule('x', 'a');
$rule = new ManyRule('xx', $aRule);
$input = 'aa';

// Output
$output = array('a', 'a');

// Input
$aRule = new StringRule('x', 'a', 'strtoupper');
$rule = new ManyRule('xx', $aRule, 1);
$input = '';

// Output
throw new ParserException('x', 0);

// Input
$aRule = new StringRule('x', 'a');
$rule = new ManyRule('xx', $aRule, 1);
$input = 'a';

// Output
$output = array('a');

// Input
$aRule = new StringRule('x', 'a');
$rule = new ManyRule('xx', $aRule, 1);
$input = 'aa';

// Output
$output = array('a', 'a');

// Input
$aRule = new StringRule('x', 'a');
$rule = new ManyRule('xx', $aRule, 1, 1);
$input = '';

// Output
throw new ParserException('x', 0);

// Input
$aRule = new StringRule('x', 'a');
$rule = new ManyRule('xx', $aRule, 1, 1);
$input = 'a';

// Output
$output = array('a');

// Input
$aRule = new StringRule('x', 'a');
$rule = new ManyRule('xx', $aRule, 1, 1);
$input = 'aa';

// Output
throw new ParserException(null, 1);

// Input
$aRule = new StringRule('x', 'a');
$rule = new ManyRule('xx', $aRule, null, null, 'count');
$input = 'aaaaaa';

// Output
$output = 6;
*/


// Input
$rule = new CallableRule('x', function (&$output) { $output = 'a'; return true; });
$input = '';

// Output
$output = 'a';

// Input
$rule = new CallableRule('x', function (&$output) { $output = 'a'; return false; });
$input = '';

// Output
throw new ParserException('x', 0);
