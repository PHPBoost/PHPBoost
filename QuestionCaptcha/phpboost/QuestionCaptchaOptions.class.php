<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2014 05 09
*/

class QuestionCaptchaOptions implements CaptchaOptions
{
	private $case_sensitive;

	public function __construct()
	{
		$this->case_sensitive = QuestionCaptchaConfig::load()->is_case_sensitive();
	}

	public function is_case_sensitive() { return $this->case_sensitive; }
}
?>
