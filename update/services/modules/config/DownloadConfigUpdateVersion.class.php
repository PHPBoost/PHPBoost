<?php
/*##################################################
 *                           DownloadConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : March 8, 2012
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

class DownloadConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('download');
	}

	protected function build_new_config()
	{
		$config = $this->get_old_config();
		
		$download_config = DownloadConfig::load();
		$download_config->set_nbr_file_max($config['nbr_file_max']);
		$download_config->set_number_columns($config['number_column']);
		$download_config->set_note_max($config['note_max']);
		$download_config->set_root_contents($config['root_contents']);
		$download_config->set_authorizations($config['global_auth']);
		DownloadConfig::save();

		return true;
	}
}
?>