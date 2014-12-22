<?php



class urlTest extends PHPBoostUnitTestCase {

	function test_constructor()
	{
		$url = new Url('' , '..', '/phpboost-svn/trunk/forum/topic.php');
		$rel = $url->relative();
		$abs = $url->absolute();
		self::assertEquals('', $rel, $rel . ' != /forum/');
		self::assertEquals('', $abs, $abs . ' != ' . Url::get_absolute_root() . '/forum/');

		
		$site_url = GeneralConfig::load()->get_site_url();
		$url = new Url('menus.php#m42', '../..', '/admin/menus/links.php');
		
        self::assertEquals('/admin/menus/menus.php#m42', $url->relative(), $url->relative() . ' != /admin/menus/menus.php#m42');
        self::assertEquals($site_url . '/admin/menus/menus.php#m42', $url->absolute(),
            $url->absolute() . ' != ' . $site_url . '/admin/menus/menus.php#m42');
		
	}

	function test_relative()
	{
		$url = new Url('url');
		$rel = $url->relative();
		self::assertTrue($url->is_relative());

		unset($url);
		$url = new Url('/url');
		$rel = $url->relative();
		self::assertTrue($url->is_relative());

		unset($url);
		$url = new Url('protocole://url/../coucou//file');
		$rel = $url->relative();
		self::assertFalse($url->is_relative());
	}

	function test_compress()
	{
		$url = new Url('/news/../admin/menus/../updates/../../wiki/wiki.php#welcome');
		self::assertEquals('/wiki/wiki.php#welcome', $url->relative());
	}

	function test_anchors()
	{
		$url = new Url('/news/../admin/menus/../updates/../../wiki/wiki.php#welcome');
        self::assertEquals('/wiki/wiki.php#welcome', $url->relative());
		$url = new Url('#welcome');
        self::assertEquals('#welcome', $url->absolute());
	}

	function test_get_relative()
	{
		$url = Url::get_relative('url', '..', '/forum/topic.php');
		self::assertEquals('/forum/url', $url);
	}

	function test_get_wellformness_regex()
	{
		self::assertEquals(Url::get_wellformness_regex(RegexHelper::REGEX_MULTIPLICITY_REQUIRED,
		RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED,
		RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED), URL::FORBID_JS_REGEX . URL::PROTOCOL_REGEX);

		self::assertEquals(Url::get_wellformness_regex(RegexHelper::REGEX_MULTIPLICITY_NOT_USED,
		RegexHelper::REGEX_MULTIPLICITY_REQUIRED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED,
		RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED), URL::USER_REGEX);

		self::assertEquals(Url::get_wellformness_regex(RegexHelper::REGEX_MULTIPLICITY_NOT_USED,
		RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_REQUIRED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED,
		RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED), URL::DOMAIN_REGEX);

		self::assertEquals(Url::get_wellformness_regex(RegexHelper::REGEX_MULTIPLICITY_NOT_USED,
		RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_REQUIRED,
		RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED), URL::FOLDERS_REGEX);

		self::assertEquals(Url::get_wellformness_regex(RegexHelper::REGEX_MULTIPLICITY_NOT_USED,
		RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED,
		RegexHelper::REGEX_MULTIPLICITY_REQUIRED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED), URL::FILE_REGEX);

		self::assertEquals(Url::get_wellformness_regex(RegexHelper::REGEX_MULTIPLICITY_NOT_USED,
		RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED,
		RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_REQUIRED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED), URL::ARGS_REGEX);

		self::assertEquals(Url::get_wellformness_regex(RegexHelper::REGEX_MULTIPLICITY_NOT_USED,
		RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED,
		RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_REQUIRED), URL::ANCHOR_REGEX);

		self::assertEquals(Url::get_wellformness_regex(RegexHelper::REGEX_MULTIPLICITY_REQUIRED,
		RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED,
		RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED, RegexHelper::REGEX_MULTIPLICITY_NOT_USED, false), URL::PROTOCOL_REGEX);
	}

