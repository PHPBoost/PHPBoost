<?php

require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/begin.php';

import('util/url');

unset($Errorh);

class UnitTest_url extends MY_UnitTestCase {

	function test()
	{
		$url = new Url('' , '..', '/phpboost-svn/trunk/forum/topic.php');
		$this->MY_check_methods($url);
	}

	function test_constructor()
	{
		$url = new Url('' , '..', '/phpboost-svn/trunk/forum/topic.php');
		$rel = $url->relative();
		$abs = $url->absolute();
		$tmp = $url->path_to_root();
		$this->assertIdentical('', $rel, $rel . ' != /forum/');
		//echo '<br />';
		$this->assertIdentical('', $abs, $abs . ' != ' . Url::get_absolute_root() . '/forum/');
		//echo '<br />';

		unset($url);
		$url = new Url('url');
		$rel = $url->relative();
		//var_dump($rel);
		$abs = $url->absolute();
		//var_dump($abs);
		$tmp = $url->path_to_root;
		//var_dump($tmp);
		//echo '<br />';

		unset($url);
		$url = new Url('/url');
		$rel = $url->relative();
		//var_dump($rel);
		$abs = $url->absolute();
		//var_dump($abs);
		$tmp = $url->path_to_root;
		//var_dump($tmp);
		//echo '<br />';

		unset($url);
		$url = new Url('http://test@test.com/url');
		$rel = $url->relative();
		//var_dump($rel);
		$abs = $url->absolute();
		//var_dump($abs);
		$tmp = $url->path_to_root;
		//var_dump($tmp);
		//echo '<br />';

		unset($url);
		$url = new Url('https://test@test.com/url');
		$rel = $url->relative();
		//var_dump($rel);
		$abs = $url->absolute();
		//var_dump($abs);
		$tmp = $url->path_to_root;
		//var_dump($tmp);
		//echo '<br />';

		unset($url);
		$url = new Url('ftp://test@test.com/url');
		$rel = $url->relative();
		//var_dump($rel);
		$abs = $url->absolute();
		//var_dump($abs);
		$tmp = $url->path_to_root;
		//var_dump($tmp);
		//echo '<br />';

		unset($url);
		$url = new Url('file://test@test.com/url');
		$rel = $url->relative();
		//var_dump($rel);
		$abs = $url->absolute();
		//var_dump($abs);
		$tmp = $url->path_to_root;
		//var_dump($tmp);
		//echo '<br />';

		unset($url);
		$url = new Url('', 'path_to_root');
		$rel = $url->relative();
		//var_dump($rel);
		$abs = $url->absolute();
		//var_dump($abs);
		$tmp = $url->path_to_root();
		//var_dump($tmp);
		//echo '<br />';
		
		global $CONFIG;
		$CONFIG2 = $CONFIG;
		$CONFIG['server_name'] = 'http://freerepairpc.net';
		$CONFIG['server_path'] = '';
		$url = new Url('menus.php#m42', '../..', '/admin/menus/links.php');
		
        $this->assertIdentical('/admin/menus/menus.php#m42', $url->relative(), $url->relative() . ' != /admin/menus/menus.php#m42');
        $this->assertIdentical('http://freerepairpc.net/admin/menus/menus.php#m42', $url->absolute(),
            $url->absolute() . ' != http://freerepairpc.net/admin/menus/menus.php#m42');
		$CONFIG = $CONFIG2;
		
	}

	function test_relative()
	{
		$url = new Url('url');
		$rel = $url->relative();
		//var_dump($rel);
		$this->assertTrue($url->is_relative());
		//echo '<br />';

		unset($url);
		$url = new Url('/url');
		$rel = $url->relative();
		//var_dump($rel);
		$this->assertTrue($url->is_relative());
		//echo '<br />';

		unset($url);
		$url = new Url('protocole://url/../coucou//file');
		$rel = $url->relative();
		//var_dump($rel);
		//echo '<br />';
		$this->assertFalse($url->is_relative());
	}

	function test_path_to_root()
	{
		$url = new Url('');
		$path = $url->path_to_root();
		$this->assertIdentical(PATH_TO_ROOT, $path);
		$path = $url->path_to_root('root');
		//var_dump($path);
		$this->assertIdentical('root', $path);
		//echo '<br />';
	}

