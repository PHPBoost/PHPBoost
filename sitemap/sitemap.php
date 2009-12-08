<?php

define('PATH_TO_ROOT', '..');
include(PATH_TO_ROOT . '/kernel/begin.php');
define('TITLE', 'titre');

include(PATH_TO_ROOT . '/kernel/header.php');

$site_map = new SiteMap();
$config_xml = new SiteMapExportConfig('framework/content/sitemap/sitemap.xml.tpl', 'framework/content/sitemap/module_map.xml.tpl',
	 'framework/content/sitemap/sitemap_section.xml.tpl', 'framework/content/sitemap/sitemap_link.xml.tpl');

$site_map->build_kernel_map(SiteMap::USER_MODE, SiteMap::AUTH_USER);
$site_map->build_modules_maps();

echo '<pre>' . htmlentities($site_map->export($config_xml)->parse(Template::TEMPLATE_PARSER_STRING)) . '</pre>';
	
$sub_section_tpl = new Template('sitemap/sitemap_section.html.tpl');
$sub_section_tpl->assign_vars(array(
	'L_LEVEL' => 'de niveau'
    ));

$config_html = new SiteMapExportConfig('sitemap/sitemap.html.tpl', 'sitemap/module_map.html.tpl', $sub_section_tpl, 'sitemap/sitemap_link.html.tpl');

echo '<hr />';

$site_map->export($config_html)->parse();	

include(PATH_TO_ROOT . '/kernel/footer.php');

?>