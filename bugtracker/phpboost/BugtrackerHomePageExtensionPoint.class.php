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
		
		return $BUGTRACKER_LANG['bugtracker'];
	}
	
	private function get_view()
	{
		global $User, $Cache, $Bread_crumb, $Errorh, $BUGTRACKER_LANG, $LANG, $Session;
		
		$bugtracker_config = BugtrackerConfig::load();
		
		//Configuration des authorisations
		$authorizations = $bugtracker_config->get_authorizations();
		
		require_once(PATH_TO_ROOT . '/bugtracker/bugtracker_begin.php');

		$tpl = new FileTemplate('bugtracker/bugtracker.tpl');
        
		//checking authorization
		if (!$User->check_auth($authorizations, BUG_READ_AUTH_BIT))
		{
			$Errorh->handler('e_auth', E_USER_REDIRECT);
			exit;
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
			case 'priority' :
				$sort = 'priority';
				break;
			case 'date' :
				$sort = 'submit_date';
				break;
			default :
				$sort = 'submit_date';
		}
		
		$get_mode = retrieve(GET, 'mode', '');
		$mode = ($get_mode == 'asc') ? 'ASC' : 'DESC';
		
		if ($User->check_auth($authorizations, BUG_CREATE_AUTH_BIT) || $User->check_level(User::ADMIN_LEVEL))
		{
			$tpl->put_all(array(
				'ADD_BUG' 	=> '&raquo; <a href="bugtracker' . url('.php?add=true') . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/add.png" alt="' . $BUGTRACKER_LANG['bugs.actions.add'] . '" title="' . $BUGTRACKER_LANG['bugs.actions.add'] . '" class="valign_middle" /></a>'
			));
		}
		
		//Activation de la colonne "Actions" si administrateur
		if ($User->check_level(User::ADMIN_LEVEL))
		{
			$tpl->put_all(array(
				'C_IS_ADMIN'	=> true
			));
		}
		
		$types = $bugtracker_config->get_types();
		$tpl->put_all(array(
			'C_EMPTY_TYPES' 		=> empty($types) ? true : false,
			'C_NO_BUGS' 			=> empty($nbr_bugs) ? true : false,
			'PAGINATION' 			=> $Pagination->display('bugtracker' . url('.php?p=%d'), $nbr_bugs, 'p', $bugtracker_config->get_items_per_page(), 3),
			'L_CONFIRM_DEL_BUG' 	=> $BUGTRACKER_LANG['bugs.actions.confirm.del_bug'],
			'L_BUGS_LIST' 			=> $BUGTRACKER_LANG['bugs.titles.bugs_list'],
			'L_ID' 					=> $BUGTRACKER_LANG['bugs.labels.fields.id'],
			'L_TITLE'				=> $BUGTRACKER_LANG['bugs.labels.fields.title'],
			'L_TYPE'				=> $BUGTRACKER_LANG['bugs.labels.fields.type'],
			'L_SEVERITY'			=> $BUGTRACKER_LANG['bugs.labels.fields.severity'],
			'L_PRIORITY'			=> $BUGTRACKER_LANG['bugs.labels.fields.priority'],
			'L_DATE'				=> $BUGTRACKER_LANG['bugs.labels.fields.submit_date'],
			'L_NO_BUG' 				=> $BUGTRACKER_LANG['bugs.notice.no_bug'],
			'L_ACTIONS' 			=> $BUGTRACKER_LANG['bugs.actions'],
			'L_UPDATE' 				=> $LANG['update'],
			'L_HISTORY' 			=> $BUGTRACKER_LANG['bugs.actions.history'],
			'L_DELETE' 				=> $LANG['delete'],
			'U_BUG_ID_TOP' 			=> url('.php?sort=id&amp;mode=desc'),
			'U_BUG_ID_BOTTOM' 		=> url('.php?sort=id&amp;mode=asc'),
			'U_BUG_TITLE_TOP' 		=> url('.php?sort=title&amp;mode=desc'),
			'U_BUG_TITLE_BOTTOM' 	=> url('.php?sort=title&amp;mode=asc'),
			'U_BUG_TYPE_TOP' 		=> url('.php?sort=type&amp;mode=desc'),
			'U_BUG_TYPE_BOTTOM' 	=> url('.php?sort=type&amp;mode=asc'),
			'U_BUG_SEVERITY_TOP' 	=> url('.php?sort=severity&amp;mode=desc'),
			'U_BUG_SEVERITY_BOTTOM'	=> url('.php?sort=severity&amp;mode=asc'),
			'U_BUG_PRIORITY_TOP' 	=> url('.php?sort=priority&amp;mode=desc'),
			'U_BUG_PRIORITY_BOTTOM'	=> url('.php?sort=priority&amp;mode=asc'),
			'U_BUG_DATE_TOP' 		=> url('.php?sort=date&amp;mode=desc'),
			'U_BUG_DATE_BOTTOM' 	=> url('.php?sort=date&amp;mode=asc')
		));
		
		$tpl->assign_block_vars('list', array());
		
		$result = $this->sql_querier->query_while("SELECT *
		FROM " . PREFIX . "bugtracker
		ORDER BY " . $sort . " " . $mode .
		$this->sql_querier->limit($Pagination->get_first_msg($bugtracker_config->get_items_per_page(), 'p'), $bugtracker_config->get_items_per_page()), __LINE__, __FILE__); //Bugs enregistr�s.
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$tpl->assign_block_vars('list.bug', array(
				'ID'			=> $row['id'],
				'TITLE'			=> (strlen($row['title']) > 45 ) ? substr($row['title'], 0, 45) . '...' : $row['title'], // On raccourcis le titre pour ne pas d�former le tableau
				'TYPE'			=> !empty($row['type']) ? stripslashes($row['type']) : $BUGTRACKER_LANG['bugs.notice.none'],
				'SEVERITY'		=> $BUGTRACKER_LANG['bugs.severity.' . $row['severity']],
				'PRIORITY'		=> $BUGTRACKER_LANG['bugs.priority.' . $row['priority']],
				'COLOR' 		=> ($row['status'] != 'closed') ? 'bgcolor="#' . $bugtracker_config->get_severity_color($row['severity']) . '"' : '',
				'LINE_COLOR'	=> ($row['status'] == 'closed') ? 'bgcolor="#' . $bugtracker_config->get_closed_bug_color() . '"' : '',
				'DATE' 			=> gmdate_format('date_format_short', $row['submit_date'])
			));
		}
		$this->sql_querier->query_close($result);
	
		return $tpl;
	}
}
?>