	function test_server_url()
	{
		$url = new Url('');
		$path = $url->server_url();
		$this->assertIdentical(SERVER_URL, $path);
		$path = $url->path_to_root('server_url');
		//var_dump($path);
		$this->assertIdentical('server_url', $path);
		//echo '<br />';
	}

	function test_compress()
	{
		$url = new Url('/news/../admin/menus/../updates/../../wiki/wiki.php#welcome');
		//var_dump($url);
		//echo '<br />';
		$this->assertIdentical('/wiki/wiki.php#welcome', $url->relative());
	}

	function test_anchors()
	{
		$url = new Url('/news/../admin/menus/../updates/../../wiki/wiki.php#welcome');
        $this->assertIdentical('/wiki/wiki.php#welcome', $url->relative());
		$url = new Url('#welcome');
        //var_dump($url);
        //echo '<br />';
        $this->assertIdentical('#welcome', $url->absolute());
	}

	function test_get_relative()
	{
		$url = Url::get_relative('url', '..', '/forum/topic.php');
		//var_dump($url);
		$this->assertIdentical('/forum/url', $url);
		//echo '<br />';
	}

	function test_get_wellformness_regex()
	{
		echo "<br>".__METHOD__."<br>\n";
		echo '<pre>' . htmlentities(Url::get_wellformness_regex()) . '</pre>';
		echo "<br /><br />\n";

		static $forbid_js_regex = '(?!javascript:)';
		static $protocol_regex = '[a-z0-9-_]+(?::[a-z0-9-_]+)*://';
		static $user_regex = '[a-z0-9-_]+(?::[a-z0-9-_]+)?@';
		static $domain_regex = '(?:[a-z0-9-_~]+\.)*[a-z0-9-_~]+(?::[0-9]{1,5})?/';
		static $folders_regex = '/*(?:[a-z0-9~_\.-]+/+)*';
		static $file_regex = '[a-z0-9-+_~:\.\%]+';
		static $args_regex = '(?:\?(?!&)(?:(?:&amp;|&)?[a-z0-9-+=_~:;/\.\?\'\%]+=[a-z0-9-+=_~:;/\.\?\'\%]+)*)?';
        static $anchor_regex = '\#[a-z0-9-_/]*';

		$this->assertIdentical(Url::get_wellformness_regex(REGEX_MULTIPLICITY_REQUIRED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED), $forbid_js_regex . $protocol_regex);

		$this->assertIdentical(Url::get_wellformness_regex(REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED), $user_regex);

		$this->assertIdentical(Url::get_wellformness_regex(REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED), $domain_regex);

		$this->assertIdentical(Url::get_wellformness_regex(REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_REQUIRED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED), $folders_regex);

		$this->assertIdentical(Url::get_wellformness_regex(REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED), $file_regex);

		$this->assertIdentical(Url::get_wellformness_regex(REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_NOT_USED), $args_regex);

		$this->assertIdentical(Url::get_wellformness_regex(REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_REQUIRED), $anchor_regex);

		$this->assertIdentical(Url::get_wellformness_regex(REGEX_MULTIPLICITY_REQUIRED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, false), $protocol_regex);
	}

