<?php
/**
 * This class contains only static methods, it souldn't be instantiated.
 * @deprecated
 * @package     PHPBoost
 * @subpackage  Member\authorization
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 2.0 - 2008 07 26
 * @contributor Benoit SAUTEL <ben.popeye@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Authorizations
{
	const AUTH_PARENT_PRIORITY = 0x01;	// Generally read mode
	const AUTH_CHILD_PRIORITY = 0x02;	// Generally write mode

	/**
	 * Returns an array with the authorizations given by variable number of arrays passed in argument.
	 * This returned array is used to be serialized.
	 * @return array The array of authorizations.
	 * @static
	 */
	public static function build_auth_array_from_form()
	{
		$array_auth_all = array();
		$sum_auth = 0;
		$nbr_arg = func_num_args();

		//Si le nom du formulaire select est passé en paramètre, c'est le dernier
		$idselect = '';
		if (gettype(func_get_arg($nbr_arg - 1)) == 'string')
		{
			$idselect = func_get_arg(--$nbr_arg);
		}

		//Récupération du dernier argument, si ce n'est pas un tableau => booléen demandant la sélection par défaut de l'admin.
		$admin_auth_default = true;
		if ($nbr_arg > 1)
		{
			$admin_auth_default = func_get_arg($nbr_arg - 1);
			if (!is_bool($admin_auth_default))
				$admin_auth_default = true;
			else
				$nbr_arg--; //On diminue de 1 le nombre d'argument, car le denier est le flag.
		}
		//On balaye les tableaux passés en argument.
		for ($i = 0; $i < $nbr_arg; $i++)
			self::get_auth_array(func_get_arg($i), $idselect, $array_auth_all, $sum_auth);

		ksort($array_auth_all); //Tri des clés du tableau par ordre alphabétique, question de lisibilité.

		return $array_auth_all;
	}

	/**
	 * Returns an array with the authorizations given by variable number of arrays passed in argument.
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

		//Récupération du tableau des autorisation.
		self::get_auth_array($bit_value, $idselect, $array_auth_all, $sum_auth);

		//Admin tous les droits dans n'importe quel cas.
		if ($admin_auth_default)
		{
			$array_auth_all['r2'] = $sum_auth;
		}
		ksort($array_auth_all); //Tri des clées du tableau par ordre alphabétique, question de lisibilité.

		return $array_auth_all;
	}

	/**
	 * Generate a multiple select field for the form which create authorization for ranks, groups and members.
	 * @param int $auth_bit The bit emplacement used to set it.
	 * @param array $array_auth Array of authorization, allow you to select value authorized for this bit.
	 * @param array $array_ranks_default Array of ranks selected by default.
	 * @param string $idselect Html id used for the select.
	 * @param int $disabled Disabled all options for the select. Set to true to disable all options.
	 * @param boolean $disabled_advanced_auth Disable advanced authorizations.
	 * @param mixed[] $disabled_ranks The ranks to disable in select.
	 * @return String The formated select.
	 * @static
	 */
	public static function generate_select($auth_bit, $array_auth = array(), $array_ranks_default = array(), $idselect = '', $disabled = false, $disabled_advanced_auth = false, $disabled_ranks = array())
	{
		$lang = LangLoader::get_all_langs();

		//Récupération du tableau des rangs.
		$array_ranks = array(
			User::VISITOR_LEVEL       => $lang['user.guest'],
			User::MEMBER_LEVEL        => $lang['user.member'],
			User::MODERATOR_LEVEL     => $lang['user.moderator'],
			User::ADMINISTRATOR_LEVEL => $lang['user.administrator']
		);

		//Identifiant du select, par défaut la valeur du bit de l'autorisation.
		$idselect = ((string)$idselect == '') ? $auth_bit : $idselect;

		$view = new FileTemplate('framework/groups_auth.tpl');
		$view->add_lang($lang);

		$view->put_all(array(
			'C_ADVANCED_AUTH' => !$disabled_advanced_auth,

			'SELECT_ID'       => $idselect,
			'DISABLED_SELECT' => (empty($disabled) ? 'if (disabled == 0)' : ''),
		));

		##### Génération d'une liste à sélection multiple des rangs et membres #####
		//Liste des rangs
		$j = -1;
		foreach ($array_ranks as $idrank => $group_name)
		{
			//Si il s'agit de l'administrateur, il a automatiquement l'autorisation
			if ($idrank == 2)
			{
				$view->assign_block_vars('ranks_list', array(
					'ID'        => $j,
					'RANK_ID'   => $idrank,
					'RANK_NAME' => $group_name,
					'DISABLED'  => '',
					'SELECTED'  => ' selected="selected"'
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

				$view->assign_block_vars('ranks_list', array(
					'C_DISABLED' => !empty($disabled) || in_array($idrank, $disabled_ranks),

					'ID'        => $j,
					'RANK_ID'   => $idrank,
					'RANK_NAME' => $group_name,
					'SELECTED'  => $selected
				));
			}
			$j++;
		}

		//Liste des groupes.
		$groups_name = GroupsService::get_groups_names();
		foreach ($groups_name as $idgroup => $group_name)
		{
			$selected = '';
			if (array_key_exists($idgroup, $array_auth) && ((int)$array_auth[$idgroup] & (int)$auth_bit) !== 0 && empty($disabled))
			{
				$selected = ' selected="selected"';
			}

			$view->assign_block_vars('groups_list', array(
				'C_DISABLED' => !empty($disabled),

				'GROUP_ID'   => $idgroup,
				'GROUP_NAME' => $group_name,
				'SELECTED'   => $selected
			));
		}

		##### Génération du formulaire pour les autorisations membre par membre. #####
		//Recherche des membres autorisé.
		$array_auth_members = array();
		if (is_array($array_auth))
		{
			foreach ($array_auth as $type => $auth)
			{
				if (TextHelper::substr($type, 0, 1) == 'm')
				{
					if (array_key_exists($type, $array_auth) && ((int)$array_auth[$type] & (int)$auth_bit) !== 0)
						$array_auth_members[$type] = $auth;
				}
			}
		}
		$advanced_auth = count($array_auth_members) > 0;

		$view->put_all(array(
			'C_ADVANCED_AUTH_OPEN' => $advanced_auth,
			'C_NO_GROUP'           => count($groups_name) == 0
		));

		//Listing des membres autorisés.
		if ($advanced_auth)
		{
			$result = PersistenceContext::get_querier()->select_rows(DB_TABLE_MEMBER, array('user_id, display_name'), 'WHERE user_id IN :user_ids', array('user_ids' => str_replace('m', '', array_keys($array_auth_members))));
			while ($row = $result->fetch())
			{
				$view->assign_block_vars('members_list', array(
					'USER_ID' => $row['user_id'],
					'LOGIN'   => $row['display_name']
				));
			}

			$result->dispose();
		}

		return $view->render();
	}

	/**
	 * Check authorizations for a member, a group or a rank
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
					return isset($array_auth['r' . $value]) && $array_auth['r' . $value] & $bit;
				else
					return false;
			case GROUP_TYPE:
				if ($value >= 1)
					return isset($array_auth[$value]) && !empty($array_auth[$value]) ? $array_auth[$value] & $bit : false;
				else
					return false;
			case USER_TYPE:
				if ($value >= 1)
					return isset($array_auth['m' . $value]) && !empty($array_auth['m' . $value]) ? $array_auth['m' . $value] & $bit : false;
				else
					return false;
			default:
				return false;
		}
	}

	/**
	 * Merge two authorizations array, first is the parent, second is the inherited child.
	 * @param array $parent Array of authorizations.
	 * @param array $child Array of authorizations.
	 * @param int $auth_bit Bit emplacement for the merge.
	 * @param int $mode Mode used for the merge. Use Authorizations::AUTH_PARENT_PRIORITY to give to the parent the priority for the authorization, Authorizations::AUTH_CHILD_PRIORITY otherwise.
	 * @return array The new array merged.
	 * @static
	 */
	public static function merge_auth($parent, $child, $auth_bit, $mode)
	{
		//Parcours des différents types d'utilisateur
		$merged = array();

		if (!is_array($child))
		{
			return $parent;
		}

		if ($mode == self::AUTH_PARENT_PRIORITY)
		{
			$parent_guest_auth = isset($parent['r-1']) ? $parent['r-1'] : 0;
			$parent_member_auth = isset($parent['r0']) ? $parent['r0'] : 0;

			foreach ($parent as $key => $value)
			{
				if ($bit = ($value & $auth_bit) || $parent_guest_auth || $parent_member_auth)
				{
					if (!empty($child[$key]) || ($parent_guest_auth && !empty($child['r-1'])) || ($parent_guest_auth && !empty($child['r-1']) && $parent_member_auth && !empty($child['r0'])))
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
				unset($child[$key]);
			}
			foreach ($child as $key => $value)
			{
				if (!empty($value) || ($parent_guest_auth && !empty($merged['r-1'])))
				{
					$merged[$key] = $parent_guest_auth;
				}
				if (!empty($value) || ($parent_guest_auth && !empty($merged['r-1']) && $parent_member_auth && !empty($merged['r0'])))
				{
					$merged[$key] = $parent_member_auth;
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
	 * Capture authorizations and shift a particular bit to an another bit (1 is used by default).
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
			//De combien doit-on se décaler à droite (Combien de divisions par 2) ?
			$quotient = log($original_bit / $final_bit, 2);

			foreach ($auth as $user_kind => $auth_values)
			{
				$result[$user_kind] = ($auth_values & $original_bit) >> $quotient;
			}
		}
		elseif ($original_bit < $final_bit)
		{
			//De combien doit-on se décaler à gauche (combien de multiplications par 2) ?
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

	//Récupération du tableau des autorisations.
	/**
	 * Get authorization array from the form.
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
		if (!empty($array_auth_groups)) //Récupération du formulaire.
		{
			$sum_auth += $bit_value;
			if (is_array($array_auth_groups))
			{
				//Ajout des autorisations supérieures si une autorisations inférieure est autorisée. Ex: Membres autorisés implique modérateurs autorisés.
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

		##### Membres (autorisations avancées) ######
		$array_auth_members = !empty($_REQUEST['members_auth' . $idselect]) ? $_REQUEST['members_auth' . $idselect] : '';
		if (!empty($array_auth_members)) //Récupération du formulaire.
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
