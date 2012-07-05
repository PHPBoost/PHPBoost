<?php
/*##################################################
 *		                   AdminOnlineConfigController.class.php
 *                            -------------------
 *   begin                : January 29, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class AdminOnlineConfigController extends AdminModuleController
{
	private $lang;
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display($this->lang['admin.success-saving-config'], E_USER_SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminOnlineDisplayResponse($tpl, $this->lang['admin.online-config']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('online_common', 'online');
	}

	private function build_form()
	{
		$form = new HTMLForm('online_admin');
		$online_config = OnlineConfig::load();

		$fieldset_config = new FormFieldsetHTML('configuration', $this->lang['admin.online-config']);
		$form->add_fieldset($fieldset_config);
		
		$fieldset_config->add_field(new FormFieldTextEditor('number_member_displayed', $this->lang['admin.nbr-displayed'], $online_config->get_number_member_displayed(), array(
			'class' => 'text', 'maxlength' => 3, 'required' => true)
		));
		
		$fieldset_config->add_field(new FormFieldTextEditor('nbr_members_per_page', $this->lang['admin.nbr-members-per-page'], $online_config->get_nbr_members_per_page(), array(
			'class' => 'text', 'maxlength' => 3, 'required' => true)
		));
		
		$fieldset_config->add_field(new FormFieldSimpleSelectChoice('display_order', $this->lang['admin.display-order'], $online_config->get_display_order(), array(
				new FormFieldSelectChoiceOption(LangLoader::get_message('ranks', 'main'), OnlineConfig::LEVEL_DISPLAY_ORDER),
				new FormFieldSelectChoiceOption($this->lang['online.last_update'], OnlineConfig::SESSION_TIME_DISPLAY_ORDER),
				new FormFieldSelectChoiceOption(LangLoader::get_message('ranks', 'main') . ' ' . LangLoader::get_message('and', 'main') . ' ' . $this->lang['online.last_update'], OnlineConfig::LEVEL_AND_SESSION_TIME_DISPLAY_ORDER)
			), array('required' => true)
		));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}
	
	private function save()
	{
		$online_config = OnlineConfig::load();
		$online_config->set_number_member_displayed($this->form->get_value('number_member_displayed'));
		$online_config->set_nbr_members_per_page($this->form->get_value('nbr_members_per_page'));
		$online_config->set_display_order($this->form->get_value('display_order')->get_raw_value());
		OnlineConfig::save();
	}
}

?>