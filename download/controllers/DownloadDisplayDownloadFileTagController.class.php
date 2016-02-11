<?php
/*##################################################
 *                               DownloadDisplayDownloadFileTagController.class.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class DownloadDisplayDownloadFileTagController extends ModuleController
{
	private $tpl;
	private $lang;
	
	private $keyword;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->build_view($request);
		
		return $this->generate_response();
	}
	
	public function init()
	{
		$this->lang = LangLoader::get('common', 'download');
		$this->tpl = new FileTemplate('download/DownloadDisplaySeveralDownloadFilesController.tpl');
		$this->tpl->add_lang($this->lang);
	}
	
	public function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$config = DownloadConfig::load();
		$authorized_categories = DownloadService::get_authorized_categories(Category::ROOT_CATEGORY);
		$mode = $request->get_getstring('sort', DownloadUrlBuilder::DEFAULT_SORT_MODE);
		$field = $request->get_getstring('field', DownloadUrlBuilder::DEFAULT_SORT_FIELD);
		
		$condition = 'WHERE relation.id_keyword = :id_keyword
		AND id_category IN :authorized_categories
		AND (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0)))';
		$parameters = array(
			'id_keyword' => $this->get_keyword()->get_id(),
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);
		
		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $field, $mode, $page);
		
		$sort_mode = ($mode == 'asc') ? 'ASC' : 'DESC';
		switch ($field)
		{
			case 'name':
				$sort_field = DownloadFile::SORT_ALPHABETIC;
				break;
			case 'download':
				$sort_field = DownloadFile::SORT_NUMBER_DOWNLOADS;
				break;
			case 'com':
				$sort_field = DownloadFile::SORT_NUMBER_COMMENTS;
				break;
			case 'note':
				$sort_field = DownloadFile::SORT_NOTATION;
				break;
			case 'author':
				$sort_field = DownloadFile::SORT_AUTHOR;
				break;
			case 'creation_date':
				$sort_field = DownloadFile::SORT_DATE;
				break;
			default:
				$sort_field = DownloadFile::SORT_UPDATED_DATE;
				break;
		}
		
		$result = PersistenceContext::get_querier()->select('SELECT download.*, member.*, com.number_comments, notes.average_notes, notes.number_notes, note.note
		FROM ' . DownloadSetup::$download_table . ' download
		LEFT JOIN ' . DB_TABLE_KEYWORDS_RELATIONS . ' relation ON relation.module_id = \'download\' AND relation.id_in_module = download.id 
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = download.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = download.id AND com.module_id = \'download\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = download.id AND notes.module_name = \'download\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = download.id AND note.module_name = \'download\' AND note.user_id = :user_id
		' . $condition . '
		ORDER BY ' . $sort_field . ' ' . $sort_mode . '
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));
		
		$this->tpl->put_all(array(
			'C_FILES' => $result->get_rows_count() > 0,
			'C_MORE_THAN_ONE_FILE' => $result->get_rows_count() > 1,
			'C_CATEGORY_DISPLAYED_SUMMARY' => $config->is_category_displayed_summary(),
			'C_CATEGORY_DISPLAYED_TABLE' => $config->is_category_displayed_table(),
			'C_COMMENTS_ENABLED' => $config->are_comments_enabled(),
			'C_NOTATION_ENABLED' => $config->is_notation_enabled(),
			'C_AUTHOR_DISPLAYED' => $config->is_author_displayed(),
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display(),
			'TABLE_COLSPAN' => 4 + (int)$config->are_comments_enabled() + (int)$config->is_notation_enabled(),
			'CATEGORY_NAME' => $this->get_keyword()->get_name()
		));
		
		while ($row = $result->fetch())
		{
			$downloadfile = new DownloadFile();
			$downloadfile->set_properties($row);
			
			$keywords = $downloadfile->get_keywords();
			$has_keywords = count($keywords) > 0;
			
			$this->tpl->assign_block_vars('downloadfiles', array_merge($downloadfile->get_array_tpl_vars(), array(
				'C_KEYWORDS' => $has_keywords
			)));
			
			if ($has_keywords)
				$this->build_keywords_view($keywords);
		}
		$result->dispose();
		$this->build_sorting_form($field, $mode);
	}
	
	private function build_sorting_form($field, $mode)
	{
		$common_lang = LangLoader::get('common');
		
		$form = new HTMLForm(__CLASS__, '', false);
		$form->set_css_class('options');
		
		$fieldset = new FormFieldsetHorizontal('filters', array('description' => $common_lang['sort_by']));
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $field, 
			array(
				new FormFieldSelectChoiceOption($common_lang['form.date.update'], 'updated_date'),
				new FormFieldSelectChoiceOption($common_lang['form.date.creation'], 'date'),
				new FormFieldSelectChoiceOption($common_lang['form.name'], 'name'),
				new FormFieldSelectChoiceOption($this->lang['downloads_number'], 'download'),
				new FormFieldSelectChoiceOption($common_lang['sort_by.number_comments'], 'com'),
				new FormFieldSelectChoiceOption($common_lang['sort_by.best_note'], 'note'),
				new FormFieldSelectChoiceOption($common_lang['author'], 'author')
			), 
			array('events' => array('change' => 'document.location = "'. DownloadUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name())->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			array(
				new FormFieldSelectChoiceOption($common_lang['sort.asc'], 'asc'),
				new FormFieldSelectChoiceOption($common_lang['sort.desc'], 'desc')
			), 
			array('events' => array('change' => 'document.location = "' . DownloadUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name())->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));
		
		$this->tpl->put('SORT_FORM', $form->display());
	}
	
	private function get_keyword()
	{
		if ($this->keyword === null)
		{
			$rewrited_name = AppContext::get_request()->get_getstring('tag', '');
			if (!empty($rewrited_name))
			{
				try {
					$this->keyword = DownloadService::get_keywords_manager()->get_keyword('WHERE rewrited_name=:rewrited_name', array('rewrited_name' => $rewrited_name));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$error_controller = PHPBoostErrors::unexisting_page();
   				DispatchManager::redirect($error_controller);
			}
		}
		return $this->keyword;
	}
	
	private function get_pagination($condition, $parameters, $field, $mode, $page)
	{
		$result = PersistenceContext::get_querier()->select_single_row_query('SELECT COUNT(*) AS downloadfiles_number
		FROM '. DownloadSetup::$download_table .' download
		LEFT JOIN '. DB_TABLE_KEYWORDS_RELATIONS .' relation ON relation.module_id = \'download\' AND relation.id_in_module = download.id 
		' . $condition, $parameters);
		
		$pagination = new ModulePagination($page, $result['downloadfiles_number'], (int)DownloadConfig::load()->get_items_number_per_page());
		$pagination->set_url(DownloadUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), $field, $mode, '%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
	
	private function build_keywords_view($keywords)
	{
		$nbr_keywords = count($keywords);
		
		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->tpl->assign_block_vars('downloadfiles.keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => DownloadUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}
	
	private function check_authorizations()
	{
		if (!DownloadAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);
		
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->get_keyword()->get_name(), $this->lang['module_title']);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['download.seo.description.tag'], array('subject' => $this->get_keyword()->get_name())));
		$graphical_environment->get_seo_meta_data()->set_canonical_url(DownloadUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), AppContext::get_request()->get_getstring('field', 'date'), AppContext::get_request()->get_getstring('sort', 'desc'), AppContext::get_request()->get_getint('page', 1)));
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], DownloadUrlBuilder::home());
		$breadcrumb->add($this->get_keyword()->get_name(), DownloadUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), AppContext::get_request()->get_getint('page', 1)));
		
		return $response;
	}
}
?>
