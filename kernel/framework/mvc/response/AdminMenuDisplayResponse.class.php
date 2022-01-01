<?php
/**
 * @package     MVC
 * @subpackage  Response
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 24
 * @since       PHPBoost 3.0 - 2009 10 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminMenuDisplayResponse extends AbstractResponse
{
	/**
	 * @var Template
	 */
	private $full_view;
	private $links = array();
	protected $title;
	protected $module;

	public function __construct(View $view)
	{
		$env = new AdminDisplayGraphicalEnvironment();
		$this->full_view = new FileTemplate('admin/AdminMenuDisplayResponse.tpl');
		$this->full_view->put('content', $view);
		$env->display_kernel_message($this->full_view);
		parent::__construct($env, $this->full_view);

		$module_name = Environment::get_running_module_name();
		if (!empty($module_name))
		{
			$this->module = ModulesManager::get_module($module_name);
			if ($this->module)
			{
				$this->set_title(StringVars::replace_vars(LangLoader::get_message('form.module.title', 'form-lang'), array('module_name' => $this->module->get_configuration()->get_name())));

				$home_page = $this->module->get_configuration()->get_home_page();
				if (!empty($home_page))
				{
					$this->links[] = array(
						'LINK' => LangLoader::get_message('common.home', 'common-lang'),
						'U_LINK' => Url::to_rel('/' . $this->module->get_id() . '/' . $home_page)
					);
				}
			}
		}
	}

	public function set_title($title)
	{
		$this->title = $title;
		$this->full_view->put_all(array('TITLE' => $title));
	}

	public function add_link($name, $url)
	{
		$this->links[] = array(
			'LINK' => $name,
			'U_LINK' => Url::to_rel($url)
		);
	}

	public function send()
	{
		$this->full_view->put('links', $this->links);
		parent::send();
	}
}
?>
