<?php
/**
 * This class is used to represent the bread crumb displayed on each page of the website.
 * It enables the user to locate himself in the whole site.
 * A bread crumb can look like this: Home >> My module >> First level category >> Second level category >>
 * Third level category >> .. >> My page >> Edition
 * @package     PHPBoost
 * @subpackage  Environment
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 01
*/

interface GraphicalEnvironment
{
	/**
	 * Displays the environment with content of the page.
	 */
	function display($content);
}
?>
