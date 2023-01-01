<?php
/**
 * @package     Core
 * @subpackage  Error
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2017 04 20
 * @since       PHPBoost 3.0 - 2010 10 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

defined('TPL_PATH_TO_ROOT') or define('TPL_PATH_TO_ROOT', PATH_TO_ROOT);

class HTTPFatalExceptionPrinter
{
	private $type;
	private $message;
	private $exception;
	private $ob_content = '';
	private $is_row_odd = true;
	private $output = '';

	public function __construct($exception)
	{
		$this->exception = $exception;
		$this->type = get_class($this->exception);
		$this->message = str_replace("\n", "<br />", $this->exception->getMessage());
		$this->ob_content = AppContext::get_response()->get_previous_ob_content();
	}

	public function render()
	{
		$this->output .= '<!DOCTYPE html>
<html lang="en">
	<head>
		<title>' . $this->type . ' caught</title>
		<meta charset="UTF-8" />
		<style type="text/css">
			body {background-color:#dddddd;}
			table {width:100%;}
			caption {text-align:left;font-size:26px;font-weight:bold;background-color:#536F8B;padding:5px;border-bottom:1px #aaaaaa solid;}
			th {font-size:18px;background-color:#7A99B1;height:30px;}
			tr.section {}
			tr.oddRow {background-color:#ECEEEF;}
			tr.evenRow {background-color:#D2E3F1;}
			td.parameterName {font-size:14px;font-weight:bold;padding:0 10px;}
			td.parameterValue {font-size:14px;}
			h1 {background-color:#536F8B;border:1px #aaaaaa solid;padding:10px;margin:0px;}
			div#exceptionContext .message {font-weight:bold;background-color:#eeeeee;border:1px #aaaaaa solid;padding:10px;}
			.outputBuffer {font-size:12px;background-color:#eeeeee;border-left:1px #aaaaaa solid;border-right:1px #aaaaaa solid;padding:10px;}
			table.stack td.prototype {font-weight:bold;padding-right:10px;}
			table.stack td.file {font-size:14px;font-style:italic;}
			table.stack td.line {text-align:right;font-size:12px;width:30px;}
			table.stack td.args {font-size:14px;padding-right:10px;}
			table.stack td.argsDetails {border-top:1px #aaaaaa solid;}
			div#exceptionContext {background-color:#eeeeee;border:1px #aaaaaa solid;padding:10px;}
			div#whyISeeThisPage {background-color:#eeeeee;border:1px #aaaaaa solid;padding:10px;}
			div#httpContext {background-color:#eeeeee;border:1px #aaaaaa solid;padding:10px;}
		</style>
		<script>
		<!--
		function toggleDisplay(link, eltId) {
			var elt = document.getElementById(eltId);
			var mode = elt.style.display;
			if (mode != \'none\') {
				elt.style.display = \'none\';
				link.innerHTML = \'+\';
			} else {
				elt.style.display = \'table-row\';
				link.innerHTML = \'-\';
			}
		}
		function getOutputBufferContent() {
			return \'' . str_replace("\n", '\n\' + ' . "\n" . '\'', str_replace("\r", '', addslashes(TextHelper::htmlspecialchars($this->ob_content)))) . '\';
		}
		function openOutputBufferPopup(content) {
			var obWindow = window.open(\'\', \'Output Buffer\', \'\');
			obWindow.document.open();
			obWindow.document.write(content);
			obWindow.document.close();
		}
		function displayOutputBufferContent() {
			var content = getOutputBufferContent();
			content = \'&lt;html&gt;&lt;head&gt;&lt;title&gt;OUTPUT BUFFER RAW&lt;/title&gt;&lt;/head&gt;&lt;body&gt;&lt;pre&gt;\'.replace(/&lt;/g, \'<\').replace(/&gt;/g, \'>\') +
				content + \'&lt;/body&gt;&lt;/html&gt;\'.replace(/&lt;/g, \'<\').replace(/&gt;/g, \'>\');
			openOutputBufferPopup(content);
		}
		-->
		</script>
	</head>
	<body>
		<div id="exceptionContext">
			<h1>' . $this->type . '</h1>
			<div class="message">' . $this->message. '</div>
			<table cellpadding="2" cellspacing="0" class="stack">
				<caption>STACKTRACE</caption>
				<tr><th></th><th>METHOD</th><th>FILE</th><th>LINE</th></tr>' . $this->build_stack_trace() . '
			</table>
		</div>
		<div class="outputBuffer"><a href="javascript:displayOutputBufferContent()">output buffer</a></div>
		<div id="whyISeeThisPage">
			You see this page because your site is configured to use the <em>DEBUG</em> mode.<br />
			If you want to see the related user error page, you have to disable the <em>DEBUG</em> mode
			from the <a href="' . TPL_PATH_TO_ROOT . '/admin/config/?url=/advanced/">administration panel</a>.
		</div>
		<div id="httpContext">
			<table cellspacing="0" cellpadding="3 5px"><caption>HTTP Request</caption>
			' . $this->get_http_context() . '
			</table>
		</div>
	</body>
</html>';
		return $this->output;
	}

	private function build_stack_trace()
	{
		$i = 0;
		$this->is_row_odd = true;
		$stack = '';
		foreach ($this->exception->getTrace() as $call)
		{
			$row_class = $this->is_row_odd ? 'oddRow' : 'evenRow';
			$has_args = ExceptionUtils::has_args($call);
			$id = 'call' . $i . 'Arguments';
			$stack .= '<tr class="' . $row_class . '">';
			$stack .= '<td class="args">';
			if ($has_args)
			{
				$stack .= '<a href="javascript:toggleDisplay(this, \'' . $id . '\');">+</a>';
			}
			$stack .= '</td>';
			$stack .= '<td class="prototype">' . ExceptionUtils::get_method_prototype($call) . '</td>';
			$stack .= '<td class="file">' . ExceptionUtils::get_file($call) . '</td>';
			$stack .= '<td class="line">' . ExceptionUtils::get_line($call) . '</td>';
			$stack .= '</tr>';
			if ($has_args)
			{
				$stack .= '<tr id="' . $id . '" style="display: none;" class="' . $row_class . '">
				<td colspan="4" class="argsDetails">' . ExceptionUtils::get_args($call) . '</td></tr>';
			}
			$i++;
			$this->is_row_odd = !$this->is_row_odd;
		}
		return $stack;
	}

	private function get_http_context()
	{
		$http_context = '';
		$http_context .= $this->dump_var('GET', $_GET);
		$http_context .= $this->dump_var('POST', $_POST);
		$http_context .= $this->dump_var('COOKIE', $_COOKIE);
		$http_context .= $this->dump_var('SERVER', $_SERVER);
		return $http_context;
	}

	private function dump_var($title, $parameters)
	{
		$dump =  '';
		if (!empty($parameters))
		{
			$this->is_row_odd = true;
			$dump .= '<tr class="section"><th colspan="2" style="text-align:left;padding:0 10px;">' . $title . '</th></tr>';
			foreach ($parameters as $key => $value)
			{
				$dump .= $this->add_parameter($key, $value);
			}
		}
		return $dump;
	}

	private function add_parameter($key, $value)
	{
		$value_to_display = '';
		if (is_array($value))
		{
			$value_to_display = '<ul>';
			foreach ($value as $a_value)
			{
				$value_to_display .= '<li>' . TextHelper::htmlspecialchars($a_value) . '</li>';
			}
			$value_to_display .= '</ul>';
		}
		else
		{
			$value_to_display = TextHelper::htmlspecialchars($value);
		}
		$row_class = $this->is_row_odd ? 'oddRow' : 'evenRow';
		$this->is_row_odd = !$this->is_row_odd;
		return '<tr class="' . $row_class. '">' .
			'<td class="parameterName">' . $key . '</td>' .
			'<td class="parameterValue">' . str_replace("\n", '<br />', $value_to_display) . '</td>' .
		'</tr>' ;
	}
}
?>
