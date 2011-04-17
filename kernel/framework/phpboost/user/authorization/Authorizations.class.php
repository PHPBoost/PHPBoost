<?php
/*##################################################
 *                          autorizations.class.php
 *                            -------------------
 *   begin                : July 26 2008
 *   copyright            : (C) 2008 Viarre R�gis / Sautel Benoit
 *   email                : crowkait@phpboost.com / ben.popeye@phpboost.com
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
 * @author R�gis VIARRE <crowkait@phpboost.com> / Sautel Benoit <ben.popeye@phpboost.com>
 * @desc This class contains only static methods, it souldn't be instantiated.
 * @package {@package}
 * @deprecated
 */
class Authorizations
{
	const AUTH_PARENT_PRIORITY = 0x01;	// Generally read mode
	const AUTH_CHILD_PRIORITY = 0x02;	// Generally write mode

	/**
	 * @desc Returns an array with the authorizations given by variable number of arrays passed in argument.
	 * This returned array is used to be serialized.
	 * @return array The array of authorizations.
	 * @static
	 */
	public static function build_auth_array_from_form()
	{
		$array_auth_all = array();
		$sum_auth = 0;
		$nbr_arg = func_num_args();

		//Si le nom du formulaire select est pass� en param�tre, c'est le dernier
		$idselect = '';
		if (gettype(func_get_arg($nbr_arg - 1)) == 'string')
		{
			$idselect = func_get_arg(--$nbr_arg);
		}

		//R�cup�ration du dernier argument, si ce n'est pas un tableau => bool�en demandant la s�lection par d�faut de l'admin.
		$admin_auth_default = true;
		if ($nbr_arg > 1)
		{
			$admin_auth_default = func_get_arg($nbr_arg - 1);
			if (!is_bool($admin_auth_default))
				$admin_auth_default = true;
			else
				$nbr_arg--; //On diminue de 1 le nombre d'argument, car le denier est le flag.
		}
		//On balaye les tableaux pass�s en argument.
		for ($i = 0; $i < $nbr_arg; $i++)
			self::get_auth_array(func_get_arg($i), $idselect, $array_auth_all, $sum_auth);

		ksort($array_auth_all); //Tri des cl�s du tableau par ordre alphab�tique, question de lisibilit�.

		return $array_auth_all;
	}

	/**
	 * @desc Returns an array with the authorizations given by variable number of arrays passed in argument.
	 * @param int $bit_value The bit emplacement in the authorization array.
	 * @param string $idselect Html id of the html select field of authorizations (in most case the same value as $bit_value).
	 * @param boolean $admin_auth_default Give authorization for the administrator by default.
	 * @return Array with the authorization for the bit specified.
	* @static
	 */
	public static function auth_array_simple($bit_value, $idselect, $admin_auth_default = true)
	{
		$array_auth_all = array();
		$sum_auth = 0;

		//R�cup�ration du tableau des autorisation.
		self::get_auth_array($bit_value, $idselect, $array_auth_all, $sum_auth);

		//Admin tous les droits dans n'importe quel cas.
		if ($admin_auth_default)
		{
			$array_auth_all['r2'] = $sum_auth;
		}
		ksort($array_auth_all); //Tri des cl�es du tableau par ordre alphab�tique, question de lisibilit�.

		return $array_auth_all;
	}

	/**
	 * @desc Generate a multiple select field for the form which create authorization for ranks, groups and members.
	 * @param int $auth_bit The bit emplacement used to set it.
	 * @param array $array_auth Array of authorization, allow you to select value authorized for this bit.
	 * @param array $array_ranks_default Array of ranks selected by default.
	 * @param string $idselect Html id used for the select.
	 * @param int $disabled Disabled all option for the select. Set to 1 for disable.
	 * @param boolean $disabled_advanced_auth Disable advanced authorizations.
	 * @return String The formated select.
     * @static
	 */
	public static function generate_select($auth_bit, $array_auth = array(), $array_ranks_default = array(), $idselect = '', $disabled = '', $disabled_advanced_auth = false)
    {
        global $Sql, $LANG, $array_ranks;

        //R�cup�ration du tableau des rangs.
		$array_ranks = is_array($array_ranks) ?
			$array_ranks :
			array(
				'-1' => $LANG['guest'],
				'0' => $LANG['member'],
				'1' => $LANG['modo'],
				'2' => $LANG['admin']
			);

		//Identifiant du select, par d�faut la valeur du bit de l'autorisation.
		$idselect = ((string)$idselect == '') ? $auth_bit : $idselect;

		$tpl = new FileTemplate('framework/groups_auth.tpl');

		$tpl->put_all(array(
			'C_NO_ADVANCED_AUTH' => ($disabled_advanced_auth) ? true : false,
			'C_ADVANCED_AUTH' => ($disabled_advanced_auth) ? false : true,
            'THEME' => get_utheme(),
            'PATH_TO_ROOT' => TPL_PATH_TO_ROOT,
			'IDSELECT' => $idselect,
			'DISABLED_SELECT' => (empty($disabled) ? 'if (disabled == 0)' : ''),
			'L_USERS' => $LANG['member_s'],
			'L_ADD_USER' => $LANG['add_member'],
			'L_REQUIRE_PSEUDO' => addslashes($LANG['require_pseudo']),
			'L_RANKS' => $LANG['ranks'],
            'L_GROUPS' => $LANG['groups'],
            'L_GO' => $LANG['go'],
			'L_ADVANCED_AUTHORIZATION' => $LANG['advanced_authorization'],
			'L_SELECT_ALL' => $LANG['select_all'],
			'L_SELECT_NONE' => $LANG['select_none'],
			'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple']
        ));

