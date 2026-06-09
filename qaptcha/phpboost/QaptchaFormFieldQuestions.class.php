<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.0 - 2014 05 09
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QaptchaFormFieldQuestions extends AbstractFormField
{
	private $max_input = 100;

	public function __construct($id, $label, array $value = [], array $field_options = [], array $constraints = [])
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	function display()
	{
		$template = $this->get_template_to_use();

		$view = new FileTemplate('qaptcha/QaptchaFormFieldQuestions.tpl');
		$view->add_lang(LangLoader::get_all_langs('qaptcha'));

		$this->assign_common_template_variables($template);

		$i = 1;
		foreach ($this->get_value() as $id => $options)
		{
			$view->assign_block_vars('fieldelements', [
				'ID' => $i,
				'LABEL' => $options['label'],
				'ANSWERS' => $options['answers'],
				'C_DELETE' => $i > 1
			]);
			$i++;
		}

		$view->put_all([
			'NAME' => $this->get_html_id(),
			'HTML_ID' => $this->get_html_id(),
			'C_DISABLED' => $this->is_disabled(),
			'MAX_INPUT' => $this->max_input,
			'ITEMS_NUMBER' => $i,
		]);

		$template->assign_block_vars('fieldelements', [
			'ELEMENT' => $view->render()
		]);

		return $template;
	}

	public function retrieve_value()
	{
		$request = AppContext::get_request();
		$values = [];

		for ($i = 1; $i <= $this->max_input; $i++)
		{
			$field_label_id = 'field_label_' . $this->get_html_id() . '_' . $i;
			if ($request->has_postparameter($field_label_id))
			{
				$field_answers_id = 'field_answers_' . $this->get_html_id() . '_' . $i;
				$field_label = $request->get_poststring($field_label_id);
				$field_answers = $request->get_poststring($field_answers_id);

				if (!empty($field_label) && !empty($field_answers))
					$values[$i] = [
						'label' => TextHelper::htmlspecialchars($field_label),
						'answers' => TextHelper::htmlspecialchars($field_answers)
					];
			}
		}
		$this->set_value($values);
	}

	protected function compute_options(array &$field_options)
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = TextHelper::strtolower($attribute);
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
