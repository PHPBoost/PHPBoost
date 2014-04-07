<?php
define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/framework/functions.inc.php';
require_once PATH_TO_ROOT . '/kernel/framework/core/ClassLoader.class.php';
ClassLoader::init_autoload();

include_once(PATH_TO_ROOT . '/test/util/phpboost_unit_tests.inc.php');

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
		<?php $params = TextHelper::htmlentities(!empty($_REQUEST['params']) ? $_REQUEST['params'] : ''); ?>
		<form name="phpunit_launcher">
            <table class="run-options">
                <tr>
                    <td class="options-set-name"><span>command line</span></td>
		            <td class="options-set">
	                    <input type="text" name="params" id="params" value="<?php echo $params; ?>" style="width: 75%;">
			            <span>html output:</span>
			            <input type="checkbox" name="is_html" id="is_html">
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
foreach (list_tu('./kernel') as $tu) {
    echo '<option value="./kernel/' . $tu . '">' . $tu . '</option>';
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
                <tr>
                    <td class="options-set-name"><span>tests suite</span></td>
                    <td class="options-set">
                        <select id="ts" name="ts">
<?php
foreach (list_test_suite('./kernel') as $ts) {
    echo '<option value="./kernel/' . $ts . '">' . $ts . '</option>';
}
?>
                        </select>
                    </td>
                    <td class="run-options-set">
                        <input type="button" name="run_ts" value="run test suite" class="run-button"
                            onclick="self.frames['phpunit'].location='run.php?is_html=0' +
                            '&amp;params=' + document.getElementById('ts').value;" />
                    </td>
                </tr>
            </table>
        </form>
		<hr />
		<iframe src="run.php?params=<?php echo $params; ?>" width="100%" height="80%" name="phpunit"></iframe>
	</body>
</html>
