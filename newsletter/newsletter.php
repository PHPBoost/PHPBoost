<?php
/*##################################################
 *                               newsletter.php
 *                            -------------------
 *   begin                : July 06, 2006
 *   copyright          : (C) 2006 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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

include('../includes/begin.php');
include_once('../newsletter/lang/' . $CONFIG['lang'] . '/newsletter_' . $CONFIG['lang'] . '.php'); //Chargement de la langue du module.
define('TITLE', $LANG['newsletter']);
include('../includes/header.php');

if( !$groups->check_auth($SECURE_MODULE['newsletter'], ACCESS_MODULE) )
{
	$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	exit;
}

$mail_newsletter = (!empty($_POST['mail_newsletter'])) ? securit($_POST['mail_newsletter']) : '';
$subscribe = (!empty($_POST['subscribe'])) ? trim($_POST['subscribe']) : 'subscribe';
$id = (!empty($_GET['id'])) ? numeric($_GET['id']) : '';
	
$template->set_filenames(array(
	'newsletter' => '../templates/' . $CONFIG['theme'] . '/newsletter/newsletter.tpl'
));	

//Inscription ou désinscription
if( !empty($mail_newsletter) )
{
	//Vérification de la validité du mail proposé
	if( preg_match('`^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-zA-Z]{2,4}$`', $mail_newsletter) )
	{
		//Inscription ou désincription?
		$subscribe = ($subscribe == 'subscribe') ? 1 : 0;
		
		//Inscription
		if( $subscribe === 1)
		{
			$check_mail = $sql->query("SELECT COUNT(*) FROM ".PREFIX."newsletter WHERE mail = '" . $mail_newsletter . "'", __LINE__, __FILE__);
			//Si il n'est pas déjà inscrit
			if( $check_mail == 0 )
			{
				//On enregistre le mail
				$sql->query_inject("INSERT INTO ".PREFIX."newsletter (mail) VALUES ('" . $mail_newsletter . "')",  __LINE__, __FILE__);
				$errorh->error_handler($LANG['newsletter_add_success'], E_USER_NOTICE);
			}			
			else
				$errorh->error_handler($LANG['newsletter_add_failure'], E_USER_NOTICE);
		}
		else
		{
			$check_mail = $sql->query("SELECT COUNT(*) FROM ".PREFIX."newsletter WHERE mail = '" . $mail_newsletter . "'", __LINE__, __FILE__);
			if( $check_mail >= 1 )
			{
				$sql->query_inject("DELETE FROM ".PREFIX."newsletter WHERE mail = '" . $mail_newsletter . "'", __lINE__, __FILE__);
				$errorh->error_handler($LANG['newsletter_del_success'], E_USER_NOTICE);
			}
			else
				$errorh->error_handler($LANG['newsletter_del_success'], E_USER_WARNING);
		}
	}
	else
		$errorh->error_handler($LANG['newsletter_email_address_is_not_valid'], E_USER_WARNING);
}
//Désinscription demandée suite à la réception d'une newsletter
elseif( $id > 0 )
{
	$check_mail = $sql->query_inject("DELETE FROM ".PREFIX."newsletter WHERE id = '" . $id . "'", __LINE__, __FILE__);
	$errorh->error_handler($LANG['newsletter_del_success'], E_USER_WARNING);
}
//Affichage des archives
else
{
	$template->assign_block_vars('arch_title', array());
	
	include_once('../includes/pagination.class.php'); 
	$pagination = new Pagination();
	
	$i = 0;	
	$result = $sql->query_while("SELECT id, title, message, timestamp, type, nbr
	FROM ".PREFIX."newsletter_arch 
	ORDER BY id DESC 
	" . $sql->sql_limit($pagination->first_msg(5, 'p'), 5), __LINE__, __FILE__);
	
	while($row = $sql->sql_fetch_assoc($result))
	{
		$template->assign_block_vars('arch', array(
			'DATE' => date($LANG['date_format'], $row['timestamp']),
			'TITLE' => stripslashes($row['title']),
			'MESSAGE' => ($row['type'] === 'bbcode' || $row['type'] === 'html') ? '<div style="text-align:center;"><a class="com" href="#" onclick="popup(\'' . HOST . DIR . transid('/newsletter/newsletter_arch.php?id=' . $row['id'], '', '') . '\', \'' . $row['title'] . '\');">' . $LANG['newsletter_msg_html'] . '</a></div>' : nl2br($row['message']), 
			'NBR_SENT_NEWSLETTERS' => sprintf($LANG['newsletter_nbr'], (int)$row['nbr']),
		));
		
		$i++;
	}
	
	$total_msg = $sql->query("SELECT COUNT(*) FROM ".PREFIX."newsletter_arch", __LINE__, __FILE__);
	
	if( $total_msg == 0 )
		$errorh->error_handler($LANG['newsletter_no_archives'], E_USER_NOTICE);
	
	$template->assign_vars(array(
		'PAGINATION' => $pagination->show_pagin('newsletter.php?p=%d', $total_msg, 'p', 5, 3),
		'L_NEWSLETTER_ARCHIVES' => $LANG['newsletter_archives'],
		'L_NEWSLETTER_ARCHIVES_EXPLAIN' => $LANG['newsletter_archives_explain']
		));
	
	if( $i === 0 )
	{	
		$template->assign_block_vars('mail', array(
			'MSG' => 'Il n\'y a pas d\'archives pour le moment.'
		));
	}
}

$template->pparse('newsletter');

include('../includes/footer.php');

?>