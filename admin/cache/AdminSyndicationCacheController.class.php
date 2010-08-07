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

class AdminSyndicationCacheController extends AbstractAdminFormPageController
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
	
	public function __construct()
	{
		$this->lang = LangLoader::get('admin-cache-common');
		parent::__construct($this->lang['cache_cleared_successfully']);
	}
	
	protected function create_form()
	{
		$form = new HTMLForm('syndication_cache_config');
		$fieldset = new FormFieldsetHTML('explain', $this->lang['syndication_cache']);
		$form->add_fieldset($fieldset);
		$fieldset->add_field(new FormFieldHTML('exp', $this->lang['explain_syndication_cache']));
		$button = new FormButtonSubmit($this->lang['clear_cache'], 'button');
		$this->set_submit_button($button);
		$form->add_button($button);
		$this->set_form($form);
	}
	
	protected function handle_submit()
	{
		AppContext::get_cache_service()->clear_syndication_cache();
	}
	
	protected function generate_response(Template $template)
	{
		return new AdminCacheControllerResponse($template);
	}
}
?>