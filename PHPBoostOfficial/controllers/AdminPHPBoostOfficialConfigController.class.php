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
	
	private $available_modules;
	private $available_themes;
	
	private $new_modules_number;
	private $new_themes_number;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_form();
		
		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE MODULES_MSG # # INCLUDE THEMES_MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
			if ($this->new_modules_number > 1)
				$tpl->put('MODULES_MSG', MessageHelper::display(StringVars::replace_vars($this->lang['success.add_new_modules'], array('number_modules' => $this->new_modules_number)), MessageHelper::SUCCESS, 4));
			else if ($this->new_modules_number == 1)
				$tpl->put('MODULES_MSG', MessageHelper::display($this->lang['success.add_new_module'], MessageHelper::SUCCESS, 4));
			if ($this->new_themes_number > 1)
				$tpl->put('THEMES_MSG', MessageHelper::display(StringVars::replace_vars($this->lang['success.add_new_themes'], array('number_themes' => $this->new_themes_number)), MessageHelper::SUCCESS, 4));
			else if ($this->new_themes_number == 1)
				$tpl->put('MODULES_MSG', MessageHelper::display($this->lang['success.add_new_theme'], MessageHelper::SUCCESS, 4));
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
		
		$fieldset_mini_menu = new FormFieldsetHTML('mini_menu', $this->lang['mini_menu_fieldset']);
		$form->add_fieldset($fieldset_mini_menu);
		
		$fieldset_mini_menu->add_field(new FormFieldNumberEditor('last_modules_number', $this->lang['last_modules_number'], (int)$this->config->get_last_modules_number(),
			array('min' => 1, 'max' => 20, 'required' => true, 'description' => $this->lang['most_recent_displayed']),
			array(new FormFieldConstraintIntegerRange(1, 20))
		));
		
		$fieldset_mini_menu->add_field(new FormFieldNumberEditor('last_themes_number', $this->lang['last_themes_number'], (int)$this->config->get_last_themes_number(),
			array('min' => 1, 'max' => 20, 'required' => true, 'description' => $this->lang['most_recent_displayed']),
			array(new FormFieldConstraintIntegerRange(1, 20))
		));
		
		if ($this->get_available_modules())
		{
			$fieldset_modules = new FormFieldsetHTML('modules', $this->lang['new_modules_fieldset']);
			$form->add_fieldset($fieldset_modules);
			
			$fieldset_modules->add_field(new FormFieldMultipleSelectChoice('new_modules', $this->lang['new_modules'], array(),
				$this->available_modules_options(), 
				array('size' => 10)
			));
			
			$fieldset_modules->add_field(new FormFieldCheckbox('new_modules_approved', $this->lang['new_modules_approved'], false,
				array('description' => $this->lang['new_modules_approved.explain'])
			));
		}
		
		if ($this->get_available_themes())
		{
			$fieldset_themes = new FormFieldsetHTML('themes', $this->lang['new_themes_fieldset']);
			$form->add_fieldset($fieldset_themes);
			
			$fieldset_themes->add_field(new FormFieldMultipleSelectChoice('new_themes', $this->lang['new_themes'], array(),
				$this->available_themes_options(), 
				array('size' => 10)
			));
			
			$fieldset_themes->add_field(new FormFieldCheckbox('new_themes_approved', $this->lang['new_themes_approved'], false,
				array('description' => $this->lang['new_themes_approved.explain'])
			));
		}
		
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
			}
		}
		
		$this->config->set_versions($versions);
		$this->config->set_last_modules_number($this->form->get_value('last_modules_number'));
		$this->config->set_last_themes_number($this->form->get_value('last_themes_number'));
		
		if ($this->get_available_modules())
		{
			try {
				$new_modules_cat_id = PersistenceContext::get_querier()->get_column_value(DownloadSetup::$download_cats_table, 'id', 'WHERE rewrited_name = :rewrited_name', array('rewrited_name' => Url::encode_rewrite($this->lang['download.modules_phpboost']) . '-' . $rewrited_major_version_number));
			} catch (RowNotFoundException $e) {
				$new_modules_cat_id = Category::ROOT_CATEGORY;
			}
			if ($new_modules_cat_id)
			{
				foreach ($this->form->get_value('new_modules') as $field => $option)
				{
					if ($this->create_selected_downloadfiles($option->get_raw_value(), $new_modules_cat_id, (bool)$this->form->get_value('new_modules_approved')))
						$this->new_modules_number++;
				}
			}
		}
		
		if ($this->get_available_themes())
		{
			try {
				$new_themes_cat_id = PersistenceContext::get_querier()->get_column_value(DownloadSetup::$download_cats_table, 'id', 'WHERE rewrited_name = :rewrited_name', array('rewrited_name' => Url::encode_rewrite($this->lang['download.themes_phpboost']) . '-' . $rewrited_major_version_number));
			} catch (RowNotFoundException $e) {
				$new_themes_cat_id = Category::ROOT_CATEGORY;
			}
			if ($new_themes_cat_id)
			{
				foreach ($this->form->get_value('new_themes') as $field => $option)
				{
					if ($this->create_selected_downloadfiles($option->get_raw_value(), $new_themes_cat_id, (bool)$this->form->get_value('new_themes_approved')))
						$this->new_themes_number++;
				}
			}
		}
		
		if ($this->new_modules_number || $this->new_themes_number)
		{
			Feed::clear_cache('download');
			DownloadCache::invalidate();
			DownloadCategoriesCache::invalidate();
		}
		
		PHPBoostOfficialConfig::save();
		PHPBoostOfficialCache::invalidate();
	}
	
	private function get_available_modules()
	{
		if ($this->available_modules === null)
		{
			$versions = $this->config->get_versions();
			$versions_number = count($versions);
			
			if ($versions_number >= 2)
			{
				$last_version = end($versions);
				$previous_version = prev($versions);
				
				$last_version_modules = array();
				
				$result = PersistenceContext::get_querier()->select('SELECT download.name
					FROM ' . PREFIX . 'download download
					LEFT JOIN ' . PREFIX . 'download_cats cats ON cats.id = download.id_category
					WHERE cats.rewrited_name = "modules-phpboost-' . Url::encode_rewrite($last_version['major_version_number']) . '"
					ORDER BY download.name ASC'
				);
				
				while ($row = $result->fetch())
				{
					$last_version_modules[] = $row['name'];
				}
				$result->dispose();
				
				$result = PersistenceContext::get_querier()->select('SELECT download.id, download.name, download.url
					FROM ' . PREFIX . 'download download
					LEFT JOIN ' . PREFIX . 'download_cats cats ON cats.id = download.id_category
					WHERE cats.rewrited_name = "modules-phpboost-' . Url::encode_rewrite($previous_version['major_version_number']) . '"
					ORDER BY download.name ASC'
				);
				
				while ($row = $result->fetch())
				{
					if (!in_array($row['name'], $last_version_modules) && Url::check_url_validity(str_replace($previous_version['major_version_number'], $last_version['major_version_number'], $row['url'])))
						$this->available_modules[$row['id']] = $row['name'];
				}
				$result->dispose();
			}
		}
		return $this->available_modules;
	}
	
	private function get_available_themes()
	{
		if ($this->available_themes === null)
		{
			$versions = $this->config->get_versions();
			$versions_number = count($versions);
			
			if ($versions_number >= 2)
			{
				$last_version = end($versions);
				$previous_version = prev($versions);
				
				$last_version_themes = array();
				
				$result = PersistenceContext::get_querier()->select('SELECT download.name
					FROM ' . PREFIX . 'download download
					LEFT JOIN ' . PREFIX . 'download_cats cats ON cats.id = download.id_category
					WHERE cats.rewrited_name = "themes-phpboost-' . Url::encode_rewrite($last_version['major_version_number']) . '"
					ORDER BY download.name ASC'
				);
				
				while ($row = $result->fetch())
				{
					$last_version_themes[] = $row['name'];
				}
				$result->dispose();
				
				$result = PersistenceContext::get_querier()->select('SELECT download.id, download.name, download.url
					FROM ' . PREFIX . 'download download
					LEFT JOIN ' . PREFIX . 'download_cats cats ON cats.id = download.id_category
					WHERE cats.rewrited_name = "themes-phpboost-' . Url::encode_rewrite($previous_version['major_version_number']) . '"
					ORDER BY download.name ASC'
				);
				
				while ($row = $result->fetch())
				{
					if (!in_array($row['name'], $last_version_themes) && Url::check_url_validity(str_replace($previous_version['major_version_number'], $last_version['major_version_number'], $row['url'])))
						$this->available_themes[$row['id']] = $row['name'];
				}
				$result->dispose();
			}
		}
		return $this->available_themes;
	}
	
	private function available_modules_options()
	{
		$options = array();
		
		foreach ($this->get_available_modules() as $id => $module)
		{
			$options[] = new FormFieldSelectChoiceOption($module, $id);
		}
		
		return $options;
	}
	
	private function available_themes_options()
	{
		$options = array();
		
		foreach ($this->get_available_themes() as $id => $theme)
		{
			$options[] = new FormFieldSelectChoiceOption($theme, $id);
		}
		
		return $options;
	}
	
	private function create_selected_downloadfiles($id, $id_category, $set_approved)
	{
		$now = new Date();
		
		try {
			$downloadfile = DownloadService::get_downloadfile('WHERE download.id=:id', array('id' => $id));
		} catch (RowNotFoundException $e) {
			return false;
		}
		
		$versions = $this->config->get_versions();
		$versions_number = count($versions);
		
		if ($versions_number >= 2)
		{
			$last_version = end($versions);
			$previous_version = prev($versions);
		}
		
		$keywords = array();
		foreach (array_keys(DownloadService::get_keywords_manager()->get_keywords($id)) as $keyword)
		{
			$keywords[] = str_replace($previous_version['major_version_number'], $last_version['major_version_number'], $keyword);
		}
		
		$contents = str_replace($previous_version['major_version_number'], $last_version['major_version_number'], $downloadfile->get_contents());
		if (preg_match('#<fieldset class="formatter-container formatter-fieldset"(.)*</fieldset>#', $contents))
			$contents = preg_replace('#<fieldset class="formatter-container formatter-fieldset"(.)*</fieldset>#', '<fieldset class="formatter-container formatter-fieldset" style=""><legend>' . $now->format(Date::FORMAT_DAY_MONTH_YEAR) . ' : v' . $last_version['major_version_number'] . '.0</legend><div class="formatter-content">Première Révision</div></fieldset>', $contents);
		
		$downloadfile->set_id(null);
		$downloadfile->set_id_category($id_category);
		$downloadfile->set_url(new Url(str_replace($previous_version['major_version_number'], $last_version['major_version_number'], $downloadfile->get_url()->rel())));
		$downloadfile->set_contents($contents);
		$downloadfile->set_short_contents(str_replace($previous_version['major_version_number'], $last_version['major_version_number'], $downloadfile->get_short_contents()));
		$downloadfile->set_picture(new Url(str_replace($previous_version['major_version_number'], $last_version['major_version_number'], $downloadfile->get_picture()->rel())));
		$downloadfile->set_creation_date($now);
		$downloadfile->set_updated_date($now);
		$downloadfile->set_number_downloads(0);
		$downloadfile->set_number_view(0);
		
		$file_size = Url::get_url_file_size($downloadfile->get_url());
		$file_size = (empty($file_size) && $downloadfile->get_size()) ? $downloadfile->get_size() : $file_size;
		$downloadfile->set_size($file_size);
		
		if (!$set_approved)
			$downloadfile->set_approbation_type(DownloadFile::NOT_APPROVAL);
		
		$downloadfile_id = DownloadService::add($downloadfile);
		
		DownloadService::get_keywords_manager()->put_relations($downloadfile_id, $keywords);
		
		return $downloadfile_id;
	}
}
?>