	function test_check_wellformness()
	{
		$this->assertTrue(Url::check_wellformness('http://www.phpboost.com'));
		$this->assertFalse(Url::check_wellformness('http://www.phpboost.com', REGEX_MULTIPLICITY_REQUIRED,
		REGEX_MULTIPLICITY_OPTIONNAL, REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_REQUIRED,
		REGEX_MULTIPLICITY_OPTIONNAL, REGEX_MULTIPLICITY_OPTIONNAL, REGEX_MULTIPLICITY_OPTIONNAL));
		$this->assertFalse(Url::check_wellformness('http://www.phpboost.com', REGEX_MULTIPLICITY_REQUIRED,
		REGEX_MULTIPLICITY_OPTIONNAL, REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_OPTIONNAL,
		REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_OPTIONNAL, REGEX_MULTIPLICITY_OPTIONNAL));
		$this->assertFalse(Url::check_wellformness('http://www.phpboost.com', REGEX_MULTIPLICITY_REQUIRED,
		REGEX_MULTIPLICITY_OPTIONNAL, REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_OPTIONNAL,
		REGEX_MULTIPLICITY_OPTIONNAL, REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_OPTIONNAL));
		$this->assertFalse(Url::check_wellformness('http://www.phpboost.com', REGEX_MULTIPLICITY_REQUIRED,
		REGEX_MULTIPLICITY_OPTIONNAL, REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_OPTIONNAL,
		REGEX_MULTIPLICITY_OPTIONNAL, REGEX_MULTIPLICITY_OPTIONNAL, REGEX_MULTIPLICITY_REQUIRED));
		$this->assertTrue(Url::check_wellformness('http://www.phpboost.com/'));
		$this->assertTrue(Url::check_wellformness('http://www.phpboost.com/admin/'));
		$this->assertTrue(Url::check_wellformness('http://www.phpboost.com/admin/menus/'));
		$this->assertTrue(Url::check_wellformness('http://www.phpboost.com/admin/menus/menus.php'));
		$this->assertTrue(Url::check_wellformness('http://www.phpboost.com/admin/menus/menus.php?id=3'));
		$this->assertTrue(Url::check_wellformness('http://www.phpboost.com/admin/menus/menus.php?id=3&save=true'));
		$this->assertTrue(Url::check_wellformness('http://www.phpboost.com/admin/menus/menus.php?id=3&save=true#4'));
		$this->assertTrue(Url::check_wellformness('http://user@www.phpboost.com/admin/menus/menus.php?id=3&save=true#4'));
		$this->assertTrue(Url::check_wellformness('http://user:password@www.phpboost.com/admin/menus/menus.php?id=3&save=true#42'));
		$this->assertFalse(Url::check_wellformness('http://user:pass@word@www.phpboost.com/admin/menus/menus.php?id=3&save=true#m24'));
		$this->assertFalse(Url::check_wellformness('http://user:pass:word@www.phpboost.com/admin/menus/menus.php?id=3&save=true#4'));
		$this->assertTrue(Url::check_wellformness('www.phpboost.com/admin/menus/menus.php?id=3&save=true#4'));
		$this->assertFalse(Url::check_wellformness('www.phpboost.com/admin/menus/menus.php?id=3&save=true#4', REGEX_MULTIPLICITY_REQUIRED));
		$this->assertTrue(Url::check_wellformness('/admin/menus/menus.php?id=3&save=true#4'));
		$this->assertTrue(Url::check_wellformness('admin/menus/menus.php?id=3&save=true#4'));
		$this->assertFalse(Url::check_wellformness('/admin/menus/menus.php?id=3&save=true#4', REGEX_MULTIPLICITY_OPTIONNAL, REGEX_MULTIPLICITY_OPTIONNAL, REGEX_MULTIPLICITY_REQUIRED));
		$this->assertTrue(Url::check_wellformness('admin/menus/menus.php?id=3&save=true#4', REGEX_MULTIPLICITY_OPTIONNAL, REGEX_MULTIPLICITY_OPTIONNAL, REGEX_MULTIPLICITY_REQUIRED));
		$this->assertTrue(Url::check_wellformness('jdbc:mysql://user:password@www.server.com/db/mysql/connect.php?ids=3&transaxtion=true#4',
		REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_REQUIRED,
		REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_REQUIRED));
		$this->assertFalse(Url::check_wellformness('javascript:window.open(\'coucou\');',
		REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_REQUIRED,
		REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_REQUIRED, true));
		$this->assertTrue(Url::check_wellformness('javascript:cou.cou',
		REGEX_MULTIPLICITY_OPTIONNAL, REGEX_MULTIPLICITY_OPTIONNAL, REGEX_MULTIPLICITY_OPTIONNAL, REGEX_MULTIPLICITY_OPTIONNAL,
		REGEX_MULTIPLICITY_OPTIONNAL, REGEX_MULTIPLICITY_OPTIONNAL, REGEX_MULTIPLICITY_OPTIONNAL, false));
		$this->assertTrue(Url::check_wellformness('http://www.phpboost.com/news.php', REGEX_MULTIPLICITY_REQUIRED));
	}
}

?>