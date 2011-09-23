<?php
/*##################################################
 *                           WebComments.class.php
 *                            -------------------
 *   begin                : May 06, 2011
 *   copyright            : (C) 2011 Kévin MASSY
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

class WebComments extends AbstractCommentsExtensionPoint
{
	public function get_authorizations($module_id, $id_in_module)
	{
		global $CAT_WEB;
		
		$cache = new Cache();
		$cache->load($module_id);
		
		$columns = 'idcat';
		$condition = 'WHERE id = :id_in_module';
		$parameters = array('id_in_module' => $id_in_module);
		$id_cat = PersistenceContext::get_querier()->get_column_value(PREFIX . 'web', $columns, $condition, $parameters);
		$authorizations = new CommentsAuthorizations();
		
		$authorizations->set_manual_authorized_read(AppContext::get_user()->check_level($CAT_WEB[$id_cat]['secure']));
		return $authorizations;
	}
	
	public function is_display($module_id, $id_in_module)
	{
		$columns = 'aprob';
		$condition = 'WHERE id = :id_in_module';
		$parameters = array('id_in_module' => $id_in_module);
		$aprobation = PersistenceContext::get_querier()->get_column_value(PREFIX . 'web', $columns, $condition, $parameters);
		return $aprobation > 0 ? true : false; 
	}
	
	public function get_url_built($module_id, $id_in_module, Array $parameters)
	{
		$url_parameters = '';
		foreach ($parameters as $name => $value)
		{
			$url_parameters .= '&' . $name . '=' . $value;
		}
		return new Url('/web/web.php?cat=1&id=1&com=0' . $url_parameters);
	}
}
?>