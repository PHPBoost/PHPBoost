<?php
/**
 * This class provides an interface editor for contents.
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 11 22
 * @since   	PHPBoost 2.0 - 2008 07 05
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class BBCodeEditor extends ContentEditor
{
	/**
	 * @var Usefull to know if we have to include all the necessary JS includes
	 */
	private static $editor_already_included = false;
	private $forbidden_positions = 0;
	private $icon_fa = array( array("fa","arrow-up"), array("fa","arrow-down"), array("fa","arrow-left"), array("fa","arrow-right"), array("fa","arrows-alt-h"), array("fa","arrows-alt"), array("fa","ban"), array("fa","unban"), array("fa","chart-bar"), array("fa","bars"), array("fa","bell"), array("fa","book"), array("fa","calendar-alt"), array("fa","caret-left"), array("fa","caret-right"), array("far","clipboard"), array("far","clock"), array("fa","cloud-upload-alt"), array("fa","download"), array("fa","code"), array("fa","code-branch"), array("fa","cog"), array("fa","cogs"), array("fa","comment"), array("far","comment"), array("far","comments"), array("fa","cube"), array("fa","cubes"), array("fa","delete"), array("fa","edit"), array("fa","remove"), array("fa","envelope"), array("far","envelope"), array("fa","eraser"), array("fa","check"), array("fa","success"), array("fa","error"), array("fa","warning"), array("fa","question"), array("fa","forbidden"), array("fa","info-circle"), array("far","eye"), array("fa","eye-slash"), array("fab","facebook"), array("fa","fast-forward"), array("fa","filter"), array("fa","flag"), array("fab","font-awesome-flag"), array("fa","folder"), array("fa","folder-open"), array("fa","gavel"), array("fa","gears"), array("fa","globe"), array("fab","google-plus-g"), array("fa","hand-point-right"), array("fa","heart"), array("fa","home"), array("fa","key"), array("far","lightbulb"), array("fa","list-ul"), array("fa","lock"), array("fa","magic"), array("fa","minus"), array("fa","plus"), array("fa","move"), array("fa","image"), array("fa","print"), array("fa","profil"), array("fa","quote-right"), array("fa","refresh"), array("fa","save"), array("fa","search"), array("far","share-square"), array("fa","sign-in-alt"), array("fa","sign-out-alt"), array("fa","smile"), array("fa","sort"), array("fa","sort-alpha-up"), array("fa","sort-amount-up"), array("fa","sort-amount-down"), array("fa","spinner"), array("fa","star"), array("fa","star-half-empty"), array("far","star"), array("fa","syndication"), array("fa","tag"), array("fa","tags"), array("fa","tasks"), array("fa","th"), array("fa","ticket-alt"), array("fa","undo"), array("fa","unlink"), array("fa","file"), array("far","file"), array("fa","file-alt"), array("far","file-alt"), array("fa","user"), array("fa","users"), array("fa","offline"), array("fa","online"), array("fa","male"), array("fa","female"), array("fa","volume-up"), array("fa","wrench")
	);

	function __construct()
	{
		parent::__construct();
	}

	public function get_template()
	{
		if (!is_object($this->template) || !($this->template instanceof Template))
		{
			$this->template = new FileTemplate('BBCode/bbcode_editor.tpl');
		}
		return $this->template;
	}

	/**
	 * @desc Display the editor
	 * @return string Formated editor.
	 */
	public function display()
	{
		$template = $this->get_template();

		$smileys_cache = SmileysCache::load();

		$bbcode_lang = LangLoader::get('common', 'BBCode');
		$template->add_lang($bbcode_lang);

		$template->put_all(array(
			'PAGE_PATH'                     => $_SERVER['PHP_SELF'],
			'C_EDITOR_NOT_ALREADY_INCLUDED' => !self::$editor_already_included,
			'FIELD'                         => $this->identifier,
			'FORBIDDEN_TAGS'                => !empty($this->forbidden_tags) ? implode(',', $this->forbidden_tags) : '',
			'C_UPLOAD_MANAGEMENT'           => AppContext::get_current_user()->check_auth(FileUploadConfig::load()->get_authorization_enable_interface_files(), FileUploadConfig::AUTH_FILES_BIT)
		));

		foreach ($this->forbidden_tags as $forbidden_tag) //Balises interdite.
		{
			if ($forbidden_tag == 'fieldset')
				$forbidden_tag = 'block';

			if ($forbidden_tag == 'float' || $forbidden_tag == 'indent')
				$this->forbidden_positions = $this->forbidden_positions + 1 ;

			if ($this->forbidden_positions == 2)
			{
				$template->put_all(array(
					'AUTH_POSITIONS' => ' bbcode-forbidden',
					'DISABLED_POSITIONS' => 'return false;'
				));
			}

			$template->put_all(array(
				'AUTH_' . TextHelper::strtoupper($forbidden_tag) => ' bbcode-forbidden',
				'DISABLED_' . TextHelper::strtoupper($forbidden_tag) => 'return false;'
			));
		}

		foreach ($smileys_cache->get_smileys() as $code_smile => $infos)
		{
			$template->assign_block_vars('smileys', array(
				'URL' => TPL_PATH_TO_ROOT . '/images/smileys/' . $infos['url_smiley'],
				'CODE' => addslashes($code_smile),
			));
		}

		foreach ($this->icon_fa as $key => $value)
		{
			$template->assign_block_vars('code_fa', array(
				'C_CUSTOM_PREFIX' => $value[0] != 'fa',
				'PREFIX'          => $value[0],
				'CODE'            => $value[1]
			));
		}

		$template->put_all(array(
			'L_SMILEY' => LangLoader::get_message('smiley', 'main'),
		));

		if (!self::$editor_already_included)
		{
			self::$editor_already_included = true;
		}

		return $template->render();
	}
}
?>
