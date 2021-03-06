<?php

namespace SpencerMortensen\Parser\Test;

use SpencerMortensen\Parser\String\Rules;
use SpencerMortensen\Parser\String\Parser;

class TestParser extends Parser
{
	/** @var Rules */
	private $rules;

	public function __construct()
	{
		$grammar = <<<'EOS'
andAB: AND a b
a: STRING a
b: STRING b
orAB: OR a b
manyA01: MANY a 0 1
manyA11: MANY a 1 1
andandABC: AND andAB c
c: STRING c
andorABC: AND orAB c
orandABC: OR andAB c
orCandAB: OR c andAB
EOS;

		$this->rules = new Rules($this, $grammar);
	}

	public function parse($name, $input)
	{
		$rule = $this->rules->getRule($name);
		return $this->run($rule, $input);
	}

	public function getAndAB(array $input)
	{
		return implode(' and ', $input);
	}

	public function getA($input)
	{
		return strtoupper($input);
	}

	public function getB($input)
	{
		return strtoupper($input);
	}

	public function getManyA01(array $input)
	{
		return implode('', $input);
	}

	public function getManyA11(array $input)
	{
		return implode('', $input);
	}

	public function getC($input)
	{
		return strtoupper($input);
	}

	public function getAndorABC(array $input)
	{
		return implode(' and ', $input);
	}
}
