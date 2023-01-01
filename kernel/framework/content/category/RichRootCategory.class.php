<?php
/**
 * @package     Content
 * @subpackage  Category
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 26
 * @since       PHPBoost 4.0 - 2013 01 31
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contrinutor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class RichRootCategory extends RichCategory
{
	public function __construct()
	{
		parent::__construct();
		$this->set_id(self::ROOT_CATEGORY);
		$this->set_id_parent(self::ROOT_CATEGORY);
		$this->set_name(LangLoader::get_message('common.root', 'common-lang'));
		$this->set_rewrited_name('root');
		$this->set_order(0);
		$this->set_additional_property('description', '');
		$this->set_additional_property('thumbnail', New Url());
	}
}
?>
