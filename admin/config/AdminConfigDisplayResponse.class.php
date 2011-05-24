<?php
/*##################################################
 *                           AdminConfigDisplayResponse.class.php
 *                            -------------------
 *   begin                : April 12 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : benoit.sautel@phpboost.com
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

class AdminConfigDisplayResponse extends AdminMenuDisplayResponse
{
	private $lang;

	public function __construct($view)
	{
		global $LANG;

		parent::__construct($view);

		$this->load_lang();

		$view->add_lang($LANG);
		$this->set_title($LANG['configuration']);
		$img = '/templates/' . get_utheme() . '/images/admin/configuration.png';
		$this->add_link($LANG['config_main'], '/admin/admin_config.php', $img);
		$this->add_link($LANG['config_advanced'], '/admin/admin_config.php?adv=1', $img);
		$this->add_link($this->lang['mail_config'], DispatchManager::get_url('/admin/config/index.php', '/mail'), $img);
	}

	private function load_lang()
	{
		$this->lang = LangLoader::get('admin-config-common');
	}
}
?>