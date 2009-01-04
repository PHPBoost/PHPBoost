<?php
/*##################################################
 *                              errors.class.php
 *                            -------------------
 *   begin                : April 12, 2007
 *   copyright            : (C) 2007 Viarre Régis
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


//Constantes de base.
define('ARCHIVE_ALL_ERRORS', true); //Archivage de toutes les erreurs, quel que soit le type.
define('ARCHIVE_ERROR', true); //Archivage de l'erreur courante, quel que soit le type.
define('NO_ARCHIVE_ERROR', false); //N'archive pas l'erreur courante, quel que soit le type.
define('NO_LINE_ERROR', ''); //N'affiche pas la ligne de l'erreur courante.
define('NO_FILE_ERROR', ''); //N'affiche pas le fichier de l'erreur courante.
define('DISPLAY_ALL_ERROR', false); //N'affiche pas le fichier de l'erreur courante.

class Errors
{
	## Public Methods ##
	//Constructeur
	function Errors($archive_all = false)
	{
		$this->archive_all = $archive_all;
		
		//Récupération de l'adresse de redirection => constantes non initialisées.
		$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
		if (!$server_path)
			$server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
		$server_path = trim(dirname($server_path));
		
		$nbr_occur = substr_count(PATH_TO_ROOT, '..'); //On supprime les x dossiers par rapport au PATH_TO_ROOT
		for ($i = 0; $i < $nbr_occur; $i++)
			$server_path = str_replace(substr(strrchr($server_path, '/'), 0), '', $server_path);
		$this->redirect = 'http://' . $_SERVER['HTTP_HOST'] . $server_path;
		
		//On utilise notre propre handler pour la gestion des erreurs php
		set_error_handler(array($this, 'handler_php'));
	}
	
	//Gestionnaire d'erreur.
	function handler_php($errno, $errstr, $errfile, $errline)
	{
		global $LANG, $CONFIG;
		
		if (!($errno & ERROR_REPORTING)) //Niveau de repport d'erreur.
			return true;
		
		//Si une erreur est supprimé par un @ alors on passe
		if (!DISPLAY_ALL_ERROR && error_reporting() == 0)
			return true;
		
		switch ($errno)
		{
			//Notice utilisateur.
			case E_USER_NOTICE:
			case E_NOTICE:
				$errdesc = $LANG['e_notice'];
				$errimg = 'notice';
				$errclass = 'error_notice';
			break;
			//Warning utilisateur.
			case E_USER_WARNING:
			case E_WARNING:
				$errdesc = $LANG['e_warning'];
				$errimg = 'important';
				$errclass = 'error_warning';
			break;
			//Erreur fatale.
			case E_USER_ERROR:
			case E_ERROR:
				$errdesc = $LANG['error'];
				$errimg = 'stop';
				$errclass = 'error_fatal';
			break;
			//Erreur inconnue.
			default:
				$errdesc = $LANG['e_unknown'];
				$errimg = 'question';
				$errclass = 'error_unknow';
		}

		//On affiche l'erreur
		echo '<div class="' . $errclass . '" style="width:500px;margin:auto;padding:15px;">
			<img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' . $errimg . '.png" alt="" style="float:left;padding-right:6px;" />
			<strong>' . $errdesc . '</strong> : ' . $errstr . ' ' . $LANG['infile'] . ' <strong>' . $errfile . '</strong> ' . $LANG['atline'] . ' <strong>' . $errline . '</strong>
			<br />
		</div>';
		
		//Et on l'archive
		$this->_error_log($errfile, $errline, $errno, $errstr, true);
		
		//Dans le cas d'un E_USER_ERROR on arrête l'execution
		if ($errno == E_USER_ERROR)
			exit;
		
		//on ne veut pas que le gestionnaire d'erreur de php s'occupe de l'erreur en question
		return true;
	}
	
	//Gestionnaire d'erreurs controlées par le développeur.
	function handler($errstr, $errno, $errline = '', $errfile = '', $tpl_cond = '', $archive = false, $stop = true)
	{
		global $LANG, $Template;
		$_err_stop = retrieve(GET, '_err_stop', false);
		
		//Parsage du bloc seulement si une erreur à afficher.
		if (!empty($errstr))
		{
			switch ($errno)
			{
				//Message d'erreur demandant une redirection.
				case E_USER_REDIRECT:
    				$this->_error_log($errfile, $errline, $errno, $errstr, $archive);
    				if (!$_err_stop)
    				    redirect($this->redirect . '/member/error' . url('.php?e=' . $errstr . ' &amp;_err_stop=1;'));
				    else
				        die($errstr);
    				break;
				//Message de succès, étrange pour une classe d'erreur non?
				case E_USER_SUCCESS:
    				$errstr = sprintf($LANG['error_success'], $errstr, '', '');
    				$Template->assign_vars(array(
    					'C_ERROR_HANDLER' . strtoupper($tpl_cond) => true,
    					'ERRORH_IMG' => 'success',
    					'ERRORH_CLASS' => 'error_success',
    					'L_ERRORH' => $errstr
    				));
				break;
				//Notice utilisateur.
				case E_USER_NOTICE:
				case E_NOTICE:
    				$errstr = sprintf($LANG['error_notice_tiny'], $errstr, '', '');
    				$Template->assign_vars(array(
    					'C_ERROR_HANDLER' . strtoupper($tpl_cond) => true,
    					'ERRORH_IMG' => 'notice',
    					'ERRORH_CLASS' => 'error_notice',
    					'L_ERRORH' => $errstr
    				));
    				break;
				//Warning utilisateur.
				case E_USER_WARNING:
				case E_WARNING:
    				$errstr = sprintf($LANG['error_warning_tiny'], $errstr, '', '');
    				$Template->assign_vars(array(
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
    				$error_id = $this->_error_log($errfile, $errline, $errno, $errstr, true);
    				
                    if ($stop)
                    {
//                        echo ($_err_stop == true ? 'TRUE' : 'FALSE') . '<br />';
                        if (!$_err_stop)
                        {
                            if (!empty($Session) && is_object($Session))
                                redirect($this->redirect . '/member/fatal' . url('.php?error=' . $error_id . '&_err_stop=1'));
                            else
                                redirect($this->redirect . '/member/fatal' . url('.php?error=' . $error_id . '&_err_stop=1'));
                            exit;
                        }
                        else
                            die($errstr);
                        
                    }
			}
		
			//Enregistrement de l'erreur si demandé.
			if ($archive)
				return $this->_error_log($errfile, $errline, $errno, $errstr, $archive);
			return true;
		}
		return false;
	}
	
	//Récupération des informations de la dernière erreur.
	function get_last__error_log()
	{
		$errinfo = '';
		$handle = @fopen(PATH_TO_ROOT . '/cache/error.log', 'r');
		if ($handle)
		{
			$i = 1;
			while (!feof($handle))
			{
				$buffer = fgets($handle, 4096);
				if ($i == 2)
					$errinfo['errno'] = $buffer;
				if ($i == 3)
					$errinfo['errstr'] = $buffer;
				if ($i == 4)
					$errinfo['errfile'] = $buffer;
				if ($i == 5)
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
	function get_errno_class($errno)
	{
		switch ($errno)
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
	function _error_log($errfile, $errline, $errno, $errstr, $archive)
	{
		if ($archive || $this->archive_all)
		{
			//Nettoyage de la chaîne avant enregistrement.
			$errstr = $this->_clean_error_string($errstr);
			
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
	function _clean_error_string($errstr)
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
