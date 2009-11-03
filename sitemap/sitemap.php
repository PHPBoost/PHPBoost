<?php

define('PATH_TO_ROOT', '..');
include(PATH_TO_ROOT . '/kernel/begin.php');
define('TITLE', 'titre');

include(PATH_TO_ROOT . '/kernel/header.php');



$site_map = new SiteMap();
$config_xml = new SitemapExportConfig('framework/content/sitemap/site_map.xml.tpl', 'framework/content/sitemap/module_map.xml.tpl',
	 'framework/content/sitemap/site_map_section.xml.tpl', 'framework/content/sitemap/site_map_link.xml.tpl');

$site_map->build_kernel_map(SITE_MAP_USER_MODE, SITE_MAP_AUTH_USER);
$site_map->build_modules_maps();

echo '<pre>' . htmlentities($site_map->export($config_xml)) . '</pre>';
	
$sub_section_tpl = new Template('sitemap/site_map_section_html.tpl');
$sub_section_tpl->assign_vars(array(
	'L_LEVEL' => 'de niveau'
    ));

$config_html = new SitemapExportConfig('sitemap/site_map_html.tpl', 'sitemap/module_map_html.tpl', $sub_section_tpl, 'sitemap/site_map_link_html.tpl');

echo '<hr />' . $site_map->export($config_html);	

include(PATH_TO_ROOT . '/kernel/footer.php');

?>