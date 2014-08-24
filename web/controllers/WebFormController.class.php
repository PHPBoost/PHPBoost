<?php
/*##################################################
 *                               WebFormController.class.php
 *                            -------------------
 *   begin                : August 21, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */

class WebFormController extends ModuleController
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
	
	private $weblink;
	
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
		$this->lang = LangLoader::get('common', 'web');
		$this->common_lang = LangLoader::get('common');
	}
	
	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('web', $this->lang['module_title']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('name', $this->common_lang['form.name'], $this->get_weblink()->get_name(), array('required' => true)));
		
		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
		$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
		$fieldset->add_field(WebService::get_categories_manager()->get_select_categories_form_field('id_category', $this->common_lang['form.category'], ($this->get_weblink()->get_id() === null ? $request->get_getint('id_category', 0) : $this->get_weblink()->get_id_category()), $search_category_children_options));
		
		$fieldset->add_field(new FormFieldTextEditor('url', $this->common_lang['form.url'], $this->get_weblink()->get_url()->absolute(), array('required' => true), array(new FormFieldConstraintUrl())));
		
		$fieldset->add_field(new FormFieldRichTextEditor('contents', $this->common_lang['form.description'], $this->get_weblink()->get_contents(), array('rows' => 15, 'required' => true)));
		
		$fieldset->add_field(new FormFieldCheckbox('partner', $this->lang['web.form.partner'], $this->get_weblink()->is_partner(), array(
			'events' => array('click' => '
				if (HTMLForms.getField("partner").getValue()) {
					HTMLForms.getField("partner_picture").enable();
				} else {
					HTMLForms.getField("partner_picture").disable();
				}'
			)
		)));
		
		$fieldset->add_field(new FormFieldUploadFile('partner_picture', $this->lang['web.form.partner_picture'], $this->get_weblink()->get_partner_picture()->relative(), array(
			'hidden' => !$this->get_weblink()->is_partner()
		)));
		
		$fieldset->add_field(WebService::get_keywords_manager()->get_form_field($this->get_weblink()->get_id(), 'keywords', $this->common_lang['form.keywords'], array('description' => $this->common_lang['form.keywords.description'])));
		
		$this->build_approval_field($fieldset);
		$this->build_contribution_fieldset($form);
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function build_approval_field($fieldset)
	{
		if (!$this->is_contributor_member())
		{
			$fieldset->add_field(new FormFieldCheckbox('approved', $this->common_lang['form.approved'], $this->get_weblink()->is_approved()));
		}
	}
	
	private function build_contribution_fieldset($form)
	{
		if ($this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', LangLoader::get_message('contribution', 'user-common'));
			$fieldset->set_description(MessageHelper::display($this->lang['web.form.contribution.explain'] . ' ' . LangLoader::get_message('contribution.explain', 'user-common'), MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);
			
			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', LangLoader::get_message('contribution.description', 'user-common'), '', array('description' => LangLoader::get_message('contribution.description.explain', 'user-common'))));
		}
	}
	
	private function is_contributor_member()
	{
		return ($this->get_weblink()->get_id() === null && !WebAuthorizationsService::check_authorizations()->write() && WebAuthorizationsService::check_authorizations()->contribution());
	}
	
	private function get_weblink()
	{
		if ($this->weblink === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->weblink = WebService::get_weblink('WHERE web.id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->weblink = new WebLink();
				$this->weblink->init_default_properties();
			}
		}
		return $this->weblink;
	}
	
	private function check_authorizations()
	{
		$weblink = $this->get_weblink();
		
		if ($weblink->get_id() === null)
		{
			if (!$weblink->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$weblink->is_authorized_to_edit())
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
		$weblink = $this->get_weblink();
		
		$weblink->set_name($this->form->get_value('name'));
		$weblink->set_rewrited_name(Url::encode_rewrite($weblink->get_name()));
		$weblink->set_id_category($this->form->get_value('id_category')->get_raw_value());
		$weblink->set_url(new Url($this->form->get_value('url')));
		$weblink->set_contents($this->form->get_value('contents'));
		$weblink->set_partner($this->form->get_value('partner'));
		$weblink->set_partner_picture(new Url($this->form->get_value('partner_picture')));
		
		if (($this->is_contributor_member() || WebAuthorizationsService::check_authorizations()->moderation()) && $this->form->get_value('approved'))
			$weblink->approve();
		else
			$weblink->unapprove();
		
		if ($weblink->get_id() === null)
		{
			$id = WebService::add($weblink);
		}
		else
		{
			$id = $weblink->get_id();
			WebService::update($weblink);
		}
		
		$this->contribution_actions($weblink, $id);
		
		WebService::get_keywords_manager()->put_relations($id, $this->form->get_value('keywords'));
		
		WebCache::invalidate();
	}
	
	private function contribution_actions(WebLink $weblink, $id)
	{
		if ($weblink->get_id() === null)
		{
			if ($this->is_contributor_member())
			{
				$contribution = new Contribution();
				$contribution->set_id_in_module($id);
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
				$contribution->set_entitled(StringVars::replace_vars(LangLoader::get_message('contribution.entitled', 'user-common'), array('module_name' => $this->lang['module_title'], 'name' => $weblink->get_name())));
				$contribution->set_fixing_url(WebUrlBuilder::edit($id)->relative());
				$contribution->set_poster_id(AppContext::get_current_user()->get_id());
				$contribution->set_module('web');
				$contribution->set_auth(
					Authorizations::capture_and_shift_bit_auth(
						WebService::get_categories_manager()->get_heritated_authorizations($weblink->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
						Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
					)
				);
				ContributionService::save_contribution($contribution);
			}
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('web', $id);
			if (count($corresponding_contributions) > 0)
			{
				$weblink_contribution = $corresponding_contributions[0];
				$weblink_contribution->set_status(Event::EVENT_STATUS_PROCESSED);
				
				ContributionService::save_contribution($weblink_contribution);
			}
		}
		$weblink->set_id($id);
	}
	
	private function redirect()
	{
		$weblink = $this->get_weblink();
		$category = WebService::get_categories_manager()->get_categories_cache()->get_category($weblink->get_id_category());
		
		if ($this->is_contributor_member() && !$weblink->is_approved())
		{
			AppContext::get_response()->redirect(UserUrlBuilder::contribution_success());
		}
		elseif ($weblink->is_approved())
		{
			AppContext::get_response()->redirect(WebUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $weblink->get_id(), $weblink->get_rewrited_name()));
		}
		else
		{
			AppContext::get_response()->redirect(WebUrlBuilder::display_pending());
		}
	}
	
	private function generate_response(View $tpl)
	{
		$weblink = $this->get_weblink();
		
		$response = new SiteDisplayResponse($tpl);
		$graphical_environment = $response->get_graphical_environment();
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], WebUrlBuilder::home());
		
		if ($weblink->get_id() === null)
		{
			$graphical_environment->set_page_title($this->lang['web.add']);
			$breadcrumb->add($this->lang['web.add'], WebUrlBuilder::add());
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['web.add']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(WebUrlBuilder::add());
		}
		else
		{
			$graphical_environment->set_page_title($this->lang['web.edit']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['web.edit']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(WebUrlBuilder::edit($weblink->get_id()));
			
			$categories = array_reverse(WebService::get_categories_manager()->get_parents($weblink->get_id_category(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), WebUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$category = WebService::get_categories_manager()->get_categories_cache()->get_category($weblink->get_id_category());
			$breadcrumb->add($weblink->get_name(), WebUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $weblink->get_id(), $weblink->get_rewrited_name()));
			$breadcrumb->add($this->lang['web.edit'], WebUrlBuilder::edit($weblink->get_id()));
		}
		
		return $response;
	}
}
?>
