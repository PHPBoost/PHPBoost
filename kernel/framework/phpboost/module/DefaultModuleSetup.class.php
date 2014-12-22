<?php
/*##################################################
/*
 *                           DefaultModuleSetup.class.php
 *                            -------------------
 *   begin                : January 16, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 *###################################################
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
 *###################################################
 */

class DefaultModuleSetup implements ModuleSetup
{
	/* (non-PHPdoc)
	 * @see kernel/framework/phpboost/module/ModuleSetup#check_environment()
	 */
	public function check_environment()
	{
		return new ValidationResult();
	}

	/* (non-PHPdoc)
	 * @see kernel/framework/phpboost/module/ModuleSetup#install()
	 */
	public function install()
	{

	}

	/* (non-PHPdoc)
	 * @see kernel/framework/phpboost/module/ModuleSetup#uninstall()
	 */
	public function uninstall()
	{

	}
	
	/* (non-PHPdoc)
	 * @see kernel/framework/phpboost/module/ModuleSetup#upgrade()
	 */
	public function upgrade($installed_version)
	{
		return null;
	}
}
?>
