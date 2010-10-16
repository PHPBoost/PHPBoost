<?php
/*##################################################
 *                             tinymce_parser.class.php
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
    private $array_tags = array('align1' => 'justifyleft', 'align2' => 'justifycenter', 'align3' => 'justifyright', 'align4' => 'justifyfull', '|1' => '|', 'title' => 'formatselect', '|2' => '|', 'list1' => 'bullist', 'list2' => 'numlist', '|3' => '|', 'indent1' => 'outdent', 'indent2' => 'indent', '|4' => '|', 'quote' => 'blockquote', 'line' => 'hr', '|5' => '|', '_cut' => 'cut', '_copy' => 'copy', '_paste' => 'paste', '|6' => '|', '_undo' => 'undo', '_redo' => 'redo');
    private $array_tags2 = array('b' => 'bold', 'i' => 'italic', 'u' => 'underline', 's' => 'strikethrough', '|1' => '|', 'color1' => 'forecolor', 'color2' => 'backcolor', '|1' => '|', '|2' => '|', 'size' => 'fontsizeselect', 'font' => 'fontselect', '|3' => '|', 'sub' => 'sub', 'sup' => 'sup', '|4' => '|', 'url1' => 'link', 'url2' => 'unlink', '|5' => '|', 'img' => 'image');
    private $array_tags3 = array('emotions' => 'emotions', 'table' => 'tablecontrols',  '|2' => '|', 'image', 'anchor' => 'anchor', '_charmap' => 'charmap', '3|' => '|', '_cleanup' => 'cleanup', '_removeformat' => 'removeformat', '|4' => '|', '_search' => 'search', '_replace' => 'replace', '|5' => '|', '_fullscreen' => 'fullscreen');

    public function __construct()
    {
        parent::__construct();
    }

  	/**
	 * @desc Display the editor
	 * @return string Formated editor.
	 */
    public function display()
    {
        global $Sql, $LANG, $User;

        $template = $this->get_template();

        $template->put_all(array(
        	'PAGE_PATH' => $_SERVER['PHP_SELF'],
			'C_BBCODE_NORMAL_MODE' => false,
			'C_BBCODE_TINYMCE_MODE' => true,
			'C_UPLOAD_MANAGEMENT' => $User->check_auth(FileUploadConfig::load()->get_authorization_enable_interface_files(), AUTH_FILES),
        	'C_NOT_JS_INCLUDED' => self::$js_included,
			'EDITOR_NAME' => 'tinymce',
			'FIELD' => $this->identifier,
			'FORBIDDEN_TAGS' => implode(',', $this->forbidden_tags),
			'TINYMCE_TRIGGER' => 'tinyMCE.triggerSave();',
			'IDENTIFIER' => $this->identifier,
			'L_REQUIRE_TEXT' => $LANG['require_text'],
			'L_BB_UPLOAD' => $LANG['bb_upload']
        ));

        self::$js_included = true;

        list($theme_advanced_buttons1, $theme_advanced_buttons2, $theme_advanced_buttons3) = array('', '', '');
        foreach ($this->array_tags as $tag => $tinymce_tag) //Balises autorisées.
        {
            $tag = preg_replace('`[0-9]`', '', $tag);
            //bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,sub,sup,charmap,|,undo,redo,|,image,link,unlink,anchor
            if (!in_array($tag, $this->forbidden_tags))
            {
                $theme_advanced_buttons1 .= $tinymce_tag . ',';
            }
        }
        foreach ($this->array_tags2 as $tag => $tinymce_tag) //Balises autorisées.
        {
            $tag = preg_replace('`[0-9]`', '', $tag);
            if (!in_array($tag, $this->forbidden_tags))
            {
                $theme_advanced_buttons2 .= $tinymce_tag . ',';
            }
        }
        foreach ($this->array_tags3 as $tag => $tinymce_tag) //Balises autorisées.
        {
            $tag = preg_replace('`[0-9]`', '', $tag);
            if (!in_array($tag, $this->forbidden_tags))
            {
                $theme_advanced_buttons3 .= $tinymce_tag . ',';
            }
        }
        $template->put_all(array(
			'THEME_ADVANCED_BUTTONS1' => preg_replace('`\|(,\|)+`', '|', trim($theme_advanced_buttons1, ',')),
			'THEME_ADVANCED_BUTTONS2' => preg_replace('`\|(,\|)+`', '|', trim($theme_advanced_buttons2, ',')),
			'THEME_ADVANCED_BUTTONS3' => preg_replace('`\|(,\|)+`', '|', trim($theme_advanced_buttons3, ','))
        ));

        return $template->render();
    }
}

?>