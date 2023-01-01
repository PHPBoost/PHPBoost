<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 22
 * @since       PHPBoost 3.0 - 2009 12 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class InstallDisplayResponse extends AbstractResponse
{
	private $lang;

	private $distribution_lang;

	private $current_step = 0;

	private $steps_number;

	/**
	 * @var Template
	 */
	private $full_view;

	public function __construct($step_number, $step_title, $lang, Template $view, $additional_stylesheet = '')
	{
		$this->init_response($step_number, $lang, $view);
		$env = new InstallDisplayGraphicalEnvironment();
		$this->add_language_bar();
		$this->init_steps();
		$this->update_progress_bar();

		$this->full_view->put_all(array(
			'C_HAS_PREVIOUS_STEP'     => false,
			'C_HAS_NEXT_STEP'         => false,
			'C_ADDITIONAL_STYLESHEET' => !empty($additional_stylesheet),

			'STEP_TITLE'                => $step_title,
			'ADDITIONAL_STYLESHEET_URL' => $additional_stylesheet,
			'PROGRESSION'               => floor(100 * $this->current_step / ($this->steps_number -1)),
			'PHPBOOST_VERSION'          => GeneralConfig::load()->get_phpboost_major_version(),

			'U_RESTART' => InstallUrlBuilder::welcome()->rel(),
		));

		parent::__construct($env, $this->full_view);
	}

	public function init_response($step_number, $lang, Template $view)
	{
		$this->current_step = $step_number;
		$this->lang = $lang;
		$this->full_view = new FileTemplate('install/main.tpl');
		$this->full_view->put('INSTALL_STEP', $view);
		$this->full_view->add_lang($this->lang);
		$view->add_lang($this->lang);
	}

	private function add_language_bar()
	{
		$lang = TextHelper::htmlspecialchars(AppContext::get_request()->get_string('lang', ''));
		$lang = in_array($lang, InstallationServices::get_available_langs()) ? $lang : InstallationServices::get_default_lang();

		$lang_dir = new Folder(PATH_TO_ROOT . '/lang');
		$langs = array();
		foreach ($lang_dir->get_folders('`^[a-z_-]+$`iu') as $folder)
		{
			$info_lang = load_ini_file(PATH_TO_ROOT . '/lang/', $folder->get_name());
			if (!empty($info_lang['name']))
			{
				$langs[] = array(
					'LANG'				=> $folder->get_name(),
					'LANG_NAME'			=> $info_lang['name'],
					'LANG_IDENTIFIER'	=> $info_lang['identifier'],
					'SELECTED'			=> $folder->get_name() == $lang ? 'selected="selected"' : ''
				);
			}
		}
		$this->full_view->put('lang', $langs);
	}

	private function init_steps()
	{
		$steps = array(
			array('name' => $this->lang['install.step.introduction'], 'img' => 'home'),
			array('name' => $this->lang['install.step.license'], 'img' => 'file'),
			array('name' => $this->lang['install.step.server'], 'img' => 'cog'),
			array('name' => $this->lang['install.step.database'], 'img' => 'server'),
			array('name' => $this->lang['install.step.website'], 'img' => 'cogs'),
			array('name' => $this->lang['install.step.admin'], 'img' => 'users'),
			array('name' => $this->lang['install.step.end'], 'img' => 'check')
		);
		$this->steps_number = count($steps);

		for ($i = 0; $i < $this->steps_number; $i++)
		{
			if ($i < $this->current_step)
			{
				$row_class = 'row-success';
			}
			elseif ($i == $this->current_step && $i == ($this->steps_number - 1))
			{
				$row_class = 'row-current row-final';
			}
			elseif ($i == $this->current_step)
			{
				$row_class = 'row-current';
			}
			elseif ($i == ($this->steps_number - 1))
			{
				$row_class = 'row-next row-final';
			}
			else
			{
				$row_class = 'row-next';
			}

			$this->full_view->assign_block_vars('step', array(
				'CSS_CLASS' => $row_class,
				'IMG' => $steps[$i]['img'],
				'NAME' => $steps[$i]['name']
			));
		}
	}

	private function update_progress_bar()
	{
		for ($i = 1; $i <= floor(($this->current_step / $this->steps_number) * 24); $i++)
		{
			$this->full_view->assign_block_vars('progress_bar', array());
		}
	}
}
?>
