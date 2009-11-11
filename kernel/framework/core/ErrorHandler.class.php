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
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Benoit Sautel <ben.popeye@phpboost.com>, Loic Rouchon <loic.rouchon@phpboost.com>
 * @package core
 * @desc
 */
class ErrorHandler
{
	protected $errno;
	protected $errstr;
	protected $errfile;
	protected $errline;
	protected $errdesc;
	protected $errclass;
	protected $errimg;
	protected $error_log_string;
	protected $fatal;

	/**
	 * @desc The user functionneeds to accept two parameters: the error code, and a string
	 * describing the error.
	 * @param unknown_type $errno contains the level of the error raised, as an integer
	 * @param unknown_type $errstr contains the error message, as a string
	 * @param unknown_type $errfile the filename that the error was raised in, as a string
	 * @param unknown_type $errline the line number the error was raised at, as an integer
	 * @return bool always true because we don't want the php default error handler to process the
	 * error again
	 */
	public function handler($errno, $errstr, $errfile, $errline)
	{
		$this->prepare($errno, $errstr, $errfile, $errline);
		if ($this->needs_to_be_processed())
		{
			$this->process();
			$this->display();
			$this->log();
		}
		if ($this->fatal)
		{
			exit;
		}
		return true;
	}

	private function prepare($errno, $errstr, $errfile, $errline)
	{
		$this->errno = $errno;
		$this->errstr = $errstr;
		$this->errfile = $errfile;
		$this->errline = $errline;
		$this->errdesc = '';
		$this->errclass = '';
		$this->errimg = '';
		$this->error_log_string = '';
		$this->fatal = false;
	}

	/**
	 * @return boolean true if the error is not thrown by a functionprefixed with an @ and if the
	 * errno is in the ERROR_REPORTING level
	 */
	private function needs_to_be_processed()
	{
		return (error_reporting() != 0) && ($this->errno & ERROR_REPORTING);
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
		if (DEBUG)
		{
			$this->display_debug();
		}
		elseif ($this->fatal)
		{
			$this->display_fatal();
		}
	}

	protected function display_debug()
	{
		echo '<span id="errorh"></span>
			<div class="' . $this->errclass . '" style="width:500px;margin:auto;padding:15px;margin-bottom:15px;">
				<img src="' . PATH_TO_ROOT . '/templates/default/images/' . $this->errimg . '.png"
					alt="" style="float:left;padding-right:6px;" />
				<strong>' . $this->errdesc . ' : </strong>' . $this->errstr . '<br /><br /><em>'
				. Path::get_path_from_root($this->errfile) . '</em>:' . $this->errline . '
				<br />
			</div>';
	}

	protected function display_fatal()
	{
		$this->display_debug();
	}

	private function log()
	{
		$this->compute_error_log_string();
		$this->add_error_in_log();
	}

	private function compute_error_log_string()
	{
		$this->error_log_string = gmdate_format('Y-m-d H:i:s', time(), TIMEZONE_SYSTEM) . "\n";
		$this->error_log_string .= $this->errno . "\n";
		$this->error_log_string .= $this->clean_error_string() . "\n";
		$this->error_log_string .= Path::get_path_from_root($this->errfile) . "\n";
		$this->error_log_string .= $this->errline . "\n";
	}

	private function add_error_in_log()
	{
		$handle = @fopen(PATH_TO_ROOT . '/cache/error.log', 'a+');
		$write = @fwrite($handle,  $this->error_log_string);
		$close = @fclose($handle);

		if ($handle === false || $write === false || $close === false)
		{
			echo '<span id="errorh">Can\'t write error to log file</span>';
		}
	}

	private function clean_error_string()
	{
		return preg_replace("`(\n){1,}`", '<br />', preg_replace("`\r|\n|\t`", "\n", $this->errstr));
	}
}