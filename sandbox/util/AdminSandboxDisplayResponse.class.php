<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 11 09
 * @since       PHPBoost 5.1 - 2017 06 16
*/

class AdminSandboxDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('common', 'sandbox');
		$this->set_title($lang['sandbox.module.title']);

		$this->add_link(LangLoader::get_message('configuration', 'admin'), SandboxUrlBuilder::config());
		$this->add_link($lang['title.admin.form'], SandboxUrlBuilder::admin_form());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page, $lang['sandbox.module.title']);
	}
}
?>
