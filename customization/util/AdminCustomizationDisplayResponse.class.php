<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 02 24
 * @since       PHPBoost 3.0 - 2011 08 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class AdminCustomizationDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('common', 'customization');
		$this->set_title($lang['customization']);

		$this->add_link($lang['customization.interface'], AdminCustomizeUrlBuilder::customize_interface());
		$this->add_link($lang['customization.favicon'], AdminCustomizeUrlBuilder::customize_favicon());
		$this->add_link($lang['customization.editor.css-files'], AdminCustomizeUrlBuilder::editor_css_file());
		$this->add_link($lang['customization.editor.tpl-files'], AdminCustomizeUrlBuilder::editor_tpl_file());
		$this->add_link(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('customization')->get_configuration()->get_documentation());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
