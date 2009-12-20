<?php
/*##################################################
 *                              mail.class.php
 *                            -------------------
 *   begin                : March 11, 2005
 *   copyright            : (C) 2009 Viarre Régis, Benoit Sautel
 *   email                : crowkait@phpboost.com, ben.popeye@phpboost.com
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

define('CRLF', "\r\n");
define('MIME_FORMAT_TEXT', 'text/plain');
define('MIME_FORMAT_HTML', 'text/html');

/**
 * @package io
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc This class allows you to send mails without having to deal with the mail headers and parameters.
 */

class Mail
{
    /**
     * @desc Builds a Mail object.
     */
    function Mail()
    {
    }

    /**
     * @desc Sets the mail sender.
     * @param string $sender The mail sender address.
     * @param string $sender_name 'admin' if the mail is sent by the administrator, 'user' otherwise.
     * @return bool True, if the mail sender address is correct, false otherwise.
     */
    function set_sender($sender, $sender_name = 'admin')
    {
        global $LANG, $CONFIG;
        $this->sender_name = str_replace('"', '', $CONFIG['site_name'] . ' - ' . ($sender_name == 'admin' ? $LANG['admin'] : $LANG['user']));

        if (Mail::check_validity($sender))
        {
            $this->sender_mail = $sender;
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * @desc Sets the recipient(s) of the mail.
     * @param string $recipients Recipients of the mail. It they are more than one, use the comma to separate their addresses.
     */
    function set_recipients($recipients)
    {
        $this->recipients = '';

        $recipients_list = explode(';', $recipients);
        $recipients_list = array_map('trim', $recipients_list);

        //We check that each recipient address is correct
        foreach ($recipients_list as $recipient)
        {
            if (Mail::check_validity($recipient))
            {
                $this->recipients[] = $recipient;
            }
        }
         
        //We return the setting status.
        if (!empty($this->recipients))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * @desc Sets the mail object
     * @param string $object Mail object
     */
    function set_object($object)
    {
        $this->object = $object;
    }

    /**
     * @desc The mail content.
     * @param string $content The mail content
     */
    function set_content($content)
    {
        $this->content = $content;
    }

    /**
     * @desc Sets the headers. Forces them, they won't be generated automatically.
     * @param string $headers The mail headers.
     */
    function set_headers($headers)
    {
        $this->headers = $headers;
    }

    /**
     * @desc Returns the mail address of the sender.
     * @return string the sender's mail address
     */
    function get_sender_mail()
    {
        return $this->sender_mail;
    }

    /**
     * @desc Returns the mail sender's name.
     * @return string The mail sender's name.
     */
    function get_sender_name()
    {
        return $this->sender_name;
    }

    /**
     * @desc Returns the mail recipients' addresses. They are separated by a comma.
     * @return string The mail recipients.
     */
    function get_recipients()
    {
        return $this->recipients;
    }

    /**
     * @desc Returns the mail object.
     * @return string The mail object.
     */
    function get_object()
    {
        return $this->object;
    }

    /**
     * @desc Returns the mail content.
     * @return string The mail content.
     */
    function get_content()
    {
        return $this->content;
    }

    /**
     * @desc Returns the mail headers.
     * @return string The mail headers.
     */
    function get_headers()
    {
        return $this->headers;
    }
    
    /**
     * Sets the MIME type of the mail content
     * @param string $mime MIME_FORMAT_TEXT or MIME_FORMAT_HTML
     */
    function set_mime($mime)
    {
        $this->format = $mime;
    }
    
    /**
     * Returns the MIME type of the mail content
     * @return string the MIME type
     */
    function get_mime()
    {
        return $this->format();
    }
    
    /**
     * @desc Sends the mail.
     * @deprecated
     * @param string $mail_to The mail recipients' address.
     * @param string $mail_object The mail object.
     * @param string $mail_content content of the mail
     * @param string $mail_from The mail sender's address.
     * @param string $mail_header The header you want to specify (it you don't specify it, it will be generated automatically).
     * @param string $sender_name The mail sender's name. If you don't use this parameter, the name of the site administrator will be taken.
     * @return bool True if the mail could be sent, false otherwise.
     */
    function send_from_properties($mail_to, $mail_object, $mail_content, $mail_from, $mail_header = null, $sender_name = 'admin')
    {
        // Initialization of the mail properties
        $recipient = $this->set_recipients($mail_to);
        $sender = $this->set_sender($mail_from, $sender_name);
        if (!$recipient || !$sender)
        {
            return false;
        }
         
        $this->set_object($mail_object);
        $this->set_content($mail_content);

        $this->set_headers($mail_header);

        // Let's send the mail
        return $this->send();
    }

    /** 
     * @desc Sends the mail. 
     * @return bool True if the mail could be sent, false otherwise.
     */
    function send()
    {
        if (empty($this->headers))
        {
            $this->_generate_headers();
        }
        
        $recipients = trim(implode(', ', $this->recipients), ', ');
        return @mail($recipients, $this->object, $this->content, $this->headers);
    }
    
    /**
     * @desc Checks that an email address has a correct form.
     * @return bool True if it's valid, false otherwise.
     */
    public static function check_validity($mail_address)
    {
        return preg_match('`^(?:[a-z0-9_!#$%&\'*+/=?^|~-].?){0,63}[a-z0-9_!#$%&\'*+/=?^|~-]+@(?:[a-z0-9_-]{2,}\.)+([a-z0-9_-]{2,}\.)*[a-z]{2,4}$`i', $mail_address);
    }
    

    ## Protected Methods ##
    /**
    * @access protected
    * @desc Generates the mail headers.
    */
    function _generate_headers()
    {
        global $LANG;

        $this->header = '';
        
        //Sender
        $this->_add_header_field('From', '"' . $this->sender_name . ' ' . HOST . '" <' . $this->sender_mail . '>');

        //Recipients
        $recipients = '';
        $nb_recipients = count($this->recipients);
        for ($i = 0; $i < $nb_recipients; $i++)
        {
            $recipients .= '"' . $this->recipients[$i] . '" <' . $this->recipients[$i] . '>';
            if ($i < $nb_recipients - 1)
            {
            	$recipients .= ', ';
            }
        }
        
        $this->_add_header_field('MIME-Version',  '1.0');
        $this->_add_header_field('Content-type', $this->format . '; charset=ISO-8859-1');
    }
    
    
    /**
     * @desc add the current coupe <code>$field</code> and <code>$value</code> to the mail headers
     * @param string $field the header field 
     * @param string $value the header value
     */
    function _add_header_field($field, $value) {
        $this->headers .= wordwrap($field . ': ' . $value, 78, "\n ") . CRLF;
    }

    ## Private Attributes ##
    /**
    * @var sting object of the mail
    */
    var $object = '';

    /**
     * @var string content of the mail
     */
    var $content = '';

    /**
     * @var string Address of the mail sender.
     */
    var $sender_mail = '';

    /**
     * @var string The mail sender name.
     */
    var $sender_name = '';

    /**
     * @var The mail headers.
     */
    var $headers = '';

    /**
     * @var string[] Recipients of the mail. If they are more than one, a comma separates their addresses.
     */
    var $recipients = array();
    
    /**
     * @var string MIME of the mail content
     */
    var $format = MIME_FORMAT_TEXT; 
}

?>
