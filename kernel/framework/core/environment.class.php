<?php
/*##################################################
 *                          environment.class.php
 *                            -------------------
 *   begin                : September 28, 2009
 *   copyright            : (C) 2009 Benoit Sautel, Loïc Rouchon
 *   email                : ben.popeye@phpboost.com, horn@phpboost.com
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

class Environment
{
	/**
	 * @var Environment
	 */
	private static $instance = null;
	/**
	 * @var string
	 */
	private $title = '';
	/**
	 * @var bool
	 */
	private $display_enabled = true;
	/**
	 * @var BreadCrumb
	 */
	private $breadcrumb;
	/**
	 * @var Bench
	 */
	private $bench;
	/**
	 * @var Sql
	 */
	private $db_connection;
	/**
	 * @var Session
	 */
	private $session;

	/**
	 * @return Environment
	 */
	public static function get_instance()
	{
		if (self::$instance === null)
		{
			self::$instance = new Environment();
			self::$instance->init_services();
		}
		return self::$instance;
	}

	protected function __construct()
	{
		$this->bench = new Bench();
		$this->breadcrumb = new BreadCrumb();

		$this->db_connection = new Sql();
	}

	public function set_title($title)
	{
		$this->title = $title;
	}

	public function get_title()
	{
		return $this->title;
	}

	public function enable_display()
	{
		$this->display_enabled = true;
	}

	public function disable_display()
	{
		$this->display_enabled = false;
	}

	public function is_display_enabled()
	{
		return $this->display_enabled;
	}

	public function get_breadcrumb()
	{
		return $this->breadcrumb;
	}

	public function get_db_connection()
	{
		return $this->db_connection;
	}

	public function get_session()
	{
		return $this->session;
	}

	public function init()
	{
		self::load_static_constants();
		self::write_http_headers();
		self::load_cache();
		self::load_dynamic_constants();
		$this->init_session();
	}

	private static function load_static_constants()
	{
		if (@include(PATH_TO_ROOT . '/cache/debug.php'))
		{
			define('DEBUG', (bool)$DEBUG['debug_mode']);
		}
		else
		{
			define('DEBUG', false);
		}
	}

	private static function load_dynamic_constants()
	{
		global $CONFIG;

		define('DIR', $CONFIG['server_path']);
		define('HOST', $CONFIG['server_name']);
		define('TPL_PATH_TO_ROOT', !empty($CONFIG['server_path']) ? $CONFIG['server_path'] : '');
	}

	public static function load_imports()
	{
		require_once PATH_TO_ROOT . '/kernel/constant.php'; //Constante utiles.
		require_once PATH_TO_ROOT . '/kernel/framework/functions.inc.php'; //Fonctions de base.

		import('content/parser/content_formatting_factory');
		import('core/breadcrumb');
		import('core/cache');
		import('core/errors');
		import('db/mysql');
		import('io/template/template');
		import('io/template/deprecated_template');
		import('members/authorizations');
		import('members/groups_service');
		import('members/session');
		import('members/user');
		import('util/bench');
	}


	private static function write_http_headers()
	{
		header('Content-type: text/html; charset=iso-8859-1');
		header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
		header('Pragma: no-cache');
	}

	private function init_services()
	{
		$this->db_connection->auto_connect();

		$this->session = new Session();
	}
	
	private function init_session()
	{
		$this->session->load();
		$this->session->act();
	}
	
	private function load_cache()
	{
		global $Cache;
		$CONFIG = array();
		$Cache->load('config');
		$Cache->load('member');
		$Cache->load('modules');
		$Cache->load('themes');
		$Cache->load('langs');
	}

}

?>