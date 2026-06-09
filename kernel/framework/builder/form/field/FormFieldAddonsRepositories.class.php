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

class FormFieldAddonsRepositories extends AbstractFormField
{
	private $max_input = 1000;
	private $addon_type = 'modules';

	public function __construct($id, $label = '', array $value = [], array $field_options = [], array $constraints = [])
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);

	}

	public function display()
    {
        $template = $this->get_template_to_use();
        $view = new FileTemplate('framework/builder/form/fieldelements/FormFieldAddonsRepositories.tpl');
        $view->add_lang(LangLoader::get_all_langs());

        $this->assign_common_template_variables($template);

        $i = 0;
        $order = [];
        $default_entries = $this->get_default_entries();
        foreach ($this->get_value() as $options) {
            if (!empty($options))
            {
                $isDefault = $this->is_default_entry($options, $default_entries);
                $view->assign_block_vars('fieldelements', [
                    'C_DELETE' => !$isDefault,
                    'ID'       => $i,
                    'OWNER'    => $options['owner'],
                    'REPO'     => $options['repository'],
                    'DIR'      => $options['directory'],
                    'TYPE'     => $this->addon_type,
                ]);
                $order[] = $i;
                $i++;
            }
        }

        if ($i == 0)
        {
            $default_entry = $default_entries[0];
            $view->assign_block_vars('fieldelements', [
                'ID'    => $i,
                'OWNER' => $default_entry['owner'],
                'REPO'  => $default_entry['repository'],
                'DIR'   => $default_entry['directory'],
                'TYPE'  => $this->addon_type,
                'C_DELETE' => false,
            ]);
            $order[] = $i;
        }

        $view->put_all([
            'HTML_ID'        => $this->get_html_id(),
            'MAX_INPUT'      => $this->max_input,
            'FIELDS_NUMBER'  => $i,
            'ADDON_TYPE'     => $this->addon_type,
            'ORDER'          => implode(',', $order),
        ]);

        $template->assign_block_vars('fieldelements', [
            'ELEMENT' => $view->render()
        ]);

        return $template;
    }

    private function get_default_entries()
    {
        switch ($this->addon_type)
        {
            case 'modules':
                return AddonsConfig::DEFAULT_MODULES_REPO;
            case 'themes':
                return AddonsConfig::DEFAULT_THEMES_REPO;
            case 'langs':
                return AddonsConfig::DEFAULT_LANGS_REPO;
            default:
                return [];
        }
    }

    private function is_default_entry($entry, $default_entries)
    {
        foreach ($default_entries as $default_entry) {
            if ($entry['owner'] === $default_entry['owner'] &&
                $entry['repository'] === $default_entry['repository'] &&
                $entry['directory'] === $default_entry['directory']) {
                return true;
            }
        }
        return false;
    }

    public function retrieve_value()
    {
        $request = AppContext::get_request();
        $values = [];

        // Get the order of the <li> elements from the DOM
        $order_parameter_name = 'order_' . $this->get_html_id();
        $order = $request->get_postvalue($order_parameter_name, '');

        // If order is not provided, use the default order
        $order = !empty($order) ? explode(',', $order) : array_keys($this->get_value());

        $retrievedValues = [];
        foreach ($order as $index) {
            $field_owner_id      = 'field_owner_' . $this->get_html_id() . '_' . $index;
            $field_repository_id = 'field_repository_' . $this->get_html_id() . '_' . $index;

            if ($request->has_postparameter($field_owner_id) && $request->has_postparameter($field_repository_id)) {
                $field_owner        = $request->get_poststring($field_owner_id);
                $field_repository   = $request->get_poststring($field_repository_id);
                $field_directory_id = 'field_directory_' . $this->get_html_id() . '_' . $index;
                $field_directory    = $request->get_poststring($field_directory_id);

                if (!empty($field_owner) && !empty($field_repository)) {
                    $retrievedValues[] = [
                        'owner'      => $field_owner,
                        'repository' => $field_repository,
                        'directory'  => $field_directory,
                        'type'       => $this->addon_type,
                    ];
                }
            }
        }

        $this->set_value($retrievedValues);
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
                case 'addon_type':
                    $this->addon_type = $value;
                    unset($field_options['addon_type']);
                    break;
                default:
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
