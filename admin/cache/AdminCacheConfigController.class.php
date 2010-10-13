<?php
/*##################################################
 *                     AdminCacheConfigController.class.php
 *                            -------------------
 *   begin                : August 8 2010
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

class AdminCacheConfigController extends AbstractAdminFormPageController
{
	private $lang;

	public function __construct()
	{
		$this->lang = LangLoader::get('admin-cache-common');
		parent::__construct($this->lang['cache_cleared_successfully']);
	}

	protected function create_form()
	{
		$form = new HTMLForm('cache_config');
		$fieldset = new FormFieldsetHTML('explain', $this->lang['cache_configuration']);
		$form->add_fieldset($fieldset);
		$fieldset->add_field(new FormFieldHTML('exp', $this->lang['explain_php_cache']));
		$fieldset->add_field(new FormFieldBooleanInformation('apc_available', $this->lang['apc_available'], $this->is_apc_available(), array('description' => $this->lang['explain_apc_available'])));

		if ($this->is_apc_available())
		{
			$fieldset->add_field(new FormFieldCheckbox('enable_apc', $this->lang['enable_apc'], false));
		}

		$button = new FormButtonDefaultSubmit();
		$this->set_submit_button($button);
		$form->add_button($button);
		$this->set_form($form);
	}

	protected function is_apc_available()
	{
		// TODO implement this method
		return true;
	}

	protected function handle_submit()
	{
		// TODO Enable APC
	}

	protected function generate_response(View $view)
	{
		return new AdminCacheMenuDisplayResponse($view);
	}
}
?>