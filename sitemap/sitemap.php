<?php
define('PATH_TO_ROOT', '..');
include(PATH_TO_ROOT . '/kernel/begin.php');
define('TITLE', 'titre');

include(PATH_TO_ROOT . '/kernel/header.php');

import('content/sitemap/site_map');
import('modules/modules_discovery_service');

$site_map = new SiteMap();
$config_xml = new SitemapExportConfig('framework/content/sitemap/site_map.xml.tpl', 'framework/content/sitemap/module_map.xml.tpl',
	 'framework/content/sitemap/site_map_section.xml.tpl', 'framework/content/sitemap/site_map_link.xml.tpl');

$Modules = new ModulesDiscoveryService();
foreach ($Modules->get_available_modules('get_module_map') as $module)
{
	$modulemap = $module->get_module_map(SITE_MAP_AUTH_USER);
	echo '<pre>'; print_r($modulemap); echo '</pre>';
	$site_map->add($modulemap);
}

echo '<pre>' . htmlentities($site_map->export($config_xml)) . '</pre>';
	
$sub_section_tpl = new Template('sitemap/modulemapsection_html.tpl');
$sub_section_tpl->assign_vars(array(
	'L_LEVEL' => 'de niveau'
	));

$config_html = new SitemapExportConfig('sitemap/site_map_html.tpl', 'sitemap/module_map_html.tpl', $sub_section_tpl, 'sitemap/modulemaplink_html.tpl');

echo '<hr />' . $site_map->export($config_html);	

include(PATH_TO_ROOT . '/kernel/footer.php');
?>