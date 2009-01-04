<?php
/*##################################################
 *                              mail.class.php
 *                            -------------------
 *   begin                : March 11, 2005
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

class Mail
{
	## Public Methods ##
	//Constructeur.
	function Mail() 
	{
	}
	
	//Envoi du mail valide.
	function send($mail_to, $mail_objet, $mail_contents, $mail_from, $mail_header = '', $mail_sender = 'admin')
	{
		$this->sender = $mail_sender;
		$this->from = $mail_from;
		$this->to = $mail_to;
		
		if ($mail_sender == 'admin')
		{
			$this->_clean($mail_objet, $mail_contents);
			if (empty($mail_header))
				$this->_send_headers();
			else
				$this->header = $mail_header;
				
			//On envoi à l'aide de la fonction mail() de php.
			return @mail($mail_to, $this->objet, $this->contents, $this->header);
		}
		else
		{
			if ($this->check_validity($this->from))
			{
				$this->_clean($mail_objet, $mail_contents);
				if (empty($mail_header))
					$this->_send_headers();
				else
					$this->header = $mail_header;
					
				//On envoi à l'aide de la fonction mail() de php.
				return @mail($mail_to, $this->objet, $this->contents, $this->header);
			}
			else 
				return false;
		}		
	}	
	
    //Vérification de la validité du mail du posteur => Protection contre injection header.
    function check_validity()
    {
        global $LANG, $Errorh;

        $array_mail = explode(';', $this->from); //Récupération de l'adresse email du posteur.
        $this->from = $array_mail[0];
        
        if (!preg_match('`^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-zA-Z]{2,4}$`', $this->from))
        {
            $Errorh->handler('e_mail_format', E_USER_REDIRECT);
            return false;
        }
        else
        { 
            return true;
        }
    }
	
	## Private Methods ##
	//Nettoie les entrées.
	function _clean($mail_objet, $mail_contents)
	{
		if (get_magic_quotes_gpc())
		{
			$this->objet = stripslashes($mail_objet);
			$this->contents = stripslashes($mail_contents);
		}
		else
		{
			$this->objet = $mail_objet;
			$this->contents = $mail_contents;
		}
	}
	
	//Génération des headers du mail.
	function _send_headers()
	{
		global $LANG;
		
		$array_cc = explode(';', $this->to); //Récupération des adresses mails auxquelles il faut envoyer le mail en copie.
		$this->header .= 'From: "' . (($this->sender == 'admin') ? $LANG['admin'] : $LANG['user']) . ' ' . HOST . '" <' . $this->from . ">\r\n"; 

		$nbr_cc = count($array_cc);
		if ($nbr_cc > 1) 
		{	
			for ($i = 0; $i < $nbr_cc; $i++) 
				$this->header .= 'cc: ' . $array_cc[$i] . "\r\n";			
		}
	}
	
	
	## Private Attribute ##
	var $objet; //Objet du mail.
	var $contents;	//Contenu du mail.
	var $from; //Mail de l'envoyeur.
	var $sender; //Nom de l'envoyeur.
	var $header; //Contient le header du mail.
}

?>