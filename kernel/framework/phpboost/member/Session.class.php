<?php
/*##################################################
 *                            sessions.class.php
 *                            -------------------
 *   begin                : July 04, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *   Session v4.0.0
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

//Constantes de base.
define('AUTOCONNECT', true);
define('NO_AUTOCONNECT', false);
define('ALREADY_HASHED', true);
define('SEASURF_ATTACK_ERROR_PAGE', PATH_TO_ROOT . '/member/csrf-attack.php');

/**
 * @author Régis VIARRE <crowkait@phpboost.com
 * @desc This class manages all sessions for the users.
 * @package {@package}
 */
class Session
{
	private $data = array(); //Tableau contenant les informations de session.
	private $session_mod = 0; //Variable contenant le mode de session à utiliser pour récupérer les infos.
	private $autoconnect = array(); //Vérification de la session pour l'autoconnexion.
	/**
	*
	* @var Sql
	*/
	private $sql;

	public function __construct()
	{
		$this->sql = PersistenceContext::get_sql();
	}

	/**
	 * @desc Manage the actions for the session caused by the user (connection, disconnection).
	 */
	public function act()
	{
		//Module de connexion.
		$login = retrieve(POST, 'login', '');
		$password = retrieve(POST, 'password', '', TSTRING_UNCHANGE);
		$autoconnexion = retrieve(POST, 'auto', false);

		if (retrieve(GET, 'disconnect', false)) //Déconnexion.
		{
			//vérification de la validité du jeton
			$this->csrf_get_protect();

			$this->end();
			AppContext::get_response()->redirect(Environment::get_home_page());
		}
		elseif (retrieve(POST, 'connect', false) && !empty($login) && !empty($password)) //Création de la session.
		{
			$user_id = $this->sql->query("SELECT user_id FROM " . DB_TABLE_MEMBER . " WHERE login = '" . $login . "'", __LINE__, __FILE__);
			if (!empty($user_id)) //Membre existant.
			{
				$info_connect = $this->sql->query_array(DB_TABLE_MEMBER, 'level', 'user_warning', 'last_connect', 'test_connect', 'user_ban', 'user_aprob', "WHERE user_id='" . $user_id . "'", __LINE__, __FILE__);
				$delay_connect = (time() - $info_connect['last_connect']); //Délai entre deux essais de connexion.
				$delay_ban = (time() - $info_connect['user_ban']); //Vérification si le membre est banni.

				if ($delay_ban >= 0 && $info_connect['user_aprob'] == '1' && $info_connect['user_warning'] < '100') //Utilisateur non (plus) banni.
				{
					if ($delay_connect >= 600) //5 nouveau essais, 10 minutes après.
					{
						$this->sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET last_connect='" . time() . "', test_connect = 0 WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__); //Remise à zéro du compteur d'essais.
						$error_report = $this->start($user_id, $password, $info_connect['level'], REWRITED_SCRIPT, '', '', $autoconnexion); //On lance la session.
					}
					elseif ($delay_connect >= 300) //2 essais 5 minutes après
					{
						$this->sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET last_connect='" . time() . "', test_connect = 3 WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__); //Redonne 2 essais.
						$error_report = $this->start($user_id, $password, $info_connect['level'], REWRITED_SCRIPT, '', '', $autoconnexion); //On lance la session.
					}
					elseif ($info_connect['test_connect'] < 5) //Succès.
					{
						$error_report = $this->start($user_id, $password, $info_connect['level'], REWRITED_SCRIPT, '', '', $autoconnexion); //On lance la session.
					}
					else //plus d'essais
					{
						AppContext::get_response()->redirect('/member/error.php?e=e_member_flood#message_helper');
					}
				}
				elseif ($info_connect['user_aprob'] == '0')
				{
					AppContext::get_response()->redirect('/member/error.php?e=e_unactiv_member#message_helper');
				}
				elseif ($info_connect['user_warning'] == '100')
				{
					AppContext::get_response()->redirect('/member/error.php?e=e_member_ban_w#message_helper');
				}
				else
				{
					$delay_ban = ceil((0 - $delay_ban)/60);
					AppContext::get_response()->redirect('/member/error.php?e=e_member_ban&ban=' . $delay_ban . '#message_helper');
				}

				if (!empty($error_report)) //Erreur
				{
					$this->sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET last_connect='" . time() . "', test_connect = test_connect + 1 WHERE user_id='" . $user_id . "'", __LINE__, __FILE__);
					$info_connect['test_connect']++;
					$info_connect['test_connect'] = 5 - $info_connect['test_connect'];
					AppContext::get_response()->redirect('/member/error.php?e=e_member_flood&flood=' . $info_connect['test_connect'] . '#message_helper');
				}
				elseif ($info_connect['test_connect'] > 0) //Succès redonne tous les essais.
				{
					$this->sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET last_connect='" . time() . "', test_connect = 0 WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__); //Remise à zéro du compteur d'essais.
				}
			}
			else
			{
				AppContext::get_response()->redirect('/member/error.php?e=e_unexist_member#message_helper');
			}
		
			if (SCRIPT != DIR . '/member/error.php')
			{
				AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
			}
			else
			{
				AppContext::get_response()->redirect(Environment::get_home_page());
			}
		}
	}

	/**
	 * @desc Start the session
	 * @param int $user_id The member's user id.
	 * @param string $password The member's password.
	 * @param string $session_script Session script value where the session is started.
	 * @param string $session_script_get Get value of session script where the session is started.
	 * @param string $session_script_title Title of session script where the session is started.
	 * @param boolean $autoconnect The member user id.
	 * @param boolean $already_hashed True if password has been already hashed width str_hash() function, false otherwise.
	 * @return True if succed, false otherwise and return an error code.
	 */
	public function start($user_id, $password, $level, $session_script, $session_script_get, $session_script_title, $autoconnect = false, $already_hashed = false)
	{
		$pwd = $password;
		if (!$already_hashed)
		{
			$password = strhash($password);
		}
			
		$error = '';
		$session_script = addslashes($session_script);
		$session_script_title = addslashes($session_script_title);
		$session_script_get = preg_replace('`&token=[^&]+`', '', QUERY_STRING);

		########Insertion dans le compteur si l'ip est inconnue.########
		$check_ip = $this->sql->query("SELECT COUNT(*) FROM " . DB_TABLE_VISIT_COUNTER . " WHERE ip = '" . USER_IP . "'", __LINE__, __FILE__);
		$_include_once = empty($check_ip) && (StatsSaver::check_bot() === false);
		if ($_include_once)
		{
			//Récupération forcée de la valeur du total de visites, car problème de CAST avec postgresql.
			$this->sql->query_inject("UPDATE ".LOW_PRIORITY." " . DB_TABLE_VISIT_COUNTER . " SET ip = ip + 1, time = '" . gmdate_format('Y-m-d', time(), TIMEZONE_SYSTEM) . "', total = total + 1 WHERE id = 1", __LINE__, __FILE__);
			$this->sql->query_inject("INSERT ".LOW_PRIORITY." INTO " . DB_TABLE_VISIT_COUNTER . " (ip, time, total) VALUES('" . USER_IP . "', '" . gmdate_format('Y-m-d', time(), TIMEZONE_SYSTEM) . "', 0)", __LINE__, __FILE__);

			//Mise à jour du last_connect, pour un membre qui vient d'arriver sur le site.
			if ($user_id !== '-1')
			{
				$this->sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET last_connect = '" . time() . "' WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
			}
		}

		//On lance les stats.
		StatsSaver::compute_referer();
		if ($_include_once)
		{
			StatsSaver::compute_users();
		}

		########Génération d'un ID de session unique########
		$session_uniq_id = strhash(uniqid(mt_rand(), true)); //On génère un numéro de session aléatoire.
		$this->data['user_id'] = $user_id;
		$this->data['session_id'] = $session_uniq_id;
		$this->data['token'] = self::generate_token();

		########Session existe t-elle?#########
		$this->garbage_collector(); //On nettoie avant les sessions périmées.

		$sessions_config = SessionsConfig::load();
		if ($user_id !== '-1')
		{
			//Suppression de la session visiteur générée avant l'enregistrement!
			$this->sql->query_inject("DELETE FROM " . DB_TABLE_SESSIONS . " WHERE session_ip = '" . USER_IP . "' AND user_id = -1", __LINE__, __FILE__);

			//En cas de double connexion, on supprime le cookie et la session associée de la base de données!
			if (AppContext::get_request()->has_cookieparameter($sessions_config->get_cookie_name() . '_data'))
			{
				AppContext::get_response()->set_cookie(new HTTPCookie($sessions_config->get_cookie_name().'_data', '', time() - 31536000));
			}
			$this->sql->query_inject("DELETE FROM " . DB_TABLE_SESSIONS . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);

			//Récupération password BDD
			$password_m = $this->sql->query("SELECT password FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $user_id . "' AND user_warning < 100 AND '" . time() . "' - user_ban >= 0", __LINE__, __FILE__);
			if (!empty($password) && (($password === $password_m) || (md5($pwd) === $password_m))) //Succès! => md5 gestion des vieux mdp
			{
				if (md5($pwd) === $password_m) // Si le mot de passe est encore stocké en md5, on l'update
				{
					$this->sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET password = '" . $password . "' WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
				}

				$this->sql->query_inject("INSERT INTO " . DB_TABLE_SESSIONS . " VALUES('" . $session_uniq_id . "', '" . $user_id . "', '" . $level . "', '" . USER_IP . "', '" . time() . "', '" . $session_script . "', '" . $session_script_get . "', '" . $session_script_title . "', '0', '', '', '" . addslashes($this->data['modules_parameters']) . "', '" . $this->data['token'] . "')", __LINE__, __FILE__);
			}
			else //Session visiteur, echec!
			{
				$this->sql->query_inject("INSERT INTO " . DB_TABLE_SESSIONS . " VALUES('" . $session_uniq_id . "', -1, -1, '" . USER_IP . "', '" . time() . "', '" . $session_script . "', '" . $session_script_get . "', '" . $session_script_title . "', '0', '', '', '', '" . $this->data['token'] . "')", __LINE__, __FILE__);

				$delay_ban = $this->sql->query("SELECT user_ban FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
				if ((time() - $delay_ban) >= 0)
				{
					$error = 'echec';
				}
				else
				{
					$error = $delay_ban;
				}
			}
		}
		else //Session visiteur valide.
		{
			$this->sql->query_inject("INSERT INTO " . DB_TABLE_SESSIONS . " VALUES('" . $session_uniq_id . "', -1, -1, '" . USER_IP . "', '" . time() . "', '" . $session_script . "', '" . $session_script_get . "', '" . $session_script_title . "', '0', '', '', '', '" . $this->data['token'] . "')", __LINE__, __FILE__);
		}

		########Génération du cookie de session########
		$data = array();
		$data['user_id'] = isset($user_id) ? NumberHelper::numeric($user_id) : -1;
		$data['session_id'] = $session_uniq_id;

		AppContext::get_response()->set_cookie(new HTTPCookie($sessions_config->get_cookie_name() . '_data', serialize($data), time() + 31536000));

		########Génération du cookie d'autoconnection########
		if ($autoconnect === true)
		{
			$session_autoconnect['user_id'] = $user_id;
			$session_autoconnect['pwd'] = $password;

			AppContext::get_response()->set_cookie(new HTTPCookie($sessions_config->get_cookie_name().'_autoconnect', serialize($session_autoconnect), time() + 31536000));
		}

		unset($pwd);
		return $error;
	}

	/**
	 * @desc Get informations from the user, and set it for his session.
	 */
	public function load()
	{
		$this->get_id(); //Récupération des identifiants de session.

		########Valeurs à retourner########
		$userdata = array();
		if ($this->data['user_id'] > 0 && !empty($this->data['session_id']))
		{
			//Récupère également les champs membres supplémentaires
			$result = $this->sql->query_while("SELECT m.user_id AS m_user_id, m.login, m.level, m.user_groups, m.user_lang, m.user_theme, m.user_mail, m.user_pm, m.user_editor, m.user_timezone, m.user_avatar avatar, m.user_readonly, s.modules_parameters, s.token AS token, me.*
			FROM " . DB_TABLE_MEMBER . " m
            JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = '" . $this->data['user_id'] . "' AND s.session_id = '" . $this->data['session_id'] . "'
			LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " me ON me.user_id = '" . $this->data['user_id'] . "'
			WHERE m.user_id = '" . $this->data['user_id'] . "'", __LINE__, __FILE__);
			$userdata = $this->sql->fetch_assoc($result);

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
			$result = $this->sql->query_while("SELECT modules_parameters, user_theme, user_lang
			FROM " . DB_TABLE_SESSIONS . "
			WHERE user_id = '-1' AND session_id = '" . $this->data['session_id'] . "'", __LINE__, __FILE__);
			$userdata = $this->sql->fetch_assoc($result);

			if (!empty($userdata)) //Succès.
			{
				$this->data = array_merge($userdata, $this->data); //Fusion des deux tableaux.
			}
		}

		$this->data['user_id'] = isset($userdata['m_user_id']) ? (int)$userdata['m_user_id'] : -1;
		$this->data['token'] = isset($userdata['token']) ? $userdata['token'] : '';
		$this->data['login'] = isset($userdata['login']) ? $userdata['login'] : '';
		$this->data['level'] = isset($userdata['level']) ? (int)$userdata['level'] : -1;
		$this->data['user_groups'] = isset($userdata['user_groups']) ? $userdata['user_groups'] : '';
		$this->data['user_lang'] = !empty($userdata['user_lang']) ? $userdata['user_lang'] : UserAccountsConfig::load()->get_default_lang(); //Langue membre
		$this->data['user_theme'] = !empty($userdata['user_theme']) ? $userdata['user_theme'] : UserAccountsConfig::load()->get_default_theme(); //Thème membre
		$this->data['user_mail'] = isset($userdata['user_mail']) ? $userdata['user_mail'] : '';
		$this->data['user_pm'] = isset($userdata['user_pm']) ? $userdata['user_pm'] : '0';
		$this->data['user_readonly'] = isset($userdata['user_readonly']) ? $userdata['user_readonly'] : '0';
		$this->data['user_editor'] = !empty($userdata['user_editor']) ? $userdata['user_editor'] : ContentFormattingConfig::load()->get_default_editor();
		$this->data['user_timezone'] = isset($userdata['user_timezone']) ? $userdata['user_timezone'] : GeneralConfig::load()->get_site_timezone();
		$this->data['avatar'] = isset($userdata['avatar']) ? $userdata['avatar'] : '';
		$this->data['modules_parameters'] = isset($userdata['modules_parameters']) ? $userdata['modules_parameters'] : '';
	}

	/**
	 * @desc Check session validity, and update it
	 * @param string $session_script_title The page title where the session has been check.
	 */
	public function check($session_script_title)
	{
		$session_script = preg_replace('`&token=[^&]+`', '', REWRITED_SCRIPT);
		$check_autoconnect = (!empty($this->autoconnect['session_id']) && $this->autoconnect['user_id'] > 0);
		$sessions_config = SessionsConfig::load();
		if ((!empty($this->data['session_id']) && $this->data['user_id'] > 0) || $check_autoconnect)
		{
			if (!$check_autoconnect) //Mode de connexion directe par le formulaire.
			{
				$this->autoconnect['session_id'] = $this->data['session_id'];
				$this->autoconnect['user_id'] = $this->data['user_id'];
			}

			//Localisation du membre.
			if (!defined('NO_SESSION_LOCATION'))
			{
				$location = " session_script = '" . addslashes($session_script) . "', session_script_get = '', session_script_title = '" . addslashes($session_script_title) . "', ";
			}
			else
			{
				$location = '';
			}
			
			//On modifie le session_flag pour forcer mysql à modifier l'entrée, pour prendre en compte la mise à jour par mysql_affected_rows().
			$resource = $this->sql->query_inject("UPDATE ".LOW_PRIORITY." " . DB_TABLE_SESSIONS . " SET session_ip = '" . USER_IP . "', session_time = '" . time() . "', " . $location . " session_flag = 1 - session_flag WHERE session_id = '" . $this->autoconnect['session_id'] . "' AND user_id = '" . $this->autoconnect['user_id'] . "'", __LINE__, __FILE__);
			if ($this->sql->affected_rows($resource, "SELECT COUNT(*) FROM " . DB_TABLE_SESSIONS . " WHERE session_id = '" . $this->autoconnect['session_id'] . "' AND user_id = '" . $this->autoconnect['user_id'] . "'") == 0) //Aucune session lancée.
			{
				if ($this->autoconnect($session_script, $session_script_get, $session_script_title) === false) //On essaie de lancer la session automatiquement.
				{
					if (AppContext::get_request()->has_cookieparameter($sessions_config->get_cookie_name() . '_data'))
					{
						AppContext::get_response()->set_cookie(new HTTPCookie($sessions_config->get_cookie_name() . '_data', '', time() - 31536000)); //Destruction cookie.
					}

					//Redirection une fois la session lancée.
					AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
				}
			}
		}
		else //Visiteur
		{
			//Localisation du visiteur.
			if (!defined('NO_SESSION_LOCATION'))
			{
				$location = " session_script = '" . addslashes($session_script) . "', session_script_get = '', session_script_title = '" . addslashes($session_script_title) . "', ";
			}
			else
			{
				$location = '';
			}

			//On modifie le session_flag pour forcer mysql à modifier l'entrée, pour prendre en compte la mise à jour par mysql_affected_rows().
			$resource = $this->sql->query_inject("UPDATE ".LOW_PRIORITY." " . DB_TABLE_SESSIONS . " SET session_ip = '" . USER_IP . "', session_time = '" . (time() + 1) . "', " . $location . " session_flag = 1 - session_flag WHERE user_id = -1 AND session_ip = '" . USER_IP . "'", __LINE__, __FILE__);
			if ($this->sql->affected_rows($resource, "SELECT COUNT(*) FROM " . DB_TABLE_SESSIONS . " WHERE user_id = -1 AND session_ip = '" . USER_IP . "'") == 0) //Aucune session lancée.
			{
				if (AppContext::get_request()->has_cookieparameter($sessions_config->get_cookie_name() . '_data'))
				{
					AppContext::get_response()->set_cookie(new HTTPCookie($sessions_config->get_cookie_name() . '_data', '', time() - 31536000)); //Destruction cookie.
				}
				$this->start('-1', '', '-1', $session_script, $session_script_get, $session_script_title, false, ALREADY_HASHED); //Session visiteur
			}
		}
	}

	/**
	 * @desc Destroy the session
	 */
	public function end()
	{
		$this->get_id();
		$sessions_config = SessionsConfig::load();

		//On supprime la session de la bdd.
		$this->sql->query_inject("DELETE FROM " . DB_TABLE_SESSIONS . " WHERE session_id = '" . $this->data['session_id'] . "'", __LINE__, __FILE__);

		if (AppContext::get_request()->has_cookieparameter($sessions_config->get_cookie_name() . '_data')) //Session cookie?
		{
			AppContext::get_response()->set_cookie(new HTTPCookie($sessions_config->get_cookie_name() . '_data', '', time() - 31536000)); //Destruction cookie.
		}

		if (AppContext::get_request()->has_cookieparameter($sessions_config->get_cookie_name() . '_autoconnect'))
		{
			AppContext::get_response()->set_cookie(new HTTPCookie($sessions_config->get_cookie_name() . '_autoconnect', '', time() - 31536000)); //Destruction cookie.
		}

		$this->garbage_collector();
	}

	/**
	 *  @desc Save module's parameters into session
	 * @param mixed module's parameters
	 */
	public function set_module_parameters($parameters, $module = '')
	{
		if (empty($this->data['user_id']) || !is_numeric($this->data['user_id']))
		{
			return false;
		}

		if (empty($module) || !is_string($module))
		{
			$module = Environment::get_running_module_name();
		}
		
		if ($this->data['user_id'] == -1) 
		{
			$sql_condition = "session_id = '" . $this->data['session_id'] . "' AND user_id = -1";
		}
		else 
		{
			$sql_condition = "user_id = '" . (int)$this->data['user_id'] . "'";
		}
		
		$this->data['modules_parameters'] = $this->sql->query("SELECT modules_parameters 
			FROM " . DB_TABLE_SESSIONS . " 
			WHERE " . $sql_condition, __LINE__, __FILE__);
		if ($this->data['modules_parameters'] !== false) // test permettant d'ecrire la premiere fois si le contenu est vide
		{
			$modules_parameters = unserialize($this->data['modules_parameters']);
			$modules_parameters[$module] = $parameters;

			$this->data['modules_parameters'] = serialize($modules_parameters);

			$this->sql->query_inject("UPDATE " . DB_TABLE_SESSIONS . " SET modules_parameters = '" .
			TextHelper::strprotect($this->data['modules_parameters'], false) .
				"' WHERE " . $sql_condition, __LINE__, __FILE__);
		}
		else
		{
			$this->data['modules_parameters'] = '';
		}
	}

	/**
	 *  @desc Get module's parametres from session
	 * @param string module  module name (if null then current module)
	 * @return array array of parameters
	 */
	public function get_module_parameters($module = '')
	{
		if (empty($this->data['user_id']) || !is_numeric($this->data['user_id']))
		{
			return false;
		}

		if (empty($module) || !is_string($module))
		{
			$module = Environment::get_running_module_name();
		}

		if ($this->data['user_id'] == -1) 
		{
			$sql_condition = "session_id = '" . $this->data['session_id'] . "' AND user_id = -1";
		}
		else 
		{
			$sql_condition = "user_id = '" . (int)$this->data['user_id'] . "'";
		}
		
		$this->data['modules_parameters'] = $this->sql->query("SELECT modules_parameters 
			FROM " . DB_TABLE_SESSIONS . " 
			WHERE " . $sql_condition, __LINE__, __FILE__);
		if ($this->data['modules_parameters'] != false)
		{
			$array = unserialize($this->data['modules_parameters']);
		}
		else
		{
			$this->data['modules_parameters'] = '';
		}

		return isset($array[$module]) ? $array[$module] : '';
	}

	/**
	 * @desc Get session identifiers
	 */
	private function get_id()
	{
		//Suppression d'éventuelles données dans ce tableau.
		$this->data = array();

		$this->data['session_id'] = '';
		$this->data['user_id'] = -1;
		$this->autoconnect['session_id'] = '';
		$this->autoconnect['user_id'] = -1;

		$this->session_mod = 0;
		$sid = retrieve(GET, 'sid', '');
		$suid = retrieve(GET, 'suid', 0);
		$sessions_config = SessionsConfig::load();
		
		########Cookie Existe?########
		if (AppContext::get_request()->has_cookieparameter($sessions_config->get_cookie_name() . '_data'))
		{
			//Redirection pour supprimer les variables de session en clair dans l'url.
			if (isset($_GET['sid']) && isset($_GET['suid']))
			{
				$query_string = preg_replace('`&?sid=(.*)&suid=(.*)`', '', REWRITED_SCRIPT);
				AppContext::get_response()->redirect(HOST . $query_string);
			}
			
			$session_data = unserialize(AppContext::get_request()->get_cookie($sessions_config->get_cookie_name() . '_data'));
			if ($session_data === false)
			{
				$session_data = array();
			}

			$this->data['session_id'] = isset($session_data['session_id']) ? TextHelper::strprotect($session_data['session_id']) : ''; //Validité du session id.
			$this->data['user_id'] = isset($session_data['user_id']) ? NumberHelper::numeric($session_data['user_id']) : ''; //Validité user id?
		}
		########SID Existe?########
		elseif (!empty($sid) && $suid > 0)
		{
			$this->data['session_id'] = $sid; //Validité du session id.
			$this->data['user_id'] = $suid; //Validité user id?
			$this->session_mod = 1;
		}
	}

	/**
	 * @desc Create session int autoconnect mode
	 * @param string $session_script Session script value where the session is started.
	 * @param string $session_script_get Get value of session script where the session is started.
	 * @param string $session_script_title Title of session script where the session is started.
	 */
	private function autoconnect($session_script, $session_script_get, $session_script_title)
	{
		$sessions_config = SessionsConfig::load();
		########Cookie Existe?########
		if (AppContext::get_request()->has_cookieparameter($sessions_config->get_cookie_name() . '_autoconnect'))
		{
			$session_autoconnect = unserialize(AppContext::get_request()->get_cookie($sessions_config->get_cookie_name() . '_autoconnect'));
			if ($session_autoconnect === false)
			{
				$session_autoconnect = array();
			}
			$session_autoconnect['user_id'] = !empty($session_autoconnect['user_id']) ? NumberHelper::numeric($session_autoconnect['user_id']) : ''; //Validité user id?.
			$session_autoconnect['pwd'] = !empty($session_autoconnect['pwd']) ? TextHelper::strprotect($session_autoconnect['pwd']) : ''; //Validité password.
			$level = $this->sql->query("SELECT level FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $session_autoconnect['user_id'] . "' AND password = '" . $session_autoconnect['pwd'] . "'", __LINE__, __FILE__);
			if (!empty($session_autoconnect['user_id']) && !empty($session_autoconnect['pwd']) && $level != '')
			{
				$error_report = $this->start($session_autoconnect['user_id'], $session_autoconnect['pwd'], $level, $session_script, $session_script_get, $session_script_title, true, ALREADY_HASHED); //Lancement d'une session utilisateur.

				//Gestion des erreurs pour éviter un brute force.
				if ($error_report === 'echec')
				{
					$this->sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET last_connect='" . time() . "', test_connect = test_connect + 1 WHERE user_id='" . $session_autoconnect['user_id'] . "'", __LINE__, __FILE__);

					$test_connect = $this->sql->query("SELECT test_connect FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $session_autoconnect['user_id'] . "'", __LINE__, __FILE__);

					AppContext::get_response()->set_cookie(new HTTPCookie($sessions_config->get_cookie_name() . '_autoconnect', '', time() - 31536000)); //Destruction cookie.

					AppContext::get_response()->redirect('/member/error.php?flood=' . (5 - ($test_connect + 1)));
				}
				elseif (is_numeric($error_report))
				{
					AppContext::get_response()->set_cookie(new HTTPCookie($sessions_config->get_cookie_name() . '_autoconnect', '', time() - 31536000)); //Destruction cookie.

					$error_report = ceil($error_report/60);
					AppContext::get_response()->redirect('/member/error.php?ban=' . $error_report);
				}
				else //Succès on recharge la page.
				{
					//On met à jour la date de dernière connexion.
					$this->sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET last_connect = '" . time() . "' WHERE user_id = '" . $session_autoconnect['user_id'] . "'", __LINE__, __FILE__);

					AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
				}
			}
			else
			{
				return false;
			}
		}
		return false;
	}

	/**
	 * @desc Deletes all the existing sessions
	 */
	public function garbage_collector()
	{
		$sessions_config = SessionsConfig::load();
		$this->sql->query_inject("DELETE
		FROM " . DB_TABLE_SESSIONS . "
		WHERE session_time < '" . (time() - $sessions_config->get_session_duration()) . "'
		OR (session_time < '" . (time() - $sessions_config->get_active_session_duration()) . "' AND user_id = -1)", __LINE__, __FILE__);
	}

	/**
	 * @desc Return the session token
	 * @return string the session token
	 */
	public function get_token()
	{
		if (empty($this->data['token']))
		{   // if the token is empty (already connected while updating the website from 2.0 version to 3.0)
			$this->data['token'] = self::generate_token();
			$this->sql->query_inject("UPDATE " . DB_TABLE_SESSIONS . " SET token='" . $this->data['token'] . "' WHERE session_id='" . $this->data['session_id']. "'", __LINE__, __FILE__);

		}
		return $this->data['token'];
	}

	private static function generate_token()
	{
		return substr(strhash(uniqid(mt_rand(), true), false), 0, 16);
	}

	/**
	 * @desc Check the session against CSRF attacks by POST. Checks that POSTs are done from
	 * this site. 2 different cases are accepted but the first is safer:
	 * <ul>
	 * 	<li>The request contains a parameter whose name is token and value is the value of the token of the current session.</li>
	 * 	<li>If the token isn't in the request, we analyse the HTTP referer to be sure that the request comes from the current site and not from another which can be suspect</li>
	 * </ul>
	 * If the request doesn't match any of these two cases, this method will consider that it's a CSRF attack.
	 * @param mixed $redirect if string, redirect to the $redirect error page if the token is wrong
	 * if false, do not redirect
	 * @return bool true if no csrf attack by post is detected
	 */
	public function csrf_post_protect($redirect = SEASURF_ATTACK_ERROR_PAGE)
	{
		//The user sent a POST request
		if (!empty($_POST))
		{
			//First verification: does the token exist?
			$token = $this->get_token();
			if (!empty($token) && retrieve(REQUEST, 'token', '') === $token)
			{
				return true;
			}
			//Second chance: the referer is correct
			if (self::check_referer())
			{
				return true;
			}
			//If those two lines are executed, none of the two cases has been matched. Thow it's a potential attack.
			$this->csrf_attack($redirect);
			return false;
		}
		//It's not a POST request, there is no problem.
		else
		{
			return true;
		}
	}

	/**
	 * @desc Check the session against CSRF attacks by GET. Checks that GETs are done from
	 * this site with a correct token.
	 * @param mixed $redirect if string, redirect to the $redirect error page if the token is wrong
	 * if false, do not redirect
	 * @return true if no csrf attack by get is detected
	 */
	public function csrf_get_protect($redirect = SEASURF_ATTACK_ERROR_PAGE)
	{
		$token = $this->get_token();
		if (empty($token) || retrieve(REQUEST, 'token', '') !== $token)
		{
			$this->csrf_attack($redirect);
			return false;
		}
		return true;
	}

	/**
	 * @desc check that the operation is done from this site
	 * @return true if the referer is on this site
	 */
	private static function check_referer()
	{
		if (empty($_SERVER['HTTP_REFERER']))
		{
			return false;
		}
		$general_config = GeneralConfig::load();
		return strpos($_SERVER['HTTP_REFERER'], trim($general_config->get_site_url() . $general_config->get_site_path(), '/')) === 0;
	}

	/**
	 * @desc Redirect to the $redirect error page if the token is wrong
	 * if false, do not redirect
	 * @param mixed $redirect if string, redirect to the $redirect error page if the token is wrong
	 * if false, do not redirect
	 */
	private function csrf_attack($redirect = SEASURF_ATTACK_ERROR_PAGE)
	{
		$bad_token = $this->get_printable_token(retrieve(REQUEST, 'token', ''));
		$good_token = $this->get_printable_token($this->get_token());

		if ($redirect !== false && !empty($redirect))
		{
			AppContext::get_response()->redirect($redirect);
		}
	}

	/**
	 * Returns the session data
	 * @return array
	 */
	public function get_data()
	{
		return $this->data;
	}

	/**
	 * Tells whether the current user supports cookies
	 * @return bool
	 */
	public function supports_cookies()
	{
		return (bool)$this->session_mod;
	}

	private function get_printable_token($token)
	{
		$digits = 6;
		return substr($token, 0, $digits) . '...' . substr($token, strlen($token) - $digits);
	}
}

?>