	function test_check_wellformness()
	{
		self::assertTrue(Url::check_wellformness('http://www.phpboost.com'));
		self::assertFalse(Url::check_wellformness('http://www.phpboost.com', RegexHelper::REGEX_MULTIPLICITY_REQUIRED,
		RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, RegexHelper::REGEX_MULTIPLICITY_REQUIRED, RegexHelper::REGEX_MULTIPLICITY_REQUIRED,
		RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL));
		self::assertFalse(Url::check_wellformness('http://www.phpboost.com', RegexHelper::REGEX_MULTIPLICITY_REQUIRED,
		RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, RegexHelper::REGEX_MULTIPLICITY_REQUIRED, RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL,
		RegexHelper::REGEX_MULTIPLICITY_REQUIRED, RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL));
		self::assertFalse(Url::check_wellformness('http://www.phpboost.com', RegexHelper::REGEX_MULTIPLICITY_REQUIRED,
		RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, RegexHelper::REGEX_MULTIPLICITY_REQUIRED, RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL,
		RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, RegexHelper::REGEX_MULTIPLICITY_REQUIRED, RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL));
		self::assertFalse(Url::check_wellformness('http://www.phpboost.com', RegexHelper::REGEX_MULTIPLICITY_REQUIRED,
		RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, RegexHelper::REGEX_MULTIPLICITY_REQUIRED, RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL,
		RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, RegexHelper::REGEX_MULTIPLICITY_REQUIRED));
		self::assertTrue(Url::check_wellformness('http://www.phpboost.com/'));
		self::assertTrue(Url::check_wellformness('http://www.phpboost.com/admin/'));
		self::assertTrue(Url::check_wellformness('http://www.phpboost.com/admin/menus/'));
		self::assertTrue(Url::check_wellformness('http://www.phpboost.com/admin/menus/menus.php'));
		self::assertTrue(Url::check_wellformness('http://www.phpboost.com/admin/menus/menus.php?id=3'));
		self::assertTrue(Url::check_wellformness('http://www.phpboost.com/admin/menus/menus.php?id=3&save=true'));
		self::assertTrue(Url::check_wellformness('http://www.phpboost.com/admin/menus/menus.php?id=3&save=true#4'));
		self::assertTrue(Url::check_wellformness('http://user@www.phpboost.com/admin/menus/menus.php?id=3&save=true#4'));
		self::assertTrue(Url::check_wellformness('http://user:password@www.phpboost.com/admin/menus/menus.php?id=3&save=true#42'));
		self::assertFalse(Url::check_wellformness('http://user:pass@word@www.phpboost.com/admin/menus/menus.php?id=3&save=true#m24'));
		self::assertFalse(Url::check_wellformness('http://user:pass:word@www.phpboost.com/admin/menus/menus.php?id=3&save=true#4'));
		self::assertTrue(Url::check_wellformness('www.phpboost.com/admin/menus/menus.php?id=3&save=true#4'));
		self::assertFalse(Url::check_wellformness('www.phpboost.com/admin/menus/menus.php?id=3&save=true#4', RegexHelper::REGEX_MULTIPLICITY_REQUIRED));
		self::assertTrue(Url::check_wellformness('/admin/menus/menus.php?id=3&save=true#4'));
		self::assertTrue(Url::check_wellformness('admin/menus/menus.php?id=3&save=true#4'));
		self::assertFalse(Url::check_wellformness('/admin/menus/menus.php?id=3&save=true#4', RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, RegexHelper::REGEX_MULTIPLICITY_REQUIRED));
		self::assertTrue(Url::check_wellformness('admin/menus/menus.php?id=3&save=true#4', RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, RegexHelper::REGEX_MULTIPLICITY_REQUIRED));
		self::assertTrue(Url::check_wellformness('jdbc:mysql://user:password@www.server.com/db/mysql/connect.php?ids=3&transaxtion=true#4',
		RegexHelper::REGEX_MULTIPLICITY_REQUIRED, RegexHelper::REGEX_MULTIPLICITY_REQUIRED, RegexHelper::REGEX_MULTIPLICITY_REQUIRED, RegexHelper::REGEX_MULTIPLICITY_REQUIRED,
		RegexHelper::REGEX_MULTIPLICITY_REQUIRED, RegexHelper::REGEX_MULTIPLICITY_REQUIRED, RegexHelper::REGEX_MULTIPLICITY_REQUIRED));
		self::assertFalse(Url::check_wellformness('javascript:window.open(\'coucou\');',
		RegexHelper::REGEX_MULTIPLICITY_REQUIRED, RegexHelper::REGEX_MULTIPLICITY_REQUIRED, RegexHelper::REGEX_MULTIPLICITY_REQUIRED, RegexHelper::REGEX_MULTIPLICITY_REQUIRED,
		RegexHelper::REGEX_MULTIPLICITY_REQUIRED, RegexHelper::REGEX_MULTIPLICITY_REQUIRED, RegexHelper::REGEX_MULTIPLICITY_REQUIRED, true));
		self::assertTrue(Url::check_wellformness('javascript:cou.cou',
		RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL,
		RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, false));
		self::assertTrue(Url::check_wellformness('http://www.phpboost.com/news.php', RegexHelper::REGEX_MULTIPLICITY_REQUIRED));
		self::assertTrue(Url::check_wellformness('http://boughafer.free.fr/kernel/framework/ajax/display_stats.php?visit_month=1&year=2009&month=8'));
        self::assertTrue(Url::check_wellformness('http://www.hiboox.fr/go/images/informatique/erreur,cd530f4bc06560c2a61b8ee0e7d2169a.jpg.html'));
        self::assertTrue(Url::check_wellformness('http://www.hiboox.fr/go/images/informatique/erreur?cd530f4bc06560c2a61b8ee0e7d2169a'));
	}
}

?>