<?php
/*##################################################
 *                            sessions.class.php
 *                            -------------------
 *   begin                : July 04, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *   Sessions v4.0.0 
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
define('AUTOCONNECT', true);
define('NO_AUTOCONNECT', false);
define('ALREADY_HASHED', true);

class Sessions
{
	## Public Attribute ##
	var $data = array(); //Tableau contenant les informations de session.
	var $session_mod = 0; //Variable contenant le mode de session à utiliser pour récupérer les infos.
	var $autoconnect = array(); //Vérification de la session pour l'autoconnexion.
	
	## Public Methods ##
	function action_checker()
	{
		global $Session, $Sql;
		
		//Module de connexion.
		$login = retrieve(POST, 'login', '');
		$password = retrieve(POST, 'password', '', TSTRING_UNSECURE);
		$autoconnexion = retrieve(POST, 'auto', false);
		
		if (retrieve(GET, 'disconnect', false)) //Déconnexion.
		{
			$Session->end();
			redirect(get_start_page());
		}
		elseif (retrieve(POST, 'connect', false) && !empty($login) && !empty($password)) //Création de la session.
		{
			$user_id = $Sql->query("SELECT user_id FROM ".PREFIX."member WHERE login = '" . $login . "'", __LINE__, __FILE__);
			if (!empty($user_id)) //Membre existant.
			{
				$info_connect = $Sql->query_array('member', 'level', 'user_warning', 'last_connect', 'test_connect', 'user_ban', 'user_aprob', "WHERE user_id='" . $user_id . "'", __LINE__, __FILE__);
				$delay_connect = (time() - $info_connect['last_connect']); //Délai entre deux essais de connexion.
				$delay_ban = (time() - $info_connect['user_ban']); //Vérification si le membre est banni.
				
				if ($delay_ban >= 0 && $info_connect['user_aprob'] == '1' && $info_connect['user_warning'] < '100') //Utilisateur non (plus) banni.
				{
					if ($delay_connect >= 600) //5 nouveau essais, 10 minutes après.
					{
						$Sql->query_inject("UPDATE ".PREFIX."member SET last_connect='" . time() . "', test_connect = 0 WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__); //Remise à zéro du compteur d'essais.
						$error_report = $Session->start($user_id, $password, $info_connect['level'], SCRIPT, QUERY_STRING, '', $autoconnexion); //On lance la session.
					}
					elseif ($delay_connect >= 300) //2 essais 5 minutes après
					{
						$Sql->query_inject("UPDATE ".PREFIX."member SET last_connect='" . time() . "', test_connect = 3 WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__); //Redonne 2 essais.
						$error_report = $Session->start($user_id, $password, $info_connect['level'], SCRIPT, QUERY_STRING, '', $autoconnexion); //On lance la session.
					}
					elseif ($info_connect['test_connect'] < 5) //Succès.
						$error_report = $Session->start($user_id, $password, $info_connect['level'], SCRIPT, QUERY_STRING, '', $autoconnexion); //On lance la session.
					else //plus d'essais
						redirect(HOST . DIR . '/member/error.php?e=e_member_flood#errorh');
				}
				elseif ($info_connect['user_aprob'] == '0')
					redirect(HOST . DIR . '/member/error.php?e=e_unactiv_member#errorh');
				elseif ($info_connect['user_warning'] == '100')
					redirect(HOST . DIR . '/member/error.php?e=e_member_ban_w#errorh');
				else
				{
					$delay_ban = ceil((0 - $delay_ban)/60);
					redirect(HOST . DIR . '/member/error.php?e=e_member_ban&ban=' . $delay_ban . '#errorh');
				}
						
				if (!empty($error_report)) //Erreur
				{
					$Sql->query_inject("UPDATE ".PREFIX."member SET last_connect='" . time() . "', test_connect = test_connect + 1 WHERE user_id='" . $user_id . "'", __LINE__, __FILE__);
					$info_connect['test_connect']++;
					$info_connect['test_connect'] = 5 - $info_connect['test_connect'];
					redirect(HOST . DIR . '/member/error.php?e=e_member_flood&flood=' . $info_connect['test_connect'] . '#errorh');
				}
				elseif ($info_connect['test_connect'] > 0) //Succès redonne tous les essais.
					$Sql->query_inject("UPDATE ".PREFIX."member SET last_connect='" . time() . "', test_connect = 0 WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__); //Remise à zéro du compteur d'essais.
			}
			else
				redirect(HOST . DIR . '/member/error.php?e=e_unexist_member#errorh');
			
			$query_string = QUERY_STRING;
			$query_string = !empty($query_string) ? '?' . QUERY_STRING . '&sid=' . $Session->data['session_id'] . '&suid=' . $Session->data['user_id'] : '?sid=' . $Session->data['session_id'] . '&suid=' . $Session->data['user_id'];
			
			//Redirection avec les variables de session dans l'url.
			if (SCRIPT != DIR . '/member/error.php')
				redirect(HOST . SCRIPT . $query_string);
			else
				redirect(get_start_page());
		}
	}
	
	//Lancement de la session après récupèration des informations par le formulaire de connexion.
	function start($user_id, $password, $level, $session_script, $session_script_get, $session_script_title, $autoconnect = false, $already_hashed = false)
	{
        global $CONFIG, $Sql;
		
        $pwd = $password;
        if (!$already_hashed)
            $password = strhash($password);
        
		$error = '';
		$session_script = addslashes($session_script);
		$session_script_title = addslashes($session_script_title);
		
		########Insertion dans le compteur si l'ip est inconnue.########
		$check_ip = $Sql->query("SELECT COUNT(*) FROM ".PREFIX."compteur WHERE ip = '" . USER_IP . "'", __LINE__, __FILE__);
		$_include_once = empty($check_ip) && ($this->_check_bot(USER_IP) === false);
		if ($_include_once)
		{
			//Récupération forcée de la valeur du total de visites, car problème de CAST avec postgresql.
			$Sql->query_inject("UPDATE ".LOW_PRIORITY." ".PREFIX."compteur SET ip = ip + 1, time = '" . gmdate_format('Y-m-d', time(), TIMEZONE_SYSTEM) . "', total = total + 1 WHERE id = 1", __LINE__, __FILE__);
			$Sql->query_inject("INSERT ".LOW_PRIORITY." INTO ".PREFIX."compteur (ip, time, total) VALUES('" . USER_IP . "', '" . gmdate_format('Y-m-d', time(), TIMEZONE_SYSTEM) . "', 0)", __LINE__, __FILE__);
			
			//Mise à jour du last_connect, pour un membre qui vient d'arriver sur le site.
			if ($user_id !== '-1')
				$Sql->query_inject("UPDATE ".PREFIX."member SET last_connect = '" . time() . "' WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
		}
		
		//On lance les stats.
		import('core/stats_saver');
		StatsSaver::compute_referer();
		if ($_include_once)
			StatsSaver::compute_users();

		########Génération d'un ID de session unique########
		$session_uniq_id = strhash(uniqid(mt_rand(), true)); //On génère un numéro de session aléatoire.
		$this->data['user_id'] = $user_id;
		$this->data['session_id'] = $session_uniq_id;
		
		########Session existe t-elle?#########
		$this->_garbage_collector(); //On nettoie avant les sessions périmées.

		if ($user_id !== '-1')
		{
			//Suppression de la session visiteur générée avant l'enregistrement!
			$Sql->query_inject("DELETE FROM ".PREFIX."sessions WHERE session_ip = '" . USER_IP . "' AND user_id = -1", __LINE__, __FILE__);
			
			//En cas de double connexion, on supprime le cookie et la session associée de la base de données!
			if (isset($_COOKIE[$CONFIG['site_cookie'] . '_data'])) 
				setcookie($CONFIG['site_cookie'].'_data', '', time() - 31536000, '/');
			$Sql->query_inject("DELETE FROM ".PREFIX."sessions WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
			
			//Récupération password BDD
			$password_m = $Sql->query("SELECT password FROM ".PREFIX."member WHERE user_id = '" . $user_id . "' AND user_warning < 100 AND '" . time() . "' - user_ban >= 0", __LINE__, __FILE__);
			if (!empty($password) && (($password === $password_m) || (md5($pwd) === $password_m))) //Succès! => md5 gestion des vieux mdp
			{
                if (md5($pwd) === $password_m) // Si le mot de passe est encore stocké en md5, on l'update
                    $Sql->query_inject("UPDATE ".PREFIX."member SET password = '" . $password . "' WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
				
				$Sql->query_inject("INSERT INTO ".PREFIX."sessions VALUES('" . $session_uniq_id . "', '" . $user_id . "', '" . $level . "', '" . USER_IP . "', '" . time() . "', '" . $session_script . "', '" . $session_script_get . "', '" . $session_script_title . "', '0', '', '', '')", __LINE__, __FILE__);
			}
			else //Session visiteur, echec!
			{
				$Sql->query_inject("INSERT INTO ".PREFIX."sessions VALUES('" . $session_uniq_id . "', -1, -1, '" . USER_IP . "', '" . time() . "', '" . $session_script . "', '" . $session_script_get . "', '" . $session_script_title . "', '0', '" . $CONFIG['theme'] . "', '" . $CONFIG['lang'] . "', '')", __LINE__, __FILE__);
				
				$delay_ban = $Sql->query("SELECT user_ban FROM ".PREFIX."member WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
				if ((time() - $delay_ban) >= 0)
					$error = 'echec';
				else
					$error = $delay_ban;
			}
		}
		else //Session visiteur valide.
			$Sql->query_inject("INSERT INTO ".PREFIX."sessions VALUES('" . $session_uniq_id . "', -1, -1, '" . USER_IP . "', '" . time() . "', '" . $session_script . "', '" . $session_script_get . "', '" . $session_script_title . "', '0', '" . $CONFIG['theme'] . "', '" . $CONFIG['lang'] . "', '')", __LINE__, __FILE__);
		
		########Génération du cookie de session########
		$data = array();
		$data['user_id'] = isset($user_id) ? numeric($user_id) : -1;
		$data['session_id'] = $session_uniq_id;
		
		setcookie($CONFIG['site_cookie'].'_data', serialize($data), time() + 31536000, '/');
		
		########Génération du cookie d'autoconnection########
		if ($autoconnect === true)
		{
			$session_autoconnect['user_id'] = $user_id;
			$session_autoconnect['pwd'] = $password;
			
			setcookie($CONFIG['site_cookie'].'_autoconnect', serialize($session_autoconnect), time() + 31536000, '/');
		}
		
		unset($pwd);
		return $error;
	}
	
	//Récupération des informations sur le membre.
	function load()
	{
		global $Sql, $CONFIG;
		
		$this->_get_id(); //Récupération des identifiants de session.
		
		########Valeurs à retourner########
		$userdata = array();
		if ($this->data['user_id'] > 0 && !empty($this->data['session_id']))
		{
			//Récupère également les champs membres supplémentaires
			$result = $Sql->query_while("SELECT m.user_id AS m_user_id, m.login, m.level, m.user_groups, m.user_lang, m.user_theme, m.user_mail, m.user_pm, m.user_editor, m.user_timezone, m.user_avatar avatar, m.user_readonly, s.modules_parameters, me.*
			FROM ".PREFIX."member m
            JOIN ".PREFIX."sessions s ON s.user_id = '" . $this->data['user_id'] . "' AND s.session_id = '" . $this->data['session_id'] . "'
			LEFT JOIN ".PREFIX."member_extend me ON me.user_id = '" . $this->data['user_id'] . "'
			WHERE m.user_id = '" . $this->data['user_id'] . "'", __LINE__, __FILE__);
			$userdata = $Sql->fetch_assoc($result);
         
			if (!empty($userdata)) //Succès.
			{    
				$this->data = array_merge($userdata, $this->data); //Fusion des deux tableaux.
			}
			elseif ($this->session_mod == 0) //Aucune entrée associée dans la base de donnée, on tente une connexion auto.
			{
				$this->autoconnect['user_id'] = $this->data['user_id'];
				$this->autoconnect['session_id'] = $this->data['session_id'];
			}
		}	
		else
		{
			//Récupère également les champs membres supplémentaires
			$result = $Sql->query_while("SELECT modules_parameters, user_theme, user_lang
			FROM ".PREFIX."sessions 
			WHERE user_id = '-1' AND session_id = '" . $this->data['session_id'] . "'", __LINE__, __FILE__);
			$userdata = $Sql->fetch_assoc($result);
         
			if (!empty($userdata)) //Succès.
				$this->data = array_merge($userdata, $this->data); //Fusion des deux tableaux.
		}
		
		$this->data['user_id'] = isset($userdata['m_user_id']) ? (int)$userdata['m_user_id'] : -1;
		$this->data['login'] = isset($userdata['login']) ? $userdata['login'] : '';	
		$this->data['level'] = isset($userdata['level']) ? (int)$userdata['level'] : -1;		
		$this->data['user_groups'] = isset($userdata['user_groups']) ? $userdata['user_groups'] : '';
		$this->data['user_lang'] = isset($userdata['user_lang']) ? $userdata['user_lang'] : $CONFIG['lang']; //Langue membre
		$this->data['user_theme'] = isset($userdata['user_theme']) ? $userdata['user_theme'] : $CONFIG['theme']; //Thème membre		
		$this->data['user_mail'] = isset($userdata['user_mail']) ? $userdata['user_mail'] : '';
		$this->data['user_pm'] = isset($userdata['user_pm']) ? $userdata['user_pm'] : '0';	
		$this->data['user_readonly'] = isset($userdata['user_readonly']) ? $userdata['user_readonly'] : '0';
		$this->data['user_editor'] = !empty($userdata['user_editor']) ? $userdata['user_editor'] : $CONFIG['editor'];
		$this->data['user_timezone'] = isset($userdata['user_timezone']) ? $userdata['user_timezone'] : $CONFIG['timezone'];
		$this->data['avatar'] = isset($userdata['avatar']) ? $userdata['avatar'] : '';
		$this->data['modules_parameters'] = isset($userdata['modules_parameters']) ? $userdata['modules_parameters'] : '';
	}
	
	//Vérification de la session.
	function check($session_script_title)
	{
		global $CONFIG, $Sql;

		$session_script = str_replace(DIR, '', SCRIPT);
		$session_script_get = QUERY_STRING;
		$check_autoconnect = (!empty($this->autoconnect['session_id']) && $this->autoconnect['user_id'] > 0);
		if ((!empty($this->data['session_id']) && $this->data['user_id'] > 0) || $check_autoconnect)
		{
			if (!$check_autoconnect) //Mode de connexion directe par le formulaire.
			{
				$this->autoconnect['session_id'] = $this->data['session_id'];
				$this->autoconnect['user_id'] = $this->data['user_id'];
			}
				
			//Localisation du membre.
			if (!defined('NO_SESSION_LOCATION'))
				$location = " session_script = '" . addslashes($session_script) . "', session_script_get = '" . addslashes($session_script_get) . "', session_script_title = '" . addslashes($session_script_title) . "', ";
			else
				$location = '';
			
			//On modifie le session_flag pour forcer mysql à modifier l'entrée, pour prendre en compte la mise à jour par mysql_affected_rows().
			$resource = $Sql->query_inject("UPDATE ".LOW_PRIORITY." ".PREFIX."sessions SET session_ip = '" . USER_IP . "', session_time = '" . time() . "', " . $location . " session_flag = 1 - session_flag WHERE session_id = '" . $this->autoconnect['session_id'] . "' AND user_id = '" . $this->autoconnect['user_id'] . "'", __LINE__, __FILE__);			
			if ($Sql->affected_rows($resource, "SELECT COUNT(*) FROM ".PREFIX."sessions WHERE session_id = '" . $this->autoconnect['session_id'] . "' AND user_id = '" . $this->autoconnect['user_id'] . "'") == 0) //Aucune session lancée.
			{
				if ($this->_autoconnect($session_script, $session_script_get, $session_script_title) === false) //On essaie de lancer la session automatiquement.
				{					
					if (isset($_COOKIE[$CONFIG['site_cookie'].'_data']))
						setcookie($CONFIG['site_cookie'].'_data', '', time() - 31536000, '/'); //Destruction cookie.						
					
					//Redirection une fois la session lancée.
					if (QUERY_STRING != '') 
						redirect(HOST . SCRIPT . '?' . QUERY_STRING);
					else
						redirect(HOST . SCRIPT);
				}
			}
		}
		else //Visiteur
		{
			//Localisation du visiteur.
			if (!defined('NO_SESSION_LOCATION'))
				$location = " session_script = '" . addslashes($session_script) . "', session_script_get = '" . addslashes($session_script_get) . "', session_script_title = '" . addslashes($session_script_title) . "', ";
			else
				$location = '';
				
			//On modifie le session_flag pour forcer mysql à modifier l'entrée, pour prendre en compte la mise à jour par mysql_affected_rows().
			$resource = $Sql->query_inject("UPDATE ".LOW_PRIORITY." ".PREFIX."sessions SET session_ip = '" . USER_IP . "', session_time = '" . (time() + 1) . "', " . $location . " session_flag = 1 - session_flag WHERE user_id = -1 AND session_ip = '" . USER_IP . "'", __LINE__, __FILE__);			
			if ($Sql->affected_rows($resource, "SELECT COUNT(*) FROM ".PREFIX."sessions WHERE user_id = -1 AND session_ip = '" . USER_IP . "'") == 0) //Aucune session lancée.
			{
				if (isset($_COOKIE[$CONFIG['site_cookie'].'_data']))
					setcookie($CONFIG['site_cookie'].'_data', '', time() - 31536000, '/'); //Destruction cookie.
				$this->start('-1', '', '-1', $session_script, $session_script_get, $session_script_title, false, ALREADY_HASHED); //Session visiteur
			}
		}
	}
	
	//Fin de la session
	function end()
	{
		global $CONFIG, $Sql;
			
		$this->_get_id();
			
		//On supprime la session de la bdd.
		$Sql->query_inject("DELETE FROM ".PREFIX."sessions WHERE session_id = '" . $this->data['session_id'] . "'", __LINE__, __FILE__);
		
		if (isset($_COOKIE[$CONFIG['site_cookie'].'_data'])) //Session cookie?
			setcookie($CONFIG['site_cookie'].'_data', '', time() - 31536000, '/'); //On supprime le cookie.		
		
		if (isset($_COOKIE[$CONFIG['site_cookie'].'_autoconnect']))
			setcookie($CONFIG['site_cookie'].'_autoconnect', '', time() - 31536000, '/'); //On supprime le cookie.
		
		$this->_garbage_collector();
	}
	
	//Récupère les paramtères de la sessions.
	function set_module_paramaters($paramaters)
	{
		$modules_parameters = unserialize($this->data['modules_parameters']);
		$modules_parameters[MODULE_NAME] = $paramaters;
		
		$Sql->query_inject("UPDATE ".PREFIX."sessions SET modules_parameters = '" . serialize($modules_parameters) . "'", __LINE__, __FILE__);		
	}
	
	//Récupère les paramtères de la sessions.
	function get_module_paramaters()
	{
		$array = unserialize($this->data['modules_parameters']);
		return isset($array[MODULE_NAME]) ? $array[MODULE_NAME] : '';
	}
	
	## Private Méthods ##
	//Récupération des l'identifiants de session.
	function _get_id()
	{
		global $CONFIG, $Sql;
		
		//Suppression d'éventuelles données dans ce tableau.
		$this->data = array();
		
		$this->data['session_id'] = '';
		$this->data['user_id'] = -1;
		$this->autoconnect['session_id'] = '';
		$this->autoconnect['user_id'] = -1;
		
		$this->session_mod = 0;

		$sid = retrieve(GET, 'sid', '');
		$suid = retrieve(GET, 'suid', 0);
		
		########Cookie Existe?########
		if (isset($_COOKIE[$CONFIG['site_cookie'].'_data']))
		{
			//Redirection pour supprimer les variables de session en clair dans l'url.
			if (isset($_GET['sid']) && isset($_GET['suid']))
			{
				$query_string = preg_replace('`&?sid=(.*)&suid=(.*)`', '', QUERY_STRING);
				redirect(HOST . SCRIPT . (!empty($query_string) ? '?' . $query_string : ''));				
			}
			$session_data = sunserialize($_COOKIE[$CONFIG['site_cookie'].'_data']);
			
			$this->data['session_id'] = isset($session_data['session_id']) ? strprotect($session_data['session_id']) : ''; //Validité du session id.
			$this->data['user_id'] = isset($session_data['user_id']) ? numeric($session_data['user_id']) : ''; //Validité user id?
		}	
		########SID Existe?########
		elseif (!empty($sid) && $suid > 0)
		{
			$this->data['session_id'] = $sid; //Validité du session id.
			$this->data['user_id'] = $suid; //Validité user id?
			$this->session_mod = 1;
		}
	}
	
	//Récupération de session en autoconnect.
	function _autoconnect($session_script, $session_script_get, $session_script_title)
	{
		global $CONFIG, $Sql;
		
		########Cookie Existe?########
		if (isset($_COOKIE[$CONFIG['site_cookie'].'_autoconnect']))
		{
			$session_autoconnect = isset($_COOKIE[$CONFIG['site_cookie'].'_autoconnect']) ? sunserialize($_COOKIE[$CONFIG['site_cookie'].'_autoconnect']) : array();
			$session_autoconnect['user_id'] = !empty($session_autoconnect['user_id']) ? numeric($session_autoconnect['user_id']) : ''; //Validité user id?.
			$session_autoconnect['pwd'] = !empty($session_autoconnect['pwd']) ? strprotect($session_autoconnect['pwd']) : ''; //Validité password.
			$level = $Sql->query("SELECT level FROM ".PREFIX."member WHERE user_id = '" . $session_autoconnect['user_id'] . "' AND password = '" . $session_autoconnect['pwd'] . "'", __LINE__, __FILE__);
			
			if (!empty($session_autoconnect['user_id']) && !empty($session_autoconnect['pwd']) && $level != '')
			{
				$error_report = $this->start($session_autoconnect['user_id'], $session_autoconnect['pwd'], $level, $session_script, $session_script_get, $session_script_title, true, ALREADY_HASHED); //Lancement d'une session utilisateur.
				
				//Gestion des erreurs pour éviter un brute force.
				if ($error_report === 'echec')
				{
					$Sql->query_inject("UPDATE ".PREFIX."member SET last_connect='" . time() . "', test_connect = test_connect + 1 WHERE user_id='" . $session_autoconnect['user_id'] . "'", __LINE__, __FILE__);
					
					$test_connect = $Sql->query("SELECT test_connect FROM ".PREFIX."member WHERE user_id = '" . $session_autoconnect['user_id'] . "'", __LINE__, __FILE__);
					
					setcookie($CONFIG['site_cookie'].'_autoconnect', '', time() - 31536000, '/'); //On supprime le cookie.
					
					redirect(HOST . DIR . '/member/error.php?flood=' . (5 - ($test_connect + 1)));
				}
				elseif (is_numeric($error_report))
				{
					setcookie($CONFIG['site_cookie'].'_autoconnect', '', time() - 31536000, '/'); //On supprime le cookie.
					
					$error_report = ceil($error_report/60);
					redirect(HOST . DIR . '/member/error.php?ban=' . $error_report);
				}
				else //Succès on recharge la page.
				{
					//On met à jour la date de dernière connexion. 
					$Sql->query_inject("UPDATE ".PREFIX."member SET last_connect = '" . time() . "' WHERE user_id = '" . $session_autoconnect['user_id'] . "'", __LINE__, __FILE__);
					
					if (QUERY_STRING != '')
						redirect(HOST . SCRIPT . '?' . QUERY_STRING);
					else
						redirect(HOST . SCRIPT);					
				}
			}
			else
				return false;
		}	
		return false;
	}
	
	//Suppression des sessions expirées par le garbage collector.
	function _garbage_collector() 
	{
		global $CONFIG, $Sql;
			
		$Sql->query_inject("DELETE 
		FROM ".PREFIX."sessions 
		WHERE session_time < '" . (time() - $CONFIG['site_session']) . "' 
		OR (session_time < '" . (time() - $CONFIG['site_session_invit']) . "' AND user_id = -1)", __LINE__, __FILE__);
	}
	
	//Détecte les principaux robots par plage ip, retourne leurs noms, et enregistre le nombre et l'heure de passages dans un fichier texte.
	function _check_bot($user_ip)
	{
		$_SERVER['HTTP_USER_AGENT'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		
		if (preg_match('`(w3c|http:\/\/|bot|spider|Gigabot|gigablast.com)+`i', $_SERVER['HTTP_USER_AGENT']))
			return 'unknow_bot';
			
		//Chaque ligne représente une plage ip.
		$plage_ip = array(
			'66.249.64.0' => '66.249.95.255',
			'209.85.128.0' => '209.85.255.255', 
			'65.52.0.0' => '65.55.255.255',
			'207.68.128.0' => '207.68.207.255',
			'66.196.64.0' => '66.196.127.255',
			'68.142.192.0' => '68.142.255.255',
			'72.30.0.0' => '72.30.255.255',
			'193.252.148.0' => '193.252.148.255',
			'66.154.102.0' => '66.154.103.255',
			'209.237.237.0' => '209.237.238.255',
			'193.47.80.0' => '193.47.80.255'
		);

		//Nom des bots associés.
		$array_robots = array(
			'Google bot',
			'Google bot',
			'Msn bot',
			'Msn bot',
			'Yahoo Slurp',
			'Yahoo Slurp',
			'Yahoo Slurp',
			'Voila',
			'Gigablast',
			'Ia archiver',
			'Exalead'
		);
		
		//Ip de l'utilisateur au format numérique.
		$user_ip = ip2long($user_ip);	

		//On explore le tableau pour identifier les robots
		$r = 0;
		foreach ($plage_ip as $start_ip => $end_ip)
		{	
			$start_ip = ip2long($start_ip);
			$end_ip = ip2long($end_ip);			
			
			//Comparaison pour chaque partie de l'ip, si l'une d'entre elle est fausse l'instruction est stopée.
			if ($user_ip >= $start_ip && $user_ip <= $end_ip) 
			{
				//Insertion dans le fichier texte des visites des robots.
				$file_path = PATH_TO_ROOT . '/cache/robots.txt';
				if (!file_exists($file_path)) 
				{
					$file = @fopen($file_path, 'w+'); //Si le fichier n'existe pas on le crée avec droit d'écriture et lecture.
					@fwrite($file, serialize(array())); //On insère un tableau vide.
					@fclose($file);
				}

				if (is_file($file_path) && is_writable($file_path)) //Fichier accessible en écriture.
				{
					$robot = $array_robots[$r]; //Nom du robot.
					$time = gmdate_format('YmdHis', time(), TIMEZONE_SYSTEM); //Date et heure du dernier passage!
					
					$line = file($file_path);
					$data = sunserialize($line[0]); //Renvoi la première ligne du fichier (le array précédement crée).
					
					if (!isset($data[$robot]))
						$data[$robot] = $robot . '/1/' . $time; //Création du array contenant les valeurs.
						
					$array_info = explode('/', $data[$robot]); //Récuperation des valeurs.
					if ($array_robots[$r] === $array_info[0]) //Robo repasse.
					{
						$array_info[1]++; //Nbr de  visite.
						$array_info[2] = $time; //Date Dernière visite
						$data[$robot] = implode('/', $array_info);
					}
					else
						$data[$robot] = $robot . '/1/' . $time; //Création du array contenant les valeurs.
					
					$file = @fopen($file_path, 'r+');
					fwrite($file, serialize($data)); //On stock le tableau dans le fichier de données
					fclose($file);
				}			
				return $array_robots[$r]; //On retourne le nom du robot d'exploration.
			}
			$r++;
		}	
		return false;
	}
}

?>
