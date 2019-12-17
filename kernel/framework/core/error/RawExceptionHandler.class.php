<?php
/**
 * @package     Core
 * @subpackage  Error
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 12 15
*/

class RawExceptionHandler
{
	/**
	 * @var Exception
	 */
	private $exception;

	/**
	 * The user function needs to accept two parameters: the error code, and a string
	 * describing the error.
	 * @param Exception $exception contains the level of the error raised, as an integer
	 * @return bool always true because we don't want the php default error handler to process the
	 * error again
	 */
	public function handle($exception)
	{
		$this->exception = $exception;
		$this->clean_output_buffer();
		$this->log();
		$this->raw_display();
	}

	private function clean_output_buffer()
	{
		AppContext::get_response()->clean_output();
	}

	private function log()
	{
		$information_to_log = $this->exception->getMessage() .
            "\n" . $this->exception->getTraceAsString();
		ErrorHandler::add_error_in_log($information_to_log, $this->exception->getFile(), $this->exception->getLine());
	}

	private function raw_display()
	{
		if (Debug::is_debug_mode_enabled())
		{
			Debug::fatal($this->exception);
		}
		else
		{
			die(ErrorHandler::FATAL_MESSAGE);
		}
	}
}
?>
