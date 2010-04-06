<?php
/**
*
*
*
*/

import('content/editor/editor');

class BBcode_editor2 extends ContentEditor
{
	function __construct()
	{
        parent::ContentEditor();
	}
	
	function BBcode_editor2()
    {
		self::__construct();
    }
	
	function lang_get($value)
	{
		global $LANG;
		
		if (is_string($value)) {
			if (!empty($LANG[$value]))
				return addslashes($LANG[$value]);
			return addslashes($value);
		}
		return 'invalid_value';
	}

    /**
	 * @desc Display the editor
	 * @return string Formated editor.
	 */
 	function display()
    {
        global $CONFIG, $Sql, $LANG, $Cache, $User, $CONFIG_UPLOADS, $_array_smiley_code;
		
		$fname = 'bbcode_editor/editor2.tpl';
        $template = new Template($fname);

        //Chargement de la configuration.
        $Cache->load('uploads');
        $Cache->load('smileys');

        $template->assign_vars(array(
        	'PAGE_PATH' => $_SERVER['PHP_SELF'],
			'C_BBCODE_TINYMCE_MODE' => false,
			'C_BBCODE_NORMAL_MODE' => true,
			'C_EDITOR_NOT_ALREADY_INCLUDED' => !defined('EDITOR_ALREADY_INCLUDED'),
			'EDITOR_NAME' => 'bbcode',
			'FIELD' => $this->identifier,
			'FORBIDDEN_TAGS' => !empty($this->forbidden_tags) ? implode(',', $this->forbidden_tags) : '',
			'C_UPLOAD_MANAGEMENT' => $User->check_auth($CONFIG_UPLOADS['auth_files'], AUTH_FILES),
			'L_REQUIRE_TEXT' => $this->lang_get('require_text'),
			'L_BB_UPLOAD' => $this->lang_get('bb_upload'),
			'L_BB_SMILEYS' => $this->lang_get('bb_smileys'),
			'L_BB_BOLD' => $this->lang_get('bb_bold'),
			'L_BB_ITALIC' => $this->lang_get('bb_italic'),
			'L_BB_UNDERLINE' => $this->lang_get('bb_underline'),
			'L_BB_STRIKE' => $this->lang_get('bb_strike'),
			'L_BB_TITLE' => $this->lang_get('bb_title'),
			'L_BB_CONTAINER' => $this->lang_get('bb_container'),
			'L_BB_HTML' => $this->lang_get('bb_html'),
			'L_BB_STYLE' => $this->lang_get('bb_style'),
			'L_BB_URL' => $this->lang_get('bb_link'),
			'L_BB_IMG' => $this->lang_get('bb_picture'),
			'L_BB_QUOTE' => $this->lang_get('bb_quote'),
			'L_BB_HIDE' => $this->lang_get('bb_hide'),
			'L_BB_COLOR' => $this->lang_get('bb_color'),
			'L_BB_SIZE' => $this->lang_get('bb_size'),
			'L_BB_SMALL' => $this->lang_get('bb_small'),
			'L_BB_LARGE' => $this->lang_get('bb_large'),
			'L_BB_LEFT' => $this->lang_get('bb_left'),
			'L_BB_CENTER' => $this->lang_get('bb_center'),
			'L_BB_RIGHT' => $this->lang_get('bb_right'),
			'L_BB_JUSTIFY' => $this->lang_get('bb_justify'),
			'L_BB_FLOAT_LEFT' => $this->lang_get('bb_float_left'),
			'L_BB_FLOAT_RIGHT' => $this->lang_get('bb_float_right'),
			'L_BB_SUP' => $this->lang_get('bb_sup'),
			'L_BB_SUB' => $this->lang_get('bb_sub'),
			'L_BB_INDENT' => $this->lang_get('bb_indent'),
			'L_BB_LIST' => $this->lang_get('bb_list'),
			'L_BB_TABLE' => $this->lang_get('bb_table'),
			'L_BB_SWF' => $this->lang_get('bb_swf'),
			'L_BB_MOVIE' => $this->lang_get('bb_movie'),
			'L_BB_SOUND' => $this->lang_get('bb_sound'),
			'L_BB_CODE' => $this->lang_get('bb_code'),
			'L_BB_MATH' => $this->lang_get('bb_math'),
			'L_BB_ANCHOR' => $this->lang_get('bb_anchor'),
			'L_BB_HELP' => $this->lang_get('bb_help'),
			'L_URL_PROMPT' => $this->lang_get('bb_url_prompt'),
			'L_TITLE' => $this->lang_get('title'),
			'L_CONTAINER' => $this->lang_get('bb_container'),
			'L_BLOCK' => $this->lang_get('bb_block'),
			'L_FIELDSET' => $this->lang_get('bb_fieldset'),
			'L_STYLE' => $this->lang_get('style'),
			'L_QUESTION' => $this->lang_get('question'),
			'L_NOTICE' => $this->lang_get('notice'),
			'L_WARNING' => $this->lang_get('warning'),
			'L_ERROR' => $this->lang_get('error'),
			'L_SUCCESS' => $this->lang_get('success'),
			'L_SIZE' => $this->lang_get('size'),
			'L_CODE' => $this->lang_get('code'),
			'L_TEXT' => $this->lang_get('bb_text'),
			'L_SCRIPT' => $this->lang_get('bb_script'),
			'L_WEB' => $this->lang_get('bb_web'),
			'L_PROG' => $this->lang_get('bb_prog'),
			'L_TABLE_HEAD' => $this->lang_get('head_table'),
			'L_ADD_HEAD' => $this->lang_get('head_add'),
			'L_LINES' => $this->lang_get('lines'),
			'L_COLS' => $this->lang_get('cols'),
			'L_ORDERED_LIST' => $this->lang_get('ordered_list'),
			'L_INSERT_LIST' => $this->lang_get('insert_list'),
			'L_INSERT_TABLE' => $this->lang_get('insert_table'),
			'L_PHPBOOST_LANGUAGES' => $this->lang_get('phpboost_languages')
        ));
		
		$this->forbidden_tags = array('b');

        foreach ($this->forbidden_tags as $forbidden_tag) //Balises interdite.
        {
            if ($forbidden_tag == 'fieldset')
            	$forbidden_tag = 'block';
            	
        	$template->assign_vars(array(
				'DISABLED_' . strtoupper($forbidden_tag) => 1
			));
        }

        //Inclusion du cache des smileys pour éviter une requête inutile.
        $Cache->load('smileys');

        $smile_max = 28; //Nombre de smiley maximim avant affichage d'un lien vers popup.

        $height_max = 50;
        $width_max = 50;
        $nbr_smile = count($_array_smiley_code);
        $z = 0;
        foreach ($_array_smiley_code as $code_smile => $url_smile)
        {
            if ($z < $smile_max) $z++; else break;
             
            $width_source = 18; //Valeur par défaut.
            $height_source = 18;
             
            // On recupère la hauteur et la largeur de l'image.
            list($width_source, $height_source) = @getimagesize(PATH_TO_ROOT . '/images/smileys/' . $url_smile);
            if ($width_source > $width_max || $height_source > $height_max)
            {
                if ($width_source > $height_source)
                {
                    $ratio = $width_source / $height_source;
                    $width = $width_max;
                    $height = $width / $ratio;
                }
                else
                {
                    $ratio = $height_source / $width_source;
                    $height = $height_max;
                    $width = $height / $ratio;
                }
            }
            else
            {
                $width = $width_source;
                $height = $height_source;
            }
             
            $template->assign_block_vars('smileys', array(
				'URL' => $url_smile,
				'HEIGHT' => $height,
				'WIDTH' => $width,
				'CODE' => addslashes($code_smile),
				));
        }

        if ($z > $smile_max) //Lien vers tous les smiley!
        {
            $template->assign_vars(array(
				'C_BBCODE_SMILEY_MORE' => true,
				'L_ALL_SMILEY' => $this->lang_get('all_smiley'),
				'L_SMILEY' => $this->lang_get('smiley')
            ));
        }

        defined('EDITOR_ALREADY_INCLUDED') || define('EDITOR_ALREADY_INCLUDED', true); // On installe une seule fois

        return $template->parse(TEMPLATE_STRING_MODE);
    }
}

?>