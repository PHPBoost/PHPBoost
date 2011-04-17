<?php
/*##################################################
 *                         InstallServerConfigController.class.php
 *                            -------------------
 *   begin                : October 02 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class InstallServerConfigController extends InstallController
{
	/**
	 * @var Template
	 */
	private $view;
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit;
	/**
	 * @var ServerConfiguration
	 */
	private $server_conf;

	public function __construct()
	{
		$this->server_conf = new ServerConfiguration();
	}

	public function execute(HTTPRequest $request)
	{
		parent::load_lang($request);
		$this->build_form();
		if ($this->submit->has_been_submited())
		{
			$this->handle_form();
		}
		$this->build_view();
		return $this->create_response();
	}

	private function build_form()
	{
		$this->form = new HTMLForm('continueForm', '#error');
		$action_fieldset = new FormFieldsetSubmit('actions');
		$back = new FormButtonLink($this->lang['step.previous'], InstallUrlBuilder::license(), 'templates/images/left.png');
		$action_fieldset->add_element($back);
		$refresh = new FormButtonLink($this->lang['folders.chmod.refresh'], InstallUrlBuilder::server_configuration()->absolute(), 'templates/images/refresh.png');
		$action_fieldset->add_element($refresh);
		$this->submit = new FormButtonSubmitImg($this->lang['step.next'], 'templates/images/right.png', 'server');
		$action_fieldset->add_element($this->submit);
		$this->form->add_fieldset($action_fieldset);
	}

	private function handle_form()
	{
		if ($this->server_conf->is_php_compatible() && PHPBoostFoldersPermissions::validate())
		{
			AppContext::get_response()->redirect(InstallUrlBuilder::database());
		}
	}

	private function build_view()
	{
		$this->view = new FileTemplate('install/server-config.tpl');
		$this->view->put_all(array(
            'MIN_PHP_VERSION' => ServerConfiguration::MIN_PHP_VERSION,
            'PHP_VERSION_OK' => $this->server_conf->is_php_compatible(),
            'HAS_GD_LIBRARY'=> $this->server_conf->has_gd_library(),
            'URL_REWRITING_AVAILABLE' => $this->supports_url_rewriting()
		));
		if (!PHPBoostFoldersPermissions::validate())
		{
			$this->view->put('ERROR', $this->lang['folders.chmod.error']);
		}
		try
		{
			$this->view->put('URL_REWRITING_KNOWN', true);
			$this->view->put('URL_REWRITING_AVAILABLE', $this->server_conf->has_url_rewriting());
		}
		catch (UnsupportedOperationException $ex)
		{
			$this->view->put('URL_REWRITING_KNOWN', false);
		}
		$this->check_folders_permissions();
		$this->view->put('CONTINUE_FORM', $this->form->display());
	}
	
	private function supports_url_rewriting()
	{
		try {
			return $this->server_conf->has_url_rewriting();
		}
		catch (UnsupportedOperationException $e)
		{
			return false;
		}
	}

	private function check_folders_permissions()
	{
		$folders = array();
		foreach (PHPBoostFoldersPermissions::get_permissions() as $folder_name => $folder)
		{
			$folders[] = array(
               'NAME' => $folder_name,
               'EXISTS' => $folder->exists(),
               'IS_WRITABLE' => $folder->is_writable(),
			);
		}
		$this->view->put('folder', $folders);
	}

	/**
	 * @return InstallDisplayResponse
	 */
	private function create_response()
	{
		$step_title = $this->lang['step.server.title'];
		$response = new InstallDisplayResponse(2, $step_title, $this->view);
		return $response;
	}
}
?>