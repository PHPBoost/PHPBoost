<?php
/**
 * @package     Util
 * @subpackage  Captcha
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 11 30
 * @since       PHPBoost 3.0 - 2012 09 04
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

abstract class Captcha implements ExtensionPoint
{
	const EXTENSION_POINT = 'captcha';

	private $html_id = 'captcha';
	private $form_id = '';
	private $options;

	abstract public function get_name();

	abstract public function is_available();

	abstract public function is_valid();

	abstract public function display();

	public function get_error()
	{
		return;
	}

	public function is_visible()
	{
		return true;
	}

	public static function display_config_form_fields(FormFieldset $fieldset)
	{
		return;
	}

	public static function save_config(HTMLForm $form)
	{
		return;
	}

	public static function get_css_stylesheet()
	{
		return;
	}

	public function set_html_id($html_id) {	$this->html_id = $html_id; }
	public function get_html_id() { return $this->html_id; }
	public function set_form_id($form_id) {	$this->form_id = $form_id; }
	public function get_form_id() { return $this->form_id; }
	public function set_options(CaptchaOptions $options) { $this->options = $options; }
	public function get_options() { return $this->options; }
}
?>
