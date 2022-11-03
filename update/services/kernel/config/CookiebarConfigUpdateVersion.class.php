<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 19
 * @since       PHPBoost 6.0 - 2020 04 29
*/

class CookiebarConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('kernel', false, 'kernel-cookiebar-config');
	}

	protected function build_new_config()
	{
		$old_config = $this->get_old_config();
		if ($old_config)
		{
			$config = CookiebarConfig::load();
			$unparser = new OldBBCodeUnparser();
			$parser = new BBCodeParser();
			$old_content = $old_config->get_property('cookiebar_content');
			
			$unparser->set_content($old_content);
			$unparser->parse();
			$parser->set_content($unparser->get_content());
			$parser->parse();
			
			if ($parser->get_content() != $old_content)
			{
				$config->set_cookiebar_content($parser->get_content());
			}

			$old_about_content = $old_config->get_property('cookiebar_aboutcookie_content');
			
			$unparser->set_content($old_about_content);
			$unparser->parse();
			$parser->set_content($unparser->get_content());
			$parser->parse();
			
			if ($parser->get_content() != $old_about_content)
			{
				$config->set_cookiebar_aboutcookie_content($parser->get_content());
			}
		}
		
		$this->save_new_config('kernel-cookiebar-config', $config);

		return true;
	}
}
?>
