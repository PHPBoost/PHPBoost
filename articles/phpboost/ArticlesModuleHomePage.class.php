<?php
/*##################################################
 *                      ArticlesModuleHomePage.class.php
 *                            -------------------
 *   begin                : March 19, 2013
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
class ArticlesModuleHomePage implements ModuleHomePage
{
	private $lang;
	private $view;
	private $form;
	private $auth_read;
	private $add_auth;
	private $edit_auth;
	private $auth_moderation;
	
	public static function get_view()
	{
		$object = new self();
		return $object->build_view();
	}
	
	public function build_view()
	{
		$this->check_authorizations();
		$this->init();
		
		$request = AppContext::get_request();
		
		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->get_authorizations_bits(Category::READ_AUTHORIZATIONS);
		$search_category_children_options->set_enable_recursive_exploration(false);
		$categories = ArticlesService::get_categories_manager()->get_childrens(Category::ROOT_CATEGORY, $search_category_children_options);
		$ids_categories = array_keys($categories);
		
		$authorized_cats_sql = !empty($ids_categories) ? 'AND ac.id IN (' . implode(', ', $ids_categories) . ')' : '';
		
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		
		$current_page_cat = ($request->get_getint('page',1) > 0) ? $request->get_getint('page',1) : 1;
		
		$nbr_categories = count($categories);
		
		$nbr_categories_per_page = ArticlesConfig::load()->get_number_categories_per_page();
		
		$pagination_cat = new ArticlesPagination($current_page_cat, $nbr_categories, $nbr_categories_per_page);
		$pagination_cat->set_url(Category::ROOT_CATEGORY, Url::encode_rewrite(LangLoader::get_message('root', 'main')));
		
		$nbr_articles_root_cat = PersistenceContext::get_querier()->count(ArticlesSetup::$articles_table, 
					'WHERE id_category = 0 AND (published = 1 OR (published = 2 AND (publishing_start_date < :timestamp_now 
					AND publishing_end_date = 0) OR publishing_end_date > :timestamp_now))', 
					array(
					    'timestamp_now' => $now->get_timestamp()
		));
		
		$number_articles_not_published = PersistenceContext::get_querier()->count(ArticlesSetup::$articles_table, 'WHERE published = 0');
		
		$this->view->put_all(array(
			'C_ADD' => $this->add_auth,
			'C_MODERATE' => $this->auth_moderation,
			'C_PENDING_ARTICLES' => $number_articles_not_published > 0 && $this->auth_moderation,
			'ID_CAT' => ArticlesService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY),
			'L_DATE' => LangLoader::get_message('date', 'main'),
			'L_VIEW' => LangLoader::get_message('views', 'main'),
			'L_COM' => LangLoader::get_message('com', 'main'),
			'L_NOTE' => LangLoader::get_message('note', 'main'),
			'L_WRITTEN' => LangLoader::get_message('written_by', 'main'),
			'L_ARTICLES_INDEX' => $this->lang['articles'],
			'L_ADD_ARTICLES' => $this->lang['articles.add'],
			'L_ALERT_DELETE_ARTICLE' => $this->lang['articles.form.alert_delete_article'],
			'L_SUBCATEGORIES' => $this->lang['articles.sub_categories'],
			'L_MANAGE_CATEGORIES' => $this->lang['admin.categories.manage'],
			'L_EDIT_CONFIG' => $this->lang['articles_configuration'],
			'L_MODULE_NAME' => $this->lang['articles'],
			'L_PENDING_ARTICLES' => $this->lang['articles.pending_articles'],
			'U_EDIT_CONFIG' => ArticlesUrlBuilder::articles_configuration()->absolute(),
			'U_MANAGE_CATEGORIES' => ArticlesUrlBuilder::manage_categories()->absolute(),
			'U_PENDING_ARTICLES' => ArticlesUrlBuilder::display_pending_articles()->absolute(), 
			'U_ADD_ARTICLES' => ArticlesUrlBuilder::add_article()->absolute()
		));

		$limit_page = $pagination_cat->get_display_from();
		
		//All root cats
		$result = PersistenceContext::get_querier()->select('SELECT @id_cat:= ac.id, ac.id, ac.name, ac.description, ac.image, ac.rewrited_name,
		(SELECT COUNT(*) FROM '. ArticlesSetup::$articles_table .' articles 
		WHERE articles.id_category = @id_cat AND (articles.published = 1 OR (articles.published = 2 
		AND (articles.publishing_start_date < :timestamp_now AND articles.publishing_end_date > :timestamp_now) 
		OR articles.publishing_end_date = 0))) AS nbr_articles FROM ' . ArticlesSetup::$articles_cats_table .
		' ac WHERE ac.id_parent = 0 ' . $authorized_cats_sql . ' ORDER BY ac.id_parent, ac.c_order LIMIT ' . $nbr_categories_per_page . ' OFFSET :start_limit',
		array(
			'timestamp_now' => $now->get_timestamp(),
			'start_limit' => $limit_page
			), SelectQueryResult::FETCH_ASSOC
		);
		//Sub-cats and their children of root display
		while ($row = $result->fetch())
		{
			$children_cat = ArticlesService::get_categories_manager()->get_childrens($row['id'], $search_category_children_options);
			$children_cat_links = '';
			$nbr_children_cat = count($children_cat);
			
			if (!empty($children_cat))
			{
				foreach ($children_cat as $child_cat)
				{
					$children_cat_links .= '<a style="font-size:10px;" href="' . ArticlesUrlBuilder::display_category($child_cat->get_id(), $child_cat->get_rewrited_name())->absolute() . '">' . $child_cat->get_name() . '</a>';
					if ($nbr_children_cat - 1 > 0)
					{
					    $children_cat_links .= ', ';
					    $nbr_children_cat--;
					}
				}
			}
			
			$this->view->assign_block_vars('cat_list', array(
				'ID_CATEGORY' => $row['id'],
				'CATEGORY_NAME' => $row['name'] . ' (' . $row['nbr_articles'] . ')',
				'CATEGORY_DESCRIPTION' => FormatingHelper::second_parse($row['description']),
				'CATEGORY_ICON_SOURCE' => !empty($row['image']) ? ($row['image'] == 'articles.png' || $row['image'] == 'articles_mini.png' ? ArticlesUrlBuilder::home()->absolute() . $row['image'] : $row['image']) : '',
				'L_NBR_ARTICLES_CAT' => sprintf($this->lang['articles.nbr_articles_category'],$row['nbr_articles']),
				'U_CATEGORY' => ArticlesUrlBuilder::display_category($row['id'], $row['rewrited_name'])->absolute(),
				'U_SUBCATEGORIES' => (count($children_cat) > 0) ? $children_cat_links : 'aucune'
			));
		}
		
		if ($nbr_categories > 0)
		{
			$nbr_column_cats = ($nbr_categories > $nbr_categories_per_page) ? $nbr_categories_per_page : $nbr_categories;
			$nbr_column_cats = ($nbr_column_cats <= 2) ? 1 : $nbr_column_cats - 1;//We exclude the root cat
			$column_width_cat = floor(100 / $nbr_column_cats);
			
			$this->view->put_all(array(
				'C_ARTICLES_CAT' => true,
				'COLUMN_WIDTH_CAT' => $column_width_cat,
				'PAGINATION_CAT' => ($nbr_categories > $nbr_categories_per_page) ? $pagination_cat->display()->render() : '',
			));
		}
		//Articles in root cat
		if ($nbr_articles_root_cat > 0)
		{
			$mode = $request->get_getstring('sort', 'desc');
			$field = $request->get_getstring('field', 'date');
			
			$sort_mode = ($mode == 'asc') ? 'ASC' : 'DESC';
			
			switch ($field)
			{
				case 'title':
					$sort_field = 'title';
					break;
				case 'view':
					$sort_field = 'number_view';
					break;
				case 'com':
					$sort_field = 'number_comments';
					break;
				case 'note':
					$sort_field = 'number_notes';
					break;
				case 'author':
					$sort_field = 'author_user_id';
					break;
				default:
					$sort_field = 'date_created';
					break;
			}
			
			$current_page = ($request->get_getint('page', 1) > 0) ? $request->get_getint('page', 1) : 1;
			$nbr_articles_per_page = ArticlesConfig::load()->get_number_articles_per_page();
			
			$pagination = new ArticlesPagination($current_page, $nbr_articles_root_cat, $nbr_articles_per_page);
			
			$limit_page = $pagination->get_display_from();

			$result = PersistenceContext::get_querier()->select('SELECT articles.*, member.level, member.user_groups, member.login, note.number_notes
			FROM ' . ArticlesSetup::$articles_table . ' articles
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = articles.author_user_id
			LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = articles.id AND com.module_id = "articles"
			LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' note ON note.id_in_module = articles.id AND note.module_name = "articles"
			WHERE articles.id_category = :id_category AND (articles.published = 1 OR (articles.published = 2 AND (articles.publishing_start_date < :timestamp_now 
			AND articles.publishing_end_date = 0) OR articles.publishing_end_date > :timestamp_now)) 
			ORDER BY :field :sort LIMIT ' . $nbr_articles_per_page . ' OFFSET :start_limit', 
				array(
				'id_category' => Category::ROOT_CATEGORY,
				'timestamp_now' => $now->get_timestamp(),
				'start_limit' => $limit_page,
				'field' => $sort_field,
				'sort' => $sort_mode
				    ), SelectQueryResult::FETCH_ASSOC
			    );

			$pagination->set_url(Category::ROOT_CATEGORY, Url::encode_rewrite(LangLoader::get_message('root', 'main')));

			$this->build_form($field, $mode);

			$this->view->put_all(array(
				    'C_ARTICLES_FILTERS' => true,
				    'L_ARTICLES_FILTERS_TITLE' => $this->lang['articles.sort_filter_title'],
				    'PAGINATION' => ($nbr_articles_root_cat > $nbr_articles_per_page) ? $pagination->display()->render() : '',
				    'L_TOTAL_ARTICLES' => sprintf($this->lang['articles.nbr_articles_category'],$nbr_articles_root_cat)
			));

			$notation = new Notation();
			$notation->set_module_name('articles');
			$notation->set_notation_scale(ArticlesConfig::load()->get_notation_scale());

			while($row = $result->fetch())
			{
				$notation->set_id_in_module($row['id']);

				$group_color = User::get_group_color($row['user_groups'], $row['level']);

				$this->view->assign_block_vars('articles', array(
					'C_GROUP_COLOR' => !empty($group_color),
					'TITLE' => $row['title'],
					'PICTURE' => $row['picture_url'],// @todo : link
					'DATE' => gmdate_format('date_format_short', $row['date_created']),
					'NUMBER_VIEW' => $row['number_view'],
					'L_NUMBER_COM' => empty($row['number_comments']) ? '0' : $row['number_comments'],
					'NOTE' => $row['number_notes'] > 0 ? NotationService::display_static_image($notation, $row['average_notes']) : $this->lang['articles.no_notes'],
					'DESCRIPTION' =>FormatingHelper::second_parse($row['description']),                                    
					'U_ARTICLE_LINK_COM' => ArticlesUrlBuilder::home()->absolute() . $row['id'] . '-' . $row['rewrited_title'] . '/comments/',
					'U_AUTHOR' => '<a href="' . UserUrlBuilder::profile($row['author_user_id'])->absolute() . '" class="' . UserService::get_level_class($row['level']) . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . TextHelper::wordwrap_html($row['login'], 19) . '</a>',
					/* @ todo: à voir si je ne devrais pas créer un objet RichCategory*/'U_ARTICLE_LINK' => ArticlesUrlBuilder::display_article(Category::ROOT_CATEGORY, 'root', $row['id'], $row['rewrited_title'])->absolute(),
					'U_EDIT_ARTICLE' => ArticlesUrlBuilder::edit_article($row['id'])->absolute(),
					'U_DELETE_ARTICLE' => ArticlesUrlBuilder::delete_article($row['id'])->absolute()
				));
			}
			
			$this->view->put('FORM', $this->form->display());
		}
		else 
		{
			$this->view->put_all(array(
				'L_NO_ARTICLE' => $this->lang['articles.no_article']
			));
		}
		
		return $this->view;
	}
	
	private function build_form($field, $mode)
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHorizontal('filters'); // @todo : voir si je passe une classe en option pour styliser
		$form->add_fieldset($fieldset);
		
		$sort_fields = $this->list_sort_fields();
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $field, $sort_fields,
			array('events' => array('change' => 'document.location = "'. ArticlesUrlBuilder::home()->absolute() .'" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			array(
				new FormFieldSelectChoiceOption($this->lang['articles.sort_mode.asc'], 'asc'),
				new FormFieldSelectChoiceOption($this->lang['articles.sort_mode.desc'], 'desc')
			), 
			array('events' => array('change' => 'document.location = "' . ArticlesUrlBuilder::home()->absolute() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));
		
		$this->form = $form;
	}
	
	private function list_sort_fields()
	{
		$options = array();

		$options[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.date'], 'date');
		$options[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.title'], 'title');
		$options[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.views'], 'view');
		$options[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.com'], 'com');
		$options[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.note'], 'note');
		$options[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.author'], 'author');

		return $options;
	}
	
	private function check_authorizations()
	{
		$this->auth_read = ArticlesAuthorizationsService::check_authorizations()->read();
		$this->add_auth = ArticlesAuthorizationsService::check_authorizations()->write() || ArticlesAuthorizationsService::check_authorizations()->contribution();
		$this->edit_auth = ArticlesAuthorizationsService::check_authorizations()->write() || ArticlesAuthorizationsService::check_authorizations()->moderation();
		$this->auth_moderation = ArticlesAuthorizationsService::check_authorizations()->moderation();
		
		if (!($this->auth_read))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('articles-common', 'articles');
		$this->view = new FileTemplate('articles/ArticlesModuleHomePage.tpl');
		$this->view->add_lang($this->lang);
	}
}
?>