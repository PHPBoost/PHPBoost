<?php
/*##################################################
 *                       ArticlesAddArticleController.class.php
 *                            -------------------
 *   begin                : October 22, 2011
 *   copyright            : (C) 2011 Kévin MASSY
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

class ArticlesAddArticleController extends ModuleController
{
	private $tpl;
	private $lang;
	private $form;
	private $submit_button;

	public function execute(HTTPRequest $request)
	{
		if (!ArticlesAuthorizationsService::default_autorizations()->write() || !ArticlesAuthorizationsService::default_autorizations()->contribution())
		{
			//TODO Not authorized
		}
		
		$this->init();
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
		}
		
		$this->tpl->put('FORM', $this->form->display());
		return $this->build_response($this->tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('articles-common', 'articles');
		$this->tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$this->tpl->add_lang($this->lang);
	}	
	
	private function build_form()
	{
		$this->form = new HTMLForm('add_article');
		
		$fieldset = new FormFieldsetHTML('add_article', $this->lang['add_article']);
		$this->form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $this->lang['title'], '', array('required' => true)));
		
		$fieldset->add_field(new FormFieldMultiLineTextEditor('description', $this->lang['description'], ''));
		
		$fieldset->add_field(new FormFieldRichTextEditor('content', $this->lang['content'], ''));
		
		$this->form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$this->form->add_button($this->submit_button);
	}
	
	private function save()
	{
		
	}

	private function build_response(View $view)
	{
		$title = $this->lang['add_article'];
		$response = new ArticlesDisplayResponse();
		$response->set_page_title($title);
		$response->add_breadcrumb_link($this->lang['articles'], ArticlesUrlBuilder::home()->absolute());
		$response->add_breadcrumb_link($title, ArticlesUrlBuilder::add_article()->absolute());
		return $response->display($view);
	}
}
?>