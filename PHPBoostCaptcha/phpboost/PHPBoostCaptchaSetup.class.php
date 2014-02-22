<?php
/*##################################################
 *                             PHPBoostCaptchaSetup.class.php
 *                            -------------------
 *   begin                : December 20, 2013
 *   copyright            : (C) 2013 Kévin MASSY
 *   email                : kevin.massy@phpboost.com
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

class PHPBoostCaptchaSetup extends DefaultModuleSetup
{
	private static $db_utils;
	public static $verif_code_table;
	
	public static function __static()
	{
		self::$db_utils = PersistenceContext::get_dbms_utils();
		self::$verif_code_table = PREFIX . 'verif_code';
	}
	
	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
	}

	public function uninstall()
	{
		$this->drop_tables();
		ConfigManager::delete('phpboost-captcha', 'config');
		return AppContext::get_captcha_service()->uninstall_captcha('PHPBoostCaptcha');
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$verif_code_table));
	}

	private function create_tables()
	{
		$this->create_verif_code_table();
	}

	private function create_verif_code_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'user_id' => array('type' => 'integer', 'length' => 15, 'notnull' => 1, 'default' => 0),
			'code' => array('type' => 'string', 'length' => 20, 'notnull' => 1, 'default' => 0),
			'difficulty' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id')
		);
		self::$db_utils->create_table(self::$verif_code_table, $fields, $options);
	}
}
?>