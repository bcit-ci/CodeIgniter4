<?php

/**
 * This file is part of the CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CodeIgniter\Exceptions;

/**
 * Cast Exceptions.
 */
class CastException extends CriticalError
{
	use DebugTraceableTrait;

	/**
	 * Error code
	 *
	 * @var integer
	 */
	protected $code = 3;

	public static function forInvalidJsonFormatException(int $error)
	{
		switch($error)
		{
			case JSON_ERROR_DEPTH:
				return new static(lang('Cast.jsonErrorDepth'));
			case JSON_ERROR_STATE_MISMATCH:
				return new static(lang('Cast.jsonErrorStateMismatch'));
			case JSON_ERROR_CTRL_CHAR:
				return new static(lang('Cast.jsonErrorCtrlChar'));
			case JSON_ERROR_SYNTAX:
				return new static(lang('Cast.jsonErrorSyntax'));
			case JSON_ERROR_UTF8:
				return new static(lang('Cast.jsonErrorUtf8'));
			default:
				return new static(lang('Cast.jsonErrorUnknown'));
		}
	}

	public static function forMissingInterface($class) : self
	{
		return new static(lang('Cast.BaseCastMissing', [$class]));
	}

	public static function forInvalidCastMethod() : self
	{
		return new static(lang('Cast.wrongCastMethod'));
	}

	public static function forInvalidTimestamp() : self
	{
		return new static(lang('Cast.invalidTimestamp'));
	}

}
