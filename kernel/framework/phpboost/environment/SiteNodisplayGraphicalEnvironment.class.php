<?php
/**
 * @package     PHPBoost
 * @subpackage  Environment
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2017 06 28
 * @since       PHPBoost 3.0 - 2009 10 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SiteNodisplayGraphicalEnvironment extends AbstractGraphicalEnvironment
{
	/**
	 * {@inheritdoc}
	 */
	function display($content)
	{
		self::no_session_location();

		$this->process_site_maintenance();

		echo $content;
	}
}
?>
