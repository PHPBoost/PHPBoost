<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 11 07
 * @since       PHPBoost 3.0 - 2011 04 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminExtendedFieldsDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('admin-user-common');
		$this->set_title($lang['extended-field']);

		$this->add_link($lang['extended-fields-management'], AdminExtendedFieldsUrlBuilder::fields_list());
		$this->add_link($lang['extended-field-add'], AdminExtendedFieldsUrlBuilder::add());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
