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
		$this->build_view();
		$this->add_navigation();
		return $this->create_response();
	}

	private function build_view()
	{
        $this->view = new FileTemplate('install/server-config.tpl');
        $this->view->put_all(array(
            'MIN_PHP_VERSION' => ServerConfiguration::MIN_PHP_VERSION,
            'PHP_VERSION_OK' => $this->server_conf->is_php_compatible(),
            'HAS_GD_LIBRARY'=> $this->server_conf->has_gd_libray(),
            'URL_REWRITING_AVAILABLE' => $this->server_conf->has_url_rewriting()
        ));
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

	private function add_navigation()
    {
    	$form = new HTMLForm('continueForm', InstallUrlBuilder::database()->absolute());
    	$nav = new InstallNavigationBar();
    	$nav->set_previous_step_url(InstallUrlBuilder::license()->absolute());
        $form->add_button($nav);
        $this->view->put('CONTINUE_FORM', $form->display());
    }
}
?>