<?php
/*##################################################
 *                           PluginFieldsetConfiguration.class.php
 *                            -------------------
 *   begin                : February 21, 2012
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

abstract class PluginFieldsetConfiguration
{
	private $form;
	private $fieldset;
	private $id;
	private $plugin_id;
	private $plugin_configuration;
	
	public function __construct($plugin_id, PluginConfiguration $plugin_configuration, HTMLForm $form)
	{
		$this->plugin_id = $plugin_id;
		$this->plugin_configuration = $plugin_configuration;
		$this->form = $form;
		$this->create_fieldset();
	}
	
	public function get_plugin_id()
	{
		return $this->plugin_id;
	}
	
	public function get_form()
	{
		return $this->form;
	}
	
	protected abstract function create_fieldset();
	
	public abstract function handle_submit();
	
	protected function set_fieldset(FormFieldset $fieldset)
	{
		$this->fieldset = $fieldset;
	}
	
	public function get_fieldset()
	{
		return $this->fieldset;
	}
	
	public function get_plugin_configuration()
	{
		return $this->plugin_configuration;
	}
	
	public function save()
	{
		PersistenceContext::get_querier()->update(HomePageSetup::$home_page_table, array('object' => serialize($this->get_plugin_configuration())), 'WHERE id=:id', array('id' => $this->plugin_configuration->get_id()));
	}
}
?>