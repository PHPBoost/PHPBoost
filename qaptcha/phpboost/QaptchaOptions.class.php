<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.0 - 2014 05 09
*/

class QaptchaOptions implements CaptchaOptions
{
	private $case_sensitive;

	public function __construct()
	{
		$this->case_sensitive = QaptchaConfig::load()->is_case_sensitive();
	}

	public function is_case_sensitive() { return $this->case_sensitive; }
}
?>
