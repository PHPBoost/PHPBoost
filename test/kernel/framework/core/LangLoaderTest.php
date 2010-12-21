<?php

class LangLoaderTest extends PHPBoostUnitTestCase {

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
			self::assertFalse(true, 'LangNotFoundException not raised');
		}
		catch (Exception $exception)
		{
			self::assertType('LangNotFoundException', $exception);
		}
	}

	public function test_get_kernel()
	{
		$lang = LangLoader::get('ErrorViewBuilder');
		self::assertTrue(!empty($lang));
	}

	public function test_get()
	{
		$lang = LangLoader::get('mylang_common', 'test/data/');
		$expected = array(
			'common_lang_var1' => 'hello1',
			'common_lang_var2' => 'hello2',
			'common_lang_var3' => 'hello3');

		self::assertEquals($expected, $lang);
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

		self::assertEquals($expected, $lang);
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
		self::assertEquals($expected, $lang);
	}

	public function test_get_class()
	{
		$classname = 'MenuControllerConfigurationsList';
		LangLoader::get($classname, '/test/data/');
	}
}
