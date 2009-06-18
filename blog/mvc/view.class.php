<?php
/*##################################################
 *                           view.class.php
 *                            -------------------
 *   begin                : June 18 2009
 *   copyright            : (C) 2009 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

import('io/template');

class View extends Template
{
	public function __construct($tpl)
	{
		parent::__construct($tpl);
	}

	public function add_lang($lang)
	{
		$this->langs[] = $lang;
	}

	protected function get_var($varname)
	{
		if (!empty($this->_var[$varname]))
		{
			return $this->_var[$varname];
		}
		else if (strpos($varname, 'E_') === 0)
		{
			$varname = substr($varname, 2);
			if (!empty($this->_var[$varname]))
			{
				return htmlspecialchars($this->_var[$varname]);
			}
		}
		else if (strpos($varname, 'J_') === 0)
		{
			$varname = substr($varname, 2);
			if (!empty($this->_var[$varname]))
			{
				return to_js_string($this->_var[$varname]);
			}
		}
		else if (strpos($varname, 'L_') === 0)
		{
			$var = $this->find_var(strtolower(substr($varname, 2)));
			if (!empty($var))
			{
				return $var;
			}
		}
		else if (strpos($varname, 'EL_') === 0)
		{
			$var = $this->find_var(strtolower(substr($varname, 3)));
			if (!empty($var))
			{
				return htmlspecialchars($var);
			}
		}
		else if (strpos($varname, 'JL_') === 0)
		{
			$var = $this->find_var(strtolower(substr($varname, 3)));
			if (!empty($var))
			{
				return to_js_string($var);
			}
		}
		return '';
	}

	protected function find_var($varname)
	{
		foreach ($this->langs as $lang)
		{
			if (isset($lang[$varname]))
			{
				return $lang[$varname];
			}
		}
		return '';
	}

	/**
	 * @desc Writes the file cache.
	 * @param string $file_cache_path Path where the file must be written.
	 */
	function _save($file_cache_path)
	{
		import('io/filesystem/file');
		$file = new File($file_cache_path);
		$file->open(WRITE);
		$file->lock();
		$file->write(
		//if (isset($this->_var[\'$1\'])) echo $this->_var[\'$1\'];
		preg_replace('`if\s+\(isset\(\$this->_var\[\'(.+)\'\]\)\)\s+echo\s+\$this->_var\[\'.+\'\]`iU', 'echo $this->get_var(\'$1\')', $this->template)
		);
		$file->unlock();
		$file->close();
		$file->change_chmod(0666);
	}

	private $langs = array();
}
?>