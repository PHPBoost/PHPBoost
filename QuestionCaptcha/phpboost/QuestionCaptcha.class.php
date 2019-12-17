<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 30
 * @since       PHPBoost 4.0 - 2014 05 09
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class QuestionCaptcha extends Captcha
{
	private static $questions;

	public static function __static()
	{
		self::$questions = QuestionCaptchaConfig::load()->get_questions();
	}

	public function get_name()
	{
		return 'QuestionCaptcha';
	}

	public static function display_config_form_fields(FormFieldset $fieldset)
	{
		return AdminQuestionCaptchaConfig::get_form_fields($fieldset);
	}

	public static function save_config(HTMLForm $form)
	{
		AdminQuestionCaptchaConfig::save_config($form);
	}

	public static function get_css_stylesheet()
	{
		return '/QuestionCaptcha/templates/QuestionCaptcha.css';
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
		$question_id = AppContext::get_request()->get_int($this->get_html_id() . '_question_id', 0);

		if (!empty($question_id))
		{
			$question = new QuestionCaptchaQuestion();
			$question->set_properties(self::$questions[$question_id]);

			return in_array(trim(TextHelper::strtolower($answer)), $question->get_formated_answers());
		}
		return false;
	}

	public function display()
	{
		$question_id = array_rand(self::$questions); //Question aléatoire

		$question = new QuestionCaptchaQuestion();
		$question->set_properties(self::$questions[$question_id]);

		$tpl = new FileTemplate('QuestionCaptcha/QuestionCaptcha.tpl');
		$tpl->put_all(array(
			'QUESTION_ID' => $question_id,
			'QUESTION' => $question->get_label(),
			'HTML_ID' => $this->get_html_id()
		));

		return $tpl->render();
	}
}
?>
