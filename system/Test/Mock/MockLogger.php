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
 * @license    https://opensource.org/licenses/MIT	MIT License
 * @link       https://codeigniter.com
 * @since      Version 4.0.0
 * @filesource
 */

namespace CodeIgniter\Test\Mock;

class MockLogger
{
	/**
	 * --------------------------------------------------------------------------
	 *  Error Logging Threshold
	 * --------------------------------------------------------------------------
	 *
	 * You can enable error logging by setting a threshold over zero. The
	 * threshold determines what gets logged. Any values below or equal to the
	 * threshold will be logged. Threshold options are:
	 *
	 *	0 = Disables logging, Error logging TURNED OFF
	 *	1 = Emergency Messages  - System is unusable
	 *	2 = Alert Messages      - Action Must Be Taken Immediately
	 *  3 = Critical Messages   - Application component unavailable, unexpected exception.
	 *  4 = Runtime Errors      - Don't need immediate action, but should be monitored.
	 *  5 = Warnings            - Exceptional occurrences that are not errors.
	 *  6 = Notices             - Normal but significant events.
	 *  7 = Info                - Interesting events, like user logging in, etc.
	 *  8 = Debug               - Detailed debug information.
	 *  9 = All Messages
	 *
	 * You can also pass an array with threshold levels to show individual error types
	 *
	 * 	array(1, 2, 3, 8) = Emergency, Alert, Critical, and Debug messages
	 *
	 * For a live site you'll usually enable Critical or higher (3) to be logged otherwise
	 * your log files will fill up very fast.
	 *
	 * @var int
	 */
	public $threshold = 9;

	/**
	 * --------------------------------------------------------------------------
	 *  Date Format for Logs
	 * --------------------------------------------------------------------------
	 *
	 * Each item that is logged has an associated date. You can use PHP date
	 * codes to set your own date formatting
	 *
	 * @var string
	 */
	public $dateFormat = 'Y-m-d';

	/**
	 * --------------------------------------------------------------------------
	 *  Log Handlers
	 * --------------------------------------------------------------------------
	 *
	 * The logging system supports multiple actions to be taken when something
	 * is logged. This is done by allowing for multiple Handlers, special classes
	 * designed to write the log to their chosen destinations, whether that is
	 * a file on the getServer, a cloud-based service, or even taking actions such
	 * as emailing the dev team.
	 *
	 * Each handler is defined by the class name used for that handler, and it
	 * MUST implement the CodeIgniter\Log\Handlers\HandlerInterface interface.
	 *
	 * The value of each key is an array of configuration items that are sent
	 * to the constructor of each handler. The only required configuration item
	 * is the 'handles' element, which must be an array of integer log levels.
	 * This is most easily handled by using the constants defined in the
	 * Psr\Log\LogLevel class.
	 *
	 * Handlers are executed in the order defined in this array, starting with
	 * the handler on top and continuing down.
	 *
	 * @var array
	 */
	public $handlers = [
		//--------------------------------------------------------------------
		// File Handler
		//--------------------------------------------------------------------

		'Tests\Support\Log\Handlers\TestHandler' => [
			// The log levels that this handler will handle.
			'handles' => [
				'critical',
				'alert',
				'emergency',
				'debug',
				'error',
				'info',
				'notice',
				'warning',
			],

			// Logging Directory Path
			'path' => '',
		],
	];
}
