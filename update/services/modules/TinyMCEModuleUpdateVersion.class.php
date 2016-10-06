<?php
/*##################################################
 *                       TinyMCEModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : May 22, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
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
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/os/contextmenu.js'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/ajax/php/lib.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/ajax/php/spellcheck.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/ajax/php/tinyspell.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/dictionaries/language-rules/banned-words.txt'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/dictionaries/language-rules/common-mistakes.txt'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/dictionaries/language-rules/enforced-corrections.txt'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/dictionaries/custom.txt'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/dictionaries/en.dic'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/dictionaries/fr.dic'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/installer/json.js'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/installer/php.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/installer/test.js'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/installer/theme.css'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/license/EULA.txt'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/license/key.lic'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/license/web.config'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/robots.txt'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/theme/nanospell.css'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/theme/wiggle.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/license.txt'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/plugin.js'));
		$file->delete();
		
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/os'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/ajax/php'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/ajax'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/dictionaries/language-rules'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/dictionaries'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/license'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server/installer'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/server'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell/theme'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/js/tinymce/plugins/nanospell'));
		if ($folder->exists())
			$folder->delete();
	}
}
?>