<?php
/*##################################################
 *                        newsletter_service.class.php
 *                            -------------------
 *   begin                : July 07, 2007
 *   copyright            : (C) 2007 Sautel Benoit
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

import('content/parser/content_second_parser');
import('io/mail');
	
class NewsletterService
{
	function send_html($mail_object, $message, $email_test = '')
	{
		global $_NEWSLETTER_CONFIG, $LANG, $Sql;
		
		$error_mailing_list = array();
		$message = stripslashes($message);
		$message = str_replace('"../', '"' . HOST . DIR . '/' , $message);
		$message = NewsletterService::clean_html($message);
		$message = ContentSecondParser::export_html_text($message);
				
		if ($email_test == '') // envoi définitif
		{
			$nbr = $Sql->count_table('newsletter', __LINE__, __FILE__);
			//On enregistre dans les archives la newsletter envoyée
			$Sql->query_inject("INSERT INTO " . PREFIX . "newsletter_arch (title,message,timestamp,type,nbr) VALUES('" . $mail_object . "','" . $message . "', '" . time() . "', 'html', '" . $nbr . "')", __LINE__, __FILE__);
			
			$mailing_list = array();
			$result = $Sql->query_while("SELECT id, mail 
			FROM " . PREFIX . "newsletter 
			ORDER BY id", __LINE__, __FILE__);			
			while ($row = $Sql->fetch_assoc($result))
			{
				$mailing_list[] = array($row['id'], $row['mail']);
			}
			$Sql->query_close($result);
			
			$mail_sender = new Mail();
			$mail_sender->set_sender($_NEWSLETTER_CONFIG['sender_mail']);
			$mail_sender->set_mime(MIME_FORMAT_HTML);
			$mail_sender->set_object($mail_object);
			 
			foreach ($mailing_list as $array_mail)
			{
			    $mail_sender->set_recipients($array_mail[1]);
                $mail_sender->set_content(str_replace('[UNSUBSCRIBE_LINK]', '<br /><br /><a href="' . HOST . DIR . '/newsletter/newsletter.php?id=' . $array_mail[0] . '">' . $LANG['newsletter_unscubscribe_text'] . '</a><br /><br />', $message));

                if (!$mail_sender->send())
                {
                    $error_mailing_list[] = $array_mail[1];
                }
			}

			return $error_mailing_list;
		}
		else
		{
		    $mail_sender = new Mail();
		    $mail_sender->set_sender($_NEWSLETTER_CONFIG['sender_mail']);
		    $mail_sender->set_mime(MIME_FORMAT_HTML);
		    $mail_sender->set_recipients($email_test);
		    $mail_sender->set_content($message);
		    $mail_sender->set_object($mail_object);
		    
		    $mail_sender->send();
			return true;
		}		
	}
	
	function send_bbcode($mail_object, $message, $email_test = '')
	{
		global $_NEWSLETTER_CONFIG, $LANG, $Sql;
		
		$error_mailing_list = array();
		$message = stripslashes(strparse(addslashes($message)));

		$message = ContentSecondParser::export_html_text($message);
		
		$mail_contents = '<html>
<head><title>' . $mail_object . '</title></head><body>';
		$mail_contents .= $message;
		
		if ($email_test == '') // envoi définitif
		{
			$nbr = $Sql->count_table('newsletter', __LINE__, __FILE__);
			//On enregistre dans les archives la newsletter envoyée
			$Sql->query_inject("INSERT INTO " . PREFIX . "newsletter_arch (title,message,timestamp,type,nbr) VALUES('" . $mail_object . "', '" . $message . "', '" . time() . "', 'bbcode', '" . $nbr . "')", __LINE__, __FILE__);
			
			$mailing_list = array();
			$result = $Sql->query_while("SELECT id, mail 
			FROM " . PREFIX . "newsletter 
			ORDER BY id", __LINE__, __FILE__);			
			while ($row = $Sql->fetch_assoc($result))
			{
				$mailing_list[] = array($row['id'], $row['mail']);
			}
			$Sql->query_close($result);
			
			$mail_sender = new Mail();
			$mail_sender->set_sender($_NEWSLETTER_CONFIG['sender_mail']);
			$mail_sender->set_mime(MIME_FORMAT_HTML);
            $mail_sender->set_object($mail_object);
           
            foreach ($mailing_list as $array_mail)
            {
    	        $mail_sender->set_recipients($array_mail[1]);
    	        $mail_contents_end = '<br /><br /><a href="' . HOST . DIR . '/newsletter/newsletter.php?id=' . $array_mail[0] . '">' . $LANG['newsletter_unscubscribe_text'] . '</a></body></html>';
                $mail_sender->set_content($mail_contents . $mail_contents_end);
    
                if (!$mail_sender->send())
                {
                    $error_mailing_list[] = $array_mail[1];
                }
            }
			
			return $error_mailing_list;
		}
		else
		{
		    $mail_sender = new Mail();
		    $mail_sender->set_sender($_NEWSLETTER_CONFIG['sender_mail']);
		    $mail_sender->set_mime(MIME_FORMAT_HTML);
            $mail_sender->set_recipients($email_test);
            $mail_sender->set_content($mail_contents . '</body></html>');
            $mail_sender->set_object($mail_object);
            
            $mail_sender->send();
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
			$Sql->query_inject("INSERT INTO " . PREFIX . "newsletter_arch (title,message,timestamp,type,nbr) VALUES('" . strprotect($mail_object, HTML_NO_PROTECT, ADDSLASHES_FORCE) . "', '" . strprotect($message, HTML_NO_PROTECT, ADDSLASHES_FORCE) . "', '" . time() . "', 'text', '" . $nbr . "')", __LINE__, __FILE__);
			
			$mailing_list = array();
			$result = $Sql->query_while("SELECT id, mail 
			FROM " . PREFIX . "newsletter 
			ORDER BY id", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				$mailing_list[] = array($row['id'], $row['mail']);
			}
			$Sql->query_close($result);
			
		    $mail_sender = new Mail();
		    $mail_sender->set_sender($_NEWSLETTER_CONFIG['sender_mail']);
            $mail_sender->set_mime(MIME_FORMAT_TEXT);
            $mail_sender->set_object($mail_object);
           
            foreach ($mailing_list as $array_mail)
            {
                $mail_sender->set_recipients($array_mail[1]);
                $mail_sender->set_content($message . "\n\n" . $LANG['newsletter_unscubscribe_text'] . HOST . DIR . '/newsletter/newsletter.php?id=' . $array_mail[0]);
    
                if (!$mail_sender->send())
                {
                    $error_mailing_list[] = $array_mail[1];
                }
            }
			
			return $error_mailing_list;
		}
		else
		{
            $mail_sender = new Mail();
            $mail_sender->set_sender($_NEWSLETTER_CONFIG['sender_mail']);
            $mail_sender->set_mime(MIME_FORMAT_HTML);
            $mail_sender->set_recipients($email_test);
            $mail_sender->set_content($message);
            $mail_sender->set_object($mail_object);
            
            $mail_sender->send();
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