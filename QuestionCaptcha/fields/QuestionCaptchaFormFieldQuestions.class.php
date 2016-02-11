<?php
/*##################################################
 *		               QuestionCaptchaFormFieldQuestions.class.php
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
class QuestionCaptchaFormFieldQuestions extends AbstractFormField
{
	private $max_input = 30;
	
	public function __construct($id, $label, array $value = array(), array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	function display()
	{
		$template = $this->get_template_to_use();

		$tpl = new FileTemplate('QuestionCaptcha/QuestionCaptchaFormFieldQuestions.tpl');
		$tpl->add_lang(LangLoader::get('common', 'QuestionCaptcha'));
		
		$this->assign_common_template_variables($template);
		
		$i = 1;
		foreach ($this->get_value() as $id => $options)
		{
			$tpl->assign_block_vars('fieldelements', array(
				'ID' => $i,
				'LABEL' => $options['label'],
				'ANSWERS' => $options['answers'],
				'C_DELETE' => $i > 1
			));
			$i++;
		}
		
		$tpl->put_all(array(
			'NAME' => $this->get_html_id(),
			'HTML_ID' => $this->get_html_id(),
			'C_DISABLED' => $this->is_disabled(),
			'MAX_INPUT' => $this->max_input,
			'NBR_QUESTIONS' => $i,
		));
		
		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $tpl->render()
		));

		return $template;
	}

	public function retrieve_value()
	{
		$request = AppContext::get_request();
		$values = array();
		
		for ($i = 1; $i <= $this->max_input; $i++)
		{
			$field_label_id = 'field_label_' . $this->get_html_id() . '_' . $i;
			if ($request->has_postparameter($field_label_id))
			{
				$field_answers_id = 'field_answers_' . $this->get_html_id() . '_' . $i;
				$field_label = $request->get_poststring($field_label_id);
				$field_answers = $request->get_poststring($field_answers_id);
				
				if (!empty($field_label) && !empty($field_answers))
					$values[$i] = array(
						'label' => TextHelper::htmlspecialchars($field_label),
						'answers' => TextHelper::htmlspecialchars($field_answers)
					);
			}
		}
		$this->set_value($values);
	}

	protected function compute_options(array &$field_options)
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				 case 'max_input':
					$this->max_input = $value;
					unset($field_options['max_input']);
					break;
			}
		}
		parent::compute_options($field_options);
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}
}
?>