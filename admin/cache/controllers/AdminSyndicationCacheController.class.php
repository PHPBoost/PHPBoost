<?php
/*##################################################
 *                      AdminSyndicationCacheController.class.php
 *                            -------------------
 *   begin                : August 7 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : benoit.sautel@phpboost.com
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

class AdminSyndicationCacheController extends AdminController
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
		
		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->handle_submit();
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return new AdminCacheMenuDisplayResponse($tpl, $this->lang['syndication_cache']);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('admin-cache-common');
	}

	protected function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('syndication_cache', $this->lang['syndication_cache']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldHTML('explain', $this->lang['explain_syndication_cache']));
		
		$this->submit_button = new FormButtonSubmit($this->lang['clear_cache'], 'button');
		$form->add_button($this->submit_button);
		
		$this->form = $form;
	}

	protected function handle_submit()
	{
		AppContext::get_cache_service()->clear_syndication_cache();
	}
}
?>