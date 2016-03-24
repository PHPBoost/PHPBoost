<?php
/*##################################################
 *		               PHPBoostOfficialVersions.class.php
 *                            -------------------
 *   begin                : December 5, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
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

class PHPBoostOfficialVersions extends AbstractFormField
{
	private $max_input = 100;
	
	public function __construct($id, $label = '', array $value = array(), array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}
	
	function display()
	{
		$template = $this->get_template_to_use();
		$lang = LangLoader::get('common', 'PHPBoostOfficial');
		$config = ContactConfig::load();
		
		$tpl = new FileTemplate('PHPBoostOfficial/PHPBoostOfficialVersions.tpl');
		$tpl->add_lang($lang);
		
		$this->assign_common_template_variables($template);
		
		$i = 0;
		foreach ($this->get_value() as $id => $options)
		{
			if (!empty($options))
			{
				$tpl->assign_block_vars('fieldelements', array(
					'ID' => $i,
					'MAJOR_VERSION_NUMBER' => $options['major_version_number'],
					'MINOR_VERSION_NUMBER' => $options['minor_version_number'],
					'MINIMAL_PHP_VERSION' => $options['minimal_php_version'],
					'NAME' => stripslashes($options['name'])
				));
				$i++;
			}
		}
		
		if ($i == 0)
		{
			$tpl->assign_block_vars('fieldelements', array(
				'ID' => $i
			));
			$i++;
		}
		
		$tpl->put_all(array(
			'NAME' => $this->get_html_id(),
			'HTML_ID' => $this->get_html_id(),
			'C_DISABLED' => $this->is_disabled(),
			'MAX_INPUT' => $this->max_input,
		 	'NBR_VERSIONS' => $i,
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
		for ($i = 0; $i <= $this->max_input; $i++)
		{
			$field_major_version_number = 'field_major_version_number_' . $this->get_html_id() . '_' . $i;
			$field_minor_version_number = 'field_minor_version_number_' . $this->get_html_id() . '_' . $i;
			$field_minimal_php_version = 'field_minimal_php_version_' . $this->get_html_id() . '_' . $i;
			$field_name = 'field_name_' . $this->get_html_id() . '_' . $i;
			if ($request->has_postparameter($field_major_version_number) && $request->has_postparameter($field_name))
			{
				if ($request->get_poststring($field_major_version_number) && $request->get_poststring($field_name))
				{
					$values[Url::encode_rewrite($request->get_postvalue($field_major_version_number))] = array(
						'major_version_number' => $request->get_postvalue($field_major_version_number, 0),
						'minor_version_number' => $request->get_postint($field_minor_version_number, 0),
						'minimal_php_version' => $request->get_postvalue($field_minimal_php_version, ServerConfiguration::MIN_PHP_VERSION),
						'name' => addslashes($request->get_poststring($field_name))
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
