<?php
/**
 * This class defines the minimalist controler pattern to initialize common variables
 * @package     MVC
 * @subpackage  Controller
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 29
 * @since       PHPBoost 6.0 - 2021 11 29
*/

abstract class DefaultAdminController extends AdminController
{
	protected $lang;
	protected $view;
	
	public function __construct()
	{
		$this->lang = LangLoader::get_all_langs('admin');
		$this->view = new StringTemplate('# INCLUDE MESSAGE_HELPER # # INCLUDE FORM #');
	}
}
?>
