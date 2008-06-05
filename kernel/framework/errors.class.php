<?php
/*##################################################
 *                                errors.class.php
 *                            -------------------
 *   begin                : April 12, 2007
 *   copyright          : (C) 2007 Viarre Régis
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

//Niveau de rapport d'erreurs.
@error_reporting(ERROR_REPORTING); 

//Constantes de base.
define('ARCHIVE_ALL_ERRORS', true); //Archivage de toutes les erreurs, quel que soit le type.
define('ARCHIVE_ERROR', true); //Archivage de l'erreur courante, quel que soit le type.
define('NO_ARCHIVE_ERROR', false); //N'archive pas l'erreur courante, quel que soit le type.
define('NO_LINE_ERROR', ''); //N'affiche pas la ligne de l'erreur courante.
define('NO_FILE_ERROR', ''); //N'affiche pas le fichier de l'erreur courante.

class Errors
{
	## Public Methods ##
	//Constructeur
	function Errors($archive_all = false)
	{
		$this->archive_all = $archive_all;
		//Récupération de l'adresse de redirection => constantes non initialisées.
		$this->redirect = 'http://' . $_SERVER['HTTP_HOST'] . preg_replace('`(/(.*))?/(.*)/(.*)\.php`', '$1', $_SERVER['PHP_SELF']);
	}	
	
	//Gestionnaire d'erreurs controlées par le développeur.
	function Error_handler($errstr, $errno, $errline = '', $errfile = '', $tpl_cond = '', $archive = false)
	{
		global $LANG, $Template;
		
		//Parsage du bloc seulement si une erreur à afficher.
		if( !empty($errstr) )
		{		
			switch($errno) 
			{
				//Message d'erreur demandant une redirection.
				case E_USER_REDIRECT:
				$this->error_log($errfile, $errline, $errno, $errstr, $archive);
				redirect($this->redirect . '/member/error' . transid('.php?e=' . $errstr, '', '&'));
				break;				
				//Message de succès, étrange pour une classe d'erreur non?
				case E_USER_SUCCESS:
				$errstr = sprintf($LANG['error_success'], $errstr, '', '');
				$Template->Assign_vars(array(
					'C_ERROR_HANDLER' . strtoupper($tpl_cond) => true,
					'ERRORH_IMG' => 'success',
					'ERRORH_CLASS' => 'error_success',
					'L_ERRORH' => $errstr
				));
				break;
				//Notice utilisateur.
				case E_USER_NOTICE:
				case E_NOTICE:
				$errstr = sprintf($LANG['error_notice'], $errstr, '', '');
				$Template->Assign_vars(array(
					'C_ERROR_HANDLER' . strtoupper($tpl_cond) => true,
					'ERRORH_IMG' => 'notice',
					'ERRORH_CLASS' => 'error_notice',
					'L_ERRORH' => $errstr
				));
				break;
				//Warning utilisateur.
				case E_USER_WARNING:
				case E_WARNING:
				$errstr = sprintf($LANG['error_warning'], $errstr, '', '');
				$Template->Assign_vars(array(
					'C_ERROR_HANDLER' . strtoupper($tpl_cond) => true,
					'ERRORH_IMG' => 'important',
					'ERRORH_CLASS' => 'error_warning',
					'L_ERRORH' => $errstr
				));
				break;
				//Erreur fatale.
				case E_USER_ERROR:
				case E_ERROR:
				//Enregistrement de l'erreur fatale dans tout les cas.
				$error_id = $this->error_log($errfile, $errline, $errno, $errstr, true);
				if( !empty($Session) && is_object($Session) )
					redirect($this->redirect . '/member/fatal' . transid('.php?error=' . $error_id, '', '&'));
				else
					redirect($this->redirect . '/member/fatal.php?error=' . $error_id);
				exit;
			}
		
			//Enregistrement de l'erreur si demandé.			
			if( $archive )
				return $this->error_log($errfile, $errline, $errno, $errstr, $archive);
			return true;
		}
		return false;
	}
	
	//Récupération des informations de la dernière erreur.
	function Get_last_error_log()
	{
		$errinfo = '';		
		$handle = @fopen(PATH_TO_ROOT . '/cache/error.log', 'r');
		if( $handle ) 
		{
			$i = 1;
			while( !feof($handle) ) 
			{
				$buffer = fgets($handle, 4096);
				if( $i == 2 )
					$errinfo['errno'] = $buffer;
				if( $i == 3 )
					$errinfo['errstr'] = $buffer;
				if( $i == 4 )
					$errinfo['errfile'] = $buffer;
				if( $i == 5 )
				{
					$errinfo['errline'] = $buffer;		
					$i = 0;	
				}
				$i++;				
			}
			@fclose($handle);
		}
		return $errinfo;
	}
	
	//Récupération du type de l'erreur.
	function Get_errno_class($errno)
	{
		switch($errno)
		{
			//Redirection utilisateur.
			case E_USER_REDIRECT:
			$class = 'error_fatal';
			break;
			//Notice utilisateur.
			case E_USER_NOTICE:
			case E_NOTICE:
			$class = 'error_notice';
			break;
			//Warning utilisateur.
			case E_USER_WARNING:
			case E_WARNING:
			$class = 'error_warning';
			break;
			//Erreur fatale.
			case E_USER_ERROR:
			case E_ERROR:	
			$class = 'error_fatal';
			break;
			//Erreur inconnue.
			default:
			$class = 'error_unknow';
		}
		return $class;
	}
	
	
	## Private Methods ##
	//Enregistre l'erreur dans le fichier de log.
	function error_log($errfile, $errline, $errno, $errstr, $archive)
	{		
		if( $archive || $this->archive_all )
		{				
			//Nettoyage de la chaîne avant enregistrement.
			$errstr = $this->clean_errstr($errstr);
			
		    $error = gmdate_format('Y-m-d H:i:s', time(), TIMEZONE_SYSTEM) . "\n";
		    $error .= $errno . "\n";
		    $error .= $errstr . "\n";
		    $error .= basename($errfile) . "\n";
		    $error .= $errline . "\n";
		   
			$handle = @fopen(PATH_TO_ROOT . '/cache/error.log', 'a+'); //On crée le fichier avec droit d'écriture et lecture.
			@fwrite($handle,  $error);
			@fclose($handle);
			return true;
		}
		return false;
	}
	
	//Nettoie la chaine d'erreur pour compresser le fichier.
	function clean_errstr($errstr)
	{
		$errstr = preg_replace("`\r|\n|\t`", "\n", $errstr);
		$errstr = preg_replace("`(\n){1,}`", '<br />', $errstr);
		return $errstr;
	}
	
	
	## Private Attribute ##
	var $archive_all; //Enregistrement des logs d'erreurs, pour tout les types d'erreurs.
	var $redirect;
}

?>