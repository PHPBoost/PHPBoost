<?php
/*##################################################
 *                        SearchProvidersService.class.php
 *                            -------------------
 *   begin                : April 10, 2010
 *   copyright            : (C) 2010 Rouchon Loic
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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

class SearchProvidersService {

	public static function get_providers_ids()
	{
		return array_keys(self::get_providers());
	}

	public static function get_providers()
	{
		$providers = array();
        $unauthorized_providers = SearchModuleConfig::load()->get_unauthorized_providers();
        $provider_service = AppContext::get_extension_provider_service();
        foreach ($provider_service->get_providers(SearchableExtensionPoint::EXTENSION_POINT) as $provider)
        {
        	$provider_id = $provider->get_id();
            if (!in_array($provider_id, $unauthorized_providers))
            {
                $providers[$provider_id] = $provider;
            }
        }
        return $providers;
	}
}

?>