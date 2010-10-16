<?php
/*##################################################
 *                         DownloadScheduledJobs.class.php
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

class DownloadScheduledJobs extends AbstractScheduledJobExtensionPoint
{
	/**
	 * {@inheritDoc}
	 */
	public function on_changeday(Date $yesterday, Date $today)
	{
		$querier = PersistenceContext::get_querier();
		$results = $querier->select_rows($table_name, array('id', 'start', 'end'), 'WHERE start > 0 AND end > 0');
		foreach ($results as $row)
		{
			if ($row['start'] <= $time && $row['end'] >= $time && $row['visible'] = 0)
			{
				$this->update_download_table_row($row['id'], array('visible' => 1));
			}
			if (($row['start'] >= $time || $row['end'] <= $time) && $row['visible'] = 1)
			{
				$this->update_download_table_row($row['id'], array('visible' => 0));
			}
		}
	}

	private function update_download_table_row($id, array $fields)
	{
		PersistenceContext::get_querier()->update(PREFIX . 'download', $fields, 'WHERE id=:id', array('id' => $id));
	}
}
?>