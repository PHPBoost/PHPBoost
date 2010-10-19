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
		$this->output .= '<html>
	<head>
		<title>' . $this->type . ' caught</title>
		<style>
			body {background-color:#dddddd;}
			h1 {background-color:#536F8B;border:1px #aaaaaa solid;padding:10px;margin-bottom:0px;}
			div#exceptionContext .message {font-weight:bold;background-color:#eeeeee;border:1px #aaaaaa solid;padding:10px;}
			div#exceptionContext .stacktrace {background-color:#eeeeee;border:1px #aaaaaa solid;padding:10px;}
			div#exceptionContext .stacktrace span.class {font-weight:bold;}
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
	</head>
	<body>
		<div id="exceptionContext">
			<h1>' . $this->type . '</h1>
			<div class="message">' . $this->message. '</div>
			<div class="stacktrace">' . $this->build_stack_trace() . '</div>
		</div>
		<div id="whyISeeThisPage">
			You see this page because your site is configured to use the <em>DEBUG</em> mode.<br />
			I you want to see the related user error page, you could disable the <em>DEBUG</em> mode
			from the <a href="' . TPL_PATH_TO_ROOT . '/admin/admin_config.php?adv=1">administration panel</a>.
		</div>
		<div id="httpContext">' . $this->get_http_context() . '</div>
	</body>
</html>';
		return $this->output;
	}

	private function build_stack_trace()
	{
		$stack = '<ul>';
		foreach ($this->exception->getTrace() as $call)
		{
			$stack .= '<li>';
			$stack .= $this->get_file($call) . '</a> - ' . $this->get_method_prototype($call);
			$stack .= '</li>';
		}
		$stack .= '</ul>';
		return $stack;
	}

	private function get_file($call)
	{
		if (!empty($call['file']))
		{
			return Path::get_path_from_root($call['file']) . ':' . $call['line'];
		}
		return 'Internal';
	}

	private function get_method_prototype($call)
	{
		$prototype = '<span class="class">';
		if (!empty($call['class']))
		{
			$prototype .= $call['class'] . $call['type'];
		}
		$prototype .= $call['function'] . '(</span>';
		if (!empty($call['args']))
		{
			$prototype .= $this->get_args($call['args']);
		}
		$prototype .= '<span class="class">)</span>';
		return $prototype;
	}

	private function get_args($args)
	{
		$trace = '';

		$i = 0;
		$count = count($args) - 1;
		foreach ($args as $arg)
		{
			if (is_numeric($arg))
			{
				$trace .= (int) $arg;
			}
			elseif (is_bool($arg))
			{
				$trace .= ($arg ? 'True' : 'False');
			}
			elseif (is_object($arg))
			{
				$trace .= get_class($arg);
			}
			elseif (is_array($arg))
			{
				$trace .= 'array[' . count($arg) . ']';
			}
			else
			{
				$string_maxlength = 150;
				if (strlen($arg) > $string_maxlength)
				{
					$arg = substr($arg, 0, $string_maxlength - 3) . '...';
				}
				$trace .= '\'' . htmlspecialchars(addslashes($arg)) . '\'';
			}

			if ($i < $count)
			{
				$trace .= ', ';
			}
			$i++;
		}
		return $trace;
	}

	private function get_http_context()
	{
		$dumper = new HTTPRequestDumper();
		return $dumper->dump(AppContext::get_request());
	}
}

?>