		##### G�n�ration d'une liste � s�lection multiple des rangs et membres #####
		//Liste des rangs
        $j = -1;
        foreach ($array_ranks as $idrank => $group_name)
        {
           	//Si il s'agit de l'administrateur, il a automatiquement l'autorisation
        	if ($idrank == 2)
        	{
        		$tpl->assign_block_vars('ranks_list', array(
					'ID' => $j,
					'IDRANK' => $idrank,
					'RANK_NAME' => $group_name,
					'DISABLED' => '',
					'SELECTED' => ' selected="selected"'
				));
        	}
        	else
        	{
	            $selected = '';
	            if ( array_key_exists('r' . $idrank, $array_auth) && ((int)$array_auth['r' . $idrank] & (int)$auth_bit) !== 0 && empty($disabled))
	            {
	                $selected = ' selected="selected"';
	            }
	            $selected = (isset($array_ranks_default[$idrank]) && $array_ranks_default[$idrank] === true && empty($disabled)) ? 'selected="selected"' : $selected;

				$tpl->assign_block_vars('ranks_list', array(
					'ID' => $j,
					'IDRANK' => $idrank,
					'RANK_NAME' => $group_name,
					'DISABLED' => (!empty($disabled) ? 'disabled = "disabled" ' : ''),
					'SELECTED' => $selected
				));
        	}
			$j++;
        }

        //Liste des groupes.
        foreach (GroupsService::get_groups_names() as $idgroup => $group_name)
        {
            $selected = '';
            if (array_key_exists($idgroup, $array_auth) && ((int)$array_auth[$idgroup] & (int)$auth_bit) !== 0 && empty($disabled))
            {
                $selected = ' selected="selected"';
            }

            $tpl->assign_block_vars('groups_list', array(
				'IDGROUP' => $idgroup,
				'GROUP_NAME' => $group_name,
				'DISABLED' => $disabled,
				'SELECTED' => $selected
			));
        }

		##### G�n�ration du formulaire pour les autorisations membre par membre. #####
		//Recherche des membres autoris�.
		$array_auth_members = array();
		if (is_array($array_auth))
		{
			foreach ($array_auth as $type => $auth)
			{
				if (substr($type, 0, 1) == 'm')
				{
					if (array_key_exists($type, $array_auth) && ((int)$array_auth[$type] & (int)$auth_bit) !== 0)
						$array_auth_members[$type] = $auth;
				}
			}
		}
		$advanced_auth = count($array_auth_members) > 0;

		$tpl->put_all(array(
			'ADVANCED_AUTH_STYLE' => ($advanced_auth ? 'display:block;' : 'display:none;'),
			'C_ADVANCED_AUTH_OPEN' => $advanced_auth
		));

