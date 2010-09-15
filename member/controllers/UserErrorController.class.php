<?php
/*##################################################
 *                           UserErrorController.class.php
 *                            -------------------
 *   begin                : December 09 2009
 *   copyright            : (C) 2009 Loic Rouchon
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

class UserErrorController extends AbstractController
{
	const SUCCESS = 1;
	const QUESTION = 2;
	const NOTICE = 3;
	const WARNING = 4;
	const FATAL = 5;

	const PREVIOUS_PAGE = 'javascript:history.back(1);';
	
    const SITE_RESPONSE = 'SiteDisplayResponse';
    const ADMIN_RESPONSE = 'AdminDisplayResponse';

	private $error_type = E_UNKNOWN;
	private $title = '';
	private $message = '';
	private $link = self::PREVIOUS_PAGE;
    private $link_name = '';
    private $response_classname = self::SITE_RESPONSE;

	/**
	 * @var View
	 */
	private $view;

	public function __construct($title, $message, $error_type = self::QUESTION)
	{
		global $LANG;
		
		$this->title = $title;
		$this->message = $message;
		$this->error_type = $error_type;
	    $this->link_name = $LANG['back'];
	}

	public function set_correction_link($link_name, $link = self::PREVIOUS_PAGE)
	{
		$this->link_name = $link_name;
		$this->link = $link;
	}
	
	public function set_response_classname($response_classname)
	{
		$this->response_classname = $response_classname;
	}
	
	public function disable_correction_link() {
		$this->link_name = "";
        $this->link = "";
	}
	
	public function execute(HTTPRequest $request)
	{
		$this->create_view();
		$this->fill_view();
		$response = new $this->response_classname($this->view);
		$graphical_env = $response->get_graphical_environment();
		if (!($graphical_env instanceof AbstractDisplayGraphicalEnvironment))
		{
			throw new Exception($this->response_classname . ' does not contains a graphical environement ' .
			    'that is an instance of AbstractDisplayGraphicalEnvironment');
		}
		$graphical_env->set_page_title($this->title);
		return $response;
	}

	private function create_view()
	{
		$this->view = new FileTemplate('member/UserErrorController.tpl');
	}

	private function fill_view()
	{
		$this->view->assign_vars(array(
            'ERROR_TYPE' => $this->get_error_type(),
            'ERROR_IMG' => $this->get_error_type_associated_image(),
            'TITLE' => $this->title,
            'MESSAGE' => $this->message,
            'U_LINK' => $this->link,
            'LINK_NAME' => $this->link_name,
		    'HAS_LINK' => !empty($this->link) && !empty($this->link_name)
		));
	}

	private function get_error_type_associated_image() {
		$img_error = 'question';
		switch ($this->error_type)
        {
            case self::NOTICE:
                $img_error = 'notice';
                break;
            case self::WARNING:
                $img_error = 'important';
                break;
            case self::FATAL:
                $img_error = 'stop';
                break;
            case self::SUCCESS:
                $img_error = 'success';
                break;
        }
        return $img_error;
	}
	
	private function get_error_type()
	{
		$css_class_name = 'unknow';
		switch ($this->error_type)
		{
			case self::NOTICE:
				$css_class_name = 'notice';
				break;
			case self::WARNING:
				$css_class_name = 'warning';
				break;
			case self::FATAL:
				$css_class_name = 'fatal';
				break;
			case self::SUCCESS:
				$css_class_name = 'success';
				break;
		}
		return $css_class_name;
	}
}
?>