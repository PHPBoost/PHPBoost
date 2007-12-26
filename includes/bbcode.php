<?php
/*##################################################
 *                               bbcode.php
 *                            -------------------
 *   begin                : August 01, 2005
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

$get_show = !empty($_GET['show']) ? trim($_GET['show']) : '';
if( defined('PHP_BOOST') !== true && empty($get_show) )
	exit;
elseif( !empty($get_show) )
{
	include_once('../includes/begin.php');
	define('TITLE', '');
	include_once('../includes/header_no_display.php');
}	

if( empty($get_show) )
{
	$smile_max = 28; //Nombre de smiley maximim avant affichage d'un lien vers popup.
	$smile_by_line = 5; //Smiley par ligne.

	$template->set_filenames(array(
	'bbcode' => '../templates/' . $CONFIG['theme'] . '/bbcode.tpl'
	));
	
	$field = (!isset($_field) ? 'contents' : $_field);
	$template->assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'FIELD' => $field ,
		'L_REQUIRE_TEXT' => $LANG['require_text']
	));
	
	//Chargement de la configuration.
	$cache->load_file('files');
		
	$template->assign_vars(array(	
		'UPLOAD_MANAGEMENT' => $groups->check_auth($CONFIG_FILES['auth_files'], 1) ? '<a style="font-size: 10px;" title="' . $LANG['bb_upload'] . '" href="#" onclick="window.open(\'../member/upload.php?popup=1&amp;fd=' . $field  . '\', \'\', \'height=435,width=680,resizable=yes,scrollbars=yes\');return false;"><img src="../templates/' . $CONFIG['theme'] . '/images/upload/files_add.png" alt="" /></a>' : '',
		'L_BB_SMILEYS' => $LANG['bb_smileys'],
		'L_BB_BOLD' => $LANG['bb_bold'],
		'L_BB_ITALIC' => $LANG['bb_italic'],
		'L_BB_UNDERLINE' => $LANG['bb_underline'],
		'L_BB_STRIKE' => $LANG['bb_strike'],
		'L_BB_TITLE' => $LANG['bb_title'],
		'L_BB_SUBTITLE' => $LANG['bb_subtitle'],
		'L_BB_STYLE' => $LANG['bb_style'],
		'L_BB_URL' => $LANG['bb_link'],
		'L_BB_IMG' => $LANG['bb_picture'],		
		'L_BB_QUOTE' => $LANG['bb_quote'],		
		'L_BB_HIDE' => $LANG['bb_hide'],
		'L_BB_COLOR' => $LANG['bb_color'],
		'L_BB_SIZE' => $LANG['bb_size'],
		'L_BB_SMALL' => $LANG['bb_small'],
		'L_BB_LARGE' => $LANG['bb_large'],
		'L_BB_LEFT' => $LANG['bb_left'],
		'L_BB_CENTER' => $LANG['bb_center'],
		'L_BB_RIGHT' => $LANG['bb_right'],
		'L_BB_FLOAT_LEFT' => $LANG['bb_float_left'],
		'L_BB_FLOAT_RIGHT' => $LANG['bb_float_right'],	
		'L_BB_SUP' => $LANG['bb_sup'],
		'L_BB_SUB' => $LANG['bb_sub'],
		'L_BB_INDENT' => $LANG['bb_indent'],
		'L_BB_LIST' => $LANG['bb_list'],
		'L_BB_TABLE' => $LANG['bb_table'],
		'L_BB_SWF' => $LANG['bb_swf'],
		'L_BB_MOVIE' => $LANG['bb_movie'],
		'L_BB_SOUND' => $LANG['bb_sound'],
		'L_BB_CODE' => $LANG['bb_code'],		
		'L_BB_MATH' => $LANG['bb_math'],
		'L_BB_ANCHOR' => $LANG['bb_anchor'],
		'L_BB_HELP' => $LANG['bb_help'],
		'L_TITLE' => $LANG['title'],
		'L_SUBTITLE' => $LANG['subtitle'],
		'L_STYLE' => $LANG['style'],
		'L_QUESTION' => $LANG['question'],
		'L_NOTICE' => $LANG['notice'],
		'L_WARNING' => $LANG['warning'],
		'L_ERROR' => $LANG['error'],
		'L_SUCCESS' => $LANG['success'],
		'L_SIZE' => $LANG['size']
	));
	
	//Inclusion du cache des smileys pour éviter une requête inutile.
	$cache->load_file('smileys');
	$template->assign_block_vars('show_smiley', array(
	));
		
	$height_max = 50;
	$width_max = 50;
	$nbr_smile = count($_array_smiley_code);
	$i = 1;	
	$z = 0;
	foreach($_array_smiley_code as $code_smile => $url_smile)
	{
		if( $z == $smile_max )
		{ 
			$z++;
			break;		
		}
		
		$width_source = 18; //Valeur par défaut.
		$height_source = 18;		
		
		// On recupère la hauteur et la largeur de l'image.
		list($width_source, $height_source) = @getimagesize('../images/smileys/' . $url_smile);
		if( $width_source > $width_max || $height_source > $height_max )
		{
			if( $width_source > $height_source )
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
		
		$img = '<img src="../images/smileys/' . $url_smile . '" height="' . $height . '" width="' . $width . '" alt="' . $code_smile . '" title="' . $code_smile . '" />'; 
					
		$template->assign_block_vars('smiley', array(
			'IMG' => $img,
			'CODE' => addslashes($code_smile),
			'END_LINE' => is_int($i/$smile_by_line) ? '<br />' : ''
		));	
		
		$i++;	
		$z++;
	}	

	if( $z > $smile_max ) //Lien vers tous les smiley!
	{		
		$template->assign_block_vars('more', array(
			'L_ALL_SMILEY' => $LANG['all_smiley'],
			'L_SMILEY' => $LANG['smiley']
		));
	}
}
elseif( !empty($get_show) )
{
	$_field = !empty($_GET['field']) ? $_GET['field'] : '';
	$smile_max = 28; //Nombre de smiley maximim avant affichage d'un lien vers popup.
	$smile_by_line = 4; //Smiley par ligne.

	#########################bbcode_smileys.tpl#########################
	$template->set_filenames(array(
		'bbcode_smileys' => '../templates/' . $CONFIG['theme'] . '/bbcode_smileys.tpl'
	));
	
	$template->assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'FIELD' => (!isset($_field) ? 'contents' : $_field),
		'COLSPAN' => $smile_by_line + 1,
		'L_LANGUAGE' => $CONFIG['lang'],
		'L_XML_LANGUAGE' => $LANG['xml_lang'],
		'L_TITLE' => '',		
		'L_SMILEY' => $LANG['smiley'],
		'L_CLOSE' => $LANG['close']
	));
	
	//Inclusion du cache des smileys pour éviter une requête inutile.
	$cache->load_file('smileys'); //include simple et non include_once car inclusion double avec unparse();.
	$template->assign_block_vars('show_smiley', array(
	));
	
	$height_max = 50;
	$width_max = 50;
	$nbr_smile = count($_array_smiley_code);
	$j = 0;	
	foreach($_array_smiley_code as $code_smile => $url_smile)
	{
		$width_source = 18; //Valeur par défaut.
		$height_source = 18;		
		
		// On recupère la hauteur et la largeur de l'image.
		list($width_source, $height_source) = @getimagesize('../images/smileys/' . $url_smile);
		if( $width_source > $width_max || $height_source > $height_max )
		{
			if( $width_source > $height_source )
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
		
		$img = '<img src="../images/smileys/' . $url_smile . '" height="' . $height . '" width="' . $width . '" alt="' . $code_smile . '" title="' . $code_smile . '" />'; 
		
		//On genère le tableau pour $smile_by_line colonnes
		$multiple_x = $j / $smile_by_line ;
		$tr_start = (is_int($multiple_x)) ? '<tr>' : '';
		$j++;	
		$multiple_x = $j / $smile_by_line ;
		$tr_end = (is_int($multiple_x)) ? '</tr>' : '';
		
		//Si la ligne n'est pas complète on termine par </tr>.
		if( $nbr_smile == $j )
			$tr_end = '</tr>';

		$template->assign_block_vars('smiley', array(
			'IMG' => $img,
			'CODE' => addslashes($code_smile),
			'TR_START' => $tr_start,
			'TR_END' => $tr_end,
		));	

		//Création des cellules du tableau si besoin est.
		if( $nbr_smile == $j && $nbr_smile > $smile_by_line )
		{
			while( !is_int($j / $smile_by_line) )
			{
				$template->assign_block_vars('smiley.td', array(
					'TD' => '<td>&nbsp;</td>'
				));	
				$j++;
			}
		}
	}	
	
	$template->pparse('bbcode_smileys'); 
}

if( !empty($get_show) )
{
	include_once('../includes/footer_no_display.php');
}	
?>