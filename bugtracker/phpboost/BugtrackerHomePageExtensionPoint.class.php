<?php
/*##################################################
 *                              BugtrackerHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : April 16, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class BugtrackerHomePageExtensionPoint implements HomePageExtensionPoint
{
	private $sql_querier;

    public function __construct()
    {
        $this->sql_querier = PersistenceContext::get_sql();
	}
	
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), $this->get_view());
	}
	
	private function get_title()
	{
		global $BUGTRACKER_LANG;
		
		return $BUGTRACKER_LANG['bugs.module_title'];
	}
	
	private function get_view()
	{
		global $User, $Cache, $Bread_crumb, $Errorh, $LANG, $Session;
		
		$bugtracker_config = BugtrackerConfig::load();
		
		//Configuration des authorisations
		$authorizations = $bugtracker_config->get_authorizations();
		$items_per_page = $bugtracker_config->get_items_per_page();
		$comments_activated = $bugtracker_config->get_comments_activated();
		$types = $bugtracker_config->get_types();
		$rejected_bug_color = $bugtracker_config->get_rejected_bug_color();
		$closed_bug_color = $bugtracker_config->get_closed_bug_color();
		
		$display_types = sizeof($types) > 1 ? true : false;
		
		require_once(PATH_TO_ROOT . '/bugtracker/bugtracker_begin.php');

		$tpl = new FileTemplate('bugtracker/bugtracker.tpl');
        
		//checking authorization
		if (!$User->check_auth($authorizations, BugtrackerConfig::BUG_READ_AUTH_BIT))
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		//Nombre de bugs
		$nbr_bugs = $this->sql_querier->query("SELECT COUNT(*) FROM " . PREFIX . "bugtracker", __LINE__, __FILE__);
		
		$Pagination = new DeprecatedPagination();
		
		$get_sort = retrieve(GET, 'sort', '');
		switch ($get_sort)
		{
			case 'id' :
				$sort = 'id';
				break;
			case 'title' :
				$sort = 'title';
				break;
			case 'type' :
				$sort = 'type';
				break;
			case 'severity' :
				$sort = 'severity';
				break;
			case 'status' :
				$sort = 'status';
				break;
			case 'comments' :
				$sort = 'nbr_com';
				break;
			case 'date' :
				$sort = 'submit_date';
				break;
			default :
				$sort = 'submit_date';
		}
		
		$get_mode = retrieve(GET, 'mode', '');
		$mode = ($get_mode == 'asc') ? 'ASC' : 'DESC';
		
		if ($User->check_auth($authorizations, BugtrackerConfig::BUG_CREATE_AUTH_BIT) || $User->check_level(User::ADMIN_LEVEL))
		{
			$tpl->put_all(array(
				'ADD_BUG' 	=> '&raquo; <a href="bugtracker' . url('.php?add=true') . '"><img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' . get_ulang() . '/add.png" alt="' . $LANG['bugs.actions.add'] . '" title="' . $LANG['bugs.actions.add'] . '" class="valign_middle" /></a>'
			));
		}
		
		$no_bugs_colspan = 6;
		//Activation de la colonne "Actions" si administrateur
		if ($User->check_level(User::ADMIN_LEVEL) || $User->check_auth($authorizations, BugtrackerConfig::BUG_MODERATE_AUTH_BIT))
		{
			$tpl->put_all(array(
				'C_IS_ADMIN'	=> true
			));
			$no_bugs_colspan = $no_bugs_colspan + 1;
		}
		
		if ($comments_activated == true) $no_bugs_colspan = $no_bugs_colspan + 1;
		
		$tpl->put_all(array(
			'C_DISPLAY_TYPES' 		=> $display_types,
			'C_NO_BUGS' 			=> empty($nbr_bugs) ? true : false,
			'NO_BUGS_COLSPAN' 		=> $no_bugs_colspan,
			'C_COM' 				=> ($comments_activated == true) ? true : false,
			'PAGINATION' 			=> $Pagination->display('bugtracker' . url('.php?p=%d' . (!empty($get_sort) ? '&amp;sort=' . $get_sort : '') . (!empty($get_mode) ? '&amp;mode=' . $get_mode : '')), $nbr_bugs, 'p', $items_per_page, 3),
			'L_CONFIRM_DEL_BUG' 	=> $LANG['bugs.actions.confirm.del_bug'],
			'L_BUGS_LIST' 			=> $LANG['bugs.titles.bugs_list'],
			'L_ID' 					=> $LANG['bugs.labels.fields.id'],
			'L_TITLE'				=> $LANG['bugs.labels.fields.title'],
			'L_TYPE'				=> $LANG['bugs.labels.fields.type'],
			'L_SEVERITY'			=> $LANG['bugs.labels.fields.severity'],
			'L_STATUS'				=> $LANG['bugs.labels.fields.status'],
			'L_DATE'				=> $LANG['bugs.labels.fields.submit_date'],
			'L_COMMENTS'			=> $LANG['title_com'],
			'L_NO_BUG' 				=> $LANG['bugs.notice.no_bug'],
			'L_ACTIONS' 			=> $LANG['bugs.actions'],
			'L_UPDATE' 				=> $LANG['update'],
			'L_HISTORY' 			=> $LANG['bugs.actions.history'],
			'L_DELETE' 				=> $LANG['delete'],
			'L_SOLVED' 				=> $LANG['bugs.titles.solved_bug'],
			'L_STATS' 				=> $LANG['bugs.titles.bugs_stats'],
			'U_BUG_ID_TOP' 			=> url('.php?sort=id&amp;mode=desc'),
			'U_BUG_ID_BOTTOM' 		=> url('.php?sort=id&amp;mode=asc'),
			'U_BUG_TITLE_TOP' 		=> url('.php?sort=title&amp;mode=desc'),
			'U_BUG_TITLE_BOTTOM' 	=> url('.php?sort=title&amp;mode=asc'),
			'U_BUG_TYPE_TOP' 		=> url('.php?sort=type&amp;mode=desc'),
			'U_BUG_TYPE_BOTTOM' 	=> url('.php?sort=type&amp;mode=asc'),
			'U_BUG_SEVERITY_TOP' 	=> url('.php?sort=severity&amp;mode=desc'),
			'U_BUG_SEVERITY_BOTTOM'	=> url('.php?sort=severity&amp;mode=asc'),
			'U_BUG_STATUS_TOP'	 	=> url('.php?sort=status&amp;mode=desc'),
			'U_BUG_STATUS_BOTTOM'	=> url('.php?sort=status&amp;mode=asc'),
			'U_BUG_COMMENTS_TOP' 	=> url('.php?sort=comments&amp;mode=desc'),
			'U_BUG_COMMENTS_BOTTOM'	=> url('.php?sort=comments&amp;mode=asc'),
			'U_BUG_DATE_TOP' 		=> url('.php?sort=date&amp;mode=desc'),
			'U_BUG_DATE_BOTTOM' 	=> url('.php?sort=date&amp;mode=asc')
		));
		
		$tpl->assign_block_vars('list', array());
		
		$result = $this->sql_querier->query_while("SELECT *
		FROM " . PREFIX . "bugtracker
		WHERE status <> 'closed' AND status != 'rejected'
		ORDER BY " . $sort . " " . $mode .
		$this->sql_querier->limit($Pagination->get_first_msg($items_per_page, 'p'), $items_per_page), __LINE__, __FILE__); //Bugs enregistrés.
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			switch ($row['status'])
			{
			case 'closed' :
				$color = $severity_color = 'style="background-color:#' . $closed_bug_color . ';"';
				break;
			case 'rejected' :
				$color = $severity_color = 'style="background-color:#' . $rejected_bug_color . ';"';
				break;
			default :
				$color = '';
				$severity_color = 'style="background-color:#' . $bugtracker_config->get_severity_color($row['severity']) . ';"';
			}
			
			//Nombre de commentaires
			$nbr_coms = $this->sql_querier->query("SELECT number_comments FROM " . PREFIX . "comments_topic WHERE module_id = 'bugtracker' AND id_in_module = '" . $row['id'] . "'", __LINE__, __FILE__);

			$tpl->assign_block_vars('list.bug', array(
				'ID'			=> $row['id'],
				'TITLE'			=> $row['title'],
				'TYPE'			=> !empty($row['type']) ? stripslashes($row['type']) : $LANG['bugs.notice.none'],
				'SEVERITY'		=> $LANG['bugs.severity.' . $row['severity']],
				'STATUS'		=> $LANG['bugs.status.' . $row['status']],
				'COLOR' 		=> $color,
				'SEVERITY_COLOR'=> $severity_color,
				'COMMENTS'		=> '<a href="bugtracker' . url('.php?view=true&id=' . $row['id'] . '&com=0#anchor_bugtracker') . '">' . (empty($nbr_coms) ? 0 : $nbr_coms) . '</a>',
				'DATE' 			=> gmdate_format('date_format', $row['submit_date'])
			));
		}
		$this->sql_querier->query_close($result);
		
		//Gestion erreur.
		$get_error = retrieve(GET, 'error', '');
		switch ($get_error)
		{
			case 'edit_success':
			$errstr = $LANG['bugs.error.e_edit_success'];
			$errtyp = E_USER_SUCCESS;
			break;
			case 'unexist_bug':
			$errstr = $LANG['bugs.error.e_unexist_bug'];
			$errtyp = E_USER_WARNING;
			break;
			default:
			$errstr = '';
			$errtyp = E_USER_NOTICE;
		}
		if (!empty($errstr))
			$tpl->put('message_helper', MessageHelper::display($errstr, $errtyp));
			
		return $tpl;
	}
}
?>