<?php
/*##################################################
 *		                   AdminPHPBoostOfficialConfigController.class.php
 *                            -------------------
 *   begin                : December 5, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class AdminPHPBoostOfficialConfigController extends AdminModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;
	
	private $lang;
	
	/**
	 * @var PHPBoostOfficialConfig
	 */
	private $config;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_form();
		
		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return new AdminPHPBoostOfficialDisplayResponse($tpl, $this->lang['module_title']);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'PHPBoostOfficial');
		$this->config = PHPBoostOfficialConfig::load();
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset_config = new FormFieldsetHTML('configuration', LangLoader::get_message('configuration', 'admin-common'));
		$form->add_fieldset($fieldset_config);
		
		$fieldset_config->add_field(new PHPBoostOfficialVersions('versions', $this->lang['versions'], $this->config->get_versions(),
			array('description' => $this->lang['versions.explain'])
		));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}
	
	private function save()
	{
		$versions = $this->form->get_value('versions');
		
		foreach ($versions as $id => &$version)
		{
			if (isset($version['major_version_number']))
			{
				$rewrited_major_version_number = Url::encode_rewrite($version['major_version_number']);
				
				// Automatic Download categories creation if not present
				try {
					$phpboost_cat_id = PersistenceContext::get_querier()->get_column_value(DownloadSetup::$download_cats_table, 'id', 'WHERE rewrited_name = :rewrited_name', array('rewrited_name' => 'phpboost-' . $rewrited_major_version_number));
				} catch (RowNotFoundException $e) {
					$phpboost_cat = new RichCategory();
					$phpboost_cat->set_name('PHPBoost ' . $version['major_version_number']);
					$phpboost_cat->set_rewrited_name(Url::encode_rewrite($phpboost_cat->get_name()));
					$phpboost_cat->set_id_parent(Category::ROOT_CATEGORY);
					$phpboost_cat->set_image(new Url(DownloadFile::DEFAULT_PICTURE));
					$phpboost_cat_id = DownloadService::get_categories_manager()->add($phpboost_cat);
				}
				
				try {
					$updates_cat_id = PersistenceContext::get_querier()->get_column_value(DownloadSetup::$download_cats_table, 'id', 'WHERE rewrited_name = :rewrited_name', array('rewrited_name' => Url::encode_rewrite($this->lang['download.updates_phpboost']) . '-' . $rewrited_major_version_number));
				} catch (RowNotFoundException $e) {
					$updates_cat = new RichCategory();
					$updates_cat->set_name($this->lang['download.updates_phpboost'] . ' ' . $version['major_version_number']);
					$updates_cat->set_rewrited_name(Url::encode_rewrite($updates_cat->get_name()));
					$updates_cat->set_id_parent($phpboost_cat_id);
					$updates_cat->set_image(new Url(DownloadFile::DEFAULT_PICTURE));
					$updates_cat_id = DownloadService::get_categories_manager()->add($updates_cat);
				}
				$version['updates_cat_link'] = !empty($updates_cat_id) ? DownloadUrlBuilder::display_category($updates_cat_id, Url::encode_rewrite($this->lang['download.updates_phpboost']) . '-' . $rewrited_major_version_number)->rel() : '';
				
				try {
					$phpboost_pdk_id = PersistenceContext::get_querier()->get_column_value(DownloadSetup::$download_table, 'id', 'WHERE rewrited_name = :rewrited_name', array('rewrited_name' => 'phpboost-' . $rewrited_major_version_number . '-pdk'));
				} catch (RowNotFoundException $e) {
					$phpboost_pdk_id = 0;
				}
				$version['phpboost_pdk_link'] = !empty($phpboost_cat_id) && !empty($phpboost_pdk_id) ? DownloadUrlBuilder::display($phpboost_cat_id, 'phpboost-' . $rewrited_major_version_number, $phpboost_pdk_id, 'phpboost-' . $rewrited_major_version_number . '-pdk')->rel() : '';
				
				try {
					$modules_cat_id = PersistenceContext::get_querier()->get_column_value(DownloadSetup::$download_cats_table, 'id', 'WHERE rewrited_name = :rewrited_name', array('rewrited_name' => Url::encode_rewrite($this->lang['download.modules_phpboost']) . '-' . $rewrited_major_version_number));
				} catch (RowNotFoundException $e) {
					$modules_cat = new RichCategory();
					$modules_cat->set_name($this->lang['download.modules_phpboost'] . ' ' . $version['major_version_number']);
					$modules_cat->set_rewrited_name(Url::encode_rewrite($modules_cat->get_name()));
					$modules_cat->set_id_parent($phpboost_cat_id);
					$modules_cat->set_description($this->lang['download.module_category.description'] . ' ' . $version['major_version_number']);
					$modules_cat->set_image(new Url(DownloadFile::DEFAULT_PICTURE));
					$modules_cat_id = DownloadService::get_categories_manager()->add($modules_cat);
				}
				$version['modules_cat_link'] = !empty($modules_cat_id) ? DownloadUrlBuilder::display_category($modules_cat_id, Url::encode_rewrite($this->lang['download.modules_phpboost']) . '-' . $rewrited_major_version_number)->rel() : '';
				
				try {
					$themes_cat_id = PersistenceContext::get_querier()->get_column_value(DownloadSetup::$download_cats_table, 'id', 'WHERE rewrited_name = :rewrited_name', array('rewrited_name' => Url::encode_rewrite($this->lang['download.themes_phpboost']) . '-' . $rewrited_major_version_number));
				} catch (RowNotFoundException $e) {
					$themes_cat = new RichCategory();
					$themes_cat->set_name($this->lang['download.themes_phpboost'] . ' ' . $version['major_version_number']);
					$themes_cat->set_rewrited_name(Url::encode_rewrite($themes_cat->get_name()));
					$themes_cat->set_id_parent($phpboost_cat_id);
					$themes_cat->set_description($this->lang['download.theme_category.description'] . ' ' . $version['major_version_number']);
					$themes_cat->set_image(new Url(DownloadFile::DEFAULT_PICTURE));
					$themes_cat_id = DownloadService::get_categories_manager()->add($themes_cat);
				}
				$version['themes_cat_link'] = !empty($themes_cat_id) ? DownloadUrlBuilder::display_category($themes_cat_id, Url::encode_rewrite($this->lang['download.themes_phpboost']) . '-' . $rewrited_major_version_number)->rel() : '';
				
				// Automatic News category creation if not present
				try {
					$phpboost_news_cat_id = PersistenceContext::get_querier()->get_column_value(NewsSetup::$news_cats_table, 'id', 'WHERE rewrited_name = :rewrited_name', array('rewrited_name' => 'phpboost-' . $rewrited_major_version_number));
				} catch (RowNotFoundException $e) {
					$phpboost_news_cat = new RichCategory();
					$phpboost_news_cat->set_name('PHPBoost ' . $version['major_version_number']);
					$phpboost_news_cat->set_rewrited_name(Url::encode_rewrite($phpboost_news_cat->get_name()));
					$phpboost_news_cat->set_id_parent(Category::ROOT_CATEGORY);
					$phpboost_news_cat->set_description($this->lang['news.category.description'] . ' ' . $version['major_version_number']);
					$phpboost_news_cat->set_image(new Url(News::DEFAULT_PICTURE));
					$phpboost_news_cat_id = NewsService::get_categories_manager()->add($phpboost_news_cat);
				}
				
				// Automatic Forum categories creation if not present
				$forum_categories_cache = ForumService::get_categories_manager()->get_categories_cache();
				
				if ($forum_categories_cache->category_exists(87))
				{
					try {
						$phpboost_forum_themes_cat_id = PersistenceContext::get_querier()->get_column_value(ForumSetup::$forum_cats_table, 'id', 'WHERE rewrited_name = :rewrited_name', array('rewrited_name' => Url::encode_rewrite($this->lang['download.themes_phpboost']) . '-' . $rewrited_major_version_number));
					} catch (RowNotFoundException $e) {
						$phpboost_forum_themes_cat = new ForumCategory();
						$phpboost_forum_themes_cat->set_name($this->lang['download.themes_phpboost'] . ' ' . $version['major_version_number']);
						$phpboost_forum_themes_cat->set_rewrited_name(Url::encode_rewrite($phpboost_forum_themes_cat->get_name()));
						$phpboost_forum_themes_cat->set_id_parent(87);
						$phpboost_forum_themes_cat->set_description($this->lang['download.theme_category.description'] . ' ' . $version['major_version_number']);
						$phpboost_forum_themes_cat_id = ForumService::get_categories_manager()->add($phpboost_forum_themes_cat);
					}
				}
				
				if ($forum_categories_cache->category_exists(84))
				{
					try {
						$phpboost_forum_modules_cat_id = PersistenceContext::get_querier()->get_column_value(ForumSetup::$forum_cats_table, 'id', 'WHERE rewrited_name = :rewrited_name', array('rewrited_name' => Url::encode_rewrite($this->lang['download.modules_phpboost']) . '-' . $rewrited_major_version_number));
					} catch (RowNotFoundException $e) {
						$phpboost_forum_modules_cat = new ForumCategory();
						$phpboost_forum_modules_cat->set_name($this->lang['download.modules_phpboost'] . ' ' . $version['major_version_number']);
						$phpboost_forum_modules_cat->set_rewrited_name(Url::encode_rewrite($phpboost_forum_modules_cat->get_name()));
						$phpboost_forum_modules_cat->set_id_parent(84);
						$phpboost_forum_modules_cat->set_description($this->lang['download.theme_category.description'] . ' ' . $version['major_version_number']);
						$phpboost_forum_modules_cat_id = ForumService::get_categories_manager()->add($phpboost_forum_modules_cat);
					}
				}
				
				// Bugtracker version creation if not present
				// TODO : (check minor version, create new version, update previous version with date)
			}
		}
		
		$this->config->set_versions($versions);
		PHPBoostOfficialConfig::save();
		PHPBoostOfficialCache::invalidate();
	}
}
?>
