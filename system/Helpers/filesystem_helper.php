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

// CodeIgniter File System Helpers

if (! function_exists('directory_map'))
{
	/**
	 * Create a Directory Map
	 *
	 * Reads the specified directory and builds an array
	 * representation of it. Sub-folders contained with the
	 * directory will be mapped as well.
	 *
	 * @param string $sourceDir      Path to source
	 * @param int    $directoryDepth Depth of directories to traverse
	 *                               (0 = fully recursive, 1 = current dir, etc)
	 * @param bool   $hidden         Whether to show hidden files
	 *
	 * @return array
	 */
	function directory_map(string $sourceDir, int $directoryDepth = 0, bool $hidden = false): array
	{
		try
		{
			$fp = opendir($sourceDir);

			$fileData  = [];
			$newDepth  = $directoryDepth - 1;
			$sourceDir = rtrim($sourceDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

			while (false !== ($file = readdir($fp)))
			{
				// Remove '.', '..', and hidden files [optional]
				if ($file === '.' || $file === '..' || ($hidden === false && $file[0] === '.'))
				{
					continue;
				}

				is_dir($sourceDir . $file) && $file .= DIRECTORY_SEPARATOR;

				if (($directoryDepth < 1 || $newDepth > 0) && is_dir($sourceDir . $file))
				{
					$fileData[$file] = directory_map($sourceDir . $file, $newDepth, $hidden);
				}
				else
				{
					$fileData[] = $file;
				}
			}

			closedir($fp);

			return $fileData;
		}
		catch (Throwable $e)
		{
			return [];
		}
	}
}

if (! function_exists('write_file'))
{
	/**
	 * Write File
	 *
	 * Writes data to the file specified in the path.
	 * Creates a new file if non-existent.
	 *
	 * @param string $path File path
	 * @param string $data Data to write
	 * @param string $mode fopen() mode (default: 'wb')
	 *
	 * @return bool
	 */
	function write_file(string $path, string $data, string $mode = 'wb'): bool
	{
		try
		{
			$fp = fopen($path, $mode);

			flock($fp, LOCK_EX);

			for ($result = $written = 0, $length = strlen($data); $written < $length; $written += $result)
			{
				if (($result = fwrite($fp, substr($data, $written))) === false)
				{
					break;
				}
			}

			flock($fp, LOCK_UN);
			fclose($fp);

			return is_int($result);
		}
		catch (Throwable $e)
		{
			return false;
		}
	}
}

if (! function_exists('delete_files'))
{
	/**
	 * Delete Files
	 *
	 * Deletes all files contained in the supplied directory path.
	 * Files must be writable or owned by the system in order to be deleted.
	 * If the second parameter is set to true, any directories contained
	 * within the supplied base directory will be nuked as well.
	 *
	 * @param string $path   File path
	 * @param bool   $delDir Whether to delete any directories found in the path
	 * @param bool   $htdocs Whether to skip deleting .htaccess and index page files
	 * @param bool   $hidden Whether to include hidden files (files beginning with a period)
	 *
	 * @return bool
	 */
	function delete_files(string $path, bool $delDir = false, bool $htdocs = false, bool $hidden = false): bool
	{
		$path = realpath($path) ?: $path;
		$path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

		try
		{
			foreach (new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
				RecursiveIteratorIterator::CHILD_FIRST
			) as $object)
			{
				$filename = $object->getFilename();

				if (! $hidden && $filename[0] === '.')
				{
					continue;
				}

				if (! $htdocs || ! preg_match('/^(\.htaccess|index\.(html|htm|php)|web\.config)$/i', $filename))
				{
					$isDir = $object->isDir();

					if ($isDir && $delDir)
					{
						@rmdir($object->getPathname());

						continue;
					}

					if (! $isDir)
					{
						@unlink($object->getPathname());
					}
				}
			}

			return true;
		}
		catch (Throwable $e)
		{
			return false;
		}
	}
}

