<?php
/*##################################################
 *                       FileUploadConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : February 28, 2012
 *   copyright            : (C) 2012 Kévin MASSY
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

class FileUploadConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('uploads');
	}
	
	protected function build_new_config()
	{
		$config = $this->get_old_config();
		
		$file_upload_config = FileUploadConfig::load();
	    $file_upload_config->set_authorization_enable_interface_files($config['auth_files']);
        $file_upload_config->set_maximum_size_upload($config['size_limit']);
        $file_upload_config->set_enable_bandwidth_protect($config['bandwidth_protect']);
        $file_upload_config->set_authorized_extensions($config['auth_extensions']);
        FileUploadConfig::save();
        
		return true;
	}
}
?>