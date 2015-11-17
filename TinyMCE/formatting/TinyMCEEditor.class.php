<?php
/*##################################################
 *                             TinyMCEEditor.class.php
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
class TinyMCEEditor extends ContentEditor
{
	private static $js_included = false;
	private $array_tags = array('align1' => 'alignleft', 'align2' => 'aligncenter', 'align3' => 'alignright', 'align4' => 'alignjustify', '|1' => '|', 'title' => 'formatselect', 'style' => 'styleselect', '|2' => '|', 'list1' => 'bullist', 'list2' => 'numlist', '|3' => '|', 'indent1' => 'outdent', 'indent2' => 'indent', 'quote' => 'blockquote', '|4' => '|', 'cut' => 'cut', 'copy' => 'copy', 'paste' => 'paste');
	private $array_tags2 = array('undo' => 'undo', 'redo' => 'redo', '|1' => '|', 'b' => 'bold', 'i' => 'italic', 'u' => 'underline', 's' => 'strikethrough', '|2' => '|', 'color1' => 'forecolor', 'color2' => 'backcolor', '|3' => '|', 'size' => 'fontsizeselect', 'font' => 'fontselect', '|4' => '|', 'sub' => 'subscript', 'sup' => 'superscript', 'line' => 'hr');
	private $array_tags3 = array('emotions' => 'smileys', 'table' => 'table', 'insertdatetime' => 'insertdatetime', '|1' => '|', 'url1' => 'link', 'url2' => 'unlink', '|2' => '|', 'img' => 'image', 'movie' => 'media', 'insertfile' => 'insertfile', '|3' => '|', 'nanospell' => 'nanospell', '|4' => '|', 'anchor' => 'anchor', 'charmap' => 'charmap', '5|' => '|', 'removeformat' => 'removeformat', '|6' => '|', 'visualchars' => 'visualchars', 'visualblocks' => 'visualblocks', '|7' => '|', '_search' => 'searchreplace', '|8' => '|', '_fullscreen' => 'fullscreen');
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_template()
	{
		if (!is_object($this->template) || !($this->template instanceof Template))
		{
			$this->template = new FileTemplate('TinyMCE/tinymce_editor.tpl');
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
		
		$displayed_icons_number = 0;
		list($toolbar1, $toolbar2, $toolbar3) = array('', '', '');
		foreach ($this->array_tags as $tag => $tinymce_tag) //Balises autorisées.
		{
			$tag = preg_replace('`[0-9]`', '', $tag);
			if (!in_array($tag, $this->forbidden_tags))
			{
				$toolbar1 .= $tinymce_tag . ',';
				
				$displayed_icons_number++;
			}
		}
		foreach ($this->array_tags2 as $tag => $tinymce_tag) //Balises autorisées.
		{
			$tag = preg_replace('`[0-9]`', '', $tag);
			if (!in_array($tag, $this->forbidden_tags))
			{
				if ($displayed_icons_number < 18)
					$toolbar1 .= $tinymce_tag . ',';
				else
					$toolbar2 .= $tinymce_tag . ',';
				
				$displayed_icons_number++;
			}
		}
		foreach ($this->array_tags3 as $tag => $tinymce_tag) //Balises autorisées.
		{
			$tag = preg_replace('`[0-9]`', '', $tag);
			if (!in_array($tag, $this->forbidden_tags))
			{
				if ($tag != 'insertfile' || ($tag == 'insertfile' && AppContext::get_current_user()->check_auth(FileUploadConfig::load()->get_authorization_enable_interface_files(), FileUploadConfig::AUTH_FILES_BIT)))
				{
					if ($displayed_icons_number < 25)
						$toolbar1 .= $tinymce_tag . ',';
					else if ($displayed_icons_number < 35)
						$toolbar2 .= $tinymce_tag . ',';
					else
						$toolbar3 .= $tinymce_tag . ',';
					
					$displayed_icons_number++;
				}
			}
		}
		
		$language = substr(AppContext::get_current_user()->get_locale(), 0, 2);
		switch ($language) {
			case 'fr' : $language = 'fr_FR';
						break;
						
			case 'en' : $language = 'en_GB';
						break;
						
			default :	break;
		}
		
		$form_field_infos = explode('_', $this->identifier);
		$form_name = $form_field_infos[0];
		unset($form_field_infos[0]);
		$field_name = implode('_', $form_field_infos);
		
		$template->put_all(array(
			'C_NOT_JS_INCLUDED' => self::$js_included,
			'C_HTMLFORM' => !empty($form_name) && !empty($field_name),
			'PAGE_PATH' => $_SERVER['PHP_SELF'],
			'FIELD' => $this->identifier,
			'FORM_NAME' => $form_name,
			'FIELD_NAME' => $field_name,
			'FORBIDDEN_TAGS' => implode(',', $this->forbidden_tags),
			'L_REQUIRE_TEXT' => LangLoader::get_message('require_text', 'main'),
			'C_TOOLBAR1' => !empty($toolbar1),
			'C_TOOLBAR2' => !empty($toolbar2),
			'C_TOOLBAR3' => !empty($toolbar3),
			'TOOLBAR1' => preg_replace('`\|(,\|)+`', '|', trim($toolbar1, ',')),
			'TOOLBAR2' => preg_replace('`\|(,\|)+`', '|', trim($toolbar2, ',')),
			'TOOLBAR3' => preg_replace('`\|(,\|)+`', '|', trim($toolbar3, ',')),
			'LANGUAGE' => $language
		));
		
		self::$js_included = true;

		//Chargement des smileys.
		$smileys = SmileysCache::load()->get_smileys();
		$smile_by_line = 9;
		
		$nbr_smile = count($smileys);
		$j = 1;
		foreach($smileys as $code_smile => $infos)
		{
			$template->assign_block_vars('smiley', array(
				'C_NEW_ROW' => is_int(($j -1) / $smile_by_line),
				'C_LAST_OF_THE_ROW' => is_int($j / $smile_by_line),
				'C_END_ROW' => is_int($j / $smile_by_line) || $nbr_smile == $j,
				'C_LAST_ROW' => $nbr_smile == $j,
				'URL' => Url::to_rel('/images/smileys/' . $infos['url_smiley']),
				'CODE' => addslashes($code_smile)
			));
			$j++;
		}

		return $template->render();
	}
}
?>