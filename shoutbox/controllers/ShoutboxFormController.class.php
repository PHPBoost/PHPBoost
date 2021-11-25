<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 25
 * @since       PHPBoost 4.1 - 2014 10 14
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ShoutboxFormController extends ModuleController
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

	private $view;

	private $message;
	private $is_new_message;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_authorizations();

		$this->build_form($request);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$id = $this->save();
			AppContext::get_response()->redirect(ShoutboxUrlBuilder::home($this->is_new_message ? 1 : $this->form->get_value('page'), $id));
		}

		$this->view->put('FORM', $this->form->display());

		return $this->generate_response($this->view);
	}

	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_form(AppContext::get_request());
		if ($object->submit_button->has_been_submited() && $object->form->validate())
		{
			$id = $object->save();
			AppContext::get_response()->redirect(ShoutboxUrlBuilder::home($object->is_new_message ? 1 : $object->form->get_value('page'), $id));
		}
		$object->view->put('FORM', ShoutboxAuthorizationsService::check_authorizations()->write() && !AppContext::get_current_user()->is_readonly() ? $object->form->display() : '');
		return $object->view;
	}

	private function init()
	{
		$this->lang = array_merge(
			LangLoader::get('form-lang'),
			LangLoader::get('common', 'shoutbox')
		);
		$this->view = new StringTemplate('# INCLUDE FORM #');
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$config = ShoutboxConfig::load();
		$current_user = AppContext::get_current_user();

		$formatter = AppContext::get_content_formatting_service()->get_default_factory();
		$formatter->set_forbidden_tags($config->get_forbidden_formatting_tags());

		$form = new HTMLForm(__CLASS__);
		$form->set_layout_title($this->is_new_message ? $this->lang['shoutbox.add.item'] : $this->lang['shoutbox.edit.item']);

		$fieldset = new FormFieldsetHTML('message', $this->lang['form.parameters']);
		$form->add_fieldset($fieldset);

		if (!$current_user->check_level(User::MEMBER_LEVEL))
		{
			$fieldset->add_field(new FormFieldTextEditor('pseudo', $this->lang['form.name'], $this->get_message()->get_login(), array(
				'required' => true, 'maxlength' => 25)
			));
		}

		$fieldset->add_field(new FormFieldRichTextEditor('content', LangLoader::get_message('common.message', 'common-lang'), $this->get_message()->get_content(),
			array('formatter' => $formatter, 'rows' => 10, 'cols' => 47, 'required' => true),
			array(
				(!$current_user->is_moderator() && !$current_user->is_admin() ? new FormFieldConstraintMaxLinks($config->get_max_links_number_per_message(), true) : ''),
				new FormFieldConstraintAntiFlood(ShoutboxService::get_last_message_timestamp_from_user($this->get_message()->get_author_user()->get_id())
			))
		));

		$fieldset->add_field(new FormFieldHidden('page', $request->get_getint('page', 1)));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function get_message()
	{
		if ($this->message === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->message = ShoutboxService::get_message('WHERE id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_message = true;
				$this->message = new ShoutboxMessage();
				$this->message->init_default_properties();
			}
		}
		return $this->message;
	}

	private function check_authorizations()
	{
		$message = $this->get_message();

		if ($message->get_id() === null)
		{
			if (!ShoutboxAuthorizationsService::check_authorizations()->write())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$message->is_authorized_to_edit())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}
	}

	private function save()
	{
		$message = $this->get_message();

		if ($this->form->has_field('pseudo'))
			$message->set_login($this->form->get_value('pseudo'));
		$message->set_content($this->form->get_value('content'));

		if ($message->get_id() === null)
		{
			$message->set_creation_date(new Date());
			$id_message = ShoutboxService::add($message);
			$message->set_id($id_message);
			HooksService::execute_hook_action('add', self::$module_id, array_merge($message->get_properties(), array('item_url' => ShoutboxUrlBuilder::home(1, $id_message)->rel())));
		}
		else
		{
			$id_message = $message->get_id();
			ShoutboxService::update($message);
			HooksService::execute_hook_action('edit', self::$module_id, array_merge($message->get_properties(), array('item_url' => ShoutboxUrlBuilder::home(AppContext::get_request()->get_getint('page', 1), $id_message)->rel())));
		}

		return $id_message;
	}

	private function generate_response(View $tpl)
	{
		$message = $this->get_message();
		$page = AppContext::get_request()->get_getint('page', 1);

		$location_id = $message->get_id() ? 'shoutbox-edit-'. $message->get_id() : '';

		$response = new SiteDisplayResponse($tpl, $location_id);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['shoutbox.module.title'], ShoutboxUrlBuilder::home($page));

		if ($message->get_id() === null)
		{
			$graphical_environment->set_page_title($this->lang['shoutbox.add.item'], $this->lang['shoutbox.module.title']);
			$breadcrumb->add($this->lang['shoutbox.add.item'], ShoutboxUrlBuilder::add());
			$graphical_environment->get_seo_meta_data()->set_canonical_url(ShoutboxUrlBuilder::add());
		}
		else
		{
			if (!AppContext::get_session()->location_id_already_exists($location_id))
				$graphical_environment->set_location_id($location_id);

			$graphical_environment->set_page_title($this->lang['shoutbox.edit.item'], $this->lang['shoutbox.module.title']);
			$breadcrumb->add($this->lang['shoutbox.edit.item'], ShoutboxUrlBuilder::edit($message->get_id(), $page));
			$graphical_environment->get_seo_meta_data()->set_canonical_url(ShoutboxUrlBuilder::edit($message->get_id(), $page));
		}

		return $response;
	}
}
?>
