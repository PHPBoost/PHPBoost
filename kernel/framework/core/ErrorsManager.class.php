<?php
/*##################################################
 *                          ErrorsManager.class.php
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

import('util/Path');
defined('DEBUG') or define('DEBUG', true);

/**
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 * @package core
 * @desc
 */
class ErrorsManager
{
	private static $errno;
	private static $errstr;
	private static $errfile;
	private static $errline;
	private static $errdesc;
	private static $errclass;
	private static $errimg;
	private static $error_log_string;

	/**
	 * @desc The user static functionneeds to accept two parameters: the error code, and a string
	 * describing the error.
	 * @param unknown_type $errno contains the level of the error raised, as an integer
	 * @param unknown_type $errstr contains the error message, as a string
	 * @param unknown_type $errfile the filename that the error was raised in, as a string
	 * @param unknown_type $errline the line number the error was raised at, as an integer
	 * @return bool always true because we don't want the php default error handler to process the
	 * error again
	 */
	public static function handler($errno, $errstr, $errfile, $errline)
	{
		self::prepare($errno, $errstr, $errfile, $errline);
		if (self::needs_to_be_processed())
		{
			self::process();
			self::display();
			self::log();
		}
		return true;
	}

	private static function prepare($errno, $errstr, $errfile, $errline)
	{
		self::$errno = $errno;
		self::$errstr = $errstr;
		self::$errfile = $errfile;
		self::$errline = $errline;
		self::$errdesc = '';
		self::$errclass = '';
		self::$errimg = '';
		self::$error_log_string = '';
	}

	/**
	 * @return boolean true if the error is not thrown by a static functionprefixed with an @ and if the
	 * errno is in the ERROR_REPORTING level
	 */
	private static function needs_to_be_processed()
	{
//		return true;
		return (error_reporting() != 0) && (self::$errno & ERROR_REPORTING);
	}

	private static function process()
	{
		switch (self::$errno)
		{
			case E_USER_NOTICE:
			case E_NOTICE:
				self::$errdesc = 'Notice';
				self::$errimg =  'notice';
				self::$errclass =  'error_notice';
				break;
				//Warning utilisateur.
			case E_USER_WARNING:
			case E_WARNING:
				self::$errdesc = 'Warning';
				self::$errimg =  'important';
				self::$errclass =  'error_warning';
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
				self::$errdesc = 'Fatal Error';
				self::$errimg =  'stop';
				self::$errclass =  'error_fatal';
				break;
			default:
				self::$errdesc = 'Unknown Error';
				self::$errimg =  'question';
				self::$errclass =  'error_unknow';
				break;
		}
	}

	private static function display()
	{
		if (DEBUG)
		{
			echo '<span id="errorh"></span>
				<div class="' . self::$errclass . '" style="width:500px;margin:auto;padding:15px;margin-bottom:15px;">
					<img src="' . PATH_TO_ROOT . '/templates/default/images/' . self::$errimg . '.png"
						alt="" style="float:left;padding-right:6px;" />
					<strong>' . self::$errdesc . ' : </strong>' . self::$errstr . '<br /><br /><em>'
					. Path::get_path_from_root(self::$errfile) . '</em>:' . self::$errline . '
					<br />
				</div>';
		}
	}

	private static function log()
	{
		self::compute_error_log_string();
		self::add_error_in_log();
	}

	private static function compute_error_log_string()
	{
		self::$error_log_string = gmdate_format('Y-m-d H:i:s', time(), TIMEZONE_SYSTEM) . "\n";
		self::$error_log_string .= self::$errno . "\n";
		self::$error_log_string .= self::clean_error_string() . "\n";
		self::$error_log_string .= Path::get_path_from_root(self::$errfile) . "\n";
		self::$error_log_string .= self::$errline . "\n";
	}

	private static function add_error_in_log()
	{
		$handle = @fopen(PATH_TO_ROOT . '/cache/error.log', 'a+');
		$write = @fwrite($handle,  self::$error_log_string);
		$close = @fclose($handle);

		if ($handle === false || $write === false || $close === false)
		{
			echo '<span id="errorh">Can\'t write error to log file</span>';
		}
	}

	private static function clean_error_string()
	{
		return preg_replace("`(\n){1,}`", '<br />', preg_replace("`\r|\n|\t`", "\n", self::$errstr));
	}
}