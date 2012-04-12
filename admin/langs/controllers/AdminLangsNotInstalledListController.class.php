<?php
/*##################################################
 *                      AdminLangsNotInstalledListController.class.php
 *                            -------------------
 *   begin                : April 20, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

class AdminLangsNotInstalledListController extends AdminController
{
	private $lang;
	private $view;
	private $form;
	private $submit_button;
	
	public function execute(HTTPRequest $request)
	{
		$this->init();
		
		$this->upload_form();
		$this->view->put('UPLOAD_FORM', $this->form->display());
		
		$this->build_view();
		$this->save($request);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->upload();
		}

		return new AdminLangsDisplayResponse($this->view, $this->lang['langs.install']);
	}
	
	private function build_view()
	{
		$not_installed_langs = $this->get_not_installed_langs();
		foreach($not_installed_langs as $id)
		{
			$configuration = LangConfigurationManager::get($id);
			$this->view->assign_block_vars('langs_not_installed', array(
				'C_WEBSITE' => $configuration->get_author_link() !== '',
				'ID' => $id,
				'NAME' => $configuration->get_name(),
				'VERSION' => $configuration->get_version(),
				'AUTHOR_NAME' => $configuration->get_author_name(),
				'AUTHOR_WEBSITE' => $configuration->get_author_link(),
				'AUTHOR_EMAIL' => $configuration->get_author_mail(),
				'COMPATIBILITY' => $configuration->get_compatibility(),
				'AUTHORIZATIONS' => Authorizations::generate_select(Lang::ACCES_LANG, array('r-1' => 1, 'r0' => 1, 'r1' => 1), array(2 => true), $id)
			));
		}
		$this->view->put_all(array(
			'C_LANG_INSTALL' => count($not_installed_langs) > 0 ? true : false,
			'L_ADD' => $this->lang['langs.install']
		));
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('admin-langs-common');
		$this->view = new FileTemplate('admin/langs/AdminLangsNotInstalledListController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function get_not_installed_langs()
	{
		$langs_not_installed = array();
		$folder_containing_phpboost_langs = new Folder(PATH_TO_ROOT .'/lang/');
		foreach($folder_containing_phpboost_langs->get_folders() as $lang)
		{
			$name = $lang->get_name();
			if (!LangManager::get_lang_existed($name))
			{
				$langs_not_installed[] = $name;
			}
		}
		sort($langs_not_installed);
		return $langs_not_installed;
	}
	
	private function save(HTTPRequest $request)
	{
		foreach ($this->get_not_installed_langs() as $id)
		{
			try {
				if ($request->get_string('add-' . $id))
				{
					$activated = $request->get_bool('activated-' . $id, false);
					$authorizations = Authorizations::auth_array_simple(Lang::ACCES_LANG, $id);
					LangManager::install($id, $authorizations, $activated);
					$error = LangManager::get_error();
					if ($error !== null)
					{
						$this->view->put('MSG', MessageHelper::display($error, MessageHelper::NOTICE, 10));
					}
					else
					{
						$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('proccess.success', 'errors-common'), MessageHelper::SUCCESS, 4));
					}
				}
			} catch (Exception $e) {
			}
		}
	}
	
	private function upload_form()
	{
		$form = new HTMLForm('upload');
		
		$fieldset = new FormFieldsetHTML('upload', $this->lang['langs.upload']);
		$form->add_fieldset($fieldset);
	
		$fieldset->add_field(new FormFieldFilePicker('upload', $this->lang['langs.upload.description']));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}
	
	private function upload()
	{
		//TODO
		new Exception('todo');
	}
}
?>