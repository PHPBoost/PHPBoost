<?php
/*##################################################
 *                           AdminErrorsControllerConfig.class.php
 *                            -------------------
 *   begin                : September 30, 2010
 *   copyright            : (C) 2010 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

class AdminErrorsControllerConfig extends AdminController
{
	private $form;
	private $view;
	private $response;
	private $lang;
	private $submit_button;
	
	public function execute(HTTPRequest $request)
	{
		$this->load_environnement();	
		
		if ($this->submit_button->has_been_submited())
		{
			if ($this->form->validate())
			{
				$errors_config = ErrorsConfig::load();
				$errors_config->set_not_required_level($this->form->get_value('not_required_level'));
				$errors_config->set_unauthorized_post($this->form->get_value('unauthorized_post'));
				$errors_config->set_registered_to_post($this->form->get_value('registered_to_post'));
				$errors_config->set_read_only($this->form->get_value('read_only'));
				$errors_config->set_unexisted_page($this->form->get_value('unexisted_page'));
				$errors_config->set_unexisted_member($this->form->get_value('unexisted_member'));
				$errors_config->set_member_banned($this->form->get_value('member_banned'));
				$errors_config->set_unexisted_category($this->form->get_value('unexisted_category'));
				$errors_config->set_unknow_error($this->form->get_value('unknow_error'));
				
				ErrorsConfig::save();
			}
		}
		
		return $this->response;
	}
	
	private function load_environnement()
	{
		$this->form();
		
		$this->lang = LangLoader::get('main');
		
		$this->view = new StringTemplate('# INCLUDE form #');
 
		$this->view->add_lang($this->lang);
		
        $this->view->add_subtemplate('form', $this->form->display());
		
		$this->response = new AdminErrorsDisplayResponse($this->view);
        
		$this->response->get_graphical_environment()->set_page_title('test');
	}
	
	private function form()
	{
		$this->form = new HTMLForm('edit_lang_errors');
		
		$fieldset = new FormFieldsetHTML('edit_lang_errors', 'Editer les erreurs courantes');
		$fieldset->set_description('Cette page va vous permettre, si vous le souhaitez, de modifier les erreurs les plus courantes, pour permettre à vos utilisateurs de mieux se repérer.');
		
		$fieldset->add_field(new FormFieldRichTextEditor('not_required_level', 'Vous n\'avez pas le niveau requis', ErrorsConfig::load()->get_not_required_level(), array('forbiddentags' => $this->forbidden_tags())));

		$fieldset->add_field(new FormFieldRichTextEditor('unauthorized_post', 'Vous n\'êtes pas autorisé à poster', ErrorsConfig::load()->get_unauthorized_post(), array('forbiddentags' => $this->forbidden_tags())));

		$fieldset->add_field(new FormFieldRichTextEditor('registered_to_post', 'Vous devez être inscrit pour poster', ErrorsConfig::load()->get_registered_to_post(), array('forbiddentags' => $this->forbidden_tags())));

		$fieldset->add_field(new FormFieldRichTextEditor('read_only', 'Vous êtes en lecture seule', ErrorsConfig::load()->get_read_only(), array('forbiddentags' => $this->forbidden_tags())));

		$fieldset->add_field(new FormFieldRichTextEditor('unexisted_page', 'Page inexistante', ErrorsConfig::load()->get_unexisted_page(), array('forbiddentags' => $this->forbidden_tags())));
		
		$fieldset->add_field(new FormFieldRichTextEditor('unexisted_member', 'Membre inexistant', ErrorsConfig::load()->get_unexisted_member(), array('forbiddentags' => $this->forbidden_tags())));

		$fieldset->add_field(new FormFieldRichTextEditor('member_banned', 'Membre banni', ErrorsConfig::load()->get_member_banned(), array('forbiddentags' => $this->forbidden_tags())));

		$fieldset->add_field(new FormFieldRichTextEditor('unexisted_category', 'Catégorie inéxistante', ErrorsConfig::load()->get_unexisted_category(), array('forbiddentags' => $this->forbidden_tags())));
		
		$fieldset->add_field(new FormFieldRichTextEditor('unknow_error', 'Erreur Inconnue', ErrorsConfig::load()->get_unknow_error(), array('forbiddentags' => $this->forbidden_tags())));

		$this->form->add_fieldset($fieldset);
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$this->form->add_button($this->submit_button);
		
		return $this->form;
	}
	
	private function forbidden_tags()
	{
		return array('style');
	}

}
?>