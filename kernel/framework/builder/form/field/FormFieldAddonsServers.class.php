<?php
/**
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.0 - 2013 02 27
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldAddonsServers extends AbstractFormField
{
	private $max_input = 20;

	public function __construct($id, $label, array $value = [], array $field_options = [], array $constraints = [])
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	function display()
	{
		$template = $this->get_template_to_use();

		$view = new FileTemplate('framework/builder/form/fieldelements/FormFieldAddonsServers.tpl');
		$view->add_lang(LangLoader::get_all_langs());

		$this->assign_common_template_variables($template);

		$i = 0;
        $default_entries = $this->get_default_entries();
		foreach ($this->get_value() as $options)
		{
			if (!empty($options))
			{
                $isDefault = $this->is_default_entry($options, $default_entries);
                $view->assign_block_vars('fieldelements', [
                    'C_DELETE' => !$isDefault,
                    'ID'      => $i,
                    'WEBSITE' => $options['website'],
                    'URL'     => $options['url'],
                    'DIR'     => $options['directory'],
                ]);
                $i++;
            }
		}

		if ($i == 0)
		{
            $default_entry = $default_entries[0];
			$view->assign_block_vars('fieldelements', [
				'ID'      => $i,
				'WEBSITE' => $default_entry['website'],
				'URL'     => $default_entry['url'],
				'DIR'     => $default_entry['directory'],
			]);
		}

		$view->put_all([
			'HTML_ID' => $this->get_html_id(),

            'MAX_INPUT'  => $this->max_input,
			'FIELDS_NUMBER' => $i == 0 ? 1 : $i,
		]);

		$template->assign_block_vars('fieldelements', [
			'ELEMENT' => $view->render()
		]);

		return $template;
	}

    private function get_default_entries()
    {
        return AddonsConfig::DEFAULT_ADDONS_SERVER;
    }

    private function is_default_entry($entry, $default_entries)
    {
        foreach ($default_entries as $default_entry) {
            if ($entry['website'] === $default_entry['website'] &&
                $entry['url'] === $default_entry['url'] &&
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
		for ($i = 0; $i < $this->max_input; $i++)
		{
			$field_website_id = 'field_website_' . $this->get_html_id() . '_' . $i;
            $field_url_id     = 'field_url_' . $this->get_html_id() . '_' . $i;
			if ($request->has_postparameter($field_url_id))
			{
				$field_website      = $request->get_poststring($field_website_id);
				$field_url          = $request->get_poststring($field_url_id);
                $field_directory_id = 'field_directory_' . $this->get_html_id() . '_' . $i;
				$field_directory    = $request->get_poststring($field_directory_id);

				if (!empty($field_url))
					$values[] = [
                        'website'   => $field_website,
                        'url'       => $field_url,
                        'directory' => $field_directory,
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
