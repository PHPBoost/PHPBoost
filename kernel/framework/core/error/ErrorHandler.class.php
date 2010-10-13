<?php
/*##################################################
 *                          ErrorHandler.class.php
 *                            -------------------
 *   begin                : September 30, 2009
 *   copyright            : (C) 2009 Benoit Sautel, Loic Rouchon
 *   email                : ben.popeye@phpboost.com, loic.rouchon@phpboost.com
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

/**
 * @author Benoit Sautel <ben.popeye@phpboost.com>, Loic Rouchon <loic.rouchon@phpboost.com>
 * @package {@package}
 * @desc
 */
class ErrorHandler
{
	const FATAL_MESSAGE = 'Sorry, we encountered a problem and we cannot complete your request...';

	/**
	 * @var int the maximum size of the error log file in bytes
	 */
	private static $LOG_FILE_MAX_SIZE = 1048576;

	protected $errno;
	protected $errfile;
	protected $errline;
	protected $errdesc;
	protected $errclass;
	protected $errimg;
	protected $fatal;
	protected $stacktrace;
	protected $exception;

	/**
	 * @desc log the error and displays it in debug mode
	 * @param unknown_type $errno contains the level of the error raised, as an integer
	 * @param unknown_type $errstr contains the error message, as a string
	 * @param unknown_type $errfile the filename that the error was raised in, as a string
	 * @param unknown_type $errline the line number the error was raised at, as an integer
	 * @return bool always true because we don't want the php default error handler to process the
	 * error again
	 */
	public function handle($errno, $errstr, $errfile, $errline)
	{
		if ($this->needs_to_be_processed($errno))
		{
			$this->prepare($errno, $errstr, $errfile, $errline);
			$this->process();
			$this->display();
			$this->log();
			if ($this->fatal)
			{
				exit;
			}
		}
		return true;
	}

	private function prepare($errno, $errstr, $errfile, $errline)
	{
		$this->exception = new Exception($errstr);
        $this->errno = $errno;
        $this->errfile = $errfile;
        $this->errline = $errline;
		$this->stacktrace = '';
		$this->errdesc = '';
		$this->errclass = '';
		$this->errimg = '';
		$this->fatal = false;

	}

	/**
	 * @return boolean true if the error is not thrown by a functionprefixed with an @ and if the
	 * errno is in the ERROR_REPORTING level
	 */
	private function needs_to_be_processed($errno)
	{
		return error_reporting() != 0 && ($errno & ERROR_REPORTING);
	}

	private function process()
	{
		switch ($this->errno)
		{
			case E_USER_NOTICE:
			case E_NOTICE:
				$this->errdesc = 'Notice';
				$this->errimg =  'notice';
				$this->errclass =  'error_notice';
				break;
				//Warning utilisateur.
			case E_USER_WARNING:
			case E_WARNING:
				$this->errdesc = 'Warning';
				$this->errimg =  'important';
				$this->errclass =  'error_warning';
				break;
				//Strict standards
			case E_STRICT:
				$this->errdesc = 'Strict Standards';
				$this->errimg =  'notice';
				$this->errclass =  'error_notice';
				break;
				//Erreur fatale.
			case E_USER_ERROR:
			case E_ERROR:
			case E_RECOVERABLE_ERROR:
				$this->fatal = true;
				$this->errdesc = 'Fatal Error';
				$this->errimg =  'stop';
				$this->errclass =  'error_fatal';
				break;
			default:
				$this->errdesc = 'Unknown Error';
				$this->errimg =  'question';
				$this->errclass =  'error_unknow';
				break;
		}
	}

	private function display()
	{
		if (Debug::is_debug_mode_enabled())
		{
			$this->display_debug();
		}
		elseif ($this->fatal)
		{
			$this->display_fatal();
		}
	}

	protected function get_stackstrace_as_string($start_trace_index) {
		$stack = '[0] ' . Path::get_path_from_root($this->errfile) . ':' . $this->errline;
		if (count($this->exception->getTrace()) > 2)
		{
            $stack .= (Debug::is_output_html() ? '<br />' : "\n");
            $stack .= Debug::get_stacktrace_as_string($start_trace_index);
		}
		return $stack;
	}

	protected function display_debug()
	{
		if (Debug::is_output_html())
		{
			echo '<span id="errorh"></span>
            <div class="' . $this->errclass . '" style="width:auto;max-width:750px;margin:15px auto;padding:15px;">
                <img src="' . PATH_TO_ROOT . '/templates/default/images/' . $this->errimg . '.png"
                    alt="" style="float:left;padding-right:6px;" />
                <strong>' . $this->errdesc . ' : </strong>' . $this->exception->getMessage() . '<br /><br /><br />
                <em>' . $this->get_stackstrace_as_string(6) . '</em></div>';
		}
		else
		{
			echo "\n" . $this->errdesc . ': ' . $this->exception->getMessage() .
				"\n" . $this->get_stackstrace_as_string(6) . "\n";
		}
	}

	protected function display_fatal()
	{
		$this->display_debug();
	}

	private function log()
	{
		self::add_error_in_log($this->exception->getMessage(), $this->get_stackstrace_as_string(4), $this->errno);
	}

	public static function add_error_in_log($error_msg, $error_stacktrace, $errno = 0)
	{
		$error_log_file = PATH_TO_ROOT . '/cache/error.log';
		self::clear_error_log_file($error_log_file);
		self::add_error_in_log_file($error_log_file, $error_msg, $error_stacktrace, $errno);
	}

	private static function clear_error_log_file($log_file)
	{
		if (file_exists($log_file) && filesize($log_file) > self::$LOG_FILE_MAX_SIZE)
		{
			$handle = @fopen($log_file, 'w+');
			@ftruncate($handle, 0);
			@fclose($handle);
		}
	}

	private static function add_error_in_log_file($log_file, $error_msg, $error_stacktrace, $errno = 0)
	{
		$handle = @fopen($log_file, 'a+');
		$write = @fwrite($handle, self::compute_error_log_string($error_msg, $error_stacktrace, $errno));
		$close = @fclose($handle);

		if ($handle === false || $write === false || $close === false)
		{
			echo '<span id="errorh">Can\'t write error to log file</span>';
		}
	}

	private static function compute_error_log_string($error_msg, $error_stacktrace, $errno = 0)
	{
		return gmdate_format('Y-m-d H:i:s', time(), TIMEZONE_SYSTEM) . "\n" .
		$errno . "\n" .
		self::clean_error_string($error_msg) . "\n" .
		self::clean_error_string($error_stacktrace) . "\n";
	}

	private static function clean_error_string($message)
	{
		return preg_replace("`(\n)+`", '<br />', preg_replace("`\r|\n|\t`", "\n", $message));
	}
}
