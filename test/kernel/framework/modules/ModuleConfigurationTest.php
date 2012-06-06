<?php

class ModuleConfigurationTest extends PHPBoostUnitTestCase
{
	private $module_path;
	private $module_config;

	public function setUp()
	{
		$this->module_path = PATH_TO_ROOT . '/test/data/modules/test-module/';
		$this->module_config = new ModuleConfiguration(
		$this->module_path . 'config.ini',
		$this->module_path . '/lang/french/desc.ini');
	}

	public function test___construct()
	{
		new ModuleConfiguration($this->module_path . 'config.ini', $this->module_path . '/lang/french/desc.ini');
	}

	public function test___construct2()
	{
		try
		{
			new ModuleConfiguration($this->module_path . 'config.ini.bad', $this->module_path . '/lang/french/desc.ini');
		}
		catch (Exception $ex)
		{
		}
	}

	public function test___construct3()
	{
		try
		{
			new ModuleConfiguration($this->module_path . 'config.ini', $this->module_path . '/lang/french/desc.ini.bad');
		}
		catch (Exception $ex)
		{
		}
	}

	public function test_get_name()
	{
		self::assertEquals('Module name', $this->module_config->get_name());
	}

	public function test_get_description()
	{
		self::assertEquals('this is the module description', $this->module_config->get_description());
	}

	public function test_get_author()
	{
		self::assertEquals('ben.popeye', $this->module_config->get_author());
	}

	public function test_get_author_email()
	{
		self::assertEquals('ben.popeye@phpboost.com', $this->module_config->get_author_email());
	}

	public function test_get_author_website()
	{
		self::assertEquals('http://www.phpboost.com', $this->module_config->get_author_website());
	}

	public function test_get_version()
	{
		self::assertEquals('3.1', $this->module_config->get_version());
	}

	public function test_get_date()
	{
		self::assertEquals('09/12/2009', $this->module_config->get_date());
	}

	public function test_get_compatibility()
	{
		self::assertEquals('3.1', $this->module_config->get_compatibility());
	}

	public function test_get_repository()
	{
		self::assertEquals(Updates::PHPBOOST_OFFICIAL_REPOSITORY, $this->module_config->get_repository());
	}

	public function test_get_admin_main_page()
	{
		self::assertEquals("admin.php", $this->module_config->get_admin_main_page());
	}

	public function test_get_admin_menu()
	{
		self::assertEquals("content", $this->module_config->get_admin_menu());
	}

	public function test_get_admin_links()
	{
		$admin_menu = array(
            'Categories' => array(
		         'Management' => 'admin_forum.php',
		         'Add' => 'admin_forum_add.php'
		    ),
		    'Configuration' => 'admin_forum_config.php',
		    'Groups' => 'admin_forum_groups.php'
		);
		self::assertEquals($admin_menu, $this->module_config->get_admin_links());
	}

	public function test_get_home_page()
	{
		self::assertEquals('index.php', $this->module_config->get_home_page());
	}

	public function test_get_contribution_interface()
	{
		self::assertEquals('contrib.php', $this->module_config->get_contribution_interface());
	}

	public function test_get_mini_modules()
	{
        $mini_modules = array('MyModuleFirstMiniModule' => 'right', 'MyModuleSecondMiniModule' => 'left', 'MyModuleThirdMiniModule' => 'header');
		self::assertEquals($mini_modules, $this->module_config->get_mini_modules());
	}

	public function test_get_url_rewrite_rules()
	{
		$rewrite_rules = array('a rewrite rule', 'anoter rewrite rule');
		self::assertEquals($rewrite_rules, $this->module_config->get_url_rewrite_rules());
	}
}
?>