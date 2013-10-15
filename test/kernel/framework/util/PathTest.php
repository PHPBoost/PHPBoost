<?php

class PathTest extends PHPBoostUnitTestCase
{
	public function test_phpboost_path()
	{
		self::assertEquals(str_replace('\\', '/', realpath(PATH_TO_ROOT)), Path::phpboost_path());
	}

	public function test_get_path_from_root()
	{
		$path_from_root = '/kernel/framework/util';
		$path = Path::phpboost_path() . $path_from_root;
		self::assertEquals($path_from_root, Path::get_path_from_root($path));
	}

	public function test_get_package()
	{
		$package = 'kernel/framework/util';
		$class_file = Path::phpboost_path() . '/' . $package . '/File.class.php';
		self::assertEquals($package, Path::get_package($class_file));
	}

	public function test_get_classname()
	{
		$classname = 'File';
		$class_file = Path::phpboost_path() . '/kernel/framework/util/' . $classname . '.class.php';
		self::assertEquals($classname, Path::get_classname($class_file));
	}

	public function test_get_classname_without_extension()
	{
		$classname = 'File';
		$class_file = Path::phpboost_path() . '/kernel/framework/util/' . $classname;
		self::assertEquals($classname, Path::get_classname($class_file));
	}

	public function test_get_classname_without_package()
	{
		$classname = 'File';
		$class_file = $classname . '.class.php';
		self::assertEquals($classname, Path::get_classname($class_file));
	}
}
?>