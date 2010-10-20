<?php
/*##################################################
 *                    HTTPFatalExceptionPrinter.class.php
 *                            -------------------
 *   begin                : October 18, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : horn@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class HTTPFatalExceptionPrinter
{
	private $type;
	private $message;
	private $exception;
	private $output = '';

	public function __construct(Exception $exception)
	{
		$this->exception = $exception;
		$this->type = get_class($this->exception);
		$this->message = str_replace("\n", "<br />", $this->exception->getMessage());
	}

	public function render()
	{
		$this->output .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >
	<head>
		<title>' . $this->type . ' caught</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta http-equiv="Content-Language" content="en" />
		<style type="text/css">
			body {background-color:#dddddd;}
			h1 {background-color:#536F8B;border:1px #aaaaaa solid;padding:10px;margin-bottom:0px;}
			h2 {background-color:#536F8B;border:1px #aaaaaa solid;padding:10px;margin-bottom:0px;}
			div#exceptionContext .message {font-weight:bold;background-color:#eeeeee;border:1px #aaaaaa solid;padding:10px;}
			div#exceptionContext .stacktrace {background-color:#eeeeee;border:1px #aaaaaa solid;padding:10px;}
			div#exceptionContext .stacktrace table.stack td.prototype {font-weight:bold;padding-right:10px;}
			div#exceptionContext .stacktrace table.stack td.file {font-style:italic;}
			div#exceptionContext .stacktrace table.stack td.args {font-size:12px;padding-right:10px;}
			div#exceptionContext .stacktrace table.stack td.argsDetails {}
			div#whyISeeThisPage {background-color:#eeeeee;border:1px #aaaaaa solid;padding:10px;}
			div#httpContext {border:1px #aaaaaa solid;margin-top:10px;padding-top:0px;}
			div#httpContext .header {text-align:left;font-size:26px;font-weight:bold;background-color:#536F8B;padding:5px;border-bottom:1px #aaaaaa solid;}
			div#httpContext .section {font-size:18px;background-color:#7A99B1;height:30px;}
			div#httpContext .parameterRow {}
			div#httpContext .parameterOddRow {background-color:#ECEEEF;}
			div#httpContext .parameterEvenRow {background-color:#D2E3F1;}
			div#httpContext .parameterRow .parameterName {font-size:14px;font-weight:bold;padding:0 10px;}
			div#httpContext .parameterRow .parameterValue {font-size:14px;}
		</style>
		<script type="text/javascript">
		<!--
		function toggleDisplay(eltId) {
			var elt = document.getElementById(eltId);
			var mode = elt.style.display;
			if (mode != \'none\') {
				elt.style.display = \'none\';
			} else {
				elt.style.display = \'table-row\';
			}
		}
		-->
		</script>
	</head>
	<body>
		<div id="exceptionContext">
			<h1>' . $this->type . '</h1>
			<div class="message">' . $this->message. '</div>
			<div class="stacktrace">' . $this->build_stack_trace() . '</div>
		</div>
		<div id="whyISeeThisPage">
			You see this page because your site is configured to use the <em>DEBUG</em> mode.<br />
			If you want to see the related user error page, you have to disable the <em>DEBUG</em> mode
			from the <a href="' . TPL_PATH_TO_ROOT . '/admin/admin_config.php?adv=1">administration panel</a>.
		</div>
		<div id="httpContext">' . $this->get_http_context() . '</div>
	</body>
</html>';
		return $this->output;
	}

	private function build_stack_trace()
	{
		$stack = '<table cellpadding="2" class="stack"><tr><th></th><th>Method</th><th>File</th></tr>';
		$i = 0;
		foreach ($this->exception->getTrace() as $call)
		{
			$has_args = ExceptionUtils::has_args($call);
			$id = 'call' . $i . 'Arguments';
			$stack .= '<tr><td class="args">';
			if ($has_args)
			{
				$stack .= '<a href="#" onclick="toggleDisplay(\'' . $id . '\');">args</a>';
			}
			else {
				$stack .= 'no args';
			}
			$stack .= '</td><td class="prototype">' . ExceptionUtils::get_method_prototype($call) .
				'</td><td class="file">' . ExceptionUtils::get_file($call) .
				'</td></tr>';
			if ($has_args)
			{
				$stack .= '<tr id="' . $id . '" style="display:none;"><td colspan="3" class="argsDetails">' .
					ExceptionUtils::get_args($call) . '</td></tr>';
			}
			$i++;
		}
		$stack .= '</table>';
		return $stack;
	}

	private function get_http_context()
	{
		$dumper = new HTTPRequestDumper();
		return $dumper->dump(AppContext::get_request());
	}
}

?>