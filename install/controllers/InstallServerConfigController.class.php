<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 05 05
 * @since       PHPBoost 3.0 - 2010 10 02
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

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

	public function execute(HTTPRequestCustom $request)
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
		$this->form = new HTMLForm('continueForm', '', false);

		$action_fieldset = new FormFieldsetSubmit('actions');
		$action_fieldset->add_element(new FormButtonLinkCssImg($this->lang['step.previous'], InstallUrlBuilder::license(), 'fa fa-arrow-left'));
		$action_fieldset->add_element(new FormButtonLinkCssImg($this->lang['folders.chmod.refresh'], InstallUrlBuilder::server_configuration()->rel(), 'fa fa-sync'));
		$this->submit = new FormButtonSubmitCssImg($this->lang['step.next'], 'fa fa-arrow-right', 'server');
		$action_fieldset->add_element($this->submit);
		$this->form->add_fieldset($action_fieldset);
	}

	private function handle_form()
	{
		if ($this->server_conf->is_php_compatible() && PHPBoostFoldersPermissions::validate() && $this->server_conf->has_mbstring_library())
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
			'HAS_CURL_LIBRARY'=> $this->server_conf->has_curl_library(),
			'HAS_MBSTRING_LIBRARY'=> $this->server_conf->has_mbstring_library()
		));
		if (!$this->server_conf->has_mbstring_library())
		{
			$this->view->put('C_MBSTRING_ERROR', true);
		}
		if (!PHPBoostFoldersPermissions::validate())
		{
			$this->view->put('C_FOLDERS_ERROR', true);
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
