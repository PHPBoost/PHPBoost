<?php
/*##################################################
 *                              quotes.php
 *                            -------------------
 *   begin                : October 14, 2008
 *   copyright         : (C) 2008 Alain GANDON based on Guestbook
 *   email                : 
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

require_once('../kernel/begin.php'); 
require_once('../quotes/quotes_begin.php');
require_once('../kernel/header.php'); 

$id_get = retrieve(GET, 'id', 0);
$quotes = retrieve(POST, 'quotes', false);
//Chargement du cache
$Cache->load('quotes');
	
if( $quotes && empty($id_get) ) //Enregistrement
{
	$quotes_contents = retrieve(POST, 'quotes_contents', '', TSTRING_UNSECURE);
	$quotes_author = retrieve(POST, 'quotes_author', '', TSTRING_UNSECURE);
	$quotes_pseudo = retrieve(POST, 'quotes_pseudo', $LANG['guest']);

	//Membre en lecture seule?
	if( $User->get_attribute('user_readonly') > time() ) 
		$Errorh->handler('e_readonly', E_USER_REDIRECT); 
	
	if( !empty($quotes_contents) && !empty($quotes_author) && !empty($quotes_pseudo) )
	{	
		//Accès pour poster.			
		if( $User->check_level($CONFIG_QUOTES['quotes_auth']) )
		{
			//Mod anti-flood
			$check_time = ($User->get_attribute('user_id') !== -1 && $CONFIG['anti_flood'] == 1) ? $Sql->query("SELECT MAX(timestamp) as timestamp FROM ".PREFIX."quotes WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__) : '';
			if( !empty($check_time) )
			{			
				if( $check_time >= (time() - $CONFIG['delay_flood']) ) //On calcul la fin du delai.	
					redirect(HOST . SCRIPT . url('?error=flood', '', '&') . '#errorh');
			}
			
			$Sql->query_inject(
			"INSERT INTO ".PREFIX."quotes
				SET contents = '" . strparse($quotes_contents) . "',
					author = '" . strparse($quotes_author) . "',
					user_id = '" . $User->get_attribute('user_id') . "',
					timestamp = '" . time() . "'",
				__LINE__, __FILE__);
			$last_msg_id = $Sql->insert_id(""); //Dernier message inséré.
			
			redirect(HOST . SCRIPT . SID2 . '#m' . $last_msg_id);
		}
		else //utilisateur non autorisé!
			redirect(HOST . SCRIPT . url('?error=auth', '', '&') . '#errorh');
	}
	else
		redirect(HOST . SCRIPT . url('?error=incomplete', '', '&') . '#errorh');
}
elseif( retrieve(POST, 'previs', false) ) //Prévisualisation.
{
	$Template->set_filenames(array(
		'quotes' => 'quotes/quotes.tpl'
	));

	$user_id = (int)$Sql->query("SELECT user_id FROM ".PREFIX."quotes WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
	
	$quotes_contents = retrieve(POST, 'quotes_contents', '', TSTRING_UNCHANGE);
	$quotes_author = retrieve(POST, 'quotes_author', '', TSTRING_UNCHANGE);
	$quotes_pseudo = retrieve(POST, 'quotes_pseudo', $LANG['guest']);

	//Pseudo du membre connecté.
	if( $user_id !== -1 )
		$Template->assign_block_vars('hidden_quotes', array(
			'PSEUDO' => $quotes_pseudo
		));
	else
		$Template->assign_block_vars('visible_quotes', array(
			'PSEUDO' => stripslashes($quotes_pseudo)
		));

	$Template->assign_block_vars('quotes', array(
		'CONTENTS' => stripslashes($quotes_contents),
		'AUTHOR' => stripslashes($quotes_author),
		'PSEUDO' => stripslashes($quotes_pseudo),
		'DATE' => gmdate_format('date_format_short')
	));

	//On met à jour en cas d'édition après prévisualisation
	$update = retrieve(GET, 'update', false);
	$update = $update && !empty($id_get) ? '?update=1&amp;id=' . $id_get : '';
	
	$Template->assign_vars(array(
		'CONTENTS' => stripslashes($quotes_contents),
		'AUTHOR' => stripslashes($quotes_author),
		'PSEUDO' => stripslashes($quotes_pseudo),
		'DATE' => gmdate_format('date_format_short'),
		'UPDATE' => url($update),
		'ERROR' => '',
		'L_ALERT_TEXT' => $LANG['require_text'],
		'L_UPDATE_MSG' => $LANG['update_msg'],
		'L_REQUIRE' => $LANG['require'],
		'L_CONTENTS' => $LANG['message'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset'],
		'L_ON' => $LANG['on']
	));	
	
	$Template->pparse('quotes'); 
}
elseif( !empty($id_get) ) //Edition + suppression!
{
	$del = retrieve(GET, 'del', false);
	$edit = retrieve(GET, 'edit', false);
	$update = retrieve(GET, 'update', false);
	
	$result = $Sql->query_while("SELECT q.*, m.login AS mlogin
			FROM ".PREFIX."quotes q
			LEFT JOIN ".PREFIX."member m ON m.user_id = q.user_id
			WHERE q.id = ".$id_get,	__LINE__, __FILE__);
	$row = $Sql->fetch_assoc($result);
	$row['user_id'] = (int)$row['user_id'];
	
	if( $User->check_level(MODO_LEVEL) || ($row['user_id'] === $User->get_attribute('user_id') && $User->get_attribute('user_id') !== -1) )
	{
		if( $del ) //Suppression.
		{
			$Sql->query_inject("DELETE FROM ".PREFIX."quotes WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
			$previous_id = $Sql->query("SELECT MAX(id) FROM ".PREFIX."quotes", __LINE__, __FILE__);
			
			$Cache->generate_module_file('quotes'); //Régénération du cache du mini-module.
			
			redirect(HOST . SCRIPT . SID2 . '#m' . $previous_id);
		}
		elseif( $edit )
		{
			$Template->set_filenames(array(
				'quotes' => 'quotes/quotes.tpl'
			));

			if( $row['user_id'] !== -1 )
				$Template->assign_vars(array(
					'C_HIDDEN_quotes' => true,
					'PSEUDO' => $row['mlogin']
				));
			else
				$Template->assign_vars(array(
					'C_VISIBLE_quotes' => true,
					'PSEUDO' => $row['mlogin']
				));		
				
			$Template->assign_vars(array(
				'UPDATE' => url('?update=1&amp;id=' . $id_get),
				'CONTENTS' => unparse($row['contents']),
				'AUTHOR' => $row['author'],
				'DATE' => gmdate_format('date_format_short', $row['timestamp']),
				'THEME' => $CONFIG['theme'],
				'L_ALERT_TEXT' => $LANG['require_text'],
				'L_UPDATE_QUOTES' => $LANG['update_quotes'],
				'L_REQUIRE' => $LANG['require'],
				'L_CONTENTS' => $LANG['quotes_contents'],
				'L_AUTHOR' => $LANG['quotes_author'],
				'L_PSEUDO' => $LANG['pseudo'],
				'L_SUBMIT' => $LANG['update'],
				'L_RESET' => $LANG['reset']
			));
			
			$Template->pparse('quotes'); 
		}
		elseif( $update )
		{
			$quotes_contents = retrieve(POST, 'quotes_contents', '', TSTRING_UNSECURE);
			$quotes_author = retrieve(POST, 'quotes_author', '', TSTRING_UNSECURE);
			
			if( !empty($quotes_contents) && !empty($quotes_author) )
			{		
				$Sql->query_inject("UPDATE ".PREFIX."quotes
				SET contents = '" . strparse($quotes_contents) . "', author = '" . strparse($quotes_author) . "'
				WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
				
				$Cache->generate_module_file('quotes'); //Régénération du cache du mini-module.
			
				redirect(HOST . SCRIPT. SID2 . '#m' . $id_get);
			}
			else
				$Errorh->handler('e_incomplete', E_USER_REDIRECT);
		}
		else
			redirect(HOST . SCRIPT . SID2);
	}
	else
		redirect(HOST . SCRIPT . SID2);
}
else //Affichage.
{
	$Template->set_filenames(array(
		'quotes' => 'quotes/quotes.tpl'
	));
		
	//Pseudo du membre connecté.
	if( $User->get_attribute('user_id') !== -1 )
		$Template->assign_vars(array(
			'C_HIDDEN_quotes' => true,
			'PSEUDO' => $User->get_attribute('login')
		));
	else
		$Template->assign_vars(array(
			'C_VISIBLE_quotes' => true,
			'PSEUDO' => $LANG['guest']
		));
	
	$post_access_ok = $User->check_level($CONFIG_QUOTES['quotes_auth']);
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	switch($get_error)
	{
		case 'auth':
		$errstr = $LANG['e_unauthorized'];
		break;
		case 'flood':
		$errstr = $LANG['e_flood'];
		break;
		case 'l_flood':
		$errstr = sprintf($LANG['e_l_flood'], $CONFIG_QUOTES['quotes_max_link']);
		break;
		case 'l_pseudo':
		$errstr = $LANG['e_link_pseudo'];
		break;
		case 'incomplete':
		$errstr = $LANG['e_incomplete'];
		break;
		default:
		$errstr = '';
	}
	if( !empty($errstr) )
		$Errorh->handler($errstr, E_USER_NOTICE);
	
	$nbr_quotes = $Sql->count_table('quotes', __LINE__, __FILE__);
	//On crée une pagination si le nombre de msg est trop important.
	include_once('../kernel/framework/util/pagination.class.php'); 
	$Pagination = new Pagination();
	
	$Template->assign_vars(array(
		'UPDATE' => url(''),
		'C_POST_ACCESS' => $post_access_ok,
		'PAGINATION' => $Pagination->display('quotes' . url('.php?p=%d'), $nbr_quotes, 'p', 10, 3),
		'L_ALERT_TEXT' => $LANG['require_text'],
		'L_DELETE_MSG' => $LANG['alert_delete_msg'],
		'L_ADD_QUOTES' => $LANG['add_quotes'],
		'L_REQUIRE' => $LANG['require'],
		'L_CONTENTS' => $LANG['quotes_contents'],
		'L_AUTHOR' => $LANG['quotes_author'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset'],
		'L_ON' => $LANG['on']
	));
	
	$j = 0;
	$result = $Sql->query_while("SELECT q.id, q.user_id, q.timestamp, q.contents, q.author
		FROM ".PREFIX."quotes q
		GROUP BY q.id
		ORDER BY q.timestamp DESC"
		. $Sql->limit($Pagination->get_first_msg(10, 'p'), 10), __LINE__, __FILE__);	
	while ($row = $Sql->fetch_assoc($result))
	{
		$user_id = (int)$row['user_id'];
		$edit = '';
		$del = '';
	
		//Edition/suppression.
		if( $User->check_level(MODO_LEVEL) || ($user_id === $User->get_attribute('user_id') && $User->get_attribute('user_id') !== -1) )
		{
			$edit = '&nbsp;&nbsp;<a href="../quotes/quotes' . url('.php?edit=1&id=' . $row['id']) . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" alt="' . $LANG['edit'] . '" title="' . $LANG['edit'] . '" class="valign_middle" /></a>';
			$del = '&nbsp;&nbsp;<a href="../quotes/quotes' . url('.php?del=1&id=' . $row['id']) . '" onclick="javascript:return Confirm();"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" alt="' . $LANG['delete'] . '" title="' . $LANG['delete'] . '" class="valign_middle" /></a>';
		}
		
		$Template->assign_block_vars('quotes',array(
			'ID' => $row['id'],
			'CONTENTS' => ucfirst($row['contents']),
			'AUTHOR' => ucfirst($row['author']),
			'DATE' => $LANG['on'] . ': ' . gmdate_format('date_format', $row['timestamp']),
			'DEL' => $del,
			'EDIT' => $edit,
			'U_ANCHOR' => 'quotes.php' . SID . '#m' . $row['id']
		));
		$j++;
	}
	$Sql->query_close($result);
		
	$Template->pparse('quotes'); 
}

require_once('../kernel/footer.php'); 

?>