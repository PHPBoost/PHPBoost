<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 06
 * @since       PHPBoost 6.0 - 2021 04 06
*/

class PagesConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('pages');

		$this->config_parameters_to_modify = array(
			'count_hits_activated' => 'views_number_enabled'
		);
	}
}
?>
