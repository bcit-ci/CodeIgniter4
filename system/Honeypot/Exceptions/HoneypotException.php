<?php

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014-2019 British Columbia Institute of Technology
 * Copyright (c) 2019-2020 CodeIgniter Foundation
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package    CodeIgniter
 * @author     CodeIgniter Dev Team
 * @copyright  2019-2020 CodeIgniter Foundation
 * @license    https://opensource.org/licenses/MIT - MIT License
 * @link       https://codeigniter.com
 * @since      Version 4.0.0
 * @filesource
 */

namespace CodeIgniter\Honeypot\Exceptions;

use CodeIgniter\Exceptions\ConfigException;
use CodeIgniter\Exceptions\ExceptionInterface;

class HoneypotException extends ConfigException implements ExceptionInterface
{
	/**
	 * Thrown when template isn't set or empty.
	 *
	 * @return \CodeIgniter\Honeypot\Exceptions\HoneypotException
	 */
	public static function forNoTemplate()
	{
		return new static(lang('Honeypot.noTemplate'));
	}

  	//--------------------------------------------------------------------

	/**
	 * Thrown when there is no name for the field.
	 *
	 * @return \CodeIgniter\Honeypot\Exceptions\HoneypotException
	 */
	public static function forNoNameField()
	{
		return new static(lang('Honeypot.noNameField'));
	}

  	//--------------------------------------------------------------------

	/**
	 * Thrown when there isn't hidden value.
	 *
	 * @return \CodeIgniter\Honeypot\Exceptions\HoneypotException
	 */
	public static function forNoHiddenValue()
	{
		return new static(lang('Honeypot.noHiddenValue'));
	}

  	//--------------------------------------------------------------------

	/**
	 * Thrown when the client is a bot.
	 *
	 * @return \CodeIgniter\Honeypot\Exceptions\HoneypotException
	 */
	public static function isBot()
	{
		return new static(lang('Honeypot.theClientIsABot'));
	}
}
