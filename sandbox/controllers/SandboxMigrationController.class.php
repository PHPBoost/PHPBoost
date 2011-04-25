<?php
/*##################################################
 *                       SandboxMigrationController.class.php
 *                            -------------------
 *   begin                : May 2, 2010
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

class SandboxMigrationController extends ModuleController
{
	private $form;
	
	private $submit_button;
	
	public function execute(HTTPRequest $request)
	{
		$view = new FileTemplate('sandbox/SandboxMigrationController.tpl');
		$form = $this->build_form();
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$general_config = GeneralConfig::load();
			$general_config->set_site_url('http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST')));
			
			$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
			if (!$server_path)
				$server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
			$server_path = trim(str_replace('/sandbox', '', dirname($server_path)));
			$server_path = ($server_path == '/') ? '' : $server_path;
			
			$general_config->set_site_path($server_path);
			
			GeneralConfig::save();
		}
		
		$view->put('form', $form->display());
		return new SiteDisplayResponse($view);
	}

	private function build_form()
	{	
		$form = new HTMLForm('register');
		
		// S'inscrire
		$fieldset = new FormFieldsetHTML('migration', 'Migration du serveur');
		$fieldset->set_description('Si vous venez de migrer de serveur, l\'url de celui ci aura changer, il va donc falloir régénérer une partie du cache.');
		$form->add_fieldset($fieldset);
		
		$this->submit_button = new FormButtonSubmit('Régénérer le chemin et l\'url du serveur', 'alert("coucou");');
		$form->add_button($this->submit_button);
		
		$this->form = $form;
		return $this->form;
	}
}

?>