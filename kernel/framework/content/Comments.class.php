<?php
/*##################################################
 *                             comments.class.php
 *                            -------------------
 *   begin                : March 08, 2008
 *   copyright            : (C) 2008 Viarre Régis, Sautel Benoit
 *   email                : crowkait@phpboost.com, ben.popeye@phpboost.com
 * v 2.0
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

define('INTEGRATED_IN_ENVIRONMENT', true);
define('POP_UP_WINDOW', false);
define('KERNEL_SCRIPT', true);

/**
 * @package content
 * @author Révis Viarre <crowkait@phpboost.com>
 * @desc This class manages comments everywhere in phpboost
 * Simplyfied use with the display_comments function: 
 * //news is the name of the modue, $idnews is the id in database for this item.
 * display_comments('news', $idnews, url('news.php?id=' . $idnews . '&amp;com=%s', 'news-0-' . $idnews . '.php?com=%s'))
 */
class Comments
{
	## Public Methods ##
	/**
	 * @desc Display comments form.
	 * @param string $script Module's name
	 * @param string $idprov Id field in the database. related to item where the comments are posted.
	 * @param string $vars Link for the module, it has to include com=%s in the link for the comments system.
	 * @param string $module_folder Module's folder
	 * @param string $is_kernel_script
	 */
	 function Comments($script, $idprov, $vars, $module_folder = '', $is_kernel_script = false)
	{
		$this->module_folder = !empty($module_folder) ? strprotect($module_folder) : strprotect($script);
		list($this->script, $this->idprov, $this->vars, $this->path) = array(strprotect($script), numeric($idprov), $vars, PATH_TO_ROOT . '/' . $this->module_folder . '/');
		
		$this->is_kernel_script = $is_kernel_script;
	}
	
	/**
	 * @desc Add a comment
	 * @param string $contents Comment content
	 * @param string $login Poster's login
	 * @return int the inserted identifier.
	 */
	 function add($contents, $login)
	{
		global $Sql, $User;
		
		$Sql->query_inject("INSERT INTO " . DB_TABLE_COM . " (idprov, login, user_id, contents, timestamp, script, path, user_ip) VALUES('" . $this->idprov . "', '" . $login . "', '" . $User->get_attribute('user_id') . "', '" . $contents . "', '" . time() . "', '" . $this->script . "', '" .PATH_TO_ROOT . strprotect(str_replace(DIR, '', SCRIPT) . '?' . QUERY_STRING) . "', '" . USER_IP . "')", __LINE__, __FILE__);
		$idcom = $Sql->insert_id("SELECT MAX(idcom) FROM " . DB_TABLE_COM);
		
		//Incrémente le nombre de commentaire dans la table du script concerné.
		$Sql->query_inject("UPDATE ".PREFIX.$this->sql_table." SET nbr_com = nbr_com + 1 WHERE id = '" . $this->idprov . "'", __LINE__, __FILE__);
		
		return $idcom;
	}
	
	/**
	 * @desc Edit a comment
	 * @param string $contents Comment content
	 * @param string $login Poster's login
	 */
	function update($contents, $login)
	{
		global $Sql;
		$Sql->query_inject("UPDATE " . DB_TABLE_COM . " SET contents = '" . $contents . "', login = '" . $login . "' WHERE idcom = '" . $this->idcom . "' AND idprov = '" . $this->idprov . "' AND script = '" . $this->script . "'", __LINE__, __FILE__);
	}
	
