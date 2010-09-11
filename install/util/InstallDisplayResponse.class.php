<?php
/*##################################################
 *                           AdminErrorsDisplayResponse.class.php
 *                            -------------------
 *   begin                : December 13 2009
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

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc the response
 */
class InstallDisplayResponse extends AbstractResponse
{
	const INSTALL_DEFAULT_LANGUAGE = 'french';

	private $lang;

	private $current_step = 0;

	private $nb_steps;

	/**
	 * @var Template
	 */
	private $full_view;

	public function __construct($page_title, Template $view, $step_number, $step_title, $step_explanation)
	{
		$this->init_response($step_number, $view);
		$env = new InstallDisplayGraphicalEnvironment();
		$env->set_page_title($page_title);
		$this->add_language_bar();
		$this->init_steps();
		$this->update_progress_bar();

		$this->full_view->assign_vars(array(
            'STEP_TITLE' => $step_title,
            'STEP_EXPLANATION' => $step_explanation,
            'C_HAS_PREVIOUS_STEP' => false,
            'C_HAS_NEXT_STEP' => false,
            'PROGRESSION' => floor(100 * $this->current_step / $this->nb_steps)
		));

		parent::__construct($env, $this->full_view);
	}

	public function init_response($step_number, Template $view)
	{
		$this->current_step = $step_number;
		$this->full_view = new FileTemplate('install/main.tpl');
		$this->full_view->add_subtemplate('step', $view);
		$this->lang = LangLoader::get('install', 'install');
		$this->full_view->add_lang($this->lang);
		$view->add_lang($this->lang);
	}

	public function set_previous_step($name, $url)
	{
		$this->full_view->assign_vars(array(
            'C_HAS_PREVIOUS_STEP' => true,
            'PREVIOUS_STEP_URL' => $url,
            'PREVIOUS_STEP_TITLE' => $name,
		));
	}

	public function set_next_step($name, $url)
	{
		$this->full_view->assign_vars(array(
            'C_HAS_NEXT_STEP' => true,
            'NEXT_STEP_URL' => $url,
            'NEXT_STEP_TITLE' => $name,
		));
	}

	private function add_language_bar()
	{
		$lang = AppContext::get_request()->get_string('lang', self::INSTALL_DEFAULT_LANGUAGE);
		$lang_dir = new Folder(PATH_TO_ROOT . '/lang');
		foreach ($lang_dir->get_folders('`^[a-z_-]+$`i') as $folder)
		{
			$info_lang = load_ini_file(PATH_TO_ROOT . '/lang/', $folder->get_name());
			if (!empty($info_lang['name']))
			{
				$this->full_view->assign_block_vars('lang', array(
					'LANG' => $folder->get_name(),
					'LANG_NAME' => $info_lang['name'],
					'SELECTED' => $folder->get_name() == $lang ? 'selected="selected"' : ''
				));

				if ($folder->get_name() == $lang)
				{
					$this->full_view->assign_vars(array('LANG_IDENTIFIER' => $info_lang['identifier']));
				}
			}
		}
	}

	private function init_steps()
	{
		$steps = array(
			array('name' => $this->lang['step.welcome.title'], 'img' => 'intro.png'),
			array('name' => $this->lang['step.license.title'], 'img' => 'license.png'),
			array('name' => $this->lang['config_server'], 'img' => 'config.png'),
			array('name' => $this->lang['database_config'], 'img' => 'database.png'),
			array('name' => $this->lang['advanced_config'], 'img' => 'advanced_config.png'),
			array('name' => $this->lang['administrator_account_creation'], 'img' => 'admin.png'),
			array('name' => $this->lang['end'], 'img' => 'end.png')
		);
		$this->nb_steps = count($steps);

		for ($i = 0; $i < $this->nb_steps; $i++)
		{
			if ($i < $this->current_step)
			{
				$row_class = 'row_success';
			}
			elseif ($i == $this->current_step && $i == ($this->nb_steps - 1))
			{
				$row_class = 'row_current row_final';
			}
			elseif ($i == $this->current_step)
			{
				$row_class = 'row_current';
			}
			elseif ($i == ($this->nb_steps - 1))
			{
				$row_class = 'row_next row_final';
			}
			else
			{
				$row_class = 'row_next';
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
		for ($i = 1; $i <= floor(($this->current_step / $this->nb_steps) * 24); $i++)
		{
			$this->full_view->assign_block_vars('progress_bar', array());
		}
	}
}
?>