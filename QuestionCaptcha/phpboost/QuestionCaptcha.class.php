<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 26
 * @since       PHPBoost 4.0 - 2014 05 09
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QuestionCaptcha extends Captcha
{
	private static $items;

	public static function __static()
	{
		self::$items = QuestionCaptchaConfig::load()->get_items();
	}

	public function get_name()
	{
		return 'QuestionCaptcha';
	}

	public static function display_config_form_fields(FormFieldset $fieldset, $locale = '')
	{
		return AdminQuestionCaptchaConfig::get_form_fields($fieldset, $locale);
	}

	public static function save_config(HTMLForm $form)
	{
		AdminQuestionCaptchaConfig::save_config($form);
	}

	public static function get_css_stylesheet()
	{
		return '/QuestionCaptcha/templates/questioncaptcha.css';
	}

	public function is_available()
	{
		return true;
	}

	public function is_valid()
	{
		if (!$this->is_available() || AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
		{
			return true;
		}

		$answer = AppContext::get_request()->get_value($this->get_html_id(), '');
		$item_id = AppContext::get_request()->get_int($this->get_html_id() . '_item_id', 0);

		if (!empty($item_id))
		{
			$item = new QuestionCaptchaItem();
			$item->set_properties(self::$items[$item_id]);

			return in_array(trim(TextHelper::strtolower($answer)), $item->get_formated_answers());
		}
		return false;
	}

	public function display()
	{
		$item_id = array_rand(self::$items); //Question aléatoire

		$item = new QuestionCaptchaItem();
		$item->set_properties(self::$items[$item_id]);

		$tpl = new FileTemplate('QuestionCaptcha/QuestionCaptcha.tpl');
		$tpl->put_all(array(
			'ITEM_ID' => $item_id,
			'LABEL' => $item->get_label(),
			'HTML_ID' => $this->get_html_id()
		));

		return $tpl->render();
	}
}
?>
