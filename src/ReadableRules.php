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
 * @author Spencer Mortensen <spencer@testphp.org>
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL-3.0
 * @copyright 2017 Spencer Mortensen
 */

namespace SpencerMortensen\Parser;

use ErrorException;
use SpencerMortensen\Parser\Rules\AndRule;
use SpencerMortensen\Parser\Rules\ManyRule;
use SpencerMortensen\Parser\Rules\CallableRule;
use SpencerMortensen\Parser\Rules\OrRule;
use SpencerMortensen\Parser\Rules\ReRule;
use SpencerMortensen\Parser\Rules\Rule;
use SpencerMortensen\Parser\Rules\StringRule;

class ReadableRules
{
	/** @var Object */
	private $object;

	/** @var Rule[] */
	private $rules;

	public function __construct($object, $grammar)
	{
		$this->object = $object;
		$this->setRules($grammar);
	}

	public function getRule($name)
	{
		$rule = &$this->rules[$name];

		if ($rule === null) {
			throw $this->unknownRuleName($name);
		}

		return $rule;
	}

	private function setRules($grammar)
	{
		$this->rules = array();

		$grammar = trim($grammar);
		$lines = explode("\n", $grammar);

		foreach ($lines as $line) {
			$line = trim($line);

			if (strlen($line) === 0) {
				continue;
			}

			list($name, $text) = explode(':', $line, 2);
			list($type, $definition) = explode(' ', ltrim($text), 2);

			if (isset($this->rules[$name])) {
				throw $this->redefinedRule($name);
			}

			$this->rules[$name] = $this->createRule($name, $type, $definition);
		}

		foreach ($this->rules as $name => $rule) {
			if ($rule === null) {
				throw $this->undefinedRule($name);
			}
		}
	}

	private function createRule($name, $type, $definition)
	{
		$type = strtolower($type);

		switch ($type) {
			case 'and':
				return $this->createAndRule($name, $definition);

			case 'many':
				return $this->createManyRule($name, $definition);

			case 'method':
				return $this->createMethodRule($name, $definition);

			case 'or':
				return $this->createOrRule($name, $definition);

			case 're':
				return $this->createReRule($name, $definition);

			case 'string':
				return $this->createStringRule($name, $definition);

			default:
				throw $this->unknownRuleType($type);
		}
	}

	private function createAndRule($name, $definition)
	{
		$rules = $this->getRulesList($definition);
		$formatter = $this->getFormatter($name);

		return new AndRule($name, $rules, $formatter);
	}

	private function createManyRule($name, $definition)
	{
		$arguments = explode(' ', $definition);
		$childRuleName = array_shift($arguments);
		$childRule = &$this->rules[$childRuleName];

		$arguments = array_map('intval', $arguments);
		$min = &$arguments[0];
		$max = &$arguments[1];

		$formatter = $this->getFormatter($name);

		return new ManyRule($name, $childRule, $min, $max, $formatter);
	}

	private function createMethodRule($name, $definition)
	{
		$method = $definition;
		$callable = array($this->object, $method);

		return new CallableRule($name, $callable);
	}

	private function createOrRule($name, $definition)
	{
		$rules = $this->getRulesList($definition);
		$formatter = $this->getFormatter($name);

		return new OrRule($name, $rules, $formatter);
	}

	private function createReRule($name, $definition)
	{
		$expression = $definition;
		$formatter = $this->getFormatter($name);

		return new ReRule($name, $expression, $formatter);
	}

	private function createStringRule($name, $definition)
	{
		$string = $definition;
		$formatter = $this->getFormatter($name);

		return new StringRule($name, $string, $formatter);
	}

	private function getRulesList($definition)
	{
		$rules = array();

		$names = explode(' ', $definition);

		foreach ($names as $name) {
			$rules[] = &$this->rules[$name];
		}

		return $rules;
	}

	private function getFormatter($name)
	{
		$method = 'get' . ucfirst($name);
		$callable = array($this->object, $method);

		if (is_callable($callable)) {
			return $callable;
		}

		return null;
	}

	private function undefinedRule($name)
	{
		$nameText = json_encode($name);

		return new ErrorException("The rule {$nameText} has no definition.");
	}

	private function redefinedRule($name)
	{
		$nameText = json_encode($name);

		return new ErrorException("The rule {$nameText} has multiple definitions.");
	}

	private function unknownRuleName($name)
	{
		$nameText = json_encode($name);

		return new ErrorException("The rule {$nameText} is unknown.");
	}

	private function unknownRuleType($type)
	{
		$typeText = json_encode($type);

		return new ErrorException("The rule type {$typeText} is unknown.");
	}
}
