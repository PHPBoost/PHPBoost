<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 04 14
 * @since       PHPBoost 4.0 - 2013 07 29
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarItemController extends DefaultModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view();

		return $this->generate_response();
	}

	private function get_item()
	{
		if ($this->item === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->item = CalendarService::get_item($id);
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->item = new CalendarItem();
		}
		return $this->item;
	}

	private function build_view()
	{
		$item = $this->get_item();
		$category = $item->get_content()->get_category();

		$this->view->put_all(array_merge($item->get_template_vars(), array(
			'NOT_VISIBLE_MESSAGE' => MessageHelper::display($this->lang['warning.element.not.visible'], MessageHelper::WARNING)
		)));

		$participants_number = count($item->get_participants());
		$i = 0;
		foreach ($item->get_participants() as $participant)
		{
			$i++;
			$this->view->assign_block_vars('participant', array_merge($participant->get_template_vars(), array(
				'C_LAST_PARTICIPANT' => $i == $participants_number
			)));
		}

		$comments_config = CommentsConfig::load();
		if ($comments_config->module_comments_is_enabled('calendar'))
		{
			$comments_topic = new CalendarCommentsTopic($item);
			$comments_topic->set_id_in_module($item->get_id());
			$comments_topic->set_url(CalendarUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_content()->get_rewrited_title()));

			$this->view->put_all(array(
				'C_COMMENTS_ENABLED' => true,
				'COMMENTS' => $comments_topic->display()
			));
		}
	}

	private function check_authorizations()
	{
		$item = $this->get_item();

		if (!$item->get_content()->is_approved())
		{
			$current_user = AppContext::get_current_user();
			if ((!CategoriesAuthorizationsService::check_authorizations($item->get_content()->get_id_category())->moderation() && !CategoriesAuthorizationsService::check_authorizations($item->get_content()->get_id_category())->write() && (!CategoriesAuthorizationsService::check_authorizations($item->get_content()->get_id_category())->contribution() || $item->get_content()->get_author_user()->get_id() != $current_user->get_id())) || ($current_user->get_id() == User::VISITOR_LEVEL))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!CategoriesAuthorizationsService::check_authorizations($item->get_content()->get_id_category())->read())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	protected function get_template_to_use()
	{
		return new FileTemplate('calendar/CalendarItemController.tpl');
	}

	private function generate_response()
	{
		$item = $this->get_item();
		$category = $item->get_content()->get_category();
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($item->get_content()->get_title(), ($category->get_id() != Category::ROOT_CATEGORY ? $category->get_name() . ' - ' : '') . $this->lang['calendar.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($item->get_content()->get_content());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(CalendarUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_content()->get_rewrited_title()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['calendar.module.title'], CalendarUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager('calendar')->get_parents($item->get_content()->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), CalendarUrlBuilder::home());
		}
		$breadcrumb->add($item->get_content()->get_title(), CalendarUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_content()->get_rewrited_title()));

		if ($item->get_content()->has_thumbnail())
			$graphical_environment->get_seo_meta_data()->set_picture_url($item->get_content()->get_thumbnail());

		return $response;
	}
}
?>
