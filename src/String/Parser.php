<?php

/**
 * Copyright (C) 2017 Spencer Mortensen
 *
 * This file is part of parser.
 *
 * Parser is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Parser is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with parser. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Spencer Mortensen <spencer@lens.guide>
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL-3.0
 * @copyright 2017 Spencer Mortensen
 */

namespace SpencerMortensen\Parser\String;

use ErrorException;
use SpencerMortensen\Parser\Expectation;
use SpencerMortensen\Parser\ParserException;
use SpencerMortensen\Parser\Rules\AndRule;
use SpencerMortensen\Parser\Rules\ManyRule;
use SpencerMortensen\Parser\Rules\CallableRule;
use SpencerMortensen\Parser\Rules\OrRule;
use SpencerMortensen\Parser\Rules\ReRule;
use SpencerMortensen\Parser\Rules\Rule;
use SpencerMortensen\Parser\Rules\StringRule;

class Parser
{
	/** @var Rule */
	private $rule;

	/** @var Lexer */
	private $lexer;

	/** @var Expectation */
	private $expectation;

	public function __construct(Rule $rule)
	{
		$this->rule = $rule;
	}

	public function parse($input)
	{
		$this->lexer = new Lexer($input);
		$this->setExpectation(null);

		if (!$this->runRule($this->rule, $output) || !$this->lexer->isHalted()) {
			throw $this->parserException();
		}

		return $output;
	}

	private function runRule(Rule $rule, &$output = null)
	{
		$type = $rule->getType();

		switch ($type) {
			case Rule::TYPE_AND:
				/** @var AndRule $rule */
				return $this->runAndRule($rule, $output);

			case Rule::TYPE_MANY:
				/** @var ManyRule $rule */
				return $this->runManyRule($rule, $output);

			case Rule::TYPE_CALLABLE:
				/** @var CallableRule $rule */
				return $this->runCallableRule($rule, $output);

			case Rule::TYPE_OR:
				/** @var OrRule $rule */
				return $this->runOrRule($rule, $output);

			case Rule::TYPE_RE:
				/** @var ReRule $rule */
				return $this->runReRule($rule, $output);

			case Rule::TYPE_STRING:
				/** @var StringRule $rule */
				return $this->runStringRule($rule, $output);

			default:
				throw $this->unknownRuleType($type);
		}
	}

	private function runAndRule(AndRule $rule, &$output)
	{
		$state = clone $this->lexer;

		$childRules = $rule->getRules();
		$input = array();

		foreach ($childRules as $childRule) {
			$this->setExpectation($childRule);

			if (!$this->runRule($childRule, $input[])) {
				$this->lexer = $state;
				return false;
			}
		}

		$output = $this->formatOutput($rule, $input);
		$this->setExpectation(null);
		return true;
	}

	private function runManyRule(ManyRule $rule, &$output)
	{
		$state = clone $this->lexer;

		$childRule = $rule->getRule();
		$min = $rule->getMin();
		$max = $rule->getMax();

		$input = array();

		for ($i = 0; (($max === null) || ($i < $max)); ++$i) {
			$this->setExpectation($childRule);

			if (!$this->runRule($childRule, $inputValue)) {
				break;
			}

			$input[] = $inputValue;
		}

		if ((($min !== null) && ($i < $min))) {
			$this->lexer = $state;
			return false;
		}

		$output = $this->formatOutput($rule, $input);
		$this->setExpectation(null);
		return true;
	}

	private function runCallableRule(CallableRule $rule, &$output)
	{
		$callable = $rule->getCallable();

		if (!call_user_func_array($callable, array(&$output))) {
			$this->setExpectation($rule);
			return false;
		}

		$this->setExpectation(null);
		return true;
	}

	private function runOrRule(OrRule $rule, &$output)
	{
		$childRules = $rule->getRules();

		foreach ($childRules as $childRule) {
			if ($this->runRule($childRule, $input)) {
				$output = $this->formatOutput($rule, $input);
				$this->setExpectation(null);
				return true;
			}
		}

		$this->setExpectation($rule);
		return false;
	}

	private function runReRule(ReRule $rule, &$output)
	{
		$expression = $rule->getExpression();

		if (!$this->lexer->getRe($expression, $input)) {
			$this->setExpectation($rule);
			return false;
		}

		$output = $this->formatReOutput($rule, $input);
		$this->setExpectation(null);
		return true;
	}

	private function formatReOutput(Rule $rule, array $input)
	{
		$formatter = $rule->getFormatter();

		if ($formatter !== null) {
			return call_user_func($formatter, $input);
		}

		return $input[0];
	}

	private function runStringRule(StringRule $rule, &$output)
	{
		$string = $rule->getString();

		if (!$this->lexer->getString($string)) {
			$this->setExpectation($rule);
			return false;
		}

		$output = $this->formatOutput($rule, $string);
		$this->setExpectation(null);
		return true;
	}

	private function formatOutput(Rule $rule, $input)
	{
		$formatter = $rule->getFormatter();

		if ($formatter !== null) {
			return call_user_func($formatter, $input);
		}

		return $input;
	}

	private function parserException()
	{
		$ruleName = $this->expectation->getRuleName();
		$state = $this->expectation->getState();

		return new ParserException($ruleName, $state);
	}

	private function unknownRuleType($type)
	{
		$ruleType = json_encode($type);

		return new ErrorException("Unknown rule type ($ruleType)");
	}

	private function setExpectation(Rule $rule = null)
	{
		if ($rule === null) {
			$ruleName = null;
		} else {
			$ruleName = $rule->getName();
		}

		$position = $this->lexer->getPosition();

		$this->expectation = new Expectation($ruleName, $position);
	}
}