	/**
	 * @desc Delete a comment
	 * @return int the previous comment identifier.
	 */
	function del()
	{
		global $Sql;
		
		//Sélectionne le message précédent à celui qui va être supprimé.
		$lastid_com = $Sql->query("SELECT idcom
		FROM " . PREFIX . "com
		WHERE idcom < '" . $this->idcom . "' AND script = '" . $this->script . "' AND idprov = '" . $this->idprov . "'
		ORDER BY idcom DESC
		" . $Sql->limit(0, 1), __LINE__, __FILE__);
		
		$resource = $Sql->query_inject("DELETE FROM " . DB_TABLE_COM . " WHERE idcom = '" . $this->idcom . "' AND script = '" . $this->script . "' AND idprov = '" . $this->idprov . "'", __LINE__, __FILE__);
		//Si la suppression a été effective
		if ($Sql->affected_rows($resource) > 0)
		{
		    $Sql->query_inject("UPDATE ".PREFIX.$this->sql_table." SET nbr_com= nbr_com - 1 WHERE id = '" . $this->idprov . "'", __LINE__, __FILE__);
		}
		
		return $lastid_com;
	}
	
	/**
	 * @desc Delete all comments for the specified item.
	 * @param int $idprov The id field of the item in the database.
	 */
	 function delete_all($idprov)
	{
		global $Sql;
		
		$Sql->query_inject("DELETE FROM " . DB_TABLE_COM . " WHERE idprov = '" . $idprov . "' AND script = '" . $this->script . "'", __LINE__, __FILE__);
	}
	
	/**
	 * @desc Lock or unlock comments for an item.
	 * @param boolean $lock true for locking, false otherwise
	 */
	function lock($lock)
	{
		global $Sql;

		$Sql->query_inject("UPDATE ".PREFIX.$this->sql_table." SET lock_com = '" . $lock . "' WHERE id = '" . $this->idprov . "'", __LINE__, __FILE__);
	}
	
	/**
	 * @desc Check if the comments system is correctly loaded.
	 * @return boolean true if loaded correctly, false otherwise.
	 */
	function is_loaded()
	{
		global $Errorh;
		
		if (empty($this->sql_table)) //Erreur avec le module non prévu pour gérer les commentaires.
			$Errorh->handler('e_unexist_page', E_USER_REDIRECT);
			
		return (!empty($this->script) && !empty($this->idprov) && !empty($this->vars));
	}
	
	/**
	 * @desc Set argument for the comments system.
	 * @param int $idcom
	 * @param string $path
	 */
	function set_arg($idcom, $path = '')
	{
		if (!empty($path))
			$this->path = $path;
		$this->idcom = (int)max($idcom, 0);
		
		//Si c'est un module qui appelle
		if (!$this->is_kernel_script)
			list($this->sql_table, $this->nbr_com, $this->lock_com) = $this->_get_info_module();
		//Sinon c'est le noyau
		else
			list($this->sql_table, $this->nbr_com, $this->lock_com) = $this->_get_info_kernel_script();
	}
	
	//Accesseur
	function get_attribute($varname)
	{
		return $this->$varname;
	}
	
	/**
	 * @desc Display comments form.
	 * @param int $integrated_in_environment
	 * @param Template $Template Optional template.
	 * @param string $page_path_to_root
	 * @return string The parsed template
	 */
	function display($integrated_in_environment = INTEGRATED_IN_ENVIRONMENT, $Template = false, $page_path_to_root = '')
	{
		global $Cache, $User, $Errorh, $Sql, $LANG, $CONFIG, $CONFIG_COM, $_array_rank, $Session;
		
		if ($integrated_in_environment)
		{
			$idcom_get = retrieve(GET, 'com', 0);
			$idcom_post = retrieve(POST, 'idcom', 0);
		    $idcom = $idcom_post > 0 ? $idcom_post : $idcom_get;
			
		    $this->set_arg($idcom); //On met à jour les attributs de l'objet.
		}
		
	    $vars_simple = sprintf($this->vars, 0);
		$delcom = retrieve(GET, 'delcom', 0);
		$editcom = retrieve(GET, 'editcom', 0);
		$updatecom = retrieve(GET, 'updatecom', false);
		
		$path_redirect = $this->path . sprintf(str_replace('&amp;', '&', $this->vars), 0) . ((!empty($page_path_to_root) && !$integrated_in_environment) ? '&path_to_root=' . $page_path_to_root : '');
		
		if (!is_object($Template) || strtolower(get_class($Template)) != 'template')
			$Template = new Template('framework/content/com.tpl');
		
		if ($this->is_loaded()) //Commentaires chargés?
		{
			//Chargement du cache
			$Cache->load('com');
			
			
			$captcha = new Captcha();
			$captcha->set_difficulty($CONFIG_COM['com_verif_code_difficulty']);

			###########################Insertion##############################
			if (retrieve(POST, 'comForm', false) && !$updatecom)
			{
				//Membre en lecture seule?
				if ($User->get_attribute('user_readonly') > time())
					$Errorh->handler('e_auth', E_USER_REDIRECT);
				
				$login = $User->check_level(MEMBER_LEVEL) ? $User->get_attribute('login') : retrieve(POST, $this->script . 'login', $LANG['guest']);
				$contents = retrieve(POST, $this->script . 'contents', '', TSTRING_UNCHANGE);
				
				if (!empty($login) && !empty($contents))
				{
					//Status des commentaires, verrouillé/déverrouillé?
					if ($this->lock_com >= 1 && !$User->check_level(MODO_LEVEL))
						redirect($path_redirect);
					
					//Autorisation de poster des commentaires?
					if ($User->check_level($CONFIG_COM['com_auth']))
					{
						//Mod anti-flood, autorisé aux membres qui bénificie de l'autorisation de flooder.
						$check_time = ($User->get_attribute('user_id') !== -1 && $CONFIG['anti_flood'] == 1) ? $Sql->query("SELECT MAX(timestamp) as timestamp FROM " . DB_TABLE_COM . " WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__) : '';
						if (!empty($check_time) && !$User->check_max_value(AUTH_FLOOD))
						{
							if ($check_time >= (time() - $CONFIG['delay_flood'])) //On calcule la fin du delai.
								redirect($path_redirect . '&errorh=flood#errorh');
						}
						
						//Code de vérification anti-bots.
						if ($CONFIG_COM['com_verif_code'] && !$captcha->is_valid())
						{
							redirect($path_redirect . '&errorh=verif#errorh');
						}
						$contents = strparse($contents, $CONFIG_COM['forbidden_tags']);
						
						if (!check_nbr_links($login, 0)) //Nombre de liens max dans le pseudo.
							redirect($path_redirect . '&errorh=l_pseudo#errorh');
						if (!check_nbr_links($contents, $CONFIG_COM['max_link'])) //Nombre de liens max dans le message.
							redirect($path_redirect . '&errorh=l_flood#errorh');
						
						//Récupération de l'adresse de la page.
						$last_idcom = $this->add($contents, $login);
						
						//Rédirection vers la page pour éviter le double post!
						redirect($path_redirect . '#m' . $last_idcom);
					}
					else //utilisateur non autorisé!
						redirect($path_redirect . '&errorh=auth#errorh');
				}
				else
					redirect($path_redirect . '&errorh=incomplete#errorh');
			}
			elseif ($updatecom || $delcom > 0 || $editcom > 0) //Modération des commentaires.
			{
				//Membre en lecture seule?
				if ($User->get_attribute('user_readonly') > time())
					$Errorh->handler('e_auth', E_USER_REDIRECT);
				
				$row = $Sql->query_array(DB_TABLE_COM, '*', "WHERE idcom = '" . $this->idcom . "' AND idprov = '" . $this->idprov . "' AND script = '" . $this->script . "'", __LINE__, __FILE__);
				$row['user_id'] = (int)$row['user_id'];
				
				if ($this->idcom != 0 && ($User->check_level(MODO_LEVEL) || ($row['user_id'] === $User->get_attribute('user_id') && $User->get_attribute('user_id') !== -1))) //Modération des commentaires.
				{
					if ($delcom > 0) //Suppression du commentaire.
					{
					    $Session->csrf_get_protect();
						$lastid_com = $this->del();
						$lastid_com = !empty($lastid_com) ? '#m' . $lastid_com : '';
						
						//Succès redirection.
						redirect($path_redirect . $lastid_com);
					}
					elseif ($editcom > 0) //Edition du commentaire.
					{
						$Template->assign_vars(array(
							'CURRENT_PAGE_COM' => $integrated_in_environment,
							'POPUP_PAGE_COM' => !$integrated_in_environment,
							'AUTH_POST_COM' => true
						));
						
						$is_guest = $row['user_id'] == -1;
						
						//Post form
						
						$form = new FormBuilder('comForm', $this->path . sprintf($this->vars, $this->idcom) . '&amp;token=' . $Session->get_token() . '&amp;updatecom=1' . ((!empty($page_path_to_root) && !$integrated_in_environment) ? '&amp;path_to_root=' . $page_path_to_root : ''));
						$fieldset = new FormFieldset($LANG['edit_comment']);
						if ($is_guest) //Visiteur
						{
							$fieldset->add_field(new FormTextEdit($this->script . 'login', $row['login'], array(
								'title' => $LANG['pseudo'], 'class' => 'text', 'required' => true, 
								'maxlength' => 25, 'required_alert' => $LANG['require_pseudo'])
							));
						}
						$fieldset->add_field(new FormTextarea($this->script . 'contents', unparse($row['contents']), array(
							'forbiddentags' => $CONFIG_COM['forbidden_tags'], 'title' => $LANG['message'], 
							'rows' => 10, 'cols' => 47, 'required' => true, 'required_alert' => $LANG['require_text'])
						));
						$fieldset->add_field(new FormHiddenField('idprov', $row['idprov']));
						$fieldset->add_field(new FormHiddenField('idcom', $row['idcom']));
						$fieldset->add_field(new FormHiddenField('script', $this->script));
				
						$form->add_fieldset($fieldset);
						$form->display_preview_button($this->script . 'contents'); //Display a preview button for the textarea field(ajax).
						$form->set_form_submit($LANG['update']);	
						
						$Template->assign_vars(array(
							'COM_FORM' =>  $form->display(),
							'SCRIPT' => $this->script,
							'L_XML_LANGUAGE' => $LANG['xml_lang'],
							'L_TITLE' => ($CONFIG['com_popup'] == 0 || $integrated_in_environment === true) ? $LANG['title_com'] : '',
							'L_DELETE_MESSAGE' => $LANG['alert_delete_msg'],
						));
					}
					elseif ($updatecom) //Mise à jour du commentaire.
					{
						$contents = retrieve(POST, $this->script . 'contents', '', TSTRING_UNCHANGE);
						$login = retrieve(POST, $this->script . 'login', $LANG['guest']);
						$login = empty($login) && $User->check_level(MEMBER_LEVEL) ? $User->get_attribute('login') : $login;
			
						if (!empty($contents) && !empty($login))
						{
							$contents = strparse($contents, $CONFIG_COM['forbidden_tags']);
							
							if (!check_nbr_links($contents, $CONFIG_COM['max_link'])) //Nombre de liens max dans le message.
								redirect($path_redirect . '&errorh=l_flood#errorh');

							$this->update($contents, $login);
							
							//Succès redirection.
							redirect($path_redirect . '#m' . $this->idcom);
						}
						else //Champs incomplet!
							redirect($path_redirect . '&errorh=incomplete#errorh');
					}
					else
						redirect($path_redirect . '&errorh=incomplete#errorh');
				}
				else
					$Errorh->handler('e_auth', E_USER_REDIRECT);
			}
			elseif (isset($_GET['lock']) && $User->check_level(MODO_LEVEL)) //Verrouillage des commentaires.
			{
				$Session->csrf_get_protect();
				
				if ($User->check_level(MODO_LEVEL))
				{
					$lock = retrieve(GET, 'lock', 0);
					$this->lock($lock);
				}
				redirect($path_redirect . '#anchor_' . $this->script);
			}
			else
			{
				###########################Affichage##############################
				$get_quote = retrieve(GET, 'quote', 0);
				$contents = '';
				
				if ($get_quote > 0)
				{
					$info_com = $Sql->query_array(DB_TABLE_COM, 'login', 'contents', "WHERE script = '" . $this->script . "' AND idprov = '" . $this->idprov . "' AND idcom = '" . $get_quote . "'", __LINE__, __FILE__);
					$contents = '[quote=' . $info_com['login'] . ']' . $info_com['contents'] . '[/quote]';
				}

				//Affichage du lien de verrouillage/déverrouillage.
				if ($User->check_level(MODO_LEVEL))
				{
					$Template->assign_vars(array(
						'COM_LOCK' => true,
						'IMG' => ($this->lock_com >= 1) ? 'unlock' : 'lock',
						'L_LOCK' => ($this->lock_com >= 1) ? $LANG['unlock'] : $LANG['lock'],
						'U_LOCK' => $this->path . (($this->lock_com >= 1) ? $vars_simple . '&amp;lock=0&amp;token=' . $Session->get_token() : $vars_simple . '&amp;lock=1&amp;token=' . $Session->get_token()) . ((!empty($page_path_to_root) && !$integrated_in_environment) ? '&amp;path_to_root=' . $page_path_to_root : '')
					));
				}
				
				//Gestion des erreurs.
				$get_error = !empty($_GET['errorh']) ? trim($_GET['errorh']) :'';
				$errno = E_USER_NOTICE;
				switch ($get_error)
				{
					case 'auth':
						$errstr = $LANG['e_unauthorized'];
						$errno = E_USER_WARNING;
						break;
					case 'verif':
						$errstr = $LANG['e_incorrect_verif_code'];
						$errno = E_USER_WARNING;
						break;
					case 'l_flood':
						$errstr = sprintf($LANG['e_l_flood'], $CONFIG_COM['max_link']);
						break;
					case 'l_pseudo':
						$errstr = $LANG['e_link_pseudo'];
						break;
					case 'flood':
						$errstr = $LANG['e_flood'];
						break;
					case 'incomplete':
						$errstr = $LANG['e_incomplete'];
						break;
					default:
						$errstr = '';
				}
				
				$Errorh->set_template($Template); //On spécifie le template utilisé.
				if (!empty($errstr))
				{	
					$Template->assign_vars(array(
						'ERROR_HANDLER' => $Errorh->display($errstr, E_USER_NOTICE)
					));
				}
				
				//Affichage du formulaire pour poster si les commentaires ne sont pas vérrouillé
				if (!$this->lock_com || $User->check_level(MODO_LEVEL))
				{
					if ($User->check_level($CONFIG_COM['com_auth']))
					{	
						$Template->assign_vars(array(
							'AUTH_POST_COM' => true
						));
					}
					else
					{	
						$Template->assign_vars(array(
							'ERROR_HANDLER' => $Errorh->display($LANG['e_unauthorized'], E_USER_NOTICE)
						));
					}
				}
				else
				{	
					$Template->assign_vars(array(
						'ERROR_HANDLER' => $Errorh->display($LANG['com_locked'], E_USER_NOTICE)
					));
				}
					
				$get_pos = strpos($_SERVER['QUERY_STRING'], '&pc');
				if ($get_pos)
					$get_page = substr($_SERVER['QUERY_STRING'], 0, $get_pos) . '&amp;pc';
				else
					$get_page = $_SERVER['QUERY_STRING'] . '&amp;pc';
				
				$is_modo = $User->check_level(MODO_LEVEL);
				$is_guest = !$User->check_level(MEMBER_LEVEL);
						
				//Post form
				
				$form = new FormBuilder('comForm', $this->path . sprintf($this->vars, $this->idcom) . ((!empty($page_path_to_root) && !$integrated_in_environment) ? '&amp;path_to_root=' . $page_path_to_root : '') . '&amp;token=' . $Session->get_token());
				$fieldset = new FormFieldset($LANG['add_comment']);
				if ($is_guest) //Visiteur
				{
					$fieldset->add_field(new FormTextEdit($this->script . 'login', $LANG['guest'], array(
						'title' => $LANG['pseudo'], 'class' => 'text', 'required' => true, 
						'maxlength' => 25, 'required_alert' => $LANG['require_pseudo'])
					));
				}
				$fieldset->add_field(new FormTextarea($this->script . 'contents', unparse($contents), array(
					'forbiddentags' => $CONFIG_COM['forbidden_tags'], 'title' => $LANG['message'], 
					'rows' => 10, 'cols' => 47, 'required' => true, 'required_alert' => $LANG['require_text'])
				));
				if ($is_guest && $CONFIG_COM['com_verif_code']) //Code de vérification, anti-bots.
				{
					$fieldset->add_field(new FormCaptchaField('verif_code', $captcha));
				}
				$fieldset->add_field(new FormHiddenField('idprov', $this->idprov));
				$fieldset->add_field(new FormHiddenField('idcom', ''));
				$fieldset->add_field(new FormHiddenField('script', $this->script));
				
				$form->add_fieldset($fieldset);
				$form->display_preview_button($this->script . 'contents'); //Display a preview button for the textarea field(ajax).
				
				//On crée une pagination si le nombre de commentaires est trop important.
				
				$pagination = new Pagination();
				
				$Template->assign_vars(array(
					'C_COM_DISPLAY' => $this->get_attribute('nbr_com') > 0 ? true : false,
					'C_IS_MODERATOR' => $is_modo,
					'COM_FORM' => $form->display(),
					'CURRENT_PAGE_COM' => $integrated_in_environment,
					'POPUP_PAGE_COM' => !$integrated_in_environment,
					'PAGINATION_COM' => $pagination->display($this->path . $vars_simple . '&amp;pc=%d#anchor_' . $this->script, $this->nbr_com, 'pc', $CONFIG_COM['com_max'], 3),
					'SCRIPT' => $this->script,
					'PATH' => SCRIPT,
					'VAR' => $vars_simple,
					'C_BBCODE_TINYMCE_MODE' => $User->get_attribute('user_editor') == 'tinymce',
					'L_XML_LANGUAGE' => $LANG['xml_lang'],
					'L_TITLE' => ($CONFIG['com_popup'] == 0 || $integrated_in_environment === true) ? $LANG['title_com'] : '',
					'L_DELETE_MESSAGE' => $LANG['alert_delete_msg'],
					'L_PUNISHMENT_MANAGEMENT' => $LANG['punishment_management'],
					'L_WARNING_MANAGEMENT' => $LANG['warning_management'],
					'L_QUOTE' => $LANG['quote']
				));
				
				//Création du tableau des rangs.
				$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
				
				//Gestion des rangs.
				$Cache->load('ranks');
				$j = 0;
				$result = $Sql->query_while("SELECT c.idprov, c.idcom, c.login, c.timestamp, m.user_id, m.login as mlogin, m.level, m.user_mail, m.user_show_mail, m.timestamp AS registered, m.user_avatar, m.user_msg, m.user_local, m.user_web, m.user_sex, m.user_msn, m.user_yahoo, m.user_sign, m.user_warning, m.user_ban, m.user_groups, s.user_id AS connect, c.contents
				FROM " . DB_TABLE_COM . " c
				LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = c.user_id
				LEFT JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = c.user_id AND s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "'
				WHERE c.script = '" . $this->script . "' AND c.idprov = '" . $this->idprov . "'
				GROUP BY c.idcom
				ORDER BY c.timestamp DESC
				" . $Sql->limit($pagination->get_first_msg($CONFIG_COM['com_max'], 'pc'), $CONFIG_COM['com_max']), __LINE__, __FILE__);
				while ($row = $Sql->fetch_assoc($result))
				{
					list($edit, $del) = array(false, false);
					$is_guest = empty($row['user_id']);
					
					//Edition/suppression.
					if ($is_modo || ($row['user_id'] == $User->get_attribute('user_id') && $User->get_attribute('user_id') !== -1))
					{
						list($edit, $del) = array(true, true);
					}
					
					//Pseudo.
					if (!$is_guest)
						$com_pseudo = '<a class="msg_link_pseudo" href="' . PATH_TO_ROOT . '/member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" title="' . $row['mlogin'] . '"><span style="font-weight: bold;">' . wordwrap_html($row['mlogin'], 13) . '</span></a>';
					else
						$com_pseudo = '<span style="font-style:italic;">' . (!empty($row['login']) ? wordwrap_html($row['login'], 13) : $LANG['guest']) . '</span>';
					
					//Rang de l'utilisateur.
					$user_rank = ($row['level'] === '0') ? $LANG['member'] : $LANG['guest'];
					$user_group = $user_rank;
					$user_rank_icon = '';
					if ($row['level'] === '2') //Rang spécial (admins).
					{
						$user_rank = $_array_rank[-2][0];
						$user_group = $user_rank;
						$user_rank_icon = $_array_rank[-2][1];
					}
					elseif ($row['level'] === '1') //Rang spécial (modos).
					{
						$user_rank = $_array_rank[-1][0];
						$user_group = $user_rank;
						$user_rank_icon = $_array_rank[-1][1];
					}
					else
					{
						foreach ($_array_rank as $msg => $ranks_info)
						{
							if ($msg >= 0 && $msg <= $row['user_msg'])
							{
								$user_rank = $ranks_info[0];
								$user_rank_icon = $ranks_info[1];
								break;
							}
						}
					}
					
					//Image associée au rang.
					$user_assoc_img = !empty($user_rank_icon) ? '<img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/ranks/' . $user_rank_icon . '" alt="" />' : '';
					
					//Affichage des groupes du membre.
					if (!empty($row['user_groups']))
					{
						$user_groups = '';
						$array_user_groups = explode('|', $row['user_groups']);
						foreach (GroupsService::get_groups_names() as $idgroup => $array_group_info)
						{
							if (is_numeric(array_search($idgroup, $array_user_groups)))
								$user_groups .= !empty($array_group_info['img']) ? '<img src="' . PATH_TO_ROOT . '/images/group/' . $array_group_info['img'] . '" alt="' . $array_group_info['name'] . '" title="' . $array_group_info['name'] . '"/><br />' : $LANG['group'] . ': ' . $array_group_info['name'];
						}
					}
					else
						$user_groups = $LANG['group'] . ': ' . $user_group;
					
					//Membre en ligne?
					$user_online = !empty($row['connect']) ? 'online' : 'offline';
					
					$user_accounts_config = UserAccountsConfig::load();
					
					//Avatar
					if (empty($row['user_avatar']))
						$user_avatar = $user_accounts_config->is_default_avatar_enabled() ? '<img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' .  $user_accounts_config->get_default_avatar_name() . '" alt="" />' : '';
					else
						$user_avatar = '<img src="' . $row['user_avatar'] . '" alt=""	/>';
						
					//Affichage du sexe et du statut (connecté/déconnecté).
					$user_sex = '';
					if ($row['user_sex'] == 1)
						$user_sex = $LANG['sex'] . ': <img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/man.png" alt="" /><br />';
					elseif ($row['user_sex'] == 2)
						$user_sex = $LANG['sex'] . ': <img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/woman.png" alt="" /><br />';
							
					//Nombre de message.
					$user_msg = ($row['user_msg'] > 1) ? $LANG['message_s'] . ': ' . $row['user_msg'] : $LANG['message'] . ': ' . $row['user_msg'];
						
					//Localisation.
					if (!empty($row['user_local']))
					{
						$user_local = $LANG['place'] . ': ' . $row['user_local'];
						$user_local = $user_local > 15 ? substr_html($user_local, 0, 15) . '...<br />' : $user_local . '<br />';
					}
					else $user_local = '';
						
					$contents = ucfirst(second_parse($row['contents']));
						
					//Correction des chemins du BBCode
					if (!$integrated_in_environment && !empty($page_path_to_root))
						$contents = str_replace('"' . $page_path_to_root . '/', '"' . PATH_TO_ROOT . '/', $contents);
				
					$Template->assign_block_vars('com_list', array(
						'ID' => $row['idcom'],
						'CONTENTS' => $contents,
						'DATE' => $LANG['on'] . ': ' . gmdate_format('date_format', $row['timestamp']),
						'CLASS_COLOR' => ($j%2 == 0) ? '' : 2,
						'USER_ONLINE' => '<img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' . $user_online . '.png" alt="" class="valign_middle" />',
						'USER_PSEUDO' => $com_pseudo,
						'USER_RANK' => (($row['user_warning'] < '100' || (time() - $row['user_ban']) < 0) ? $user_rank : $LANG['banned']),
						'USER_IMG_ASSOC' => $user_assoc_img,
						'USER_AVATAR' => $user_avatar,
						'USER_GROUP' => $user_groups,
						'USER_DATE' => !$is_guest ? $LANG['registered_on'] . ': ' . gmdate_format('date_format_short', $row['registered']) : '',
						'USER_SEX' => $user_sex,
						'USER_MSG' => !$is_guest ? $user_msg : '',
						'USER_LOCAL' => $user_local,
						'USER_MAIL' => (!empty($row['user_mail']) && ($row['user_show_mail'] == '1')) ? '<a href="mailto:' . $row['user_mail'] . '"><img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' . get_ulang() . '/email.png" alt="' . $row['user_mail']  . '" title="' . $row['user_mail']  . '" /></a>' : '',
						'USER_MSN' => !empty($row['user_msn']) ? '<a href="mailto:' . $row['user_msn'] . '"><img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' . get_ulang() . '/msn.png" alt="' . $row['user_msn']  . '" title="' . $row['user_msn']  . '" /></a>' : '',
						'USER_YAHOO' => !empty($row['user_yahoo']) ? '<a href="mailto:' . $row['user_yahoo'] . '"><img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' . get_ulang() . '/yahoo.png" alt="' . $row['user_yahoo']  . '" title="' . $row['user_yahoo']  . '" /></a>' : '',
						'USER_SIGN' => !empty($row['user_sign']) ? '____________________<br />' . second_parse($row['user_sign']) : '',
						'USER_WEB' => !empty($row['user_web']) ? '<a href="' . $row['user_web'] . '"><img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' . get_ulang() . '/user_web.png" alt="' . $row['user_web']  . '" title="' . $row['user_yahoo']  . '" /></a>' : '',
						'USER_WARNING' => (!empty($row['user_warning']) ? $row['user_warning'] : '0'),
						'C_COM_MSG_EDIT' => $del,
						'C_COM_MSG_DEL' => $edit,
						'U_COM_EDIT' => $this->path . sprintf($this->vars, $row['idcom']) . '&amp;editcom=1' . ((!empty($page_path_to_root) && !$integrated_in_environment) ? '&amp;path_to_root=' . $page_path_to_root : '') . '#anchor_' . $this->script,
						'U_COM_DEL' => $this->path . sprintf($this->vars, $row['idcom']) . '&amp;token=' . $Session->get_token() . '&amp;delcom=1' . ((!empty($page_path_to_root) && !$integrated_in_environment) ? '&amp;path_to_root=' . $page_path_to_root : '') . '#anchor_' . $this->script,
						'U_COM_WARNING' => ($is_modo && !$is_guest) ? PATH_TO_ROOT . '/member/moderation_panel' . url('.php?action=warning&amp;id=' . $row['user_id'] . ((!empty($page_path_to_root) && !$integrated_in_environment) ? '&amp;path_to_root=' . $page_path_to_root : '')) . '" title="' . $LANG['warning_management'] : '',
						'U_COM_PUNISHEMENT' => ($is_modo && !$is_guest) ? PATH_TO_ROOT . '/member/moderation_panel' . url('.php?action=punish&amp;id=' . $row['user_id'] . ((!empty($page_path_to_root) && !$integrated_in_environment) ? '&amp;path_to_root=' . $page_path_to_root : '')) . '" title="' . $LANG['punishment_management'] : '',
						'U_USER_PM' => '<a href="' . PATH_TO_ROOT . '/member/pm' . url('.php?pm=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '"><img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' . get_ulang() . '/pm.png" alt="" /></a>',
						'U_ANCHOR' => $this->path . $vars_simple . ((!empty($page_path_to_root) && !$integrated_in_environment) ? '&amp;path_to_root=' . $page_path_to_root : '') . '#m' . $row['idcom'],
						'U_QUOTE' => $this->path . sprintf($this->vars, $row['idcom']) . '&amp;quote=' . $row['idcom'] . ((!empty($page_path_to_root) && !$integrated_in_environment) ? '&amp;path_to_root=' . $page_path_to_root : '') . '#anchor_' . $this->script
					));
					$j++;
				}
				$Sql->query_close($result);
			}
			return $Template->parse(Template::TEMPLATE_PARSER_STRING);
		}
		else
			return 'error : class Comments loaded uncorrectly';
	}
	
	
	/**
	 * @static
	 * @param int $nbr_com
	 * @param string $path
	 * @param int $idprov
	 * @param string $script
	 * @param int $options
	 * @return string
	 */
	public static function com_display_link($nbr_com, $path, $idprov, $script, $options = 0)
	{
	    global $CONFIG, $LANG;
	
	    $link = '';
	    $l_com = ($nbr_com > 1) ? $LANG['com_s'] : $LANG['com'];
	    $l_com = !empty($nbr_com) ? $l_com . ' (' . $nbr_com . ')' : $LANG['post_com'];
	
	    $link_pop = "javascript:popup('" . HOST . DIR . url('/kernel/framework/ajax/pop_up_comments.php?com=' . $idprov . $script) . "', '" . $script . "')";
	    $link_current = $path . '#anchor_' . $script;
	
	    $link .= '<a class="com" href="' . (($CONFIG['com_popup'] == '0') ? $link_current : $link_pop) . '">' . $l_com . '</a>';
	
	    return $link;
	}

	## Private Methods ##
	//Récupération de la table du module associée aux commentaires.
	function _get_info_module()
	{
		global $Sql, $CONFIG;

		//Récupération des informations sur le module.
		$info_module = load_ini_file(PATH_TO_ROOT . '/' . $this->module_folder . '/lang/', get_ulang());
		
		$check_script = false;
		if (isset($info_module['com']))
		{
			if ($info_module['com'] == $this->script)
			{
				$info_sql_module = $Sql->query_array(PREFIX . strprotect($info_module['com']), "id", "nbr_com", "lock_com", "WHERE id = '" . $this->idprov . "'", __LINE__, __FILE__);
				if ($info_sql_module['id'] == $this->idprov)
					$check_script = true;
			}
		}
		return $check_script ? array(strprotect($info_module['com']), $info_sql_module['nbr_com'], (bool)$info_sql_module['lock_com']) : array('', 0, 0);
	}
	
	//Initialisation des paramètres quand il s'agit du noyau qui appelle
	function _get_info_kernel_script()
	{
		global $Sql, $CONFIG;
		
		$row_infos = $Sql->query_array(PREFIX . $this->script, "id", "nbr_com", "lock_com", "WHERE id = '" . $this->idprov . "'", __LINE__, __FILE__);
		
		return array($this->script, $row_infos['nbr_com'], (bool)$row_infos['lock_com']);
	}
	
	## Private attributes ##
	var $script = '';
	var $idprov = 0;
	var $idcom = 0;
	var $path = '';
	var $vars = '';
	var $module_folder = '';
	var $sql_table = '';
	var $nbr_com = 0;
	var $lock_com = 0;
	var $is_kernel_script = false;
}

?>