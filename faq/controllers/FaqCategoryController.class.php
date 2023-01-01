<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 12 14
 * @since       PHPBoost 4.0 - 2014 09 02
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FaqCategoryController extends DefaultModuleController
{
	private $category;

	protected function get_template_to_use()
	{
		return new FileTemplate('faq/FaqSeveralItemsController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view($request);

		return $this->generate_response($request);
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$subcategories_page = $request->get_getint('subcategories_page', 1);

		$subcategories = CategoriesService::get_categories_manager('faq')->get_categories_cache()->get_children($this->get_category()->get_id(), CategoriesService::get_authorized_categories($this->get_category()->get_id(), true, 'faq'));
		$subcategories_pagination = $this->get_subcategories_pagination(count($subcategories), $this->config->get_categories_per_page(), $subcategories_page);

		$categories_number = 0;
		foreach ($subcategories as $id => $category)
		{
			$categories_number++;

			if ($categories_number > $subcategories_pagination->get_display_from() && $categories_number <= ($subcategories_pagination->get_display_from() + $subcategories_pagination->get_number_items_per_page()))
			{
				$this->view->assign_block_vars('sub_categories_list', array(
					'C_CATEGORY_THUMBNAIL' => !empty($category->get_thumbnail()),
					'C_SEVERAL_ITEMS'      => $category->get_elements_number() > 1,

					'CATEGORY_ID'        => $category->get_id(),
					'CATEGORY_NAME'      => $category->get_name(),
					'CATEGORY_PARENT_ID' => $category->get_id_parent(),
					'CATEGORY_SUB_ORDER' => $category->get_order(),
					'ITEMS_NUMBER' 	     => $category->get_elements_number(),

					'U_CATEGORY_THUMBNAIL' => $category->get_thumbnail()->rel(),
					'U_CATEGORY'           => FaqUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel()
				));
			}
		}

		$categories_per_row = ($categories_number > $this->config->get_categories_per_row()) ? $this->config->get_categories_per_row() : $categories_number;
		$categories_per_row = !empty($categories_per_row) ? $categories_per_row : 1;

		$result = PersistenceContext::get_querier()->select('SELECT *
		FROM '. FaqSetup::$faq_table .' faq
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = faq.author_user_id
		WHERE approved = 1
		AND faq.id_category = :id_category
		ORDER BY q_order ASC', array(
			'id_category' => $this->get_category()->get_id()
		));

		$category_description = FormatingHelper::second_parse($this->get_category()->get_description());

		$this->view->put_all(array(
			'C_CATEGORY'                 => true,
			'C_CATEGORY_THUMBNAIL' 		 => !$this->get_category()->get_id() == Category::ROOT_CATEGORY && !empty($this->get_category()->get_thumbnail()->rel()),
			'C_ROOT_CATEGORY'            => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_HIDE_NO_ITEM_MESSAGE'     => $this->get_category()->get_id() == Category::ROOT_CATEGORY && ($categories_number != 0 || !empty($category_description)),
			'C_CATEGORY_DESCRIPTION'     => !empty($category_description),
			'C_SUB_CATEGORIES'           => $categories_number > 0,
			'C_ITEMS'                    => $result->get_rows_count() > 0,
			'C_SEVERAL_ITEMS'            => $result->get_rows_count() > 1,
			'C_SINGLE_VIEW'              => $this->config->get_display_type() == FaqConfig::SIBLINGS_VIEW,
			'C_DISPLAY_CONTROLS'         => $this->config->are_control_buttons_displayed(),
			'C_DISPLAY_REORDER_LINK'     => $result->get_rows_count() > 1 && CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id())->moderation(),
			'C_SUBCATEGORIES_PAGINATION' => $subcategories_pagination->has_several_pages(),

			'SUBCATEGORIES_PAGINATION' => $subcategories_pagination->display(),
			'CATEGORIES_NUMBER'        => $categories_per_row,
			'CATEGORY_ID'              => $this->get_category()->get_id(),
			'CATEGORY_NAME'            => $this->get_category()->get_name(),
			'CATEGORY_PARENT_ID'   	   => $this->get_category()->get_id_parent(),
			'CATEGORY_SUB_ORDER'   	   => $this->get_category()->get_order(),
			'CATEGORY_DESCRIPTION'     => $category_description,
			'ITEMS_NUMBER'             => $result->get_rows_count(),

			'U_CATEGORY_THUMBNAIL' => $this->get_category()->get_thumbnail()->rel(),
			'U_EDIT_CATEGORY'      => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? FaqUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit($this->get_category()->get_id(), 'faq')->rel(),
			'U_REORDER_ITEMS'      => FaqUrlBuilder::reorder_items($this->get_category()->get_id(), $this->get_category()->get_rewrited_name())->rel(),
		));

		while ($row = $result->fetch())
		{
			$item = new FaqItem();
			$item->set_properties($row);

			$this->view->assign_block_vars('items', $item->get_template_vars());
		}
		$result->dispose();
	}

	private function get_subcategories_pagination($subcategories_number, $categories_per_page, $subcategories_page)
	{
		$pagination = new ModulePagination($subcategories_page, $subcategories_number, (int)$categories_per_page);
		$pagination->set_url(FaqUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), '%d'));

		if ($pagination->current_page_is_empty() && $subcategories_page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getint('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = CategoriesService::get_categories_manager('faq')->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = CategoriesService::get_categories_manager('faq')->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function check_authorizations()
	{
		$id_category = $this->get_category()->get_id();
		if (!CategoriesAuthorizationsService::check_authorizations($id_category)->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$page = $request->get_getint('subcategories_page', 1);
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();

		$graphical_environment->set_page_title($this->lang['faq.module.title'], ($this->get_category()->get_id() != Category::ROOT_CATEGORY ? $this->get_category()->get_name() : ''), $page);

		$description = $this->get_category()->get_description();
		if (empty($description))
			$description = StringVars::replace_vars($this->lang['faq.seo.description.root'], array('site' => GeneralConfig::load()->get_site_name())) . ($this->get_category()->get_id() != Category::ROOT_CATEGORY ? ' ' . LangLoader::get_message('category.category', 'category-lang') . ' ' . $this->get_category()->get_name() : '');
		$graphical_environment->get_seo_meta_data()->set_description($description, $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(FaqUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), $page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['faq.module.title'], FaqUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager('faq')->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), FaqUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), ($category->get_id() == $this->get_category()->get_id() ? $page : 1)));
		}

		return $response;
	}

	public static function get_view()
	{
		$object = new self('faq');
		$object->check_authorizations();
		$object->build_view(AppContext::get_request());
		return $object->view;
	}
}
?>
