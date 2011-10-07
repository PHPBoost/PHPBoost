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

	private $distribution_lang;

	private $current_step = 0;

	private $nb_steps;

	/**
	 * @var Template
	 */
	private $full_view;

	public function __construct($step_number, $step_title, Template $view)
	{
		$this->load_language_resources();
		$this->init_response($step_number, $view);
		$env = new InstallDisplayGraphicalEnvironment();
		$this->add_language_bar();
		$this->init_steps();
		$this->update_progress_bar();

		$this->full_view->put_all(array(
			'RESTART' => InstallUrlBuilder::welcome()->rel(),
            'STEP_TITLE' => $step_title,
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
		$this->full_view->put('installStep', $view);
		$this->full_view->add_lang($this->lang);
		$this->full_view->add_lang($this->distribution_lang);
		$view->add_lang($this->lang);
		$view->add_lang($this->distribution_lang);
	}

	public function load_language_resources()
	{
		$this->lang = LangLoader::get('install', 'install');
		$this->distribution_lang = LangLoader::get('distribution', 'install');
	}

	private function add_language_bar()
	{
		$lang = AppContext::get_request()->get_string('lang', self::INSTALL_DEFAULT_LANGUAGE);
		$lang_dir = new Folder(PATH_TO_ROOT . '/lang');
		$langs = array();
		foreach ($lang_dir->get_folders('`^[a-z_-]+$`i') as $folder)
		{
			$info_lang = load_ini_file(PATH_TO_ROOT . '/lang/', $folder->get_name());
			if (!empty($info_lang['name']))
			{
				$langs[] = array(
					'LANG' => $folder->get_name(),
					'LANG_NAME' => $info_lang['name'],
					'SELECTED' => $folder->get_name() == $lang ? 'selected="selected"' : ''
				);
				if ($folder->get_name() == $lang)
				{
					$this->full_view->put('LANG_IDENTIFIER', $info_lang['identifier']);
				}
			}
		}
		$this->full_view->put('lang', $langs);
	}

	private function init_steps()
	{
		$steps = array(
			array('name' => $this->lang['step.list.introduction'], 'img' => 'intro.png'),
			array('name' => $this->lang['step.list.license'], 'img' => 'license.png'),
			array('name' => $this->lang['step.list.server'], 'img' => 'config.png'),
			array('name' => $this->lang['step.list.database'], 'img' => 'database.png'),
			array('name' => $this->lang['step.list.website'], 'img' => 'advanced_config.png'),
			array('name' => $this->lang['step.list.admin'], 'img' => 'admin.png'),
			array('name' => $this->lang['step.list.end'], 'img' => 'end.png')
		);
		$this->nb_steps = count($steps) - 1;

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