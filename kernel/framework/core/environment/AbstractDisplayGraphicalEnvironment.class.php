<?php
/*##################################################
 *              abstract_display_graphical_environment.class.php
 *                            -------------------
 *   begin                : October 06, 2009
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

abstract class AbstractDisplayGraphicalEnvironment extends AbstractGraphicalEnvironment
{
	private $css_files = array();
	
	private $page_title = '';

	public function __construct()
	{
		parent::__construct();
	}

	public function add_css_file($file_path)
	{
		$this->css_files[] = $file_path;
	}
	
	protected function get_css_files_html_code()
	{
		$html_code = '';
		foreach ($this->css_files as $file)
		{
			$html_code .= '<link rel="stylesheet" href="' . TPL_PATH_TO_ROOT . $file . 
				'" type="text/css" media="screen, print, handheld" />' . "\n";
		}
		return $html_code;
	}
	
	public function get_page_title()
	{
		return $this->page_title;
	}
	
	public function set_page_title($title) 
	{
		$this->page_title = $title;
		defined('TITLE') or define('TITLE', $title);
	}
}

?>