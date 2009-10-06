<html>
<head>
<title>PHPUnit</title>
</head>
<body>
<?php $params = htmlentities(!empty($_REQUEST['params']) ? $_REQUEST['params'] : ''); ?>
<form name="phpunit_launcher">
<span>PHPUnit:</span>
<input type="text" name="params" id="params" value="<?php echo $params; ?>" style="width: 75%;" />
<span>html output:</span>
<input type="checkbox" name="is_html" id="is_html" />
<input type="button" name="run" value="run"
    onclick="self.frames['phpunit'].location='run.php?is_html=' +
        (document.getElementById('is_html').checked ? '1' : '0') +
        '&amp;params=' + document.getElementById('params').value;" />
    </form>
<hr />
<iframe src="run.php?params=<?php echo $params; ?>" width="100%" height="80%" name="phpunit" />

</body>
</html>
