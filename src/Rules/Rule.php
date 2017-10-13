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

namespace SpencerMortensen\Parser\Rules;

abstract class Rule
{
	const TYPE_AND = 1;
	const TYPE_CALLABLE = 2;
	const TYPE_MANY = 3;
	const TYPE_OR = 4;
	const TYPE_RE = 5;
	const TYPE_STRING = 6;

	/** @var string */
	private $name;

	/** @var integer */
	private $type;

	/** @var null|callable */
	private $formatter;

	public function __construct($name, $type, $formatter = null)
	{
		$this->name = $name;
		$this->type = $type;
		$this->formatter = $formatter;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getType()
	{
		return $this->type;
	}

	public function getFormatter()
	{
		return $this->formatter;
	}
}
