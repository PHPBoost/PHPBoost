<?php
/*##################################################
 *                       UpdateService.class.php
 *                            -------------------
 *   begin                : February 29, 2012
 *   copyright            : (C) 2012 Kévin MASSY
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

class UpdateService
{
	private static $directory = '/update/services';
	private static $configuration_pattern = '`ConfigUpdateVersion\.class\.php$`';
	private static $module_pattern = '`ModuleUpdateVersion\.class\.php$`';
	private static $kernel_pattern = '`UpdateVersion\.class\.php$`';
	
	public function execute()
	{
		$this->update_kernel();
		$this->update_configurations();
		$this->update_modules();
	}
	
	public function update_kernel()
	{
		$update_kernel_class = $this->get_class(self::$directory . '/kernel/', self::$kernel_pattern);
		foreach ($update_kernel_class as $class_name)
		{
			$object = new $class_name();
			$object->execute();
		}
	}
	
	public function update_configurations()
	{
		$configs_module_class = $this->get_class(self::$directory . '/modules/config/', self::$configuration_pattern);
		$configs_kernel_class = $this->get_class(self::$directory . '/kernel/config/', self::$configuration_pattern);
		
		$configs_class = array_merge($configs_module_class, $configs_kernel_class);
		foreach ($configs_class as $class_name)
		{
			$object = new $class_name();
			$object->execute();
		}
	}
	
	public function update_modules()
	{
		$update_module_class = $this->get_class(self::$directory . '/modules/', self::$module_pattern);
		foreach ($update_module_class as $class_name)
		{
			$object = new $class_name();
			$object->execute();
		}
	}
	
	private function get_class($directory, $pattern)
	{
		$class = array();
		$folder = new Folder($directory);
		foreach ($folder->get_files($pattern) as $file)
		{
			$class[] = $file->get_name();
		}
		return $class;
	}
}
?>