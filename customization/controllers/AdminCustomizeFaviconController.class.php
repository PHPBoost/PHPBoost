<?php
/*##################################################
 *                       AdminCustomizeFaviconControllerr.class.php
 *                            -------------------
 *   begin                : August 30, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

class AdminCustomizeFaviconController extends AdminModuleController
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
	
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->load_lang();
		$this->load_config();
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$favicon = $this->form->get_value('favicon', null);
			
			if ($favicon !== null)
			{
				$file_type = new FileType(new File($favicon->get_name()));
				if ($file_type->is_picture())
				{
					$this->save($favicon);
					$favicon_file = new File(PATH_TO_ROOT . $this->config->get_favicon_path());
					$picture = '<img src="' . Url::to_rel($favicon_file->get_path()) . '" alt="' . $this->lang['customization.favicon.current'] . '" title="' . $this->lang['customization.favicon.current'] . '"/>';
					$this->form->get_field_by_id('current_favicon')->set_value($picture);
					$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
				}
				else
				{
					$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('form.invalid_picture', 'status-messages-common'), MessageHelper::ERROR, 4));
				}
			}
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminCustomizationDisplayResponse($tpl, $this->lang['customization.interface']);
	}

	private function load_lang()
	{
		$this->lang = LangLoader::get('common', 'customization');
	}
	
	private function load_config()
	{
		$this->config = CustomizationConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('customize-favicon', $this->lang['customization.favicon']);
		$form->add_fieldset($fieldset);
		
		if ($this->config->get_favicon_path() == null || $this->config->get_favicon_path() == '')
		{
			$fieldset->add_field(new FormFieldFree('current_favicon', $this->lang['customization.favicon.current'], $this->lang['customization.favicon.current.null']));
		}
		else
		{
			if ($this->config->favicon_exists())
			{
				$favicon_file = new File(PATH_TO_ROOT . $this->config->get_favicon_path());
				$picture = '<img src="' . Url::to_rel($favicon_file->get_path()) . '" alt="' . $this->lang['customization.favicon.current'] . '" title="' . $this->lang['customization.favicon.current'] . '"/>';
				$fieldset->add_field(new FormFieldFree('current_favicon', $this->lang['customization.favicon.current'], $picture));
			}
			else
			{
				$fieldset->add_field(new FormFieldFree('current_favicon', $this->lang['customization.favicon.current'], '<span class="text-strong color-alert">' . $this->lang['customization.favicon.current.erased'] . '</span>'));
			}
		}
		
		$fieldset->add_field(new FormFieldFilePicker('favicon', $this->lang['customization.favicon.current.change']));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function save($favicon)
	{
		$save_destination = new File(PATH_TO_ROOT . '/' . $favicon->get_name());
		$favicon->save($save_destination);
		
		$this->delete_older();

		$this->config->set_favicon_path($save_destination->get_path_from_root());
		CustomizationConfig::save();
	}
	
	private function delete_older()
	{
		$file = new File(PATH_TO_ROOT . '/' . $this->config->get_favicon_path());
		if ($file->exists())
		{
			$file->delete();
		}
	}
}
?>