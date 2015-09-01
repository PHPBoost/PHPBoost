<?php
/*##################################################
 *                       TinyMCEModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : May 22, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class TinyMCEModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('TinyMCE');
	}
	
	public function execute()
	{
		$this->delete_old_files();
	}
	
	private function delete_old_files()
	{
		$file = new File(Url::to_rel('/' . $this->module_id . '/formatting/smileys.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/smileys.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/langs/en.js'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/langs/fr.js'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/fullscreen/editor_plugin.js'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/fullscreen/editor_plugin_src.js'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/fullscreen/fullscreen.htm'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/searchreplace/editor_plugin.js'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/searchreplace/editor_plugin_src.js'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/searchreplace/searchreplace.htm'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/table/cell.htm'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/table/editor_plugin.js'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/table/editor_plugin_src.js'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/table/merge_cells.htm'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/table/row.htm'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/table/table.htm'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/tiny_mce.js'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/tiny_mce_popup.js'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/tiny_mce_src.js'));
		$file->delete();
		
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/emotions'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/inlinepopups'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/searchreplace/js'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/searchreplace/langs'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/table/js'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/table/langs'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/themes/advanced'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/utils'));
		if ($folder->exists())
			$folder->delete();
	}
}
?>