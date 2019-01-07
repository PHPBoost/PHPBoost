<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 02 24
 * @since   	PHPBoost 4.0 - 2014 09 02
 * @contributor xela <xela@phpboost.com>
*/

class AdminFaqDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('common', 'faq');
		$this->set_title($lang['module_title']);

		$this->add_link(LangLoader::get_message('configuration', 'admin-common'), FaqUrlBuilder::configuration());
		$this->add_link(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('faq')->get_configuration()->get_documentation());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page, $lang['module_title']);
	}
}
?>
