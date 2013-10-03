<?php
/*##################################################
 *		    AdminManageArticlesController.class.php
 *                            -------------------
 *   begin                : June 04, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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

/**
 * @author Patrick DUBEAU <daaxwizeman@gmail.com>
 */
class AdminManageArticlesController extends AdminModuleController
{
	private $lang;
	private $view;
	private $form;
	
	public function execute(HTTPRequestCustom $request)
	{	
		$this->init();
		
		$this->build_view($request);
		
		return new AdminArticlesDisplayResponse($this->view, $this->lang['articles_management']);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('articles-common', 'articles');
		$this->view = new FileTemplate('articles/AdminManageArticlesController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function build_form($field, $mode)
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHorizontal('filters');
		$form->add_fieldset($fieldset);
		
		$sort_fields = $this->list_sort_fields();
		
		$fieldset->add_field(new FormFieldLabel($this->lang['articles.sort_filter_title']));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $field, $sort_fields,
			array('events' => array('change' => 'document.location = "'. ArticlesUrlBuilder::manage_articles()->absolute() .'" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			array(
				new FormFieldSelectChoiceOption($this->lang['articles.sort_mode.asc'], 'asc'),
				new FormFieldSelectChoiceOption($this->lang['articles.sort_mode.desc'], 'desc')
			), 
			array('events' => array('change' => 'document.location = "' . ArticlesUrlBuilder::manage_articles()->absolute() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));
		
		$this->form = $form;
	}
	
	private function build_view($request)
	{
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		
		$mode = $request->get_getstring('sort', 'desc');
		$field = $request->get_getstring('field', 'date');
			
		$sort_mode = ($mode == 'asc') ? 'ASC' : 'DESC';

		switch ($field)
		{
			case 'cat':
				$sort_field = 'id_category';
				break;
			case 'author':
				$sort_field = 'author_user_id';
				break;
			case 'title':
				$sort_field = 'title';
				break;
			case 'published':
				$sort_field = 'published';
				break;
			default:
				$sort_field = 'date_created';
				break;
		}
		
		$current_page = ($request->get_getint('page', 1) > 0) ? $request->get_getint('page', 1) : 1;
		$nbr_articles_per_page = 25;

		$limit_page = (($current_page - 1) * $nbr_articles_per_page);
		
		$result = PersistenceContext::get_querier()->select('SELECT articles.*, articles_cat.name, member.level, member.user_groups, member.user_id, member.login  
		FROM '. ArticlesSetup::$articles_table . ' articles
		LEFT JOIN ' . ArticlesSetup::$articles_cats_table . ' articles_cat ON articles_cat.id = articles.id_category 
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = articles.author_user_id
		ORDER BY ' . $sort_field . ' ' . $sort_mode . ' LIMIT ' . $nbr_articles_per_page .
		' OFFSET ' . $limit_page, array(), SelectQueryResult::FETCH_ASSOC);
		
		$nbr_articles = $result->get_rows_count();
		
		$this->build_form($field, $mode);
		
		$this->view->put_all(array(
			'L_ALERT_DELETE_ARTICLE' => $this->lang['articles.form.alert_delete_article']
		));
		
		if($nbr_articles > 0)
		{	
			$pagination = new ModulePagination($current_page, $nbr_articles, $nbr_articles_per_page);
			$pagination->set_url(ArticlesUrlBuilder::manage_articles($sort_field, $sort_mode, '/%d'));
			
			$this->view->put_all(array(
				'C_ARTICLES_FILTERS' => true,
				'L_ARTICLES_FILTERS_TITLE' => $this->lang['articles.sort_filter_title'],
				'L_AUTHOR' => $this->lang['admin.articles.sort_field.author'],
				'L_CATEGORY' => $this->lang['admin.articles.sort_field.cat'],
				'L_DATE' => $this->lang['admin.articles.sort_field.date'],
				'L_PUBLISHED' => $this->lang['admin.articles.sort_field.published'],
				'L_TITLE' => $this->lang['admin.articles.sort_field.title'],
				'PAGINATION' => ($nbr_articles > $nbr_articles_per_page) ? $pagination->display()->render() : ''
			));
			
			while($row = $result->fetch())
			{
				$user_group_color = User::get_group_color($row['user_groups'], $row['level']);
				
				$category = ArticlesService::get_categories_manager()->get_categories_cache()->get_category($row['id_category']);
				
				$title = strlen($row['title']) > 45 ? TextHelper::substr_html($row['title'], 0, 45) . '...' : $row['title'];
				
				$published_date = '';
				if ($row['publishing_start_date'] > 0)
					$published_date .= gmdate_format('date_format_short', $row['publishing_start_date']);

				if ($row['publishing_end_date'] > 0 && $row['publishing_start_date'] > 0)
					$published_date .= ' ' . strtolower(LangLoader::get_message('until', 'main')) . ' ' . gmdate_format('date_format_short', $row['publishing_end_date']);
				elseif ($row['publishing_end_date'] > 0)
					$published_date .= LangLoader::get_message('until', 'main') . ' ' . gmdate_format('date_format_short', $row['publishing_end_date']);
				
				
				$this->view->assign_block_vars('articles', array(
					'C_USER_GROUP_COLOR' => !empty($user_group_color),
					'L_TITLE' => $title,
					'L_CATEGORY' => $category->get_name(),
					'L_EDIT_ARTICLE' => $this->lang['articles.edit'],
					'L_DELETE_ARTICLE' => $this->lang['articles.delete'],
					'DATE' => gmdate_format('date_format_short', $row['date_created']), 
					'PUBLISHED' => ($row['published'] == 1) ? LangLoader::get_message('yes', 'main') : LangLoader::get_message('no', 'main'),
					'PUBLISHED_DATE' => $published_date,
					'PSEUDO' => $row['login'],
					'USER_LEVEL_CLASS' => UserService::get_level_class($row['level']),
					'USER_GROUP_COLOR' => $user_group_color,
					'U_AUTHOR' => UserUrlBuilder::profile($row['author_user_id'])->absolute(),
					'U_ARTICLE' => ArticlesUrlBuilder::display_article($category->get_id(), $category->get_rewrited_name(), $row['id'], $row['rewrited_title'])->absolute(),
					'U_CATEGORY' => ArticlesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->absolute(),
					'U_EDIT_ARTICLE' => ArticlesUrlBuilder::edit_article($row['id'])->absolute(),
					'U_DELETE_ARTICLE' => ArticlesUrlBuilder::delete_article($row['id'])->absolute()
				));
			}
		}
		else 
		{
			$this->view->put_all(array(
				'L_NO_ARTICLES' => $this->lang['articles.no_article']
			));
		}
		$this->view->put('FORM', $this->form->display());
	}
	
	private function list_sort_fields()
	{
		$options = array();

		$options[] = new FormFieldSelectChoiceOption($this->lang['admin.articles.sort_field.cat'], 'cat');
		$options[] = new FormFieldSelectChoiceOption($this->lang['admin.articles.sort_field.title'], 'title');
		$options[] = new FormFieldSelectChoiceOption($this->lang['admin.articles.sort_field.author'], 'author');
		$options[] = new FormFieldSelectChoiceOption($this->lang['admin.articles.sort_field.date'], 'date');
		$options[] = new FormFieldSelectChoiceOption($this->lang['admin.articles.sort_field.published'], 'published');

		return $options;
	}
}
?>