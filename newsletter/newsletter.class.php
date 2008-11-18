<?php
/*##################################################
 *                               newsletter.class.php
 *                            -------------------
 *   begin                : July 07, 2007
 *   copyright          : (C) 2007 Sautel Benoit
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

if (defined('PHPBOOST') !== true)	exit;
	
class Newsletter_sender
{
	function send_html($mail_object, $message, $email_test = '')
	{
		global $_NEWSLETTER_CONFIG, $LANG, $Sql;
		
		$error_mailing_list = array();
		$message = stripslashes($message);
		$message = str_replace('"../', '"' . HOST . DIR . '/' , $message);
		$message = $this->clean_html($message);
		
		//On défini les headers
		$headers = 'From: ' . $_NEWSLETTER_CONFIG['newsletter_name'] . ' <' . $_NEWSLETTER_CONFIG['sender_mail'] . '>' . "\r\n";
		$headers .= 'Reply-To: ' . $_NEWSLETTER_CONFIG['sender_mail'] . "\r\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "\r\n";
		
		if ($email_test == '') // envoi définitif
		{
			$nbr = $Sql->count_table('newsletter', __LINE__, __FILE__);
			//On enregistre dans les archives la newsletter envoyée
			$Sql->query_inject("INSERT INTO ".PREFIX."newsletter_arch (title,message,timestamp,type,nbr) VALUES('" . $mail_object . "','" . $message . "', '" . time() . "', 'html', '" . $nbr . "')", __LINE__, __FILE__);
			
			$mailing_list = array();
			$result = $Sql->query_while("SELECT id, mail 
			FROM ".PREFIX."newsletter 
			ORDER BY id", __LINE__, __FILE__);			
			while ($row = $Sql->fetch_assoc($result))
			{
				$mailing_list[] = array($row['id'], $row['mail']);
			}
			$Sql->query_close($result);
			 
			foreach ($mailing_list as $array_mail)
			{
				if (!@mail($array_mail[1], $mail_object, str_replace('[UNSUBSCRIBE_LINK]', '<br /><br /><a href="' . HOST . DIR . '/newsletter/newsletter.php?id=' . $array_mail[0] . '">' . $LANG['newsletter_unscubscribe_text'] . '</a><br /><br />', $message), $headers))
					$error_mailing_list[] = $array_mail[1];
			}

			return $error_mailing_list;
		}
		else
		{
			@mail($email_test, $mail_object, $message, $headers);
			return true;
		}		
	}
	
	function send_bbcode($mail_object, $message, $email_test = '')
	{
		global $_NEWSLETTER_CONFIG, $LANG, $Sql;
		
		$error_mailing_list = array();
		$message = stripslashes(strparse($message));
		$message = str_replace('"../', '"' . HOST . DIR . '/' , $message);
		
		//On définit les headers
		$headers = 'From: ' . $_NEWSLETTER_CONFIG['newsletter_name'] . ' <' . $_NEWSLETTER_CONFIG['sender_mail'] . '>' . "\r\n";
		$headers .= 'Reply-To: ' . $_NEWSLETTER_CONFIG['sender_mail'] . "\r\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		
		$mail_contents = '<html>
<head><title>' . $mail_object . '</title></head><body>';
		$mail_contents .= $message;
		
		if ($email_test == '') // envoi définitif
		{
			$nbr = $Sql->count_table('newsletter', __LINE__, __FILE__);
			//On enregistre dans les archives la newsletter envoyée
			$Sql->query_inject("INSERT INTO ".PREFIX."newsletter_arch (title,message,timestamp,type,nbr) VALUES('" . $mail_object . "', '" . $message . "', '" . time() . "', 'bbcode', '" . $nbr . "')", __LINE__, __FILE__);
			
			$mailing_list = array();
			$result = $Sql->query_while("SELECT id, mail 
			FROM ".PREFIX."newsletter 
			ORDER BY id", __LINE__, __FILE__);			
			while ($row = $Sql->fetch_assoc($result))
			{
				$mailing_list[] = array($row['id'], $row['mail']);
			}
			$Sql->query_close($result);
			 
			foreach ($mailing_list as $array_mail)
			{
				$mail_contents_end = '<br /><br /><a href="' . HOST . DIR . '/newsletter/newsletter.php?id=' . $array_mail[0] . '">' . $LANG['newsletter_unscubscribe_text'] . '</a></body></html>';
				if (!@mail($array_mail[1], $mail_object, $mail_contents . $mail_contents_end, $headers))
					$error_mailing_list[] = $array_mail[1];
			}
			
			return $error_mailing_list;
		}
		else
		{
			$mail_contents_end = '</body></html>';
			@mail($email_test, $mail_object, $mail_contents . $mail_contents_end, $headers);
			return true;
		}
	}
	
	function send_text($mail_object, $message, $email_test = '')
	{
		global $_NEWSLETTER_CONFIG, $LANG, $Sql;
		
		$error_mailing_list = array();
		$header = 'From: ' . $_NEWSLETTER_CONFIG['newsletter_name'] . ' <' . $_NEWSLETTER_CONFIG['sender_mail'] . '>' . "\r\n"; 
		$header .= 'Reply-To: ' . $_NEWSLETTER_CONFIG['sender_mail'] . "\r\n";
		
		if ($email_test == '') // envoi définitif
		{
			$nbr = $Sql->count_table('newsletter', __LINE__, __FILE__);
			//On enregistre dans les archives la newsletter envoyée
			$Sql->query_inject("INSERT INTO ".PREFIX."newsletter_arch (title,message,timestamp,type,nbr) VALUES('" . $mail_object . "', '" . $message . "', '" . time() . "', 'text', '" . $nbr . "')", __LINE__, __FILE__);
			
			$mailing_list = array();
			$result = $Sql->query_while("SELECT id, mail 
			FROM ".PREFIX."newsletter 
			ORDER BY id", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				$mailing_list[] = array($row['id'], $row['mail']);
			}
			$Sql->query_close($result);
			 
			foreach ($mailing_list as $array_mail)
			{
				$mail_contents = $message . "\n\n" . $LANG['newsletter_unscubscribe_text'] . HOST . DIR . '/membre/newsletter.php?id=' . $array_mail[0];			
				if (!@mail($array_mail[1], $mail_object, $mail_contents, $header))
					$error_mailing_list[] = $array_mail[1];
			}
			
			return $error_mailing_list;
		}
		else
		{
			@mail($email_test, $mail_object, $message, $header);
			return true;
		}
	}
	
	//Fonction qui remplace les caractères spéciaux par leurs entités en conservant les balises html
	function clean_html($text)
	{
		$text = htmlentities($text, ENT_NOQUOTES);
		$text = str_replace(array('&amp;', '&lt;', '&gt;'), array('&', '<', '>'), $text);
		return $text;
	}
}

?>