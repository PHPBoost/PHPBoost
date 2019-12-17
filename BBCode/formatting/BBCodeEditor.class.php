<?php
/**
 * This class provides an interface editor for contents.
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 14
 * @since       PHPBoost 2.0 - 2008 07 05
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BBCodeEditor extends ContentEditor
{
	/**
	 * @var Usefull to know if we have to include all the necessary JS includes
	 */
	private static $editor_already_included = false;
	private $forbidden_positions = 0;
	private $icon_fa = array(
		array("fa","arrow-up"),array("fa","arrow-down"),array("fa","arrow-left"),array("fa","arrow-right"),array("fa","arrows-alt-h"),array("fa","arrows-alt"),array("fa","ban"),array("fa","bars"),array("fa","chart-bar"),
		array("fa","bell"),array("fa","book"),array("fa","calendar-alt"),array("fa","caret-left"),array("fa","caret-right"),array("far","clipboard"),array("far","clock"),array("fa","cloud-upload-alt"),array("fa","download"),
		array("fa","code"),array("fa","code-branch"),array("fa","cog"),array("fa","cogs"),array("fa","comment"),array("far","comment"),array("far","comments"),array("fa","cube"),array("fa","cubes"),
		array("fa","times"),array("fa","edit"),array("fa","envelope"),array("far","envelope"),array("fa","eraser"),array("fa","check"),array("fa","exclamation-triangle"),array("fa","question"),array("fa","minus-circle"),
		array("fa","info-circle"),array("far","eye"),array("fa","eye-slash"),array("fab","facebook"),array("fa","fast-forward"),array("fa","filter"),array("fa","flag"),array("fab","font-awesome-flag"),array("fa","folder"),
		array("fa","folder-open"),array("fa","gavel"),array("fa","cogs"),array("fa","globe"),array("fa","hand-point-right"),array("fa","heart"),array("fa","home"),array("fa","key"),array("far","lightbulb"),
		array("fa","list-ul"),array("fa","lock"),array("fa","magic"),array("fa","minus"),array("fa","plus"),array("fa","share"),array("fa","image"),array("fa","print"),array("fa","tachometer-alt"),
		array("fa","quote-right"),array("fa","sync-alt"),array("fa","save"),array("fa","search"),array("far","share-square"),array("fa","sign-in-alt"),array("fa","sign-out-alt"),array("fa","smile"),array("fa","sort"),
		array("fa","sort-alpha-up"),array("fa","sort-amount-up"),array("fa","sort-amount-down"),array("fa","spinner"),array("fa","star"),array("far","star"),array("fa","rss"),array("fa","tag"),array("fa","tags"),
		array("fa","tasks"),array("fa","th"),array("fa","ticket-alt"),array("fa","undo"),array("fa","unlink"),array("fa","file"),array("far","file"),array("fa","file-alt"),array("far","file-alt"),
		array("fa","user"),array("fa","user-shield"),array("fa","users"),array("fa","user-times"),array("fa","user-check"),array("fa","male"),array("fa","female"),array("fa","volume-up"),array("fa","wrench")
	);

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

		$countries = LangLoader::get('languages');
		foreach ($countries as $id => $name)
		{
			$template->assign_block_vars('countries', array(
				'ID' => $id,
				'NAME' => $name,
			));
		}

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

			if ($forbidden_tag == 'float' && $forbidden_tag == 'indent' && $forbidden_tag == 'sup' && $forbidden_tag == 'sub')
			{
				$template->put_all(array(
					'AUTH_POSITIONS' => ' bbcode-forbidden',
					'C_DISABLED_POSITIONS' => true
				));
			}
			if ($forbidden_tag == 'p' && $forbidden_tag == 'block' && $forbidden_tag == 'container' && $forbidden_tag == 'fieldset' && $forbidden_tag == 'abbr')
			{
				$template->put_all(array(
					'AUTH_CONTAINERS' => ' bbcode-forbidden',
					'C_DISABLED_CONTAINERS' => true
				));
			}
			if ($forbidden_tag == 'hide' && $forbidden_tag == 'member' && $forbidden_tag == 'moderator')
			{
				$template->put_all(array(
					'AUTH_HIDDEN' => ' bbcode-forbidden',
					'C_DISABLED_HIDDEN' => true
				));
			}

			$template->put_all(array(
				'AUTH_' . TextHelper::strtoupper($forbidden_tag) => ' bbcode-forbidden',
				'C_DISABLED_' . TextHelper::strtoupper($forbidden_tag) => true
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

		$feeds_modules = AppContext::get_extension_provider_service()->get_providers(FeedProvider::EXTENSION_POINT);
		foreach (ModulesManager::get_activated_modules_map_sorted_by_localized_name() as $module)
		{
			if (array_key_exists($module->get_id(), $feeds_modules))
			{
				$template->assign_block_vars('modules', array(
					'NAME' => $module->get_configuration()->get_name(),
					'VALUE' => $module->get_id()
				));
			}
		}

		if (!self::$editor_already_included)
		{
			self::$editor_already_included = true;
		}

		return $template->render();
	}
}
?>
