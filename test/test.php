<?php
define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/framework/functions.inc.php';
import('io/filesystem/folder');

function list_tu($directory, $recursive = false) {
	$files = array();
    $folder = new Folder($directory);
    foreach ($folder->get_files('`^ut_.+\.class\.php$`') as $file) {
       $files[] = preg_replace('`^[\./]*kernel/framework/`', '', $file->get_name(true));
    }
    
    if ($recursive) {
        foreach ($folder->get_folders() as $folder) {
           $files = array_merge($files, list_tu($folder->get_name(true), true));
        }
    }
    return $files;
}

?>
<html>
	<head>
		<title>PHPUnit</title>
        <style>
            table.run-options {
                width:100%;
            }
            td.options-set-name {
                width:150px;
            }
            td.options-set {
                /*width:100%;*/
            }
            td.run-options-set {
                width:125px;
            }
            input.run-button {
                width:125px;
            }
        </style>
	</head>
	<body>
		<?php $params = htmlentities(!empty($_REQUEST['params']) ? $_REQUEST['params'] : ''); ?>
		<form name="phpunit_launcher">
            <table class="run-options">
                <tr>
                    <td class="options-set-name"><span>command line</span></td>
		            <td class="options-set">
	                    <input type="text" name="params" id="params" value="<?php echo $params; ?>" style="width: 75%;" />
			            <span>html output:</span>
			            <input type="checkbox" name="is_html" id="is_html" />
                    </td>
		            <td class="run-options-set">
	                    <input type="button" name="run" value="run command" class="run-button"
			                onclick="self.frames['phpunit'].location='run.php?is_html=' +
					        (document.getElementById('is_html').checked ? '1' : '0') +
					        '&amp;params=' + document.getElementById('params').value;" />
                    </td>
                </tr>
                <tr>
                    <td class="options-set-name"><span>unit tests</span></td>
                    <td class="options-set">
                        <select id="tus" name="tus">
<?php
foreach (list_tu('./kernel/framework', true) as $tu) {
	echo '<option value="./kernel/framework/' . $tu . '">' . $tu . '</option>';
}
?>
                        </select>
                    </td>
		            <td class="run-options-set">
                        <input type="button" name="run_tu" value="run unit test" class="run-button"
			                onclick="self.frames['phpunit'].location='run.php?is_html=0' +
			                '&amp;params=' + document.getElementById('tus').value;" />
                    </td>
                </tr>
            </table>
        </form>
		<hr />
		<iframe src="run.php?params=<?php echo $params; ?>" width="100%" height="80%" name="phpunit" />
	
	</body>
</html>
