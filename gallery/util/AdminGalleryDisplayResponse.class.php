<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 02 27
 * @since       PHPBoost 4.1 - 2015 02 10
 * @contributor xela <xela@phpboost.com>
*/

class AdminGalleryDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('common', 'gallery');
		$this->set_title($lang['module_title']);

		$this->add_link($lang['gallery.management'], GalleryUrlBuilder::manage());
		$this->add_link($lang['gallery.actions.add'], GalleryUrlBuilder::admin_add());
		$this->add_link(LangLoader::get_message('configuration', 'admin-common'), GalleryUrlBuilder::configuration());
		$this->add_link(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('gallery')->get_configuration()->get_documentation());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page, $lang['module_title']);
	}
}
?>
