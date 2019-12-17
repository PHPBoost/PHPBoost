<?php
/**
 * @package     Core
 * @subpackage  Error
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 01 31
 * @since       PHPBoost 3.0 - 2009 09 30
 * @contributor Loic ROUCHON <horn@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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
	protected $fatal;
	protected $stacktrace;
	protected $exception;

	/**
	 * log the error and displays it in debug mode
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
			if ($this->fatal || Debug::is_strict_mode_enabled())
			{
				exit;
			}
		}
		return true;
	}

	private function prepare($errno, $errstr, $errfile, $errline)
	{
		$this->exception  = new Exception($errstr);
		$this->errno      = $errno;
		$this->errfile    = $errfile;
		$this->errline    = $errline;
		$this->stacktrace = '';
		$this->errdesc    = '';
		$this->errclass   = '';
		$this->fatal      = false;

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
				$this->errclass =  'notice';
				break;
				//Warning utilisateur.
			case E_USER_WARNING:
			case E_WARNING:
				$this->errdesc = 'Warning';
				$this->errclass =  'warning';
				break;
				//Strict standards
			case E_STRICT:
				$this->errdesc = 'Strict Standards';
				$this->errclass =  'notice';
				break;
				//Erreur fatale.
			case E_USER_ERROR:
			case E_ERROR:
			case E_RECOVERABLE_ERROR:
				$this->fatal = true;
				$this->errdesc = 'Fatal Error';
				$this->errclass =  'error';
				break;
			default:
				$this->errdesc = 'Unknown Error';
				$this->errclass =  'question';
				break;
		}
	}

	private function display()
	{
		AppContext::get_response()->set_status_code(503);

		if ($this->fatal)
		{
			AppContext::get_response()->clean_output();
			if (Debug::is_debug_mode_enabled())
			{
				Debug::fatal($this->exception);
			}
			else
			{
				Environment::init_output_bufferization();
				echo self::FATAL_MESSAGE;
				Environment::destroy();
				exit;
			}
		}
		elseif (Debug::is_debug_mode_enabled())
		{
			$this->display_debug();
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
		echo '<div class="message-helper bgc ' . $this->errclass . ' error-handler">
                <strong>' . $this->errdesc . ' : </strong>' . $this->exception->getMessage() . '<br /><br /><br />
                <em>' . $this->get_stackstrace_as_string(6) . '</em>
              </div>';
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
			echo '<span id="message_handler">Can\'t write error to log file</span>';
		}
	}

	private static function compute_error_log_string($error_msg, $error_stacktrace, $errno = 0)
	{
		return Date::to_format(time(), 'Y-m-d H:i:s', Timezone::SERVER_TIMEZONE) . "\n" .
		$errno . "\n" .
		self::clean_error_string($error_msg) . "\n" .
		self::clean_error_string($error_stacktrace) . "\n";
	}

	private static function clean_error_string($message)
	{
		return preg_replace("`(\n)+`u", '<br />', preg_replace("`\r|\n|\t`u", "\n", $message));
	}

	/**
	 * Get Error type
	 */
	public static function get_errno_class($errno)
	{
		switch ($errno)
		{
				//Notice utilisateur.
			case E_USER_NOTICE:
			case E_NOTICE:
				return 'notice';
				//Warning utilisateur.
			case E_USER_WARNING:
            case E_WARNING:
                return 'warning';
                //Erreur fatale.
            case E_USER_ERROR:
            case E_ERROR:
            case E_RECOVERABLE_ERROR:
                return 'error';
            default: //Erreur inconnue.
                return 'question';
         }
	}
}
?>
