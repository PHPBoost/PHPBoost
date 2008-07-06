<?php
/*##################################################
*                             tinymce_parser.class.php
*                            -------------------
*   begin                : July 5 2008
*   copyright          : (C) 2008 Régis Viarre
*   email                :  crowkait@phpboost.com
*
*   
###################################################
*
*   This program is free software; you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation; either version 2 of the License, or
*   (at your option) any later version.
* 
*  This program is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  You should have received a copy of the GNU General Public License
*  along with this program; if not, write to the Free Software
*  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*
###################################################*/

include_once(PATH_TO_ROOT . '/kernel/framework/content/editor.class.php');

class TinyMCEEditor extends ContentEditor
{
	function TinyMCEEditor()
	{
		parent::ContentEditor();
	}
	
	//Affiche le formulaire
	function display()
	{
		global $CONFIG, $Sql, $LANG, $Cache, $Member, $CONFIG_FILES;
		
		if( !is_object($this->template) || get_class($this->template) != 'Template' )
			$Template = new Template('framework/content/editor.tpl');
		
		//Chargement de la configuration.
		$Cache->Load_file('files');
		
		$Template->Assign_vars(array(
			'C_BBCODE_NORMAL_MODE' => false,
			'C_BBCODE_TINYMCE_MODE' => true,
			'C_EDITOR_NOT_ALREADY_INCLUDED' => !defined('EDITOR_ALREADY_INCLUDED'),
			'FIELD' => $this->identifier,
			'FORBIDDEN_TAGS' => implode(',', $this->forbidden_tags),
			'TINYMCE_TRIGGER' => 'TinyMCE.prototype.triggerSave();',
			'UPLOAD_MANAGEMENT' => $Member->Check_auth($CONFIG_FILES['auth_files'], AUTH_FILES) ? '<div style="float:right;margin-left:5px;"><a style="font-size: 10px;" title="' . $LANG['bb_upload'] . '" href="#" onclick="window.open(\'' . PATH_TO_ROOT . '/member/upload.php?popup=1&amp;fd=' . $this->identifier  . '\', \'\', \'height=500,width=720,resizable=yes,scrollbars=yes\');return false;"><img src="' . PATH_TO_ROOT . '/templates/' . $CONFIG['theme'] . '/images/upload/files_add.png" alt="" /></a></div>' : '',
			'L_REQUIRE_TEXT' => $LANG['require_text']
		));
		
		list($theme_advanced_buttons1, $theme_advanced_buttons2) = array('', '');
		foreach($this->array_tags as $tag => $tinymce_tag) //Balises autorisées.
		{		
				//bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,sub,sup,charmap,|,undo,redo,|,image,link,unlink,anchor
			if( !in_array($tag, $this->forbidden_tags) )
				$theme_advanced_buttons1 .= $tinymce_tag . ',';
		}
		foreach($this->array_tags2 as $tag => $tinymce_tag) //Balises autorisées.
		{		
			if( !in_array($tag, $this->forbidden_tags) )
				$theme_advanced_buttons2 .= $tinymce_tag . ',';
		}
		$Template->Assign_vars(array( 
			'THEME_ADVANCED_BUTTONS1' => trim($theme_advanced_buttons1, ','),
			'THEME_ADVANCED_BUTTONS2' => trim($theme_advanced_buttons2, ',')
		));
		
		if( !defined('EDITOR_ALREADY_INCLUDED') ) //Editeur déjà includé.
			define('EDITOR_ALREADY_INCLUDED', true);
		
		return $Template->parse(TEMPLATE_STRING_MODE);
	}
	
	//Private attribute.
	var $array_tags = array('b' => 'bold', 'i' => 'italic', 'u' => 'underline', 's' => 'strikethrough', '|1' => '|', 'align' => 'justifyleft', 'align' => 'justifycenter', 'align' => 'justifyright', 'align' => 'justifyfull', '|2' => '|', 'list' => 'bullist', 'list' => 'numlist', '|3' => '|', 'sub' => 'sub', 'sup' => 'sup', '_charmap' => 'charmap', '|4' => '|', '_undo' => 'undo', '_redo' => 'redo', '|5' => '|',  'img' => 'image', 'url' => 'link', 'url' => 'unlink', 'anchor' => 'anchor');
	var $array_tags2 = array('color' => 'forecolor', 'color' => 'backcolor', '|1' => '|', 'indent' => 'outdent', 'indent' => 'indent', '|2' => '|', 'size' => 'fontsizeselect', 'title' => 'formatselect', '|3' => '|', '_cleanup' => 'cleanup', '_removeformat' => 'removeformat', '|4' => '|', 'table' => 'table', 'table' => 'split_cells', 'table' => 'merge_cells', '|5' => '|', 'swf' => 'flash');
}

?>