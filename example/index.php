<?php

namespace Example;

use SpencerMortensen\Parser\ParserException;

require __DIR__ . '/autoload.php';

$input = '01234';
echo parse($input), "\n";
// {"basic":"01234","extra":null}

$input = '$zipCode';
echo parse($input), "\n";
// Expected "digit" at position 0, but found '$zipCode' instead.

$input = '01234-0123';
echo parse($input), "\n";
// {"basic":"01234","extra":"0123"}

$input = '01234/0123';
echo parse($input), "\n";
// Expected nothing at position 5, but found '/0123' instead.

function parse($input)
{
	try {
		$parser = new ZipParser();
		$data = $parser->parse($input);

		return json_encode($data);
	} catch (ParserException $exception) {
		$rule = $exception->getRule();
		$position = $exception->getState();

		if ($rule === null) {
			$ruleText = 'nothing';
		} else {
			$ruleText = json_encode($rule);
		}

		$tailText = var_export(substr($input, $position), true);

		return "Expected {$ruleText} at position {$position}, but found {$tailText} instead.";
	}
}
