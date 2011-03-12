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

require_once('../kernel/begin.php');
require_once('../newsletter/newsletter_begin.php');
require_once('../kernel/header.php');

$mail_newsletter = retrieve(POST, 'mail_newsletter', '');
$subscribe = retrieve(POST, 'subscribe', 'subscribe');
$id = retrieve(GET, 'id', 0);
	
$Template->set_filenames(array(
	'newsletter'=> 'newsletter/newsletter.tpl'
));	

//Inscription ou désinscription
if (!empty($mail_newsletter))
{
	import('io/mail');
	//Vérification de la validité du mail proposé
	if (Mail::check_validity($mail_newsletter))
	{
		//Inscription ou désincription?
		$subscribe = ($subscribe == 'subscribe') ? 1 : 0;
		
		//Inscription
		if ($subscribe === 1)
		{
			$check_mail = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "newsletter WHERE mail = '" . $mail_newsletter . "'", __LINE__, __FILE__);
			//Si il n'est pas déjà inscrit
			if ($check_mail == 0)
			{
				//On enregistre le mail
				$Sql->query_inject("INSERT INTO " . PREFIX . "newsletter (mail) VALUES ('" . $mail_newsletter . "')",  __LINE__, __FILE__);
				$Errorh->handler($LANG['newsletter_add_success'], E_USER_NOTICE);
			}			
			else
				$Errorh->handler($LANG['newsletter_add_failure'], E_USER_WARNING);
		}
		else
		{
			$check_mail = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "newsletter WHERE mail = '" . $mail_newsletter . "'", __LINE__, __FILE__);
			if ($check_mail >= 1)
			{
				$Sql->query_inject("DELETE FROM " . PREFIX . "newsletter WHERE mail = '" . $mail_newsletter . "'", __lINE__, __FILE__);
				$Errorh->handler($LANG['newsletter_del_success'], E_USER_NOTICE);
			}
			else
				$Errorh->handler($LANG['newsletter_del_failure'], E_USER_WARNING);
		}
	}
	else
		$Errorh->handler($LANG['newsletter_email_address_is_not_valid'], E_USER_WARNING);
}
//Désinscription demandée suite à la réception d'une newsletter
elseif ($id > 0)
{
	$check_mail = $Sql->query_inject("DELETE FROM " . PREFIX . "newsletter WHERE id = '" . $id . "'", __LINE__, __FILE__);
	$Errorh->handler($LANG['newsletter_del_success'], E_USER_NOTICE);
}
//Affichage des archives
else
{
	$Template->assign_block_vars('arch_title', array());
	
	import('util/pagination'); 
	$Pagination = new Pagination();
	
	$i = 0;	
	$result = $Sql->query_while("SELECT id, title, message, timestamp, type, nbr
	FROM " . PREFIX . "newsletter_arch 
	ORDER BY id DESC 
	" . $Sql->limit($Pagination->get_first_msg(5, 'p'), 5), __LINE__, __FILE__);
	
	while ($row = $Sql->fetch_assoc($result))
	{
		$Template->assign_block_vars('arch', array(
			'DATE' => gmdate_format('date_format_short', $row['timestamp']),
			'TITLE' => stripslashes($row['title']),
			'MESSAGE' => ($row['type'] === 'bbcode' || $row['type'] === 'html') ? '<div style="text-align:center;"><a class="com" href="#" onclick="popup(\'' . HOST . DIR . url('/newsletter/newsletter_arch.php?id=' . $row['id'], '', '') . '\', \'' . $row['title'] . '\');">' . $LANG['newsletter_msg_html'] . '</a></div>' : nl2br($row['message']), 
			'NBR_SENT_NEWSLETTERS' => sprintf($LANG['newsletter_nbr'], (int)$row['nbr']),
		));
		
		$i++;
	}
	
	$total_msg = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "newsletter_arch", __LINE__, __FILE__);
	
	if ($total_msg == 0)
		$Errorh->handler($LANG['newsletter_no_archives'], E_USER_NOTICE);
	
	$Template->assign_vars(array(
		'PAGINATION' => $Pagination->display('newsletter.php?p=%d', $total_msg, 'p', 5, 3),
		'L_NEWSLETTER_ARCHIVES' => $LANG['newsletter_archives'],
		'L_NEWSLETTER_ARCHIVES_EXPLAIN' => $LANG['newsletter_archives_explain']
		));
	
	if ($i === 0)
	{	
		$Template->assign_block_vars('mail', array(
			'MSG' => 'Il n\'y a pas d\'archives pour le moment.'
		));
	}
}

$Template->pparse('newsletter');

require_once('../kernel/footer.php');

?>