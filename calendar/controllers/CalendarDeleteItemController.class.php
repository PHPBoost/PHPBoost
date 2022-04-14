<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 14
 * @since       PHPBoost 3.0 - 2012 11 20
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarDeleteItemController extends DefaultModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();

		$this->get_item($request);

		$this->check_authorizations();

		if ($this->item->belongs_to_a_serie())
			$this->build_form($request);

		if (($this->item->belongs_to_a_serie() && $this->submit_button->has_been_submited() && $this->form->validate()) || !$this->item->belongs_to_a_serie())
		{
			$this->delete_item($this->item->belongs_to_a_serie() ? $this->form->get_value('delete_serie')->get_raw_value() : false);
			$this->redirect($request);
		}

		$this->view->put('CONTENT', $this->form->display());

		return $this->generate_response($this->view);
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);
		$form->set_layout_title($this->lang['calendar.item.delete']);

		$fieldset = new FormFieldsetHTML('delete_serie', $this->lang['form.parameters']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldRadioChoice('delete_serie', $this->lang['common.delete'], 0,
			array(
				new FormFieldRadioChoiceOption($this->lang['calendar.delete.occurrence'], 0),
				new FormFieldRadioChoiceOption($this->lang['calendar.delete.serie'], 1)
			)
		));

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function get_item(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);

		try {
			$this->item = CalendarService::get_item($id);
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}

	private function delete_item($delete_all_serie_items = false)
	{
		$items_list = CalendarService::get_serie_items($this->item->get_content()->get_id());

		if ($delete_all_serie_items)
		{
			foreach ($items_list as $item)
			{
				// Delete item comments
				CommentsService::delete_comments_topic_module('calendar', $item->get_id());

				// Delete participants
				CalendarService::delete_all_participants($item->get_id());
			}

			CalendarService::delete_all_serie_items($this->item->get_content()->get_id());
			PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module = :module AND id_in_module = :id', array('module' => 'calendar', 'id' => !$this->item->get_parent_id() ? $this->item->get_id() : $this->item->get_parent_id()));
		}
		else
		{
			if (!$this->item->belongs_to_a_serie() || count($items_list) == 1)
			{
				CalendarService::delete_item_content($this->item->get_id());
			}

			// Delete item
			CalendarService::delete_item($this->item->get_id(), $this->item->get_parent_id());
		}

		CalendarService::clear_cache();
		HooksService::execute_hook_action('delete', self::$module_id, array_merge($this->item->get_content()->get_properties(), $this->item->get_properties()));
	}

	private function check_authorizations()
	{
		if (!$this->item->is_authorized_to_delete())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		if (AppContext::get_current_user()->is_readonly())
		{
			$error_controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($error_controller);
		}
	}

	private function redirect(HTTPRequestCustom $request)
	{
		if ($this->item->belongs_to_a_serie())
			AppContext::get_response()->redirect(($this->form->get_value('referrer') && !TextHelper::strstr($request->get_url_referrer(), CalendarUrlBuilder::display($this->item->get_content()->get_category()->get_id(), $this->item->get_content()->get_category()->get_rewrited_name(), $this->item->get_id(), $this->item->get_content()->get_rewrited_title())->rel()) ? $this->form->get_value('referrer') : CalendarUrlBuilder::home($this->item->get_start_date()->get_year(), $this->item->get_start_date()->get_month())), StringVars::replace_vars($this->lang['calendar.message.success.delete'], array('title' => $this->item->get_content()->get_title())));
		else
			AppContext::get_response()->redirect(($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), CalendarUrlBuilder::display($this->item->get_content()->get_category()->get_id(), $this->item->get_content()->get_category()->get_rewrited_name(), $this->item->get_id(), $this->item->get_content()->get_rewrited_title())->rel()) ? $request->get_url_referrer() : CalendarUrlBuilder::home($this->item->get_start_date()->get_year(), $this->item->get_start_date()->get_month())), StringVars::replace_vars($this->lang['calendar.message.success.delete'], array('title' => $this->item->get_content()->get_title())));
	}

	private function generate_response(View $view)
	{
		$response = new SiteDisplayResponse($view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['calendar.item.delete'], $this->lang['calendar.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['calendar.module.title'], CalendarUrlBuilder::home());

		$item_content = $this->item->get_content();

		$category = $item_content->get_category();
		$breadcrumb->add($item_content->get_title(), CalendarUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item_content->get_id(), $item_content->get_rewrited_title()));

		$breadcrumb->add($this->lang['calendar.item.delete'], CalendarUrlBuilder::delete_item($this->item->get_id()));
		$graphical_environment->get_seo_meta_data()->set_canonical_url(CalendarUrlBuilder::delete_item($this->item->get_id()));

		return $response;
	}
}
?>
