<html>
<head>
<title>PHPUnit</title>
</head>
<body>
<?php $params = htmlentities(!empty($_REQUEST['params']) ? $_REQUEST['params'] : ''); ?>
<form name="phpunit_launcher">
<span>PHPUnit:</span>
<input type="text" name="params" id="params" value="<?php echo $params; ?>" style="width: 75%;" />
<span>text output:</span>
<input type="checkbox" name="is_text" id="is_text" checked="checked" />
<input type="button" name="run" value="run"
    onclick="self.frames['phpunit'].location='run.php?is_text=' +
        (document.getElementById('is_text').checked ? '1' : '0') +
        '&amp;params=' + document.getElementById('params').value;" />
    </form>
<hr />
<iframe src="run.php?params=<?php echo $params; ?>" width="100%" height="80%" name="phpunit" />

</body>
</html>
