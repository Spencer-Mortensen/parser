<?php

namespace SpencerMortensen\Parser\String;

use SpencerMortensen\Parser\ParserException;
use SpencerMortensen\Parser\Core\Rules\AndRule;
use SpencerMortensen\Parser\Core\Rules\ManyRule;
use SpencerMortensen\Parser\Core\Rules\OrRule;
use SpencerMortensen\Parser\String\Rules\ReRule;
use SpencerMortensen\Parser\String\Rules\StringRule;


// Test
$rule = new StringRule('x', 'a');
$parser = new Parser($rule);
$parser->parse('');

// Output
throw new ParserException('x', 0);


// Test
$rule = new StringRule('x', 'a');
$parser = new Parser($rule);
$output = $parser->parse('a');

// Output
$output = 'a';


// Test
$rule = new StringRule('x', 'a', 'strtoupper');
$parser = new Parser($rule);
$output = $parser->parse('a');

// Output
$output = 'A';


// Test
$rule = new StringRule('x', 'a');
$parser = new Parser($rule);
$output = $parser->parse('b');

// Output
throw new ParserException('x', 0);


// Test
$rule = new StringRule('x', 'a');
$parser = new Parser($rule);
$output = $parser->parse('ab');

// Output
throw new ParserException(null, 1);


// Test
$rule = new StringRule('xy', 'ab');
$parser = new Parser($rule);
$output = $parser->parse('ac');

// Output
throw new ParserException('xy', 0);


// Test
$rule = new ReRule('x', 'a?(b|c)');
$parser = new Parser($rule);
$output = $parser->parse('d');

// Output
throw new ParserException('x', 0);


// Test
$rule = new ReRule('x', 'a?(b|c)');
$parser = new Parser($rule);
$output = $parser->parse('ab');

// Output
$output = array('ab', 'b');


// Test
$rule = new ReRule('x', 'a?(b|c)', function ($match) { return $match[1]; });
$parser = new Parser($rule);
$output = $parser->parse('ab');

// Output
$output = 'b';


// Test
$aRule = new StringRule('x', 'a');
$bRule = new StringRule('y', 'b');
$rule = new AndRule('xy', array($aRule, $bRule));
$parser = new Parser($rule);
$output = $parser->parse('');

// Output
throw new ParserException('x', 0);


// Test
$aRule = new StringRule('x', 'a');
$bRule = new StringRule('y', 'b');
$rule = new AndRule('xy', array($aRule, $bRule));
$parser = new Parser($rule);
$output = $parser->parse('a');

// Output
throw new ParserException('y', 1);


// Test
$aRule = new StringRule('x', 'a');
$bRule = new StringRule('y', 'b');
$rule = new AndRule('xy', array($aRule, $bRule));
$parser = new Parser($rule);
$output = $parser->parse('ab');

// Output
$output = array('a', 'b');


// Test
$aRule = new StringRule('x', 'a');
$bRule = new StringRule('y', 'b');
$rule = new AndRule('xy', array($aRule, $bRule), function ($values) { return implode('', $values); });
$parser = new Parser($rule);
$output = $parser->parse('ab');

// Output
$output = 'ab';


// Test
$aRule = new StringRule('x', 'a');
$bRule = new StringRule('y', 'b');
$rule = new AndRule('xy', array($aRule, $bRule));
$parser = new Parser($rule);
$output = $parser->parse('abc');

// Output
throw new ParserException(null, 2);


// Test
$aRule = new StringRule('x', 'a');
$bRule = new StringRule('y', 'b');
$rule = new OrRule('xy', array($aRule, $bRule));
$parser = new Parser($rule);
$output = $parser->parse('');

// Output
throw new ParserException('xy', 0);


// Test
$aRule = new StringRule('x', 'a');
$bRule = new StringRule('y', 'b');
$rule = new OrRule('xy', array($aRule, $bRule));
$parser = new Parser($rule);
$output = $parser->parse('a');

// Output
$output = 'a';


// Test
$aRule = new StringRule('x', 'a');
$bRule = new StringRule('y', 'b');
$rule = new OrRule('xy', array($aRule, $bRule));
$parser = new Parser($rule);
$output = $parser->parse('b');

// Output
$output = 'b';


// Test
$aRule = new StringRule('x', 'a');
$bRule = new StringRule('y', 'b');
$rule = new OrRule('xy', array($aRule, $bRule));
$parser = new Parser($rule);
$output = $parser->parse('c');

// Output
throw new ParserException('xy', 0);


// Test
$aRule = new StringRule('x', 'a');
$bRule = new StringRule('y', 'b');
$rule = new OrRule('xy', array($aRule, $bRule));
$parser = new Parser($rule);
$output = $parser->parse('ab');

// Output
throw new ParserException(null, 1);


// Test
$aRule = new StringRule('x', 'a');
$rule = new ManyRule('xx', $aRule);
$parser = new Parser($rule);
$output = $parser->parse('');

// Output
$output = array();


// Test
$aRule = new StringRule('x', 'a');
$rule = new ManyRule('xx', $aRule);
$parser = new Parser($rule);
$output = $parser->parse('a');

// Output
$output = array('a');


// Test
$aRule = new StringRule('x', 'a');
$rule = new ManyRule('xx', $aRule);
$parser = new Parser($rule);
$output = $parser->parse('aa');

// Output
$output = array('a', 'a');


// Test
$aRule = new StringRule('x', 'a');
$rule = new ManyRule('xx', $aRule, 1);
$parser = new Parser($rule);
$output = $parser->parse('');

// Output
throw new ParserException('x', 0);


// Test
$aRule = new StringRule('x', 'a');
$rule = new ManyRule('xx', $aRule, 1);
$parser = new Parser($rule);
$output = $parser->parse('a');

// Output
$output = array('a');


// Test
$aRule = new StringRule('x', 'a');
$rule = new ManyRule('xx', $aRule, 1);
$parser = new Parser($rule);
$output = $parser->parse('aa');

// Output
$output = array('a', 'a');


// Test
$aRule = new StringRule('x', 'a');
$rule = new ManyRule('xx', $aRule, 1, 1);
$parser = new Parser($rule);
$output = $parser->parse('');

// Output
throw new ParserException('x', 0);


// Test
$aRule = new StringRule('x', 'a');
$rule = new ManyRule('xx', $aRule, 1, 1);
$parser = new Parser($rule);
$output = $parser->parse('a');

// Output
$output = array('a');


// Test
$aRule = new StringRule('x', 'a');
$rule = new ManyRule('xx', $aRule, 1, 1);
$parser = new Parser($rule);
$output = $parser->parse('aa');

// Output
throw new ParserException(null, 1);


// Test
$aRule = new StringRule('x', 'a');
$rule = new ManyRule('xx', $aRule, null, null, 'count');
$parser = new Parser($rule);
$output = $parser->parse('aaaaaa');

// Output
$output = 6;


// Test
$aRule = new StringRule('x', 'a');
$bRule = new StringRule('y', 'b');
$andRule = new AndRule('xy', array($aRule, $bRule));
$rule = new ManyRule('xy', $andRule, 1, 1);
$parser = new Parser($rule);
$output = $parser->parse('');

// Output
throw new ParserException('x', 0);
