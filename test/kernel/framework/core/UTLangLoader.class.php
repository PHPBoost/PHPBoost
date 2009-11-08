<?php

import('core/lang/LangLoader');

class UTLangLoader extends PHPBoostUnitTestCase {

	public function setUp()
	{
		LangLoader::clear_lang_cache();
		LangLoader::set_locale(LangLoader::DEFAULT_LOCALE);
	}

	public function test_unexisting_lang()
	{
		try
		{
			LangLoader::get('unexisting_lang', '/test/data/');
			$this->assertFalse(true, 'LangNotFoundException not raised');
		}
		catch (Exception $exception)
		{
			$this->assertType('LangNotFoundException', $exception);
		}
	}

	public function test_get_kernel()
	{
		$lang = LangLoader::get('ErrorViewBuilder');
		$this->assertTrue(!empty($lang));
	}

	public function test_get()
	{
		$lang = LangLoader::get('mylang_common', 'test/data/');
		$expected = array(
			'common_lang_var1' => 'hello1',
			'common_lang_var2' => 'hello2',
			'common_lang_var3' => 'hello3');

		$this->assertEquals($expected, $lang);
	}

	public function test_get_imbricated()
	{
		$lang = LangLoader::get('mylang', '/test/data/');
		$expected = array(
			'common_lang_var1' => 'hello1',
			'common_lang_var2' => 'hello2',
			'common_lang_var3' => 'hello3',
			'lang_var1' => 'hello1',
			'lang_var2' => 'hello2',
			'lang_var3' => 'hello3');

		$this->assertEquals($expected, $lang);
	}

	public function test_get_imbricated_with_locale()
	{
		LangLoader::set_locale('french');
		$lang = LangLoader::get('mylang', '/test/data/');
		$expected = array(
			'common_lang_var1' => 'hello1',
			'common_lang_var2' => 'hello2',
			'common_lang_var3' => 'hello3',
			'lang_var1' => 'coucou1',
			'lang_var2' => 'coucou2',
			'lang_var3' => 'coucou3');
		$this->assertEquals($expected, $lang);
	}

	public function test_get_class()
	{
		$class_file = Path::phpboost_path() .
			'/admin/menus/controllers/MenuControllerConfigurationsList.class.php';
		LangLoader::get_class($class_file, '/test/data/');
	}
}
