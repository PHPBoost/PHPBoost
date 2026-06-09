<?php
/**
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.0 - 2013 09 15
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @author      xela <xela@phpboost.com>
*/

class FormFieldRepository extends AbstractFormField
{
	private $max_input = 1000;

	public function __construct($id, $label = '', array $value = [], array $field_options = [], array $constraints = [])
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);

	}

	function display()
	{
		$template = $this->get_template_to_use();

		$view = new FileTemplate('framework/builder/form/FormFieldRepository.tpl');
		$view->add_lang(LangLoader::get_all_langs());

		$this->assign_common_template_variables($template);

		$i = 0;
		foreach ($this->get_value() as $options)
		{
			if (!empty($options))
			{
				$view->assign_block_vars('fieldelements', [
                    'C_DELETE' => $i !== 0,
					'ID'    => $i,
					'LABEL' => $options['label'],
					'OWNER' => $options['owner'],
					'URL'   => $options['url'],
					'DIR'   => $options['directory']
				]);
				$i++;
			}
		}

		if ($i == 0)
		{
            $view->assign_block_vars('fieldelements', [
                'ID'    => $i,
                'LABEL' => '',
                'OWNER' => '',
                'URL'   => '',
                'DIR'   => ''
            ]);
		}

		$view->put_all([

            'NAME'       => $this->get_html_id(),
			'ID'         => $this->get_html_id(),
			'C_DISABLED' => $this->is_disabled(),

			'MAX_INPUT'     => $this->max_input,
			'FIELDS_NUMBER' => $i,
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
		for ($i = 0; $i < $this->max_input; $i++)
		{
			$field_label_id = 'field_label_' . $this->get_html_id() . '_' . $i;
			$field_owner_id = 'field_owner_' . $this->get_html_id() . '_' . $i;
			$field_url_id   = 'field_url_' . $this->get_html_id() . '_' . $i;
			if ($request->has_postparameter($field_label_id) || $request->has_postparameter($field_owner_id) || $request->has_postparameter($field_url_id))
			{
				$field_label        = $request->get_poststring($field_label_id);
				$field_owner        = $request->get_poststring($field_owner_id);
				$field_url          = $request->get_poststring($field_url_id);
                $field_directory_id = 'field_directory_' . $this->get_html_id() . '_' . $i;
				$field_directory    = $request->get_poststring($field_directory_id);

				if (!empty($field_label) || !empty($field_url))
					$values[] = [
                        'label' => $field_label,
                        'owner' => $field_owner,
                        'url' => $field_url,
                        'directory' => $field_directory
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
