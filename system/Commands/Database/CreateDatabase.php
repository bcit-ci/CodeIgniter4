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

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;

/**
 * Creates a new database migration file.
 *
 * @package CodeIgniter\Commands
 */
class CreateDatabase extends BaseCommand
{

	/**
	 * The group the command is lumped under
	 * when listing commands.
	 *
	 * @var string
	 */
	protected $group = 'Database';

	/**
	 * The Command's name
	 *
	 * @var string
	 */
	protected $name = 'db:go';

	/**
	 * the Command's short description
	 *
	 * @var string
	 */
	protected $description = 'Create a new database.';

	/**
	 * the Command's usage
	 *
	 * @var string
	 */
	protected $usage = 'db:go [db_name]';

	/**
	 * the Command's Arguments
	 *
	 * @var array
	 */
	protected $arguments = [
		'db_name' => 'The database name',
	];

	/**
	 * the Command's Options
	 *
	 * @var array
	 */
	protected $options = [];

	/**
	 * Creates a new database migration file with the current timestamp.
	 *
	 * @param array $params
	 */
	public function run(array $params = [])
	{
		helper('inflector');
		$name = array_shift($params);

		if (empty($name))
		{
			$name = CLI::prompt('Database name');
		}

		if (empty($name))
		{
			CLI::write('You must provide a ' . CLI::color('database name', 'red') . '.');
			return;
		}

		if(!empty($name))
		{
			try
			{
				$connect = \Config\Database::connect();
			}
			catch (\Exception $e)
			{
				$this->showError($e);
			}

			if($connect->hostname != NULL && $connect->username != NULL)
			{
				try
				{
					$forge = \Config\Database::forge();
					$forge->createDatabase($name);
					return CLI::write('Create database ' . CLI::color($name, 'green') . ' successfully');
				}
				catch (\Exception $e)
				{
					return CLI::write('Database ' . CLI::color($name, 'red') . ' exists');
				}
			}
			else
			{
				CLI::write('Please check your database configuration in' . CLI::color(' .env', 'red') . ' file or' . CLI::color(' app/Config/Database.php', 'red'));
			}
		}
	}

}
