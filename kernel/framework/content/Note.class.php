<?php
/*##################################################
 *                              note.class.php
 *                            -------------------
 *   begin                : April 08, 2008
 *   copyright            : (C) 2008 Viarre Régis
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

define('NOTE_DISPLAY_NOTE', 0x01);
define('NOTE_NODISPLAY_NBRNOTES', 0x02);
define('NOTE_DISPLAY_BLOCK', 0x04);

/**
 * @package content
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc This class provides you an easy way to manage notation.
 */
class Note
{
	## Public Methods ##
	/**
	 * @desc Create an new object Note.
	 * @param string $script The module name in which the pagination is used.
	 * <strong>Warning</strong>The sql table name has to be the same name, otherwise you has to specify the sql table name in $script and module name in $module_folder argument
	 * @param int $idprov The id of the item in the module.
	 * @param string $script_path The script path. Example : url('file.php?id=' . $idart, 'file-' . $idart . '.php')
	 * @param int $notation_scale The notation scale
	 * @param string $module_folder (optional) The folder where the module is located. It allow you to specify a different module location
	 * @param int $options
	 */
	function Note($script, $idprov, $script_path, $notation_scale, $module_folder = '', $options = 0)
	{
		$this->module_folder = !empty($module_folder) ? TextHelper::strprotect($module_folder) : TextHelper::strprotect($script);
		$this->options = (int)$options;
		list($this->script, $this->idprov, $this->script_path, $this->notation_scale, $this->path) = array(TextHelper::strprotect($script), numeric($idprov), $script_path, $notation_scale, PATH_TO_ROOT . '/' . $this->module_folder . '/');
		$this->sql_table = $this->_get_table_module();
	}
	
	/**
	 * @desc Add a note on the item. It has to be into the notation scale.
	 * @param int $note The note given by the user.
	 * @return Return a string used in the ajax file, to update the notation. If an error occur, return an error code.
	 */
	function add($note)
	{
		global $Sql, $User;
		
		if ($User->check_level(MEMBER_LEVEL))
		{
			$check_note = ($note >= 0 && $note <= $this->notation_scale) ? true : false; //Validité de la note.
			$row_note = $Sql->query_array(PREFIX . $this->sql_table, 'users_note', 'nbrnote', 'note', "WHERE id = '" . $this->idprov . "'", __LINE__, __FILE__);
			$user_id = $User->get_attribute('user_id');
			$array_users_note = explode('/', $row_note['users_note']);
			if (!in_array($user_id, $array_users_note) && $check_note) //L'utilisateur n'a pas déjà voté, et la note est valide.
			{
				$note = (($row_note['note'] * $row_note['nbrnote']) + $note)/($row_note['nbrnote'] + 1);
				$users_note = !empty($row_note['users_note']) ? $row_note['users_note'] . '/' . $user_id : $user_id; //On ajoute l'id de l'utilisateur.
				
				$Sql->query_inject("UPDATE ".PREFIX.$this->sql_table." SET note = '" . $note . "', nbrnote = nbrnote + 1, users_note = '" . $users_note . "' WHERE id = '" . $this->idprov . "'", __LINE__, __FILE__);
				
				return 'get_note = ' . $note . ';get_nbrnote = ' . ($row_note['nbrnote'] + 1) . ';';
			}
			else
				return -1;
		}
		else
			return -2;
	}
	
