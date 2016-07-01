<?php
/*##################################################
 *                       ReCaptchaConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : July 1, 2016
 *   copyright            : (C) 2016 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class ReCaptchaConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('ReCaptcha');
	}
	
	protected function build_new_config()
	{
		$old_config = $this->get_old_config();
		
		$config = ReCaptchaConfig::load();
		if ($old_config->get_site_key() && $old_config->get_secret_key())
			$config->enable_recaptchav2();
		
		ReCaptchaConfig::save();
		
		return true;
	}
}
?>