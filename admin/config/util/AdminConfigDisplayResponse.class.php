<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 11 07
 * @since       PHPBoost 3.0 - 2010 04 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminConfigDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('admin-config-common');
		$this->set_title($lang['configuration']);

		$this->add_link($lang['general-config'], AdminConfigUrlBuilder::general_config());
		$this->add_link($lang['advanced-config'], AdminConfigUrlBuilder::advanced_config());
		$this->add_link($lang['mail-config'], AdminConfigUrlBuilder::mail_config());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
