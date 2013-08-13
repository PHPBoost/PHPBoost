<?php
/*##################################################
 *                  	 css_cache.php
 *                            -------------------
 *   begin                : June 28, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

require_once 'init.php';

$request = AppContext::get_request();
$files = $request->get_getstring('files', '');
$name = $request->get_getstring('name', '');

$files = explode(';', $files);

if (!empty($files) && !empty($name))
{
	$css_cache = new CSSCacheManager();
	try {
		$css_cache->set_files($files);
	} catch (Exception $e) {
	}
	
	$css_cache->set_cache_file_location(PATH_TO_ROOT . '/cache/css/css-cache-' . $name .'.css');
	$css_cache->execute(CSSCacheConfig::load()->get_optimization_level());
	
	AppContext::get_response()->set_header('content-type', 'text/css');
	echo file_get_contents($css_cache->get_cache_file_location());
}
?>