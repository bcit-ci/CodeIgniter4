<?php

namespace CodeIgniter\Commands;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\Filters\CITestStreamFilter;
use function is_file;
use function str_replace;
use function stream_filter_append;
use function stream_filter_remove;
use function substr;
use function trim;
use function unlink;
use const DIRECTORY_SEPARATOR;
use const STDERR;
use const STDOUT;

/**
 * @internal
 */
final class ConfigGeneratorTest extends CIUnitTestCase
{
    protected $streamFilter;

    protected function setUp(): void
    {
        CITestStreamFilter::$buffer = '';

        $this->streamFilter = stream_filter_append(STDOUT, 'CITestStreamFilter');
        $this->streamFilter = stream_filter_append(STDERR, 'CITestStreamFilter');
    }

    protected function tearDown(): void
    {
        stream_filter_remove($this->streamFilter);

        $result = str_replace(["\033[0;32m", "\033[0m", "\n"], '', CITestStreamFilter::$buffer);
        $file   = str_replace('APPPATH' . DIRECTORY_SEPARATOR, APPPATH, trim(substr($result, 14)));
        is_file($file) && unlink($file);
    }

    public function testGenerateConfig()
    {
        command('make:config auth');
        $this->assertFileExists(APPPATH . 'Config/Auth.php');
    }

    public function testGenerateConfigWithOptionSuffix()
    {
        command('make:config auth -suffix');
        $this->assertFileExists(APPPATH . 'Config/AuthConfig.php');
    }
}
