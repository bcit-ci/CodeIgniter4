<?php namespace CodeIgniter\Autoloader;

use Config\Autoload;

//--------------------------------------------------------------------

class AutoloaderTest extends \CIUnitTestCase
{

	protected $loader;

	//--------------------------------------------------------------------

	protected function setUp()
	{
		$config = new Autoload();

		$config->classmap = [
			'FirstClass'        => '/app/dir/First.php',
			'Name\Spaced\Class' => '/app/namespace/Class.php',
		];
		$config->psr4 = [
			'App\Controllers' => '/application/Controllers',
			'App\Libraries'   => '/application/somewhere',
		];

		$this->loader = new MockAutoloader();
		$this->loader->initialize($config);

		$this->loader->setFiles([
			'/application/Controllers/Classname.php',
			'/application/somewhere/Classname.php',
			'/app/dir/First.php',
			'/app/namespace/Class.php',
		    '/my/app/Class.php',
		    APPPATH.'Libraries/someLibrary.php',
		    APPPATH.'Models/someModel.php',
		]);
	}

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// PSR4 Namespacing
	//--------------------------------------------------------------------

	public function testServiceAutoLoaderFromShareInstances() {

		$auto_loader = \CodeIgniter\Config\Services::autoloader();
		// $auto_loader->register();
		$actual   = $auto_loader->loadClass('App\Controllers\Checks');
		$expected = APPPATH.'Controllers/Checks.php';
		$this->assertSame($expected, $actual);
	}

	//--------------------------------------------------------------------

	public function testServiceAutoLoader() {

		$getShared = false;
		$auto_loader = \CodeIgniter\Config\Services::autoloader($getShared);
		$auto_loader->initialize(new Autoload());
		$auto_loader->register();
		$actual   = $auto_loader->loadClass('App\Controllers\Checks');
		$expected = APPPATH.'Controllers/Checks.php';
		$this->assertSame($expected, $actual);
	}

	//--------------------------------------------------------------------

	public function testExistingFile()
	{
		$actual   = $this->loader->loadClass('App\Controllers\Classname');
		$expected = '/application/Controllers/Classname.php';
		$this->assertSame($expected, $actual);

		$actual   = $this->loader->loadClass('App\Libraries\Classname');
		$expected = '/application/somewhere/Classname.php';
		$this->assertSame($expected, $actual);
	}

	//--------------------------------------------------------------------

	public function testMatchesWithPreceedingSlash()
	{
		$actual   = $this->loader->loadClass('\App\Controllers\Classname');
		$expected = '/application/Controllers/Classname.php';
		$this->assertSame($expected, $actual);
	}

	//--------------------------------------------------------------------

	public function testMatchesWithFileExtension()
	{
		$actual   = $this->loader->loadClass('\App\Controllers\Classname.php');
		$expected = '/application/Controllers/Classname.php';
		$this->assertSame($expected, $actual);
	}

	//--------------------------------------------------------------------

	public function testMissingFile()
	{
		$this->assertFalse($this->loader->loadClass('App\Missing\Classname'));
	}

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------

	/**
	 * @expectedException        \InvalidArgumentException
	 * @expectedExceptionMessage Config array must contain either the 'psr4' key or the 'classmap' key.
	 */
	public function testInitializeException()
	{
		$config = new Autoload();
		$config->classmap = [];
		$config->psr4 = [];

		$this->loader = new MockAutoloader();
		$this->loader->initialize($config);
	}

	public function testAddNamespaceWorks()
	{
		$this->assertFalse($this->loader->loadClass('My\App\Class'));

		$this->loader->addNamespace('My\App', '/my/app');

		$actual = $this->loader->loadClass('My\App\Class');
		$expected = '/my/app/Class.php';

		$this->assertSame($expected, $actual);
	}

	public function testAddNamespaceMultiplePathsWorks()
	{
		$this->loader->addNamespace('My\App', '/my/app');
		$this->loader->addNamespace('My\App', '/test/app');
		$this->loader->setFiles([
			'/my/app/Class.php',
			'/test/app/ClassTest.php',
		]);

		$actual = $this->loader->loadClass('My\App\ClassTest');
		$expected = '/test/app/ClassTest.php';
		$this->assertSame($expected, $actual);

		$actual = $this->loader->loadClass('My\App\Class');
		$expected = '/my/app/Class.php';
		$this->assertSame($expected, $actual);
	}

	public function testAddNamespaceStingToArray()
	{
		$this->loader->addNamespace('App\Controllers', '/application/Controllers');

		$this->assertSame('/application/Controllers/Classname.php', $this->loader->loadClass('App\Controllers\Classname'));
	}

        //--------------------------------------------------------------------
        
        public function testRemoveNamespace()
        {
                $this->loader->addNamespace('My\App', '/my/app');
                $this->assertSame('/my/app/Class.php',$this->loader->loadClass('My\App\Class'));
                
                $this->loader->removeNamespace('My\App');
                $this->assertFalse((bool)$this->loader->loadClass('My\App\Class'));
        }

	//--------------------------------------------------------------------

	public function testLoadLegacy()
	{
	    $this->assertFalse((bool)$this->loader->loadClass('someLibraries'));
	    $this->assertTrue((bool)$this->loader->loadClass('someLibrary'));
	    $this->assertTrue((bool)$this->loader->loadClass('someModel'));
	}

	//--------------------------------------------------------------------

	public function testSanitizationSimply()
	{
		$test = '${../path}!#/to/some/file.php_';
		$expected = '/path/to/some/file.php';

		$this->assertEquals($expected, $this->loader->sanitizeFilename($test));
	}

	//--------------------------------------------------------------------
	
	public function testSanitizationAllowsWindowsFilepaths()
	{
		$test = 'C:\path\to\some/file.php';

		$this->assertEquals($test, $this->loader->sanitizeFilename($test));
	}

	//--------------------------------------------------------------------
}
