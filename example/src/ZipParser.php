<?php

namespace Example;

use SpencerMortensen\Parser\ReadableRules;
use SpencerMortensen\Parser\String\Parser;

class ZipParser extends Parser
{
	public function __construct()
	{
		$grammar = <<<'EOS'
zip: AND basicCode optionalExtraSuffix
basicCode: MANY digit 5 5
digit: RE [0-9]
optionalExtraSuffix: MANY extraSuffix 0
extraSuffix: AND dash extraCode
dash: STRING -
extraCode: RE [0-9]{4}
EOS;

		$rules = new ReadableRules($this, $grammar);
		$rule = $rules->getRule('zip');

		parent::__construct($rule);
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

	public function getExtraCode(array $match)
	{
		return $match[0];
	}
}