if (! function_exists('get_filenames'))
{
	/**
	 * Get Filenames
	 *
	 * Reads the specified directory and builds an array containing the filenames.
	 * Any sub-folders contained within the specified path are read as well.
	 *
	 * @param string    $sourceDir   Path to source
	 * @param bool|null $includePath Whether to include the path as part of the filename; false for no path, null for a relative path, true for full path
	 * @param bool      $hidden      Whether to include hidden files (files beginning with a period)
	 *
	 * @return array
	 */
	function get_filenames(string $sourceDir, ?bool $includePath = false, bool $hidden = false): array
	{
		$files = [];

		$sourceDir = realpath($sourceDir) ?: $sourceDir;
		$sourceDir = rtrim($sourceDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

		try
		{
			foreach (new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS),
				RecursiveIteratorIterator::SELF_FIRST
			) as $name => $object)
			{
				$basename = pathinfo($name, PATHINFO_BASENAME);

				if (! $hidden && $basename[0] === '.')
				{
					continue;
				}

				if ($includePath === false)
				{
					$files[] = $basename;
				}
				elseif ($includePath === null)
				{
					$files[] = str_replace($sourceDir, '', $name);
				}
				else
				{
					$files[] = $name;
				}
			}
		}
		catch (Throwable $e)
		{
			return [];
		}

		sort($files);

		return $files;
	}
}

