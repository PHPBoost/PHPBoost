<?php
/*##################################################
 *                          BBCodeEditor.class.php
 *                            -------------------
 *   begin                : July 5 2008
 *   copyright            : (C) 2008 Régis Viarre
 *   email                : crowkait@phpboost.com
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
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc This class provides an interface editor for contents.
 * @package {@package}
 */
class BBCodeEditor extends ContentEditor
{
	/**
	 * @var Usefull to know if we have to include all the necessary JS includes
	 */
	private static $editor_already_included = false;
	private $forbidden_positions = 0;
	private $icon_fa = array( "arrow-down",  "arrow-up", "arrow-left", "arrow-right", "arrows", "ban", "unban", "bar-chart", "bars", "bell", "book", "calendar", "caret-left", "caret-right", "clipboard", "clock-o", "cloud-upload", "download", "code", "code-fork", "cog", "cogs", "comment", "comment-o", "comments-o", "cube", "cubes", "delete", "edit", "remove", "envelope", "envelope-o", "eraser", "check", "success", "error", "warning", "question", "forbidden", "info-circle", "eye", "eye-slash", "facebook", "fast-forward",  "filter", "flag", "flag-o", "folder", "folder-open", "gavel", "gears", "globe", "google-plus", "hand-o-right", "heart", "home", "key", "legal", "lightbulb-o", "list-ul", "lock", "magic", "minus", "plus", "move", "picture-o", "print", "profil", "quote-right", "refresh", "save", "search", "share-square-o", "sign-in", "sign-out", "smile-o", "sort", "sort-alpha-asc", "sort-amount-asc", "sort-amount-desc", "spinner", "star", "star-half-empty", "star-o", "syndication", "tag", "tags", "tasks", "th", "ticket", "undo", "unlink", "file", "file-o", "file-text", "file-text-o", "user", "users", "offline", "online", "male", "female",  "volume-up", "wrench");

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
				'CODE' => $value
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