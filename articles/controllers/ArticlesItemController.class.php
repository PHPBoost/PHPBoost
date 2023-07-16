<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 07 16
 * @since       PHPBoost 6.0 - 2021 03 15
*/

class ArticlesItemController extends DefaultDisplayItemController
{
	protected function build_view()
	{
		parent::build_view();

		$current_page = $this->request->get_getint('page', 1);

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

		$page_name = (isset($array_page[1][$current_page-1]) && $array_page[1][$current_page-1] != '&nbsp;') ? $array_page[1][($current_page-1)] : '';

		$this->view->put_all(array(
            'C_PAGE_NAME' => !empty($page_name),
			'CONTENT'   => isset($clean_content[$current_page-1]) ? FormatingHelper::second_parse($clean_content[$current_page-1]) : '',
			'PAGE_NAME' => $page_name,
			'U_EDIT'    => $page_name !== '' ? ItemsUrlBuilder::edit($this->item->get_id(), 'articles', $current_page)->rel() : ItemsUrlBuilder::edit($this->item->get_id())->rel()
        ));

		$this->build_pages_pagination($current_page, $pages_number, $array_page);
	}

	private function build_form($array_page, $current_page)
	{
		$form = new HTMLForm(__CLASS__, '', false);
		$form->set_css_class('options');

		$fieldset = new FormFieldsetHorizontal('pages', ['description' => $this->lang['articles.table.of.contents']]);

		$form->add_fieldset($fieldset);

		$article_pages = $this->list_article_pages($array_page);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('article_pages', '', $current_page, $article_pages,
			array(
                'class' => 'summary', 
                'events' => array('change' => 'document.location = "' . ItemsUrlBuilder::display($this->item->get_category()->get_id(), $this->item->get_category()->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title(), 'articles', '')->rel() . '" + HTMLForms.getField("article_pages").getValue();')
            )
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
				$previous_page = ItemsUrlBuilder::display($this->item->get_category()->get_id(), $this->item->get_category()->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title(), 'articles', '')->rel() . ($current_page - 1);

				$this->view->put_all(array(
					'U_PREVIOUS_PAGE' => $previous_page,
					'L_PREVIOUS_TITLE' => $array_page[1][$current_page-2]
                ));
			}

			if ($current_page > 0 && $current_page < $pages_number)
			{
				$next_page = ItemsUrlBuilder::display($this->item->get_category()->get_id(), $this->item->get_category()->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title(), 'articles', '')->rel() . ($current_page + 1);

				$this->view->put_all(array(
					'U_NEXT_PAGE' => $next_page,
					'L_NEXT_TITLE' => $array_page[1][$current_page]
                ));
			}

			$this->view->put_all(array(
				'C_PAGINATION'	  => true,
				'C_FIRST_PAGE' 	  => $current_page == 1,
				'C_PREVIOUS_PAGE' => $current_page != 1,
				'C_NEXT_PAGE' 	  => $current_page != $pages_number,
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
		$options = [];

		$i = 1;
		foreach ($array_page[1] as $page_name)
		{
			$options[] = new FormFieldSelectChoiceOption($page_name, $i++);
		}

		return $options;
	}

	private function get_pagination($pages_number, $current_page)
	{
		$pagination = new ModulePagination($current_page, $pages_number, 1, Pagination::LIGHT_PAGINATION);
		$pagination->set_url(ItemsUrlBuilder::display($this->item->get_category()->get_id(), $this->item->get_category()->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title(), 'articles', '', '%d'));

		if ($pagination->current_page_is_empty() && $current_page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	protected function get_additionnal_seo_properties()
	{
		$additionnal_properties = [];

		$additionnal_properties['article:published_time'] = $this->get_item()->get_creation_date()->format(Date::FORMAT_ISO8601);

		if ($this->get_item()->get_category()->get_id() != Category::ROOT_CATEGORY)
			$additionnal_properties['article:section'] = $this->get_item()->get_category()->get_name();

		if ($this->get_item()->get_keywords())
			$additionnal_properties['article:tag'] = $this->get_item()->get_keywords_name();

		if ($this->get_item()->get_update_date() !== null)
			$additionnal_properties['article:modified_time'] = $this->get_item()->get_update_date()->format(Date::FORMAT_ISO8601);

		if ($this->get_item()->get_publishing_end_date() !== null)
			$additionnal_properties['article:expiration_time'] = $this->get_item()->get_publishing_end_date()->format(Date::FORMAT_ISO8601);

		return $additionnal_properties;
	}

	protected function get_seo_page_type()
	{
		return 'article';
	}
}
?>