if (! function_exists('get_dir_file_info'))
{
	/**
	 * Get Directory File Information
	 *
	 * Reads the specified directory and builds an array containing the filenames,
	 * filesize, dates, and permissions
	 *
	 * Any sub-folders contained within the specified path are read as well.
	 *
	 * @param string $sourceDir    Path to source
	 * @param bool   $topLevelOnly Look only at the top level directory specified?
	 * @param bool   $recursion    Internal variable to determine recursion status - do not use in calls
	 *
	 * @return array
	 */
	function get_dir_file_info(string $sourceDir, bool $topLevelOnly = true, bool $recursion = false): array
	{
		static $fileData = [];
		$relativePath    = $sourceDir;

		try
		{
			$fp = @opendir($sourceDir);
			// reset the array and make sure $source_dir has a trailing slash on the initial call
			if ($recursion === false)
			{
				$fileData  = [];
				$sourceDir = rtrim(realpath($sourceDir), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
			}

			// Used to be foreach (scandir($source_dir, 1) as $file), but scandir() is simply not as fast
			while (false !== ($file = readdir($fp)))
			{
				if (is_dir($sourceDir . $file) && $file[0] !== '.' && $topLevelOnly === false)
				{
					get_dir_file_info($sourceDir . $file . DIRECTORY_SEPARATOR, $topLevelOnly, true);
				}
				elseif ($file[0] !== '.')
				{
					$fileData[$file]                  = get_file_info($sourceDir . $file);
					$fileData[$file]['relative_path'] = $relativePath;
				}
			}

			closedir($fp);

			return $fileData;
		}
		catch (Throwable $fe)
		{
			return [];
		}
	}
}

if (! function_exists('get_file_info'))
{
	/**
	 * Get File Info
	 *
	 * Given a file and path, returns the name, path, size, date modified
	 * Second parameter allows you to explicitly declare what information you want returned
	 * Options are: name, server_path, size, date, readable, writable, executable, fileperms
	 * Returns false if the file cannot be found.
	 *
	 * @param string $file           Path to file
	 * @param mixed  $returnedValues Array or comma separated string of information returned
	 *
	 * @return array|null
	 */
	function get_file_info(string $file, $returnedValues = ['name', 'server_path', 'size', 'date'])
	{
		if (! is_file($file))
		{
			return null;
		}

		if (is_string($returnedValues))
		{
			$returnedValues = explode(',', $returnedValues);
		}

		foreach ($returnedValues as $key)
		{
			switch ($key) {
				case 'name':
					$fileInfo['name'] = basename($file);

					break;
				case 'server_path':
					$fileInfo['server_path'] = $file;

					break;
				case 'size':
					$fileInfo['size'] = filesize($file);

					break;
				case 'date':
					$fileInfo['date'] = filemtime($file);

					break;
				case 'readable':
					$fileInfo['readable'] = is_readable($file);

					break;
				case 'writable':
					$fileInfo['writable'] = is_really_writable($file);

					break;
				case 'executable':
					$fileInfo['executable'] = is_executable($file);

					break;
				case 'fileperms':
					$fileInfo['fileperms'] = fileperms($file);

					break;
			}
		}

		return $fileInfo; // @phpstan-ignore-line
	}
}

if (! function_exists('symbolic_permissions'))
{
	/**
	 * Symbolic Permissions
	 *
	 * Takes a numeric value representing a file's permissions and returns
	 * standard symbolic notation representing that value
	 *
	 * @param int $perms Permissions
	 *
	 * @return string
	 */
	function symbolic_permissions(int $perms): string
	{
		if (($perms & 0xC000) === 0xC000)
		{
			$symbolic = 's'; // Socket
		}
		elseif (($perms & 0xA000) === 0xA000)
		{
			$symbolic = 'l'; // Symbolic Link
		}
		elseif (($perms & 0x8000) === 0x8000)
		{
			$symbolic = '-'; // Regular
		}
		elseif (($perms & 0x6000) === 0x6000)
		{
			$symbolic = 'b'; // Block special
		}
		elseif (($perms & 0x4000) === 0x4000)
		{
			$symbolic = 'd'; // Directory
		}
		elseif (($perms & 0x2000) === 0x2000)
		{
			$symbolic = 'c'; // Character special
		}
		elseif (($perms & 0x1000) === 0x1000)
		{
			$symbolic = 'p'; // FIFO pipe
		}
		else
		{
			$symbolic = 'u'; // Unknown
		}

		// Owner
		$symbolic .= (($perms & 0x0100) ? 'r' : '-')
				. (($perms & 0x0080) ? 'w' : '-')
				. (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x') : (($perms & 0x0800) ? 'S' : '-'));

		// Group
		$symbolic .= (($perms & 0x0020) ? 'r' : '-')
				. (($perms & 0x0010) ? 'w' : '-')
				. (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x') : (($perms & 0x0400) ? 'S' : '-'));

		// World
		$symbolic .= (($perms & 0x0004) ? 'r' : '-')
				. (($perms & 0x0002) ? 'w' : '-')
				. (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x') : (($perms & 0x0200) ? 'T' : '-'));

		return $symbolic;
	}
}

if (! function_exists('octal_permissions'))
{
	/**
	 * Octal Permissions
	 *
	 * Takes a numeric value representing a file's permissions and returns
	 * a three character string representing the file's octal permissions
	 *
	 * @param int $perms Permissions
	 *
	 * @return string
	 */
	function octal_permissions(int $perms): string
	{
		return substr(sprintf('%o', $perms), -3);
	}
}

if (! function_exists('set_realpath'))
{
	/**
	 * Set Realpath
	 *
	 * @param string $path
	 * @param bool   $checkExistence Checks to see if the path exists
	 *
	 * @return string
	 */
	function set_realpath(string $path, bool $checkExistence = false): string
	{
		// Security check to make sure the path is NOT a URL. No remote file inclusion!
		if (preg_match('#^(http:\/\/|https:\/\/|www\.|ftp)#i', $path) || filter_var($path, FILTER_VALIDATE_IP) === $path)
		{
			throw new InvalidArgumentException('The path you submitted must be a local server path, not a URL');
		}

		// Resolve the path
		if (realpath($path) !== false)
		{
			$path = realpath($path);
		}
		elseif ($checkExistence && ! is_dir($path) && ! is_file($path))
		{
			throw new InvalidArgumentException('Not a valid path: ' . $path);
		}

		// Add a trailing slash, if this is a directory
		return is_dir($path) ? rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : $path;
	}
}
