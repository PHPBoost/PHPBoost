<?php
/*##################################################
 *                         ShoutboxScheduledJobs.class.php
 *                            -------------------
 *   begin                : October 16, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class ShoutboxScheduledJobs extends AbstractScheduledJobExtensionPoint
{
	/**
	 * {@inheritDoc}
	 */
	public function on_changeday(Date $yesterday, Date $today)
	{
		$querier = PersistenceContext::get_querier();
		global $Cache, $CONFIG_SHOUTBOX;
		$Cache->load('shoutbox');

		if ($CONFIG_SHOUTBOX['shoutbox_max_msg'] != -1)
		{
			// TODO check the X-SGBD request using @comp
			$querier->select('SELECT @compt := id AS compt FROM ' . PREFIX .
				'shoutbox ORDER BY id DESC LIMIT 0 OFFSET :offset',
				array('offset' => $CONFIG_SHOUTBOX['shoutbox_max_msg']));
			$querier->delete(PREFIX . 'shoutbox', 'WHERE id < @compt');
		}
	}
}
?>