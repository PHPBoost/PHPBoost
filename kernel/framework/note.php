<?php
/*##################################################
 *                               note.php
 *                            -------------------
 *   begin                : April 08, 2008
 *   copyright          : (C) 2008 Viarre Régis
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

$note = !empty($_POST['note']) ? numeric($_POST['note']) : 0;
$path_redirect = $Note->Get_attribute('path') . sprintf(str_replace('&amp;', '&', $Note->Get_attribute('vars')), 0);

//Notes chargées?
if( $Note->Note_loaded() ) //Utilisateur connecté.
{
	$Template->Set_filenames(array(
		'handle_note'=> 'note.tpl'
	));
	
	###########################Insertion##############################
	if( !empty($_POST['valid_note']) )
	{
		if( !empty($note) )
			$Note->Add_note($note); //Ajout de la note.
		
		redirect($path_redirect);
	}
	else
	{
		###########################Affichage##############################
		$row_note = $Sql->Query_array($Note->Get_attribute('sql_table'), 'users_note', 'nbrnote', 'note', "WHERE id = '" . $Note->Get_attribute('idprov') . "'", __LINE__, __FILE__);
			
		//Génération de l'échelle de notation pour ceux ayant le javascript désactivé.
		$select = '<option value="-1" selected="selected">' . $LANG['note'] . '</option>';
		for( $i = 0; $i <= $Note->Get_attribute('notation_scale'); $i++)
			$select .= '<option value="' . $i . '">' . $i . '</option>';
			
		### Notation Ajax ###
		$row_note['note'] = (round($row_note['note'] / 0.25) * 0.25);
		
		$l_note = ($Note->Get_attribute('options') & NOTE_DISPLAY_NOTE) !== 0 ? '<strong>' . $LANG['note'] . ':</strong>&nbsp;' : ''; //Affichage du titre devant la note.
		$display = ($Note->Get_attribute('options') & NOTE_DISPLAY_BLOCK) !== 0 ? 'block' : 'inline'; //Affichage en bloc ou en inline suivant besoin.
		$width = ($Note->Get_attribute('options') & NOTE_DISPLAY_BLOCK) !== 0 ? 'width:' . ($Note->Get_attribute('notation_scale')*16) . 'px;margin:auto;' : '';
		
		$ajax_note = '<div style="' . $width . 'display:none" id="note_stars' . $Note->Get_attribute('idprov') . '" onmouseout="out_div(' . $Note->Get_attribute('idprov') . ', array_note[' . $Note->Get_attribute('idprov') . '])" onmouseover="over_div()">';
		for($i = 1; $i <= $Note->Get_attribute('notation_scale'); $i++)
		{
			$star_img = 'stars.png';
			if( $row_note['note'] < $i )
			{							
				$decimal = $i - $row_note['note'];
				if( $decimal >= 1 )
					$star_img = 'stars0.png';
				elseif( $decimal >= 0.75 )
					$star_img = 'stars1.png';
				elseif( $decimal >= 0.50 )
					$star_img = 'stars2.png';
				else
					$star_img = 'stars3.png';
			}			
			$ajax_note .= '<a href="javascript:send_note(' . $Note->Get_attribute('idprov') . ', ' . $i . ')" onmouseover="select_stars(' . $Note->Get_attribute('idprov') . ', ' . $i . ');"><img src="../templates/'. $CONFIG['theme'] . '/images/' . $star_img . '" alt="" class="valign_middle" id="' . $Note->Get_attribute('idprov') . '_stars' . $i . '" /></a>';
		}
		if( ($Note->Get_attribute('options') & NOTE_NODISPLAY_NBRNOTES) !== 0 ) //Affichage du nombre de votant.
			$ajax_note .= '</div> <span id="noteloading' . $Note->Get_attribute('idprov') . '"></span>';
		else
			$ajax_note .= '</div> <span id="noteloading' . $Note->Get_attribute('idprov') . '"></span> <div style="display:' . $display . '" id="nbrnote' . $Note->Get_attribute('idprov') . '">(' . $row_note['nbrnote'] . ' ' . (($row_note['nbrnote'] > 1) ? strtolower($LANG['notes']) : strtolower($LANG['note'])) . ')</div>';
		
		$Template->Assign_vars(array(
			'C_JS_NOTE' => !defined('HANDLE_NOTE'),
			'ID' => $Note->Get_attribute('idprov'),
			'NOTE_MAX' => $Note->Get_attribute('notation_scale'),
			'SELECT' => $select,
			'NOTE' => $l_note . '<span id="note_value' . $Note->Get_attribute('idprov') . '">' . (($row_note['nbrnote'] > 0) ? '<strong>' . $row_note['note'] . '</strong>' : '<em>' . $LANG['no_note'] . '</em>') . '</span>' . $ajax_note,
			'ARRAY_NOTE' => 'array_note[' . $Note->Get_attribute('idprov') . '] = \'' . $row_note['note'] . '\';',
			'DISPLAY' => $display,
			'L_AUTH_ERROR' => addslashes($LANG['e_auth']),
			'L_ALERT_ALREADY_VOTE' => addslashes($LANG['already_vote']),
			'L_ALREADY_VOTE' => '',
			'L_NOTE' => addslashes($LANG['note']),
			'L_NOTES' => addslashes($LANG['notes']),
			'L_VALID_NOTE' => $LANG['valid_note']
		));
	}

	if( !defined('HANDLE_NOTE') )
		define('HANDLE_NOTE', true);	
}
else 
	$Errorh->Error_handler('e_unexist_page', E_USER_REDIRECT); 

?>