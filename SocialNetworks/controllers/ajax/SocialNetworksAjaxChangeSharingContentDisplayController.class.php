<?php
/*##################################################
 *                          SocialNetworksAjaxChangeSharingContentDisplayController.class.php
 *                            -------------------
 *   begin                : April 11, 2018
 *   copyright            : (C) 2018 Julien BRISWALTER
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

class SocialNetworksAjaxChangeSharingContentDisplayController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_value('id', '');
		
		$display = -1;
		if ($id !== '')
		{
			$config = SocialNetworksConfig::load();
			$enabled_content_sharing = $config->get_enabled_content_sharing();
			if (in_array($id, $enabled_content_sharing))
			{
				unset($enabled_content_sharing[array_search($id, $enabled_content_sharing)]);
				$display = 0;
			}
			else
			{
				$enabled_content_sharing[] = $id;
				$display = 1;
			}
			$config->set_enabled_content_sharing($enabled_content_sharing);
			
			SocialNetworksConfig::save();
		}
		
		return new JSONResponse(array('id' => $id, 'display' => $display));
	}
}
?>
