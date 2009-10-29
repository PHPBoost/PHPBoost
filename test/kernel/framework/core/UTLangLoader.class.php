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
			LangLoader::get('/test/data/lang/unexisting_lang');
			$this->assertFalse(true, 'LangNotFoundException not raised');
		}
		catch (Exception $exception)
		{
			$this->assertType('LangNotFoundException', $exception);
		}
	}

	public function test_get()
	{
		$lang = LangLoader::get('/test/data/lang/mylang_common');
		$expected = array(
			'common_lang_var1' => 'hello1',
			'common_lang_var2' => 'hello2',
			'common_lang_var3' => 'hello3');

		$this->assertEquals($expected, $lang);
	}

	public function test_get_imbricated()
	{
		$lang = LangLoader::get('/test/data/lang/mylang');
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
		$lang = LangLoader::get('/test/data/lang/mylang');
		$expected = array(
			'common_lang_var1' => 'hello1',
			'common_lang_var2' => 'hello2',
			'common_lang_var3' => 'hello3',
			'lang_var1' => 'coucou1',
			'lang_var2' => 'coucou2',
			'lang_var3' => 'coucou3');
		$this->assertEquals($expected, $lang);
	}

	public function test_get_file()
	{
		$lang = LangLoader::get_file(PATH_TO_ROOT . '/test/data/mylang.cla.inc.ss.php');
		$expected = array(
			'common_lang_var1' => 'hello1',
			'common_lang_var2' => 'hello2',
			'common_lang_var3' => 'hello3',
			'lang_var1' => 'hello1',
			'lang_var2' => 'hello2',
			'lang_var3' => 'hello3');
		$this->assertEquals($expected, $lang);
	}
}
