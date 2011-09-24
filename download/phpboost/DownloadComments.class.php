<?php
/*##################################################
 *                           DownloadComments.class.php
 *                            -------------------
 *   begin                : September 23, 2011
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

class DownloadComments extends AbstractCommentsExtensionPoint
{
	public function get_authorizations($module_id, $id_in_module)
	{
		global $DOWNLOAD_CATS, $CONFIG_DOWNLOAD;
		
		$cache = new Cache();
		$cache->load($module_id);
		
		require_once(PATH_TO_ROOT .'/'. $module_id . '/download_auth.php');
		
		$id_cat = $this->get_categorie_id($module_id, $id_in_module);
		
		$cat_authorizations = $DOWNLOAD_CATS[$id_cat]['auth'];
		if (!is_array($cat_authorizations))
		{
			$cat_authorizations = $CONFIG_DOWNLOAD['global_auth'];
		}
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_array_authorization($cat_authorizations);
		$authorizations->set_read_bit(DOWNLOAD_READ_CAT_AUTH_BIT);
		return $authorizations;
	}
	
	public function is_display($module_id, $id_in_module)
	{
		$columns = array('approved', 'visible');
		$condition = 'WHERE id = :id_in_module';
		$parameters = array('id_in_module' => $id_in_module);
		$row = PersistenceContext::get_querier()->select_single_row(PREFIX . 'download', $columns, $condition, $parameters);
		return (bool)$row['approved'] && (bool)$row['visible'];
	}
	
	public function get_url_built($module_id, $id_in_module, Array $parameters)
	{
		$url_parameters = '';
		foreach ($parameters as $name => $value)
		{
			$url_parameters .= '&' . $name . '=' . $value;
		}
		return new Url('/download/download.php?id='. $id_in_module .'&com=0' . $url_parameters);
	}
	
	private function get_categorie_id($module_id, $id_in_module)
	{
		$columns = 'idcat';
		$condition = 'WHERE id = :id_in_module';
		$parameters = array('id_in_module' => $id_in_module);
		return PersistenceContext::get_querier()->get_column_value(PREFIX . 'web', $columns, $condition, $parameters);
	}
}
?>