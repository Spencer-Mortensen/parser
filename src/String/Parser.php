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

use SpencerMortensen\Parser\Core\Parser as CoreParser;
use SpencerMortensen\Parser\ParserException;
use SpencerMortensen\Parser\Rule;
use SpencerMortensen\Parser\String\Rules\ReRule;
use SpencerMortensen\Parser\String\Rules\StringRule;

abstract class Parser extends CoreParser
{
	/** @var Lexer */
	private $lexer;

	/** @var array */
	private $expectation;

	protected function run(Rule $rule, $input)
	{
		$this->lexer = new Lexer($input);
		$this->setExpectation(null);

		if (!$this->runRule($rule, $output) || !$this->lexer->isHalted()) {
			throw $this->parserException();
		}

		return $output;
	}

	protected function runRule(Rule $rule, &$output = null)
	{
		if ($rule instanceof ReRule) { /** @var ReRule $rule */
			return $this->runReRule($rule, $output);
		} elseif ($rule instanceof StringRule) { /** @var StringRule $rule */
			return $this->runStringRule($rule, $output);
		} else {
			return parent::runRule($rule, $output);
		}
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

	private function formatReOutput(Rule $rule, $input)
	{
		$callable = $rule->getCallable();

		if ($callable !== null) {
			return call_user_func($callable, $input);
		}

		return $input;
	}

	private function runStringRule(StringRule $rule, &$output)
	{
		$string = $rule->getString();

		if (!$this->lexer->getString($string)) {
			$this->setExpectation($rule);
			return false;
		}

		$output = $this->formatStringOutput($rule, $string);
		$this->setExpectation(null);
		return true;
	}

	private function formatStringOutput(Rule $rule, $input)
	{
		$callable = $rule->getCallable();

		if ($callable !== null) {
			return call_user_func($callable, $input);
		}

		return $input;
	}

	private function parserException()
	{
		list($ruleName, $position) = $this->expectation;

		return new ParserException($ruleName, $position);
	}

	protected function getState()
	{
		return clone $this->lexer;
	}

	protected function setState($lexer)
	{
		$this->lexer = $lexer;
	}

	protected function setExpectation(Rule $rule = null)
	{
		if ($rule === null) {
			$ruleName = null;
		} else {
			$ruleName = $rule->getName();
		}

		$position = $this->lexer->getPosition();

		$this->expectation = array($ruleName, $position);
	}

	protected function getPosition()
	{
		return $this->lexer->getPosition();
	}
}
