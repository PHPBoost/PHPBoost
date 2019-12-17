<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 17
 * @since       PHPBoost 4.0 - 2014 09 02
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
*/

class FaqFormController extends ModuleController
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
	private $common_lang;

	private $faq_question;
	private $is_new_faq_question;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_authorizations();

		$this->build_form($request);

		$tpl = new StringTemplate('# INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->redirect();
		}

		$tpl->put('FORM', $this->form->display());

		return $this->generate_response($tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'faq');
		$this->common_lang = LangLoader::get('common');
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('faq', $this->get_faq_question()->get_id() === null ? $this->lang['faq.question.add'] : $this->lang['faq.question.edit']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('question', $this->lang['faq.form.question'], $this->get_faq_question()->get_question(), array('required' => true)));

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(CategoriesService::get_categories_manager()->get_select_categories_form_field('id_category', $this->common_lang['form.category'], $this->get_faq_question()->get_id_category(), $search_category_children_options));
		}

		$fieldset->add_field(new FormFieldRichTextEditor('answer', $this->lang['faq.form.answer'], $this->get_faq_question()->get_answer(), array('rows' => 15, 'required' => true)));

		if (CategoriesAuthorizationsService::check_authorizations($this->get_faq_question()->get_id_category())->moderation())
		{
			$fieldset->add_field(new FormFieldCheckbox('approved', $this->common_lang['form.approve'], $this->get_faq_question()->is_approved()));
		}

		$this->build_contribution_fieldset($form);

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function build_contribution_fieldset($form)
	{
		if ($this->get_faq_question()->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', LangLoader::get_message('contribution', 'user-common'));
			$fieldset->set_description(MessageHelper::display($this->lang['faq.form.contribution.explain'] . ' ' . LangLoader::get_message('contribution.explain', 'user-common'), MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', LangLoader::get_message('contribution.description', 'user-common'), '', array('description' => LangLoader::get_message('contribution.description.explain', 'user-common'))));
		}
	}

	private function is_contributor_member()
	{
		return (!CategoriesAuthorizationsService::check_authorizations()->write() && CategoriesAuthorizationsService::check_authorizations()->contribution());
	}

	private function get_faq_question()
	{
		if ($this->faq_question === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->faq_question = FaqService::get_question('WHERE id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_faq_question = true;
				$this->faq_question = new FaqQuestion();
				$this->faq_question->init_default_properties(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY));
			}
		}
		return $this->faq_question;
	}

	private function check_authorizations()
	{
		$faq_question = $this->get_faq_question();

		if ($faq_question->get_id() === null)
		{
			if (!$faq_question->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$faq_question->is_authorized_to_edit())
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
		$faq_question = $this->get_faq_question();

		$faq_question->set_question($this->form->get_value('question'));
		$faq_question->set_rewrited_question(Url::encode_rewrite($faq_question->get_question()));

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
			$faq_question->set_id_category($this->form->get_value('id_category')->get_raw_value());

		$faq_question->set_answer($this->form->get_value('answer'));

		if ($faq_question->get_q_order() === null)
		{
			$number_questions_in_category = FaqService::count('WHERE id_category = :id_category', array('id_category' => $faq_question->get_id_category()));
			$faq_question->set_q_order($number_questions_in_category + 1);
		}

		if (CategoriesAuthorizationsService::check_authorizations($faq_question->get_id_category())->moderation())
		{
			if ($this->form->get_value('approved'))
				$faq_question->approve();
			else
				$faq_question->unapprove();
		}
		else if (CategoriesAuthorizationsService::check_authorizations($faq_question->get_id_category())->contribution() && !CategoriesAuthorizationsService::check_authorizations($faq_question->get_id_category())->write())
			$faq_question->unapprove();

		if ($faq_question->get_id() === null)
		{
			$id = FaqService::add($faq_question);
		}
		else
		{
			$id = $faq_question->get_id();
			FaqService::update($faq_question);
		}

		$this->contribution_actions($faq_question, $id);

		FaqService::clear_cache();
	}

	private function contribution_actions(FaqQuestion $faq_question, $id)
	{
		if ($faq_question->get_id() === null)
		{
			if ($this->is_contributor_member())
			{
				$contribution = new Contribution();
				$contribution->set_id_in_module($id);
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
				$contribution->set_entitled($faq_question->get_question());
				$contribution->set_fixing_url(FaqUrlBuilder::edit($id)->relative());
				$contribution->set_poster_id(AppContext::get_current_user()->get_id());
				$contribution->set_module('faq');
				$contribution->set_auth(
					Authorizations::capture_and_shift_bit_auth(
						CategoriesService::get_categories_manager()->get_heritated_authorizations($faq_question->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
						Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
					)
				);
				ContributionService::save_contribution($contribution);
			}
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('faq', $id);
			if (count($corresponding_contributions) > 0)
			{
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
			}
		}
		$faq_question->set_id($id);
	}

	private function redirect()
	{
		$faq_question = $this->get_faq_question();
		$category = $faq_question->get_category();

		if ($this->is_new_faq_question && $this->is_contributor_member() && !$faq_question->is_approved())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($faq_question->is_approved())
		{
			if ($this->is_new_faq_question)
				AppContext::get_response()->redirect(FaqUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $faq_question->get_id()), StringVars::replace_vars($this->lang['faq.message.success.add'], array('question' => $faq_question->get_question())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : FaqUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $faq_question->get_id())), StringVars::replace_vars($this->lang['faq.message.success.edit'], array('question' => $faq_question->get_question())));
		}
		else
		{
			if ($this->is_new_faq_question)
				AppContext::get_response()->redirect(FaqUrlBuilder::display_pending(), StringVars::replace_vars($this->lang['faq.message.success.add'], array('question' => $faq_question->get_question())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : FaqUrlBuilder::display_pending()), StringVars::replace_vars($this->lang['faq.message.success.edit'], array('question' => $faq_question->get_question())));
		}
	}

	private function generate_response(View $tpl)
	{
		$faq_question = $this->get_faq_question();

		$location_id = $faq_question->get_id() ? 'faq-edit-'. $faq_question->get_id() : '';

		$response = new SiteDisplayResponse($tpl, $location_id);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['faq.module.title'], FaqUrlBuilder::home());

		if ($faq_question->get_id() === null)
		{
			$graphical_environment->set_page_title($this->lang['faq.question.add'], $this->lang['faq.module.title']);
			$breadcrumb->add($this->lang['faq.question.add'], FaqUrlBuilder::add($faq_question->get_id_category()));
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['faq.question.add']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(FaqUrlBuilder::add($faq_question->get_id_category()));
		}
		else
		{
			if (!AppContext::get_session()->location_id_already_exists($location_id))
				$graphical_environment->set_location_id($location_id);

			$graphical_environment->set_page_title($this->lang['faq.question.edit'], $this->lang['faq.module.title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['faq.question.edit']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(FaqUrlBuilder::edit($faq_question->get_id()));

			$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($faq_question->get_id_category(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), FaqUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$category = $faq_question->get_category();
			$breadcrumb->add($faq_question->get_question(), FaqUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $faq_question->get_id()));
			$breadcrumb->add($this->lang['faq.question.edit'], FaqUrlBuilder::edit($faq_question->get_id()));
		}

		return $response;
	}
}
?>
