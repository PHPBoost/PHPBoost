<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 28
 * @since       PHPBoost 4.0 - 2013 08 21
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarHomeController extends DefaultModuleController
{
	private $category;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view($request);

		return $this->generate_response();
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$year = $request->get_getint('year', date('Y'));
		$month = $request->get_getint('month', date('n'));
		$day = $request->get_getint('day', 0);

		if (!checkdate($month, ($day ? $day : 1), $year))
		{
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['calendar.error.invalid.date'], MessageHelper::ERROR));

			$year = date('Y');
			$month = date('n');
			$day = date('j');
		}

		$this->view->put_all(array(
			'C_CATEGORY' 	  => true,
			'C_ROOT_CATEGORY' => $this->category->get_id() == Category::ROOT_CATEGORY,

			'CATEGORY_NAME'      => $this->category->get_name(),
			'CATEGORY_PARENT_ID' => $this->get_category()->get_id_parent(),
			'CATEGORY_SUB_ORDER' => $this->get_category()->get_order(),
			'CALENDAR'           => CalendarAjaxCalendarController::get_view(false, $year, $month, $this->get_category()->get_id()),
			'EVENTS'             => CalendarAjaxEventsController::get_view($year, $month, $day),

			'U_EDIT_CATEGORY' => $this->category->get_id() == Category::ROOT_CATEGORY ? CalendarUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit($this->category->get_id(), 'calendar')->rel()
		));

		return $this->view;
	}

	private function check_authorizations()
	{
		$id_cat = $this->get_category()->get_id();
		if (!CategoriesAuthorizationsService::check_authorizations($id_cat)->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getint('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = CategoriesService::get_categories_manager('calendar')->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = CategoriesService::get_categories_manager('calendar')->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function get_template_to_use()
	{
		return new FileTemplate('calendar/CalendarSeveralItemsController.tpl');
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();

		if ($this->get_category()->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->get_category()->get_name(), $this->lang['calendar.module.title']);
		else
			$graphical_environment->set_page_title($this->lang['calendar.module.title']);

		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['calendar.seo.description.root'], array('site' => GeneralConfig::load()->get_site_name())));
		$graphical_environment->get_seo_meta_data()->set_canonical_url(CalendarUrlBuilder::home());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['calendar.module.title'], CalendarUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager('calendar')->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), CalendarUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}

		return $response;
	}

	public static function get_view()
	{
		$object = new self('calendar');
		$object->check_authorizations();
		$object->build_view(AppContext::get_request());
		return $object->view;
	}
}
?>
