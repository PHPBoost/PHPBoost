<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 19
 * @since       PHPBoost 3.0 - 2009 12 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

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

	private $error_type;
	private $title = '';
	private $message = '';
	private $time;
	private $link = self::PREVIOUS_PAGE;
	private $link_name = '';
	private $response_classname = self::SITE_RESPONSE;

	/**
	 * @var View
	 */
	protected $view;

	public function __construct($title, $message, $error_type = self::QUESTION)
	{
		$this->title = $title;
		$this->message = $message;
		$this->error_type = $error_type;
		$this->link_name = LangLoader::get_message('common.back', 'common-lang');
	}

	public function set_correction_link($link_name, $link = self::PREVIOUS_PAGE)
	{
		$this->link_name = $link_name;
		$this->link = $link;
	}

	public function set_error_type($error_type)
	{
		$this->error_type = $error_type;
	}

	public function set_time_redirect($time)
	{
		if (is_numeric($time))
		{
			$this->time = $time;
		}
	}

	public function set_response_classname($response_classname)
	{
		$this->response_classname = $response_classname;
	}

	public function disable_correction_link() {
		$this->link_name = "";
		$this->link = "";
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->create_view();
		$this->fill_view();

		$maintenance_config = MaintenanceConfig::load();

		if ($maintenance_config->is_under_maintenance() && !$maintenance_config->is_authorized_in_maintenance())
			return new SiteNodisplayResponse($this->view);
		else
		{
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
	}

	protected function create_view()
	{
		$this->view = new FileTemplate('user/UserErrorController.tpl');
	}

	private function fill_view()
	{
		$theme_id = AppContext::get_current_user()->get_theme();
		if (file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/images/error.webp'))
			$error_img = TPL_PATH_TO_ROOT . '/templates/' . $theme_id . '/images/error.webp';
		else
			$error_img = TPL_PATH_TO_ROOT . '/templates/__default__/images/error.webp';

		$this->view->put_all(array(
			'ERROR_TYPE'  => $this->get_error_type(),
			'TITLE'       => $this->title,
			'MESSAGE'     => $this->message,
			'U_LINK'      => $this->link,
			'U_ERROR_IMG' => $error_img,
			'TIME'        => $this->time,
			'LINK_NAME'   => $this->link_name,
			'HAS_LINK'    => !empty($this->link) && !empty($this->link_name),
			'HAS_TIME'    => !empty($this->time) && !empty($this->link),
		));
	}

	private function get_error_type()
	{
		$css_class_name = 'question';
		switch ($this->error_type)
		{
			case self::NOTICE:
				$css_class_name = 'notice';
				break;
			case self::WARNING:
				$css_class_name = 'warning';
				break;
			case self::FATAL:
				$css_class_name = 'error';
				break;
			case self::SUCCESS:
				$css_class_name = 'success';
				break;
		}
		return $css_class_name;
	}
}
?>
