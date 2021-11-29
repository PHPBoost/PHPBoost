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

abstract class DefaultModuleController extends ModuleController
{
	/**
	 * @var HTTPRequestCustom
	 */
	protected $request;

	protected $config;
	protected $lang;
	
	public function __construct($module_id = '')
	{
		parent::__construct($module_id);
		$this->request = AppContext::get_request();
		$this->config = self::get_module_configuration()->get_configuration_parameters();
		$this->lang = LangLoader::get_all_langs(self::$module_id);
	}
}
?>
