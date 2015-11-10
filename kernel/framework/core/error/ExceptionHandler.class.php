<?php
/*##################################################
 *                          ExceptionHandler.class.php
 *                            -------------------
 *   begin                : December 8, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @package {@package}
 * @desc
 */
class ExceptionHandler
{
	/**
	 * @var Exception
	 */
	private $exception;

	/**
	 * @desc The user functionneeds to accept two parameters: the error code, and a string
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
		$title = LangLoader::get_message('error', 'status-messages-common');
		
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
			$message = TextHelper::htmlspecialchars(LangLoader::get_message('process.error', 'status-messages-common'));
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