	/**
	 * @desc Display the ajax notation form.
	 * @param object $template Template object to use another template file.
	 * @return string the parsed template.
	 */
	function display_form($template = false)
	{
		global $CONFIG, $Sql, $LANG, $Session;
		
		$note = !empty($_POST['note']) ? numeric($_POST['note']) : 0;
		$path_redirect = $this->path . sprintf(str_replace('&amp;', '&', $this->script_path), 0);

		//Notes chargées?
		if ($this->_note_loaded()) //Utilisateur connecté.
		{
			if (!is_object($template) || strtolower(get_class($template)) != 'template')
			$template = new template('framework/note.tpl');
			
			###########################Insertion##############################
			if (!empty($_POST['valid_note']))
			{
				if (!empty($note))
					$this->add($note); //Ajout de la note.
				
				AppContext::get_response()->redirect($path_redirect);
			}
			else
			{
				###########################Affichage##############################
				$row_note = $Sql->query_array(PREFIX . $this->sql_table, 'users_note', 'nbrnote', 'note', "WHERE id = '" . $this->idprov . "'", __LINE__, __FILE__);
					
				//Génération de l'échelle de notation pour ceux ayant le javascript désactivé.
				$select = '<option value="-1" selected="selected">' . $LANG['note'] . '</option>';
				for ($i = 0; $i <= $this->notation_scale; $i++)
					$select .= '<option value="' . $i . '">' . $i . '</option>';
					
				### Notation Ajax ###
				$row_note['note'] = (round($row_note['note'] / 0.25) * 0.25);
				
				$l_note = ($this->options & NOTE_DISPLAY_NOTE) !== 0 ? '<strong>' . $LANG['note'] . ':</strong>&nbsp;' : ''; //Affichage du titre devant la note.
				$display = ($this->options & NOTE_DISPLAY_BLOCK) !== 0 ? 'block' : 'inline'; //Affichage en bloc ou en inline suivant besoin.
				$width = ($this->options & NOTE_DISPLAY_BLOCK) !== 0 ? 'width:' . ($this->notation_scale*16) . 'px;margin:auto;' : '';
				
				$ajax_note = '<div style="' . $width . 'display:none" id="note_stars' . $this->idprov . '" onmouseout="out_div(' . $this->idprov . ', array_note[' . $this->idprov . '])" onmouseover="over_div()">';
				for ($i = 1; $i <= $this->notation_scale; $i++)
				{
					$star_img = 'stars.png';
					if ($row_note['note'] < $i)
					{
						$decimal = $i - $row_note['note'];
						if ($decimal >= 1)
							$star_img = 'stars0.png';
						elseif ($decimal >= 0.75)
							$star_img = 'stars1.png';
						elseif ($decimal >= 0.50)
							$star_img = 'stars2.png';
						else
							$star_img = 'stars3.png';
					}
					$ajax_note .= '<a href="javascript:send_note(' . $this->idprov . ', ' . $i . ', \'' . $Session->get_token() . '\')" onmouseover="select_stars(' . $this->idprov . ', ' . $i . ');"><img src="../templates/'. get_utheme() . '/images/' . $star_img . '" alt="" class="valign_middle" id="n' . $this->idprov . '_stars' . $i . '" /></a>';
				}
				if (($this->options & NOTE_NODISPLAY_NBRNOTES) !== 0) //Affichage du nombre de votant.
					$ajax_note .= '</div> <span id="noteloading' . $this->idprov . '"></span>';
				else
					$ajax_note .= '</div> <span id="noteloading' . $this->idprov . '"></span> <div style="display:' . $display . '" id="nbrnote' . $this->idprov . '">(' . $row_note['nbrnote'] . ' ' . (($row_note['nbrnote'] > 1) ? strtolower($LANG['notes']) : strtolower($LANG['note'])) . ')</div>';
				
				$template->assign_vars(array(
					'C_JS_NOTE' => !defined('HANDLE_NOTE'),
					'ID' => $this->idprov,
					'NOTE_MAX' => $this->notation_scale,
					'SELECT' => $select,
					'NOTE' => $l_note . '<span id="note_value' . $this->idprov . '">' . (($row_note['nbrnote'] > 0) ? '<strong>' . $row_note['note'] . '</strong>' : '<em>' . $LANG['no_note'] . '</em>') . '</span>' . $ajax_note,
					'ARRAY_NOTE' => 'array_note[' . $this->idprov . '] = \'' . $row_note['note'] . '\';',
					'DISPLAY' => $display,
					'L_AUTH_ERROR' => addslashes($LANG['e_auth']),
					'L_ALERT_ALREADY_VOTE' => addslashes($LANG['already_vote']),
					'L_ALREADY_VOTE' => '',
					'L_NOTE' => addslashes($LANG['note']),
					'L_NOTES' => addslashes($LANG['notes']),
					'L_VALID_NOTE' => $LANG['valid_note']
				));
			}
            
			if (!defined('HANDLE_NOTE'))
				define('HANDLE_NOTE', true);
            
			return $template->parse(Template::TEMPLATE_PARSER_STRING);
		}
		else
        {
            global $Errorh;
            $Errorh->handler('e_unexist_page', E_USER_REDIRECT);
        }
	}
	
	/**
	 * @desc Static method which display the notation with images, you can restrain the number of images displayed with the argument $num_stars_display
	 * @param int $note The note of the item.
	 * @param int $notation_scale Notation scale
	 * @param int $num_stars_display Number of image to display.
	 * @return string The notation with images.
	 * @static
	 */
	/* static */ function display_img($note, $notation_scale, $num_stars_display = 0)
	{
		global $CONFIG;
		
		if ($notation_scale == 0)
			return '';
			
		$display_note = '';
		if ($num_stars_display > 0)
		{
			$note *= $num_stars_display / $notation_scale;
			$notation_scale = $num_stars_display;
		}
		for ($i = 1; $i <= $notation_scale; $i++)
		{
			$star_img = 'stars.png';
			if ($note < $i)
			{
				$decimal = $i - $note;
				if ($decimal >= 1)
					$star_img = 'stars0.png';
				elseif ($decimal >= 0.75)
					$star_img = 'stars1.png';
				elseif ($decimal >= 0.50)
					$star_img = 'stars2.png';
				else
					$star_img = 'stars3.png';
			}
			$display_note .= '<img src="../templates/'. get_utheme() . '/images/' . $star_img . '" alt="" class="valign_middle" />';
		}
		
		return $display_note;
	}
	
	/**
	 * @desc Accessor
	 * @param string $varname
	 * @return unknown_type
	 */
	function get_attribute($varname)
	{
		return $this->$varname;
	}
	
	## Private Methods ##
	/**
	 * @desc Check if the notation system is correctly loaded.
	 * @return bool
	 */
	function _note_loaded()
	{
		global $Errorh;
		
		if (empty($this->sql_table)) //Erreur avec le module non prévu pour gérer les commentaires.
			$Errorh->handler('e_unexist_page', E_USER_REDIRECT);
		
		return (!empty($this->script) && !empty($this->idprov) && !empty($this->script_path));
	}
	
	/**
	 * @desc Get the sql table of the associated module
	 * @return string The sql table of the associated module
	 */
	function _get_table_module()
	{
		global $Sql, $CONFIG;

		//Récupération des informations sur le module.
		$info_module = load_ini_file(PATH_TO_ROOT . '/' . $this->module_folder . '/lang/', get_ulang());
		$check_script = false;
		if (isset($info_module['note']))
		{
			if ($info_module['note'] == $this->script)
			{
				$idprov = $Sql->query("SELECT id FROM ".PREFIX.$info_module['note']." WHERE id = '" . $this->idprov . "'", __LINE__, __FILE__);
				if ($idprov == $this->idprov)
					$check_script = true;
			}
		}
		
		return $check_script ? $info_module['note'] : '0';
	}
	
	## Private attributes ##
	var $script = '';
	var $idprov = 0;
	var $path = '';
	var $script_path = '';
	var $module_folder = '';
	var $options = '';
	var $sql_table = '';
	var $notation_scale = '';
}

?>