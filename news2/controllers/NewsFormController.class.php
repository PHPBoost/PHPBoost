<?php
/*##################################################
 *		                         NewsFormController.class.php
 *                            -------------------
 *   begin                : February 13, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class NewsFormController extends ModuleController
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
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form();
		
		$tpl = new StringTemplate('# INCLUDE FORM #');
		$tpl->add_lang($this->lang);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return $this->generate_response($tpl);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'news');
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('news', $this->lang['news']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('title', $this->lang['news.form.title'], $this->get_news()->get_title(), array('required' => true)));

		$search_category_children_options = new SearchCategoryChildrensOptions();
		$fieldset->add_field(NewsService::get_categories_manager()->get_select_categories_form_field('id_cat', $this->lang['news.form.category'], $this->get_news()->get_id_cat(), $search_category_children_options));
		
		$fieldset->add_field(new FormFieldRichTextEditor('contents', $this->lang['news.form.contents'], $this->get_news()->get_contents(), array('required' => true)));
		
		$fieldset->add_field(new FormFieldRichTextEditor('extend_contents', $this->lang['news.form.extend_contents'], $this->get_news()->get_extend_contents()));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('approbation', $this->lang['news.form.approbation'], $this->get_news()->get_approbation(),
			array(
				new FormFieldSelectChoiceOption($this->lang['news.form.approbation.not'], '0'),
				new FormFieldSelectChoiceOption($this->lang['news.form.approbation.now'], '1'),
				new FormFieldSelectChoiceOption($this->lang['news.form.approbation.date'], '2'),
			)
		));
		
		$fieldset->add_field(new FormFieldDateTime('start_date', $this->lang['news.form.date.start'], $this->get_news()->get_start_date()));
		
		$fieldset->add_field(new FormFieldDateTime('end_date', $this->lang['news.form.date.end'], $this->get_news()->get_end_date()));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function get_news()
	{
		$id = AppContext::get_request()->get_getint('id', 0);
	
		if (!empty($id))
		{
			try {
				return NewsService::get_news('WHERE id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				//TODO
			}
		}
		
		return new News();
	}
	
	private function generate_response(View $tpl)
	{
		$response = new NewsDisplayResponse();
		$response->set_page_title($this->lang['news.add']);
		return $response->display($tpl);
	}
}
?>