		//Listing des membres autoris�s.
		if ($advanced_auth)
		{
			$result = $Sql->query_while("SELECT user_id, login
			FROM " . PREFIX . "member
			WHERE user_id IN(" . implode(str_replace('m', '', array_keys($array_auth_members)), ', ') . ")", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				 $tpl->assign_block_vars('members_list', array(
					'USER_ID' => $row['user_id'],
					'LOGIN' => $row['login']
				));
			}
			$Sql->query_close($result);
		}

        return $tpl->render();
    }

    /**
	 * @desc Check authorizations for a member, a group or a rank
	 * @param int $type Type of check, used RANK_TYPE for ranks, GROUP_TYPE for groups and USER_TYPE for users.
	 * @param int $value Value int the authorization array to check.
	 * @param array $array_auth Array of authorization.
	 * @param int $bit Bit emplacement for the check
	 * @return boolean True if authorized, false otherwise.
	 * @static
	 */
	public static function check_auth($type, $value, $array_auth, $bit)
	{
		if (!is_int($value))
			return false;

		switch ($type)
		{
			case RANK_TYPE:
				if ($value <= 2 && $value >= -1)
					return @$array_auth['r' . $value] & $bit;
				else
					return false;
			case GROUP_TYPE:
				if ($value >= 1)
					return !empty($array_auth[$value]) ? $array_auth[$value] & $bit : false;
				else
					return false;
			case USER_TYPE:
				if ($value >= 1)
					return !empty($array_auth['m' . $value]) ? $array_auth['m' . $value] & $bit : false;
				else
					return false;
			default:
				return false;
		}
	}

	/**
	 * @desc Merge two authorizations array, first is the parent, second is the inherited child.
	 * @param array $parent Array of authorizations.
	 * @param array $child Array of authorizations.
	 * @param int $auth_bit Bit emplacement for the merge.
	 * @param int $mode Mode used for the merge. Use Authorizations::AUTH_PARENT_PRIORITY to give to the parent the priority for the authorization, Authorizations::AUTH_CHILD_PRIORITY otherwise.
	 * @return array The new array merged.
	 * @static
	 */
	public static function merge_auth($parent, $child, $auth_bit, $mode)
	{
		//Parcours des diff�rents types d'utilisateur
		$merged = array();

		if (!is_array($child))
		{
			return $parent;
		}

		if ($mode == self::AUTH_PARENT_PRIORITY)
		{
			foreach ($parent as $key => $value)
			{
				if ($bit = ($value & $auth_bit))
				{
					if (!empty($child[$key]))
					{
						$merged[$key] = $auth_bit;
					}
					else
					{
						$merged[$key] = 0;
					}
				}
				else
				{
					$merged[$key] = $bit;
				}
			}
		}
		elseif ($mode == self::AUTH_CHILD_PRIORITY)
		{
			foreach ($parent as $key => $value)
			{
				$merged[$key] = $value & $auth_bit;
			}
			foreach ($child as $key => $value)
			{
				$merged[$key] = $value & $auth_bit;
			}
		}
		return $merged;
	}

	/**
	 * @desc Capture authorizations and shift a particular bit to an another bit (1 is used by default).
	 * @param array $auth Array of authorizations.
	 * @param int $original_bit The bit to shift.
	 * @param int $final_bit Bit distination (1 is used by default).
	 * @return array The new authorization array.
	 * @static
	 */
	public static function capture_and_shift_bit_auth($auth, $original_bit, $final_bit = 1)
	{
		if ($final_bit == 0)
			die('<strong>Error :</strong> The destination bit must not be void.');

		$result = $auth;

		if ($original_bit > $final_bit)
		{
			//De combien doit-on se d�caler � droite (Combien de divisions par 2) ?
			$quotient = log($original_bit / $final_bit, 2);

			foreach ($auth as $user_kind => $auth_values)
			{
				$result[$user_kind] = ($auth_values & $original_bit) >> $quotient;
			}
		}
		elseif ($original_bit < $final_bit)
		{
			//De combien doit-on se d�caler � gauche (combien de multiplications par 2) ?
			$quotient = log($final_bit / $original_bit, 2);

			foreach ($auth as $user_kind => $auth_values)
			{
				$result[$user_kind] = ($auth_values & $original_bit) << $quotient;
			}
		}
		else
		{
			foreach ($auth as $user_kind => $auth_values)
			{
				$result[$user_kind] = $auth_values & $original_bit;
			}
		}
		return $result;
	}

	//R�cup�ration du tableau des autorisations.
	/**
	 * @desc Get authorization array from the form.
	 * @param int $bit_value The bit emplacement in the authorization array used to set it.
	 * @param string $idselect Html id used for the select.
	 * @param array $array_auth_all Array where the authorizations collected are stored.
	 * @param array $sum_auth Sum up all authorizations for the authorization array.
	 * @static
	 */
	private static function get_auth_array($bit_value, $idselect, &$array_auth_all, &$sum_auth)
	{
		$idselect = ($idselect == '') ? $bit_value : $idselect; //Identifiant du formulaire.

		##### Niveau et Groupes #####
		$array_auth_groups = !empty($_REQUEST['groups_auth' . $idselect]) ? $_REQUEST['groups_auth' . $idselect] : '';
		if (!empty($array_auth_groups)) //R�cup�ration du formulaire.
		{
			$sum_auth += $bit_value;
			if (is_array($array_auth_groups))
			{
				//Ajout des autorisations sup�rieures si une autorisations inf�rieure est autoris�e. Ex: Membres autoris�s implique mod�rateurs autoris�s.
				$array_level = array(0 => 'r-1', 1 => 'r0', 2 => 'r1');
				$min_auth = 3;
				foreach ($array_level as $level => $key)
				{
					if (in_array($key, $array_auth_groups))
					{
						$min_auth = $level;
					}
					else
					{
						if ($min_auth < $level)
							$array_auth_groups[] = $key;
					}
				}

				//Ajout des autorisations au tableau final.
				foreach ($array_auth_groups as $value)
				{
					if ($value == "" || $value == 'r2')
					{
						continue;
					}
					if (isset($array_auth_all[$value]))
					{
						$array_auth_all[$value] += $bit_value;
					}
					else
					{
						$array_auth_all[$value] = $bit_value;
					}
				}
			}
		}

		##### Membres (autorisations avanc�es) ######
		$array_auth_members = !empty($_REQUEST['members_auth' . $idselect]) ? $_REQUEST['members_auth' . $idselect] : '';
		if (!empty($array_auth_members)) //R�cup�ration du formulaire.
		{
			if (is_array($array_auth_members))
			{
				//Ajout des autorisations au tableau final.
				foreach ($array_auth_members as $key => $value)
				{
					if ($value == "")
					{
						continue;
					}
					if (isset($array_auth_all['m' . $value]))
					{
						$array_auth_all['m' . $value] += $bit_value;
					}
					else
					{
						$array_auth_all['m' . $value] = $bit_value;
					}
				}
			}
		}
	}
}

?>
