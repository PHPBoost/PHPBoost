<?php
/*##################################################
 *                       WikiModuleUpdateVersion.class.php
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

class WikiModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('wiki');
	}
	
	public function execute()
	{
		$this->delete_old_files();
	}
	
	private function delete_old_files()
	{
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/bbcode/bb_form.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/bbcode/link.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/actions.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/add_article.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/add_cat.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/article.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/article_status.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/cat.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/cat_root.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/closed_cat.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/com.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/contribuate.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/create_article.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/create_redirection.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/delete.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/delete_article.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/edit.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/edit_index.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/explorer.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/follow-article.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/followed-articles.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/history.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/minus.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/move.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/opened_cat.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/plus.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/print_mini.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/random_page.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/redirect.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/rename.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/restore.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/restriction_level.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/rss.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/search.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/tools.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/wiki.js'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/search.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/search.php'));
		$file->delete();
	}
}
?>