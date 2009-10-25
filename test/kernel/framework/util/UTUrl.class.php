<?php

import('util/Url');

class UTurl extends PHPBoostUnitTestCase {

	function test_constructor()
	{
		$url = new Url('' , '..', '/phpboost-svn/trunk/forum/topic.php');
		$rel = $url->relative();
		$abs = $url->absolute();
		$tmp = $url->path_to_root();
		$this->assertEquals('', $rel, $rel . ' != /forum/');
		$this->assertEquals('', $abs, $abs . ' != ' . Url::get_absolute_root() . '/forum/');

		global $CONFIG;
		$CONFIG2 = $CONFIG;
		$CONFIG['server_name'] = 'http://freerepairpc.net';
		$CONFIG['server_path'] = '';
		$url = new Url('menus.php#m42', '../..', '/admin/menus/links.php');
		
        $this->assertEquals('/admin/menus/menus.php#m42', $url->relative(), $url->relative() . ' != /admin/menus/menus.php#m42');
        $this->assertEquals('http://freerepairpc.net/admin/menus/menus.php#m42', $url->absolute(),
            $url->absolute() . ' != http://freerepairpc.net/admin/menus/menus.php#m42');
		$CONFIG = $CONFIG2;
		
	}

	function test_relative()
	{
		$url = new Url('url');
		$rel = $url->relative();
		$this->assertTrue($url->is_relative());

		unset($url);
		$url = new Url('/url');
		$rel = $url->relative();
		$this->assertTrue($url->is_relative());

		unset($url);
		$url = new Url('protocole://url/../coucou//file');
		$rel = $url->relative();
		$this->assertFalse($url->is_relative());
	}

	function test_path_to_root()
	{
		$url = new Url('');
		$path = $url->path_to_root();
		$this->assertEquals(PATH_TO_ROOT, $path);
		$path = $url->path_to_root('root');
		$this->assertEquals('root', $path);
	}

	function test_server_url()
	{
		$url = new Url('');
		$path = $url->server_url();
		$this->assertEquals(SERVER_URL, $path);
		$path = $url->path_to_root('server_url');
		$this->assertEquals('server_url', $path);
	}

	function test_compress()
	{
		$url = new Url('/news/../admin/menus/../updates/../../wiki/wiki.php#welcome');
		$this->assertEquals('/wiki/wiki.php#welcome', $url->relative());
	}

	function test_anchors()
	{
		$url = new Url('/news/../admin/menus/../updates/../../wiki/wiki.php#welcome');
        $this->assertEquals('/wiki/wiki.php#welcome', $url->relative());
		$url = new Url('#welcome');
        $this->assertEquals('#welcome', $url->absolute());
	}

	function test_get_relative()
	{
		$url = Url::get_relative('url', '..', '/forum/topic.php');
		$this->assertEquals('/forum/url', $url);
	}

	function test_get_wellformness_regex()
	{
		$this->assertEquals(Url::get_wellformness_regex(REGEX_MULTIPLICITY_REQUIRED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED), URL::FORBID_JS_REGEX . URL::PROTOCOL_REGEX);

		$this->assertEquals(Url::get_wellformness_regex(REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED), URL::USER_REGEX);

		$this->assertEquals(Url::get_wellformness_regex(REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED), URL::DOMAIN_REGEX);

		$this->assertEquals(Url::get_wellformness_regex(REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_REQUIRED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED), URL::FOLDERS_REGEX);

		$this->assertEquals(Url::get_wellformness_regex(REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED), URL::FILE_REGEX);

		$this->assertEquals(Url::get_wellformness_regex(REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_REQUIRED, REGEX_MULTIPLICITY_NOT_USED), URL::ARGS_REGEX);

		$this->assertEquals(Url::get_wellformness_regex(REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_REQUIRED), URL::ANCHOR_REGEX);

		$this->assertEquals(Url::get_wellformness_regex(REGEX_MULTIPLICITY_REQUIRED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED,
		REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, REGEX_MULTIPLICITY_NOT_USED, false), URL::PROTOCOL_REGEX);
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
		$this->assertTrue(Url::check_wellformness('http://boughafer.free.fr/kernel/framework/ajax/display_stats.php?visit_month=1&year=2009&month=8'));
        $this->assertTrue(Url::check_wellformness('http://www.hiboox.fr/go/images/informatique/erreur,cd530f4bc06560c2a61b8ee0e7d2169a.jpg.html'));
        $this->assertTrue(Url::check_wellformness('http://www.hiboox.fr/go/images/informatique/erreur?cd530f4bc06560c2a61b8ee0e7d2169a'));
	}
}

?>