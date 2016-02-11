<?php
/*##################################################
 *		                   QuestionCaptchaConfig.class.php
 *                            -------------------
 *   begin                : May 9, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
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
