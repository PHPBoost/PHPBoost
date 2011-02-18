<?php
/*##################################################
 *                         MemberScheduledJobs.class.php
 *                            -------------------
 *   begin                : February 3, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

class MemberScheduledJobs extends AbstractScheduledJobExtensionPoint
{
	/**
	 * {@inheritDoc}
	 */
	public function on_changeday(Date $yesterday, Date $today)
	{
		$querier = PersistenceContext::get_querier();
		$nbr_days = UserAccountsConfi::load()->get_unactivated_accounts_timeout();
		$hours = $nbr_days * 24;
		$minutes = $hours * 60;
		$secondes = $minutes * 60;
		
		$results = $querier->select_rows(DB_TABLE_MEMBER, array('user_id'), "WHERE timestamp > '" . $secondes . "'");
		foreach ($results as $row)
		{
			$this->sql_querier->inject("DELETE " . DB_TABLE_MEMBER . "WHERE user_id = :user_id", array('user_id' => $row['user_id']));
		}
	}
}
?>