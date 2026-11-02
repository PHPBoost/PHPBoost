<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 02
 * @since       PHPBoost 4.0 - 2014 05 09
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Qaptcha extends Captcha
{
	private static $items;

	public static function __static()
	{
		self::$items = QaptchaConfig::load()->get_items();
	}

	public function get_name(): mixed
	{
		return 'Qaptcha';
	}

	public static function display_config_form_fields(FormFieldset $fieldset, $locale = ''): mixed
	{
		return AdminQaptchaConfig::get_form_fields($fieldset, $locale);
	}

	public static function save_config(HTMLForm $form): void
	{
		AdminQaptchaConfig::save_config($form);
	}

	public static function get_css_stylesheet(): mixed
	{
		return '/qaptcha/templates/qaptcha.css';
	}

	public function is_available(): mixed
	{
		return true;
	}

	public function is_valid(): mixed
	{
		if (!$this->is_available() || AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
		{
			return true;
		}

		$answer = AppContext::get_request()->get_value($this->get_html_id(), '');
		$item_id = AppContext::get_request()->get_int($this->get_html_id() . '_item_id', 0);

		if (!empty($item_id))
		{
			$item = new QaptchaItem();
			$item->set_properties(self::$items[$item_id]);

			return in_array(trim(TextHelper::strtolower($answer)), $item->get_formated_answers());
		}
		return false;
	}

	public function display(): mixed
	{
		$item_id = array_rand(self::$items); //Question aléatoire

		$item = new QaptchaItem();
		$item->set_properties(self::$items[$item_id]);

		$tpl = new FileTemplate('qaptcha/qaptcha.tpl');
		$tpl->put_all([
			'ITEM_ID' => $item_id,
			'LABEL' => $item->get_label(),
			'HTML_ID' => $this->get_html_id()
		]);

		return $tpl->render();
	}
}
?>
