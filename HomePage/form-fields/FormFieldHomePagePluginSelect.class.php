<?php
/*##################################################
 *                       FormFieldHomePagePluginSelect.class.php
 *                            -------------------
 *   begin                : March 18, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : soldier.weasel@gmail.com
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

class FormFieldHomePagePluginSelect extends FormFieldSimpleSelectChoice
{
    public function __construct($id, $label, $value = 0, $field_options = array(), array $constraints = array())
    {
        parent::__construct($id, $label, $value, $this->generate_options(), $field_options, $constraints);
    }

    private function generate_options()
	{
		$options = array();
		$options[] = new FormFieldSelectChoiceOption('--', '');
		$providers = AppContext::get_extension_provider_service()->get_providers(PluginsHomePageExtensionPoint::EXTENSION_POINT);
		foreach ($providers as $module_id => $properties)
		{
			$module = ModulesManager::get_module($module_id);
			$plugins = $properties->plugins_home_page()->get_plugins();
			$options_group = $this->generate_options_group($plugins);
			if (!empty($options_group))
			{
				$options[] = new FormFieldSelectChoiceGroupOption($module->get_configuration()->get_name(), $options_group);
			}
		}
		return $options;
	}
	
	private function generate_options_group(Array $plugins)
	{
		$options = array();
		foreach ($plugins as $plugin)
		{
			$options[] = new FormFieldSelectChoiceOption($plugin->get_title(), $plugin->get_class());
		}
		return $options;
	}
}
?>