<?php
/*##################################################
 *                      HtaccessCache.class.php
 *                            -------------------
 *   begin                : October 22, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

import('io/cache/CacheData');


/**
 * This class contains the cache data of the .htaccess file which is located at the root of the site
 * and is used to change the Apache configuration only in the PHPBoost folder.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
class HtaccessFileCache implements CacheData
{
	private $htaccess_file_content = '';

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/io/cache/CacheData#synchronize()
	 */
	public function synchronize()
	{
		global $CONFIG;

		$querier = AppContext::get_sql_querier();

		$this->htaccess_file_content = '';

		if ($CONFIG['rewrite'])
		{
			$this->add_section('Rewrite rules');
			$this->add_line('Options +FollowSymlinks');
			$this->add_line('RewriteEngine on');

			
			$this->add_section('Modules rules');
			$result = $querier->select("SELECT name FROM ".PREFIX."modules WHERE activ = :activ",
			array('activ' => 1));

			while ($result->has_next())
			{
				$row = $result->next();
				//Récupération des infos de config.
				$get_info_modules = load_ini_file(PATH_TO_ROOT . '/' . $row['name'] . '/lang/', get_ulang());
				if (!empty($get_info_modules['url_rewrite']))
				{
					$this->add_line(str_replace('\n', "\n", str_replace('DIR', DIR, $get_info_modules['url_rewrite'])));
				}
			}

			$this->add_section('Core');
			$this->add_line('RewriteRule ^(.*)member/member-([0-9]+)-?([0-9]*)\.php$ ' . DIR . '/member/member.php?id=$2&p=$3 [L,QSA]');
			$this->add_line('RewriteRule ^(.*)member/pm-?([0-9]+)-?([0-9]{0,})-?([0-9]{0,})-?([0-9]{0,})-?([a-z_]{0,})\.php$ ' . DIR . '/member/pm.php?pm=$2&id=$3&p=$4&quote=$5 [L,QSA]');

			$this->add_section('Feeds');
			$this->add_line('RewriteRule ^(.*)rss/?$ ' . DIR . '/syndication.php?m=news&feed=rss [L,QSA]');
			$this->add_line('RewriteRule ^(.*)rss/([a-zA-Z0-9-]+)/?$ ' . DIR . '/syndication.php?m=$1&feed=rss [L,QSA]');
			$this->add_line('RewriteRule ^(.*)rss/([a-zA-Z0-9-]+)/([0-9]+)/?$ ' . DIR . '/syndication.php?m=$1&cat=$2&feed=rss [L,QSA]');
			$this->add_line('RewriteRule ^(.*)rss/([a-zA-Z0-9-]+)/([0-9]+)/([a-zA-Z0-9-]+)/?$ ' . DIR . '/syndication.php?m=$1&cat=$2&name=$3&feed=rss [L,QSA]');
			$this->add_line('RewriteRule ^(.*)rss/([a-zA-Z0-9-]+)/([a-zA-Z0-9-]+)/([0-9]+)/?$ ' . DIR . '/syndication.php?m=$1&cat=$3&name=$2&feed=rss [L,QSA]');
			$this->add_line('RewriteRule ^(.*)atom/?$ ' . DIR . '/syndication.php?m=news&feed=atom [L,QSA]');
			$this->add_line('RewriteRule ^(.*)atom/([a-zA-Z0-9-]+)/?$ ' . DIR . '/syndication.php?m=$1&feed=atom [L,QSA]');
			$this->add_line('RewriteRule ^(.*)atom/([a-zA-Z0-9-]+)/([0-9]+)/?$ ' . DIR . '/syndication.php?m=$1&cat=$2&feed=atom [L,QSA]');
			$this->add_line('RewriteRule ^(.*)atom/([a-zA-Z0-9-]+)/([0-9]+)/([a-zA-Z0-9-]+)/?$ ' . DIR . '/syndication.php?m=$1&cat=$2&name=$3&feed=atom [L,QSA]');
			$this->add_line('RewriteRule ^(.*)atom/([a-zA-Z0-9-]+)/([a-zA-Z0-9-]+)/([0-9]+)/?$ ' . DIR . '/syndication.php?m=$1&cat=$3&name=$2&feed=atom [L,QSA]');

			//Bandwidth protection. The /upload directory can be forbidden if the request comes from
			//out of PHPBoost
			global $CONFIG_UPLOADS, $Cache;
			$Cache->load('uploads');
			if ($CONFIG_UPLOADS['bandwidth_protect'])
			{
				$this->add_section('Bandwith protection');
				$this->add_line('RewriteCond %{HTTP_REFERER} !^$\nRewriteCond %{HTTP_REFERER} !^' . HOST);
				$this->add_line('ReWriteRule .*upload/.*$ - [F]');
			}

			//Robot protection
			$this->add_section('Avoid Hacking Attempt');
			$this->add_line('RewriteCond %{HTTP_USER_AGENT} libwww [NC]');
			$this->add_line('RewriteRule .* - [F,L]');
		}

		//Error page
		$this->add_empty_line();
		$this->add_line('# Error page #');
		$this->add_line('ErrorDocument 404 ' . HOST . DIR . '/member/404.php');

		if (!empty($CONFIG['htaccess_manual_content']))
		{
			$this->add_section('Manual content');
			$this->add_line($CONFIG['htaccess_manual_content']);
		}
		
		$this->htaccess_file_content = trim($this->htaccess_file_content);
	}

	private function add_line($line)
	{
		$this->htaccess_file_content .= "\n" . $line;
	}

	private function add_empty_line()
	{
		$this->add_line('');
	}

	private function add_section($name)
	{
		$this->add_empty_line();
		$this->add_line('# ' . $name . ' #');
	}

	/**
	 * Returns the content of the .htaccess file
	 * @return string its content
	 */
	public function get_htaccess_file_content()
	{
		return $this->htaccess_file_content;
	}

	/**
	 * Loads and returns the groups cached data.
	 * @return HtaccessFileCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'kernel', 'htaccess-file');
	}

	/**
	 * Invalidates the current groups cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('kernel', 'htaccess-file');
	}

	/**
	 * Regenerates the .htaccess file
	 */
	public static function regenerate()
	{
		self::invalidate();
		self::update_htaccess_file();
	}

	private static function update_htaccess_file()
	{
		//Ecriture du fichier .htaccess
		import('io/filesystem/File');
		$file = new File(PATH_TO_ROOT . '/.htaccess');

		$file->delete();

		$file->open();
		$file->write(self::get_file_content());
		$file->close();
	}

	/**
	 *
	 * @return string
	 */
	private static function get_file_content()
	{
		return self::load()->get_htaccess_file_content();
	}
}