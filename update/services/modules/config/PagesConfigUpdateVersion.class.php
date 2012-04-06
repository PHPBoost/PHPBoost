<?php
/*##################################################
 *                           PagesConfigUpdateVersion.class.php
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

class PagesConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('pages');
	}

	protected function build_new_config()
	{
		$config = $this->get_old_config();
		
		$count_hits_activated = ($config['count_hits'] == 1) ? true : false;
		$comments_activated = ($config['activ_com'] == 1) ? true : false;
		
		$pages_config = PagesConfig::load();
		$pages_config->set_count_hits_activated($count_hits_activated);
		$pages_config->set_comments_activated($comments_activated);
		$pages_config->set_authorizations(unserialize($config['auth']));
		PagesConfig::save();

		return true;
	}
}
?>