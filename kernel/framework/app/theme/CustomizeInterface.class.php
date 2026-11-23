<?php
/**
 * @package     PHPBoost
 * @subpackage  Theme
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 23
 * @since       PHPBoost 3.0 - 2011 08 31
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CustomizeInterface
{
	private string $header_logo_path = '';

	public function set_header_logo_path(string $header_logo_path): void
	{
		$this->header_logo_path = $header_logo_path;
	}

	public function get_header_logo_path(): string
	{
		return $this->header_logo_path;
	}

	public function remove_header_logo_path(): void
	{
		$this->header_logo_path = '';
	}
}
?>
