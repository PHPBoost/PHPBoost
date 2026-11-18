<!DOCTYPE html>
<html lang="{@common.xml.lang}">
	<head>
		<title>{PAGE_TITLE}</title>
		<meta charset="UTF-8" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/__default__/theme/print.css" type="text/css" media="screen" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body>
        <a href="" class="print-button" onclick="hideButton(this); javascript:window.print();">
            <span>{@common.print}</span>
        </a>
		<h1>{TITLE}</h1>
		<div>{CONTENT}</div>
        <script>
            function hideButton(button) {
                button.style.display = 'none';
            }
        </script>
	</body>
</html>
