<?php

namespace Example;

use SpencerMortensen\Parser\String\Parser;
use SpencerMortensen\Parser\String\Rules;

class ZipParser extends Parser
{
	private $rule;

	public function __construct()
	{
		$grammar = <<<'EOS'
zip: AND basicCode optionalExtraSuffix
basicCode: MANY digit 5 5
digit: RE [0-9]
optionalExtraSuffix: MANY extraSuffix 0 1
extraSuffix: AND dash extraCode
dash: STRING -
extraCode: RE [0-9]{4}
EOS;

		$rules = new Rules($this, $grammar);
		$this->rule = $rules->getRule('zip');
	}

	public function parse($input)
	{
		return $this->run($this->rule, $input);
	}

	public function getZip(array $parts)
	{
		return array(
			'basic' => $parts[0],
			'extra' => $parts[1]
		);
	}

	public function getBasicCode(array $digits)
	{
		return implode('', $digits);
	}

	public function getOptionalExtraSuffix(array $values)
	{
		return array_shift($values);
	}

	public function getExtraSuffix(array $parts)
	{
		return $parts[1];
	}
}
