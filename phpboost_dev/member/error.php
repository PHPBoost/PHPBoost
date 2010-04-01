<?php
/*##################################################
 *                                error.php
 *                            -------------------
 *   begin                : August 08 2005
 *   copyright            : (C) 2005 CrowkaiT
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

require_once('../kernel/begin.php');
define('TITLE', $LANG['title_error']);
require_once('../kernel/header.php');

$id_error = retrieve(GET, 'e', '');
	
$array_error = array('e_member_ban', 'e_member_ban_w', 'e_unexist_member', 'e_unactiv_member',
'e_member_flood', 'e_forget_confirm_change');
if (in_array($id_error, $array_error))
{
	if ($User->get_attribute('user_id') === -1)
	{
		$Template->set_filenames(array(
			'error'=> 'member/error.tpl'
		));

		$errno = E_USER_WARNING;
		switch ($id_error)
		{
			case 'e_member_ban':
				$ban = !empty($_GET['ban']) ? NumberHelper::numeric($_GET['ban']) : '';
				if ($ban > 0)
				{
					if ($ban < 60)
						$delay_ban = $ban . ' ' . (($ban > 1) ? $LANG['minutes'] : $LANG['minute']);
					elseif ($ban < 1440)
					{
						$delay_ban = NumberHelper::round($ban/60, 0);
						$delay_ban = $delay_ban . ' ' . (($delay_ban > 1) ? $LANG['hours'] : $LANG['hour']);
					}
					elseif ($ban < 10080)
					{
						$delay_ban = NumberHelper::round($ban/1440, 0);
						$delay_ban = $delay_ban . ' ' . (($delay_ban > 1) ? $LANG['days'] : $LANG['day']);
					}
					elseif ($ban < 43200)
					{
						$delay_ban = NumberHelper::round($ban/10080, 0);
						$delay_ban = $delay_ban . ' ' . (($delay_ban > 1) ? $LANG['weeks'] : $LANG['week']);
					}
					elseif ($ban < 525600)
					{
						$delay_ban = NumberHelper::round($ban/43200, 0);
						$delay_ban = $delay_ban . ' ' . (($delay_ban > 1) ? $LANG['months'] : $LANG['month']);
					}
					else
					{
						$delay_ban = NumberHelper::round($ban/525600, 0);
						$delay_ban = $delay_ban . ' ' . (($delay_ban > 1) ? $LANG['years'] : $LANG['year']);
					}
				}
				else
					$delay_ban = 0 . ' ' . $LANG['minutes'];
				$errstr = $LANG['e_member_ban'] . ' ' . $delay_ban;
			break;
			case 'e_member_ban_w':
				$errstr = $LANG['e_member_ban_w'];
			break;
			case 'e_unexist_member':
				$errstr = $LANG['e_unexist_member'];
			break;
			case 'e_unactiv_member':
				$errstr = $LANG['e_unactiv_member'];
			break;
			case 'e_forget_confirm_change':
				$errstr = $LANG['e_forget_confirm_change'];
			break;
			case 'e_member_flood':
				$flood = retrieve(GET, 'flood', 0);
				$flood = ($flood > 0 && $flood <= 5) ? $flood : 0;
				$flood = ($flood > 0) ? sprintf($LANG['e_test_connect'], $flood) : $LANG['e_nomore_test_connect'];
				$errstr = $flood;
			break;
			default:
			$errstr = '';
		}
			
		if (!empty($errstr))
			$Errorh->handler($errstr, $errno);
			
		$Template->assign_vars(array(
			'C_ERRORH_CONNEXION' => true,
			'L_CONNECT' => $LANG['connect'],
			'L_PSEUDO' => $LANG['pseudo'],
			'L_PASSWORD' => $LANG['password'],
			'L_REGISTER' => $LANG['register'],
			'L_FORGOT_PASS' => $LANG['forget_pass'],
			'L_AUTOCONNECT' => $LANG['autoconnect'],
			'U_REGISTER' => UserAccountsConfig::load()->is_registration_enabled() ? '<a href="../member/register.php"><img src="../templates/' . get_utheme() . '/images/register_mini.png" alt="" class="valign_middle" /> ' . $LANG['register'] . '</a><br />' : ''
		));
		
		$Template->pparse('error');
	}
	else
		AppContext::get_response()->redirect(Environment::get_home_page());
}
elseif (!empty($id_error))
{
	$Template->set_filenames(array(
		'error'=> 'member/error.tpl'
	));

	//Inclusion des langues des erreurs pour le module si elle existe.
	$module = substr(strrchr($id_error, '_'), 1);
	if (is_dir('../' . $module))
		load_module_lang($module); //Chargement de la langue du module.

	$Template->assign_vars(array(
		'THEME' => get_utheme(),
		'L_ERROR' => $LANG['error'],
		'U_BACK' => !empty($_SERVER['HTTP_REFERER']) ? '<a href="' . url($_SERVER['HTTP_REFERER']) .'">' . $LANG['back'] . '</a>' : '<a href="javascript:history.back(1)">' . $LANG['back'] . '</a>',
		'U_INDEX' => '<a href="' . url(Environment::get_home_page()) .'">' . $LANG['home'] . '</a>',
	));
	
	$Template->assign_vars(array(
		'C_ERRORH_CONNEXION' => false,
		'C_ERRORH' => true,
		'ERRORH_IMG' => 'important',
		'ERRORH_CLASS' => 'error_warning',
		'L_ERRORH' => isset($LANG[$id_error]) ? $LANG[$id_error] : $LANG['unknow_error']
	));
	
	$Template->pparse('error');
}
elseif ($User->get_attribute('user_id') === -1)
{
	$Template->set_filenames(array(
		'error'=> 'member/error.tpl'
	));

	$Template->assign_vars(array(
		'C_ERRORH_CONNEXION' => true,
		'L_CONNECT' => $LANG['connect'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_PASSWORD' => $LANG['password'],
		'L_REGISTER' => $LANG['register'],
		'L_FORGOT_PASS' => $LANG['forget_pass'],
		'L_AUTOCONNECT' => $LANG['autoconnect'],
		'U_REGISTER' => UserAccountsConfig::load()->is_registration_enabled() ? '<a href="../member/register.php"><img src="../templates/' . get_utheme() . '/images/register_mini.png" alt="" class="valign_middle" /> ' . $LANG['register'] . '</a><br />' : ''
	));
	
	$Template->pparse('error');
}
else
	AppContext::get_response()->redirect(Environment::get_home_page());

require_once('../kernel/footer.php');

?>