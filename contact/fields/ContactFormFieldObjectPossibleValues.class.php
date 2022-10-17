<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 05
 * @since       PHPBoost 4.0 - 2013 10 07
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ContactFormFieldObjectPossibleValues extends AbstractFormField
{
	private $max_input = 50;

	public function __construct($id, $label = '', array $value = array(), array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	function display()
	{
		$template = $this->get_template_to_use();
		$lang = LangLoader::get_all_langs('contact');
		$config = ContactConfig::load();

		$view = new FileTemplate('contact/ContactFormFieldObjectPossibleValues.tpl');
		$view->add_lang($lang);

		$this->assign_common_template_variables($template);

		$fields = $config->get_fields();
		$recipients_field_id = $config->get_field_id_by_name('f_recipients');
		$recipients_field = new ContactField();
		$recipients_field->set_properties($fields[$recipients_field_id]);

		foreach ($recipients_field->get_possible_values() as $id => $options)
		{
			if (!empty($options))
			{
				$view->assign_block_vars('recipients_list', array(
					'ID' => $id,
					'NAME' => stripslashes($options['title'])
				));
			}
		}

		$has_default = false;
		$i = 0;
		foreach ($this->get_value() as $name => $options)
		{
			if (!empty($options))
			{
				$has_default = $options['is_default'] ? true : $has_default;
				$view->assign_block_vars('fieldelements', array(
					'ID' => $i,
					'NAME' => stripslashes($options['title']),
					'IS_DEFAULT' => (int) $options['is_default']
				));
				foreach ($recipients_field->get_possible_values() as $id => $recipient_options)
				{
					if (!empty($recipient_options))
					{
						$view->assign_block_vars('fieldelements.recipients_list', array(
							'C_RECIPIENT_SELECTED' => $options['recipient'] == $id,
							'ID' => $id,
							'NAME' => stripslashes($recipient_options['title'])
						));
					}
				}
				$i++;
			}
		}

		if ($i == 0)
		{
			$view->assign_block_vars('fieldelements', array(
				'ID' => $i,
				'NAME' => '',
				'IS_DEFAULT' => 0,
			));
			foreach ($recipients_field->get_possible_values() as $id => $options)
			{
				if (!empty($options))
				{
					$view->assign_block_vars('fieldelements.recipients_list', array(
						'ID' => $id,
						'NAME' => stripslashes($options['title'])
					));
				}
			}
			$i++;
		}

		$view->put_all(array(
			'NAME' => $this->get_html_id(),
			'HTML_ID' => $this->get_html_id(),
			'C_DISABLED' => $this->is_disabled(),
			'MAX_INPUT' => $this->max_input,
			'NBR_FIELDS' => $i,
			'C_HAS_DEFAULT_VALUE' => $has_default
		));

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $view->render()
		));

		return $template;
	}

	public function retrieve_value()
	{
		$request = AppContext::get_request();
		$values = array();
		for ($i = 0; $i <= $this->max_input; $i++)
		{
			$field_name = 'field_name_' . $this->get_html_id() . '_' . $i;
			if ($request->has_postparameter($field_name))
			{
				$field_is_default = 'field_is_default_' . $this->get_html_id() . '_' . $i;
				$field_title = 'field_name_' . $this->get_html_id() . '_' . $i;
				$field_recipient = 'field_recipient_' . $this->get_html_id() . '_' . $i;
				if ($request->get_poststring($field_title))
				{
					$values[preg_replace('/\s+/u', '', $request->get_poststring($field_name))] = array(
						'is_default'	=> $request->get_postint($field_is_default, 0),
						'title'	=> addslashes($request->get_poststring($field_title)),
						'recipient'	=> $request->get_poststring($field_recipient)
					);
				}
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
