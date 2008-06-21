<?php
define('PATH_TO_ROOT', '..');
include(PATH_TO_ROOT . '/kernel/begin.php');
define('TITLE', 'titre');

include(PATH_TO_ROOT . '/kernel/header.php');

include_once(PATH_TO_ROOT . '/kernel/framework/sitemap/modulemap.class.php');
include_once(PATH_TO_ROOT . '/kernel/framework/modules/modules.class.php');

$Modules = new Modules();
foreach($Modules->get_availables_modules('get_module_map') as $module)
{
	$modulemap = $module->get_module_map(SITE_MAP_AUTH_USER);
	
	$config_xml = new Sitemap_export_config('sitemap/sitemap_xml.tpl', 'sitemap/modulemapsection_xml.tpl', 'sitemap/modulemaplink_xml.tpl');

	echo '<pre>' . htmlentities($modulemap->Export($config_xml)) . '</pre>';

	$sub_section_tpl = new Template('sitemap/modulemapsection_html.tpl');
	$sub_section_tpl->Assign_vars(array(
		'L_LEVEL' => 'de niveau'
		));

	$config_html = new Sitemap_export_config('sitemap/sitemap_html.tpl', $sub_section_tpl, 'sitemap/modulemaplink_html.tpl');

	echo '<hr />' . $modulemap->Export($config_html);	
}

include(PATH_TO_ROOT . '/kernel/footer.php');
?>