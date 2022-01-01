<?php
/**
 * @package     PHPBoost
 * @subpackage  Theme
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 08 31
*/

class CustomizeInterface
{
	private $header_logo_path = '';

	public function set_header_logo_path($header_logo_path)
	{
		$this->header_logo_path = $header_logo_path;
	}

	public function get_header_logo_path()
	{
		return $this->header_logo_path;
	}

	public function remove_header_logo_path()
	{
		$this->header_logo_path = '';
	}
}
?>
