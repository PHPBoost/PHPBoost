<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 11 09
 * @since   	PHPBoost 5.1 - 2017 06 16
*/

class AdminSandboxDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('common', 'sandbox');
		$this->set_title($lang['mini.module.title']);

		$this->add_link(LangLoader::get_message('configuration', 'admin'), SandboxUrlBuilder::config());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page, $lang['mini.module.title']);
	}
}
?>
