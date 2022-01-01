<?php
/**
 * @package     Core
 * @subpackage  Error
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 25
 * @since       PHPBoost 3.0 - 2009 12 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ExceptionHandler
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
		$this->display();
		$this->destroy_app();
	}

	private function clean_output_buffer()
	{
		AppContext::get_response()->clean_output();
	}

	private function log()
	{
		ErrorHandler::add_error_in_log($this->exception->getMessage(), Debug::get_stacktrace_as_string(0, $this->exception), E_USER_ERROR);
	}

	private function display()
	{
		if (Debug::is_debug_mode_enabled())
		{
			$this->raw_display();
		}
		else
		{
			$controller = $this->prepare_controller();
			$this->send_response($controller);
		}
	}

	private function destroy_app()
	{
		Environment::destroy();
		exit;
	}

	private function raw_display()
	{
		if (Debug::is_debug_mode_enabled())
		{
			Debug::fatal($this->exception);
		}
		else
		{
			echo ErrorHandler::FATAL_MESSAGE;
		}
	}

	private function prepare_controller()
	{
		$title = LangLoader::get_message('warning.error', 'warning-lang');

		if ($this->exception !== null && Debug::is_debug_mode_enabled())
		{
			$message = TextHelper::htmlspecialchars($this->exception->getMessage()) . '<br /><br /><i>' .
			$this->exception->getFile() . ':' . $this->exception->getLine() .
			'</i><div class="spacer"></div>' .
			Debug::get_stacktrace_as_string(0, $this->exception);
			$title .= ' ' . $this->exception->getCode();
		}
		else
		{
			$message = TextHelper::htmlspecialchars(LangLoader::get_message('warning.process.error', 'warning-lang'));
		}

		$controller = new UserErrorController($title, $message, UserErrorController::FATAL);
		return $controller;
	}

	private function send_response(Controller $controller)
	{
		try
		{
			$this->integrated_display($controller);
		}
		catch (Exception $exception)
		{
			$this->clean_output_buffer();
			$this->raw_display();
		}
	}

	private function integrated_display(Controller $controller)
	{
		$request = AppContext::get_request();
		$response = $controller->execute($request);
		$response->send();
	}
}
?>
