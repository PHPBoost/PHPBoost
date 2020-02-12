<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 12
 * @since       PHPBoost 4.0 - 2013 03 03
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ArticlesDisplayArticlesController extends AbstractItemController
{
	private $item;
	private $category;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->check_pending_article($request);

		$this->build_view($request);

		return $this->generate_response();
	}

	private function get_article()
	{
		if ($this->item === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try
				{
					$this->item = self::get_items_manager()->get_item($id);
				}
				catch (RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->item = new Article();
		}
		return $this->item;
	}

	private function check_pending_article(HTTPRequestCustom $request)
	{
		if (!$this->item->is_published())
		{
			$this->view->put('NOT_VISIBLE_MESSAGE', MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING));
		}
		else
		{
			if ($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), ItemsUrlBuilder::display($this->item->get_category()->get_id(), $this->item->get_category()->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title(), 'articles')->rel()))
			{
				self::get_items_manager()->update_views_number($this->item);
			}
		}
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$current_page = $request->get_getint('page', 1);
		$config = ArticlesConfig::load();

		$this->category = $this->item->get_category();

		$content = $this->item->get_content();

		//If article doesn't begin with a page, we insert one
		if (TextHelper::substr(trim($content), 0, 6) != '[page]')
		{
			$content = '[page]&nbsp;[/page]' . $content;
		}

		//Removing [page] bbcode
		$clean_content = preg_split('`\[page\].+\[/page\](.*)`usU', $content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

		//Retrieving pages
		preg_match_all('`\[page\]([^[]+)\[/page\]`uU', $content, $array_page);

		$pages_number = count($array_page[1]);

		if ($pages_number > 1)
			$this->build_form($array_page, $current_page);

		foreach ($this->item->get_sources() as $name => $url)
		{
			$this->view->assign_block_vars('sources', $this->item->get_template_source_vars($name));
		}

		$this->build_keywords_view();

		$page_name = (isset($array_page[1][$current_page-1]) && $array_page[1][$current_page-1] != '&nbsp;') ? $array_page[1][($current_page-1)] : '';

		$this->view->put_all(array_merge($this->item->get_template_vars(), array(
			'KERNEL_NOTATION' => NotationService::display_active_image($this->item->get_notation()),
			'CONTENT'         => isset($clean_content[$current_page-1]) ? FormatingHelper::second_parse($clean_content[$current_page-1]) : '',
			'PAGE_NAME'       => $page_name,
			'U_EDIT'          => $page_name !== '' ? ItemsUrlBuilder::edit($this->item->get_id(), 'articles', $current_page)->rel() : ItemsUrlBuilder::edit($this->item->get_id())->rel()
		)));

		$this->build_pages_pagination($current_page, $pages_number, $array_page);

		//Affichage commentaires
		if (in_array('comments', $this->enabled_features))
		{
			$comments_topic = new DefaultCommentsTopic('articles', $this->item);
			$comments_topic->set_id_in_module($this->item->get_id());
			$comments_topic->set_url(ItemsUrlBuilder::display($this->category->get_id(), $this->category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title(), 'articles'));

			$this->view->put('COMMENTS', $comments_topic->display());
		}
	}

	private function build_form($array_page, $current_page)
	{
		$form = new HTMLForm(__CLASS__, '', false);
		$form->set_css_class('options');

		$fieldset = new FormFieldsetHorizontal('pages', array('description' => $this->lang['articles.table.of.contents']));

		$form->add_fieldset($fieldset);

		$article_pages = $this->list_article_pages($array_page);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('article_pages', '', $current_page, $article_pages,
			array('class' => 'summary', 'events' => array('change' => 'document.location = "' . ItemsUrlBuilder::display($this->category->get_id(), $this->category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title(), 'articles', '')->rel() . '" + HTMLForms.getField("article_pages").getValue();'))
		));

		$this->view->put('FORM', $form->display());
	}

	private function build_pages_pagination($current_page, $pages_number, $array_page)
	{
		if ($pages_number > 1)
		{
			$pagination = $this->get_pagination($pages_number, $current_page);

			if ($current_page > 1 && $current_page <= $pages_number)
			{
				$previous_page = ItemsUrlBuilder::display($this->category->get_id(), $this->category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title(), 'articles', '')->rel() . ($current_page - 1);

				$this->view->put_all(array(
					'U_PREVIOUS_PAGE' => $previous_page,
					'L_PREVIOUS_TITLE' => $array_page[1][$current_page-2]
				));
			}

			if ($current_page > 0 && $current_page < $pages_number)
			{
				$next_page = ItemsUrlBuilder::display($this->category->get_id(), $this->category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title(), 'articles', '')->rel() . ($current_page + 1);

				$this->view->put_all(array(
					'U_NEXT_PAGE' => $next_page,
					'L_NEXT_TITLE' => $array_page[1][$current_page]
				));
			}

			$this->view->put_all(array(
				'C_PAGINATION' => true,
				'C_FIRST_PAGE' => $current_page == 1,
				'C_PREVIOUS_PAGE' => $current_page != 1,
				'C_NEXT_PAGE' => $current_page != $pages_number,
				'PAGINATION_ARTICLES' => $pagination->display()
			));
		}
		else
		{
			$this->view->put('C_FIRST_PAGE', true);
		}
	}

	private function list_article_pages($array_page)
	{
		$options = array();

		$i = 1;
		foreach ($array_page[1] as $page_name)
		{
			$options[] = new FormFieldSelectChoiceOption($page_name, $i++);
		}

		return $options;
	}

	private function build_keywords_view()
	{
		$keywords = $this->item->get_keywords();
		$this->view->put('C_KEYWORDS', !empty($keywords));

		foreach ($keywords as $keyword)
		{
			$this->view->assign_block_vars('keywords', array(
				'NAME' => $keyword->get_name(),
				'URL' => ItemsUrlBuilder::display_tag($keyword->get_rewrited_name(), 'articles')->rel(),
			));
		}
	}

	protected function get_template_to_use()
	{
		return new FileTemplate('articles/ArticlesDisplayArticlesController.tpl');
	}

	protected function check_authorizations()
	{
		$article = $this->get_article();

		$current_user = AppContext::get_current_user();
		$not_authorized = !CategoriesAuthorizationsService::check_authorizations($article->get_id_category())->moderation() && !CategoriesAuthorizationsService::check_authorizations($article->get_id_category())->write() && (!CategoriesAuthorizationsService::check_authorizations($article->get_id_category())->contribution() || $article->get_author_user()->get_id() != $current_user->get_id());

		switch ($article->get_publishing_state())
		{
			case Article::PUBLISHED:
				if (!CategoriesAuthorizationsService::check_authorizations($article->get_id_category())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			case Article::NOT_PUBLISHED:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			case Article::DEFERRED_PUBLICATION:
				if (!$article->is_published() && ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL)))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			default:
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			break;
		}
	}

	private function get_pagination($pages_number, $current_page)
	{
		$pagination = new ModulePagination($current_page, $pages_number, 1, Pagination::LIGHT_PAGINATION);
		$pagination->set_url(ItemsUrlBuilder::display($this->category->get_id(), $this->category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title(), 'articles', '', '%d'));

		if ($pagination->current_page_is_empty() && $current_page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->item->get_title(), ($this->category->get_id() != Category::ROOT_CATEGORY ? $this->category->get_name() . ' - ' : '') . self::get_module()->get_configuration()->get_name());
		$graphical_environment->get_seo_meta_data()->set_description($this->item->get_real_summary());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(ItemsUrlBuilder::display($this->category->get_id(), $this->category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title(), 'articles', '', AppContext::get_request()->get_getint('page', 1)));

		if ($this->item->has_thumbnail())
			$graphical_environment->get_seo_meta_data()->set_picture_url($this->item->get_thumbnail());

		$graphical_environment->get_seo_meta_data()->set_page_type('article');

		$additionnal_properties = array(
			'article:section' => $this->category->get_name(),
			'article:published_time' => $this->item->get_creation_date()->format(Date::FORMAT_ISO8601)
		);

		if ($this->item->get_keywords())
			$additionnal_properties['article:tag'] = $this->item->get_keywords_name();

		if ($this->item->get_update_date() !== null)
			$additionnal_properties['article:modified_time'] = $this->item->get_update_date()->format(Date::FORMAT_ISO8601);

		if ($this->item->get_publishing_end_date() !== null)
			$additionnal_properties['article:expiration_time'] = $this->item->get_publishing_end_date()->format(Date::FORMAT_ISO8601);

		$graphical_environment->get_seo_meta_data()->set_additionnal_properties($additionnal_properties);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add(self::get_module()->get_configuration()->get_name(), ModulesUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($this->item->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), CategoriesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), 'articles'));
		}
		$breadcrumb->add($this->item->get_title(), ItemsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title(), 'articles'));

		return $response;
	}
}
?>
