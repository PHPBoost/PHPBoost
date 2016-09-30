<?php
/*##################################################
 *                       AbstractNewContentExtensionPoint.class.php
 *                            -------------------
 *   begin                : September 28, 2016
 *   copyright            : (C) 2016 Arnaud Genet
 *   email                : elenwii@phpboost.com
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

abstract class AbstractNewContentExtensionPoint implements NewContentExtensionPoint
{
	private $content_management_config;
	private $id;

	public function __construct($module_id = '')
	{
		$this->id = $module_id;
		$this->content_management_config = ContentManagementConfig::load();
	}

	public function check_if_is_new_content($date)
	{
		return $this->get_autorized_module($this->id) && $this->check_date($date);
	}

	public function get_autorized_module($module)
	{
		return $this->content_management_config->is_new_content_enabled() && !in_array($module, $this->content_management_config->get_new_content_unauthorized_modules());
	}

	public function check_date($date)
	{
		return date('m', time() - $date) < $this->content_management_config->get_new_content_duration();
	}
}
?>