<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 03 29
 * @since       PHPBoost 4.0 - 2014 05 09
 * @contributor mipel <mipel@phpboost.com>
*/

class QuestionCaptchaConfig extends AbstractConfigData
{
	const QUESTIONS = 'questions';

	public function get_questions()
	{
		return $this->get_property(self::QUESTIONS);
	}

	public function set_questions(Array $array)
	{
		$this->set_property(self::QUESTIONS, $array);
	}

	public function count_questions()
	{
		return count($this->get_questions());
	}

	private function init_questions_array()
	{
		$questions = array();

		$lang = LangLoader::get('install', 'QuestionCaptcha');

		$question = new QuestionCaptchaQuestion();
		$question->set_label($lang['question1_label']);
		$question->set_answers(explode(';', $lang['question1_answers']));

		$questions[1] = $question->get_properties();

		$question = new QuestionCaptchaQuestion();
		$question->set_label($lang['question2_label']);
		$question->set_answers(explode(';', $lang['question2_answers']));

		$questions[2] = $question->get_properties();

		return $questions;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::QUESTIONS => self::init_questions_array()
		);
	}

	/**
	 * Returns the configuration.
	 * @return QuestionCaptchaConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'question-captcha', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('question-captcha', self::load(), 'config');
	}
}
?>
