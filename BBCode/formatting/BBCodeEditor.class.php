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
		global $LANG;

		$template = $this->get_template();

		$smileys_cache = SmileysCache::load();

		$bbcode_lang = LangLoader::get('common', 'BBCode');
		
		$template->put_all(array(
			'PAGE_PATH' => $_SERVER['PHP_SELF'],
			'C_EDITOR_NOT_ALREADY_INCLUDED' => !self::$editor_already_included,
			'FIELD' => $this->identifier,
			'FORBIDDEN_TAGS' => !empty($this->forbidden_tags) ? implode(',', $this->forbidden_tags) : '',
			'C_UPLOAD_MANAGEMENT' => AppContext::get_current_user()->check_auth(FileUploadConfig::load()->get_authorization_enable_interface_files(), FileUploadConfig::AUTH_FILES_BIT),
			'L_REQUIRE_TEXT' => $LANG['require_text'],
			'L_BB_UPLOAD' => $bbcode_lang['bb_upload'],
			'L_BB_SMILEYS' => $bbcode_lang['bb_smileys'],
			'L_BB_BOLD' => $bbcode_lang['bb_bold'],
			'L_BB_ITALIC' => $bbcode_lang['bb_italic'],
			'L_BB_UNDERLINE' => $bbcode_lang['bb_underline'],
			'L_BB_STRIKE' => $bbcode_lang['bb_strike'],
			'L_BB_TITLE' => $bbcode_lang['bb_title'],
			'L_BB_CONTAINER' => $bbcode_lang['bb_container'],
			'L_BB_HTML' => $bbcode_lang['bb_html'],
			'L_BB_STYLE' => $bbcode_lang['bb_style'],
			'L_BB_URL' => $bbcode_lang['bb_link'],
			'L_BB_IMAGE' => $bbcode_lang['bb_picture'],
			'L_BB_LIGHTBOX' => $bbcode_lang['bb_lightbox'],
			'L_BB_QUOTE' => $bbcode_lang['bb_quote'],
			'L_BB_HIDE' => $bbcode_lang['bb_hide'],
			'L_BB_MEMBER' => $bbcode_lang['bb_member'],
			'L_BB_MODERATOR' => $bbcode_lang['bb_moderator'],
			'L_BB_HIDE_ALL' => $bbcode_lang['bb_hide_all'],
			'L_BB_VIEW_MEMBER' => $bbcode_lang['bb_hide_view_member'],
			'L_BB_VIEW_MODERATOR' => $bbcode_lang['bb_hide_view_moderator'],
			'L_BB_COLOR' => $bbcode_lang['bb_color'],
			'L_BB_SIZE' => $bbcode_lang['bb_size'],
			'L_BB_FONT' => $bbcode_lang['bb_font'],
			'L_BB_SMALL' => $bbcode_lang['bb_small'],
			'L_BB_LARGE' => $bbcode_lang['bb_large'],
			'L_BB_LEFT' => $bbcode_lang['bb_left'],
			'L_BB_CENTER' => $bbcode_lang['bb_center'],
			'L_BB_RIGHT' => $bbcode_lang['bb_right'],
			'L_BB_JUSTIFY' => $bbcode_lang['bb_justify'],
			'L_BB_FLOAT_LEFT' => $bbcode_lang['bb_float_left'],
			'L_BB_FLOAT_RIGHT' => $bbcode_lang['bb_float_right'],
			'L_BB_SUP' => $bbcode_lang['bb_sup'],
			'L_BB_SUB' => $bbcode_lang['bb_sub'],
			'L_BB_INDENT' => $bbcode_lang['bb_indent'],
			'L_BB_LIST' => $bbcode_lang['bb_list'],
			'L_BB_TABLE' => $bbcode_lang['bb_table'],
			'L_BB_SWF' => $bbcode_lang['bb_swf'],
			'L_BB_YOUTUBE' => $bbcode_lang['bb_youtube'],
			'L_BB_FLASH' => $bbcode_lang['bb_swf'],
			'L_BB_MOVIE' => $bbcode_lang['bb_movie'],
			'L_BB_SOUND' => $bbcode_lang['bb_sound'],
			'L_BB_CODE' => $bbcode_lang['bb_code'],
			'L_BB_MATH' => $bbcode_lang['bb_math'],
			'L_BB_ANCHOR' => $bbcode_lang['bb_anchor'],
			'L_BB_HELP' => $bbcode_lang['bb_help'],
			'L_BB_MORE' => $bbcode_lang['bb_more'],
			'L_URL_PROMPT' => $bbcode_lang['bb_url_prompt'],
			'L_ANCHOR_PROMPT' => $bbcode_lang['bb_anchor_prompt'],
			'L_TITLE' => LangLoader::get_message('format_title', 'editor-common'),
			'L_CONTAINER' => $bbcode_lang['bb_container'],
			'L_BLOCK' => $bbcode_lang['bb_block'],
			'L_FIELDSET' => $bbcode_lang['bb_fieldset'],
			'L_STYLE' => $LANG['style'],
			'L_QUESTION' => $LANG['question'],
			'L_NOTICE' => $LANG['notice'],
			'L_WARNING' => $LANG['warning'],
			'L_ERROR' => LangLoader::get_message('error', 'status-messages-common'),
			'L_SUCCESS' => $LANG['success'],
			'L_SIZE' => LangLoader::get_message('format_size', 'editor-common'),
			'L_FONT' => LangLoader::get_message('format_font', 'editor-common'),
			'L_CODE' => $bbcode_lang['bb_code'],
			'L_TEXT' => $bbcode_lang['bb_text'],
			'L_SCRIPT' => $bbcode_lang['bb_script'],
			'L_WEB' => $bbcode_lang['bb_web'],
			'L_PROG' => $bbcode_lang['bb_prog'],
			'L_TABLE_HEAD' => $bbcode_lang['head_table'],
			'L_ADD_HEAD' => $bbcode_lang['head_add'],
			'L_LINES' => $bbcode_lang['lines'],
			'L_COLS' => $bbcode_lang['cols'],
			'L_ORDERED_LIST' => $bbcode_lang['ordered_list'],
			'L_INSERT_LIST' => $bbcode_lang['insert_list'],
			'L_INSERT_TABLE' => $bbcode_lang['insert_table'],
			'L_PHPBOOST_LANGUAGES' => $bbcode_lang['phpboost_languages']
		));

		foreach ($this->forbidden_tags as $forbidden_tag) //Balises interdite.
		{
			if ($forbidden_tag == 'fieldset')
				$forbidden_tag = 'block';

			$template->put_all(array(
				'AUTH_' . strtoupper($forbidden_tag) => 'style="opacity:0.3;filter:alpha(opacity=30);cursor:default;"',
				'DISABLED_' . strtoupper($forbidden_tag) => 'return false;'
			));
		}

		foreach ($smileys_cache->get_smileys() as $code_smile => $infos)
		{
			$template->assign_block_vars('smileys', array(
				'URL' => TPL_PATH_TO_ROOT . '/images/smileys/' . $infos['url_smiley'],
				'CODE' => addslashes($code_smile),
			));
		}

		$template->put_all(array(
			'L_SMILEY' => $LANG['smiley']
		));

		if (!self::$editor_already_included)
		{
			self::$editor_already_included = true;
		}

		return $template->render();
	}
}
?>