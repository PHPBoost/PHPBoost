<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2012 02 29
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UpdateDisplayResponse extends AbstractResponse
{
	const UPDATE_DEFAULT_LANGUAGE = 'french';

	private $lang;

	private $current_step = 0;

	private $steps_number;

	/**
	 * @var Template
	 */
	private $full_view;

	public function __construct($step_number, $step_title, Template $view)
	{
		$this->load_language_resources();
		$this->init_response($view);
		$env = new UpdateDisplayGraphicalEnvironment();
		$this->add_language_bar();
		$this->init_steps($step_number);
		$this->update_progress_bar();

		$this->full_view->put_all([
			'RESTART' => UpdateUrlBuilder::introduction()->rel(),
			'STEP_TITLE' => $step_title,
			'C_HAS_PREVIOUS_STEP' => false,
			'C_HAS_NEXT_STEP' => false,
			'L_XML_LANGUAGE' => $this->lang['common.xml.lang'],
			'PROGRESSION' => floor(100 * $this->current_step / $this->steps_number)
		]);

		parent::__construct($env, $this->full_view);
	}

	public function init_response(Template $view)
	{
		$this->full_view = new FileTemplate('update/main.tpl');
		$this->full_view->put('UpdateStep', $view);
		$this->full_view->add_lang($this->lang);
		$view->add_lang($this->lang);
	}

	public function load_language_resources()
	{
		$this->lang = LangLoader::get_all_langs('update');
	}

	private function add_language_bar()
	{
		$lang = AppContext::get_request()->get_string('lang', self::UPDATE_DEFAULT_LANGUAGE);
		$lang_dir = new Folder(PATH_TO_ROOT . '/lang');
		$langs = [];
		foreach ($lang_dir->get_folders('`^[a-z_-]+$`i') as $folder)
		{
			$info_lang = load_ini_file(PATH_TO_ROOT . '/lang/', $folder->get_name());
			if (!empty($info_lang['name']))
			{
				$langs[] = [
					'LANG'				=> $folder->get_name(),
					'LANG_NAME'			=> $info_lang['name'],
					'LANG_IDENTIFIER'	=> $info_lang['identifier'],
					'SELECTED'			=> $folder->get_name() == $lang ? 'selected="selected"' : ''
				];
			}
		}
		$this->full_view->put('lang', $langs);
	}

	private function init_steps($step_number)
	{
		$this->current_step = $step_number;

		$steps = [
			['name' => $this->lang['update.step.list.introduction'], 'img' => 'home'],
        ];

		if (!UpdateServices::check_server())
			$steps[] = ['name' => $this->lang['update.step.list.server'], 'img' => 'cog'];
		else if ($this->current_step > 1)
			$this->current_step--;

		if (!UpdateServices::database_config_file_checked())
			$steps[] = ['name' => $this->lang['update.step.list.database'], 'img' => 'server'];
		else if ($this->current_step > 2)
			$this->current_step--;

		$steps[] = ['name' => $this->lang['update.step.list.execute'], 'img' => 'sync-alt'];
		$steps[] = ['name' => $this->lang['update.step.list.end'], 'img' => 'check'];

		$this->steps_number = count($steps);
		$this->full_view->put('STEPS_NUMBER', $this->steps_number);

		$i = 1;
		foreach ($steps as $step)
		{
			if ($i < $this->current_step)
				$row_class = 'row-success';
			elseif ($i == $this->current_step && $i == $this->steps_number)
				$row_class = 'row-current row-final';
			elseif ($i == $this->current_step)
				$row_class = 'row-current';
			elseif ($i == $this->steps_number)
				$row_class = 'row-next row-final';
			else
				$row_class = 'row-next';

			$this->full_view->assign_block_vars('step', [
				'CSS_CLASS' => $row_class,
				'IMG'       => $step['img'],
				'NAME'      => $step['name']
			]);

			$i++;
		}
	}

	private function update_progress_bar()
	{
		for ($i = 1; $i <= floor(($this->current_step / $this->steps_number) * 24); $i++)
		{
			$this->full_view->assign_block_vars('progress_bar', []);
		}
	}
}
?>
