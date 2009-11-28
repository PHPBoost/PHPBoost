<?php
define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/begin.php';

load_module_lang('test');

define('TITLE', $LANG['test_title']);

require_once PATH_TO_ROOT . '/kernel/header.php';

function list_tu($directory, $recursive = false) {
	$files = array();
    $folder = new Folder($directory);
    foreach ($folder->get_files('`^(?:(?:UT)|(?:ut_)).+\.class\.php$`') as $file) {
       $files[] = preg_replace('`^[\./]*kernel/framework/`', '', $file->get_name(true));
    }
    
    if ($recursive) {
        foreach ($folder->get_folders() as $folder) {
           $files = array_merge($files, list_tu($folder->get_name(true), true));
        }
    }
    return $files;
}

$params = htmlentities(!empty($_REQUEST['params']) ? $_REQUEST['params'] : '');

$tpl = new Template('test/test.tpl');

$tpl->assign_vars(array(
	'PARAMS' => $params
));

foreach (list_tu('./kernel/framework', true) as $tu)
{
	$tpl->assign_block_vars('tests', array(
		'NAME' => $tu
	));
}

$tpl->parse();

require_once PATH_TO_ROOT . '/kernel/footer.php';
?>
