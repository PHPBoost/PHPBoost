<?php
/*##################################################
 *                          RawExceptionHandler.class.php
 *                            -------------------
 *   begin                : December 15, 2009
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
class RawExceptionHandler
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