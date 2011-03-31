<?php
/*##################################################
 *                               AbstractOptimizeFiles.class.php
 *                            -------------------
 *   begin                : March 29, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 * @package {@package}
*/
abstract class AbstractOptimizeFiles
{
	protected $files = array();
	protected $scripts = array();
	protected $content = '';
	protected $extension_required = '';
	protected $regex_search_files_path = '';
	protected $replace_value_files_path = '';
	/*
	 * Const Delete comments, tabulations, spaces and newline
	*/
	const HIGH_OPTIMIZE = 'high';
	
	/*
	 * Const Delete comments, tabulations and spaces
	*/
	const LOW_OPTIMIZE = 'low';
	
	/*
	 * This class allows you to add file to optimize. Required path file
	 */
	public function add_file($path_file)
	{
		if (!file_exists($path_file))
		{
			throw new Exception('File not '. $path_file .' exist !');
		}
		
		$extension = substr($path_file, -4);
		if (strpos($extension, $this->extension_required) !== false)
		{
			$this->files[] = $path_file;
		}
		else
		{
			throw new Exception('File extension "'. $extension .'" not compatible for script !');
		}
	}
	
	/*
	 * This class allows you to add script to optimize. Required script value
	 */
	public function add_script($script)
	{
		if (!empty($script))
		{
			$this->scripts[] = $script;
		}
	}
	
	/*
	 * This class change path files in script and files added
	 */
	public function change_path_files($regex_search, $replace_value)
	{
		$this->regex_search_files_path = $regex_search;
		$this->replace_value_files_path = $replace_value;
	}
	
	/*
	 * This class optimize your code. Parameter $intensity serves to configuration highlest
	 */
	public function optimize($intensity = self::MEDIUM_OPTIMIZE)
	{
		$this->assemble_files();

		switch ($intensity) {
			case self::HIGH_OPTIMIZE:
					$delete_comments = $this->delete_comments($this->content);
					$content = str_replace(array("\r\n", "\n", "\r", "\t", "  "), '', $delete_comments);
				break;
			case self::LOW_OPTIMIZE:
					$delete_comments = $this->delete_comments($this->content);
					$content = str_replace(array("\t", "  "), '', $delete_comments);
				break;
		}

		$this->content = trim($content);
	}

	/*
	 * This class export data
	 */
	public function export()
	{
		return $this->content;
	}
	
	/*
	 * This class export data to file. Required location file cached
	 */
	public function export_to_file($location)
	{
		if (!empty($this->content) && !empty($this->files))
		{
			$file = new File($location);
			$file->delete();
			$file->lock();
			$file->write($this->content);
			$file->unlock();
			$file->close();
			$file->change_chmod(0666);
		}
		else
		{
			throw new Exception('Contents are empty !');
		}
	}
	
	/*
	 * This class return Array files
	 */
	public function get_files()
	{
		return $this->files;
	}
	
	/*
	 * This class return Array scripts
	 */
	public function get_scripts()
	{
		return $this->scripts;
	}
	
	private function delete_comments($value)
	{
		$value = preg_replace('<!\-\- [\/\ a-zA-Z]* \-\->', '', $value);
		$value = preg_replace('#/\*.*?\*/#s', '', $value);
		return $value;
	}

	private function assemble_files()
	{
		$content = '';
		foreach ($this->files as $file)
		{
			$content_file = php_strip_whitespace($file);
			if (!empty($this->regex_search_files_path) && !empty($this->replace_value_files_path))
			{
				$replace_path = StringVars::replace_vars($this->replace_value_files_path, array('path' => GeneralConfig::load()->get_site_path() . '/' . Path::get_package($file)));
				$content .= preg_replace($this->regex_search_files_path, $replace_path, $content_file);
			}
			else
			{
				$content .= $content_file;
			}
		}
		
		foreach ($this->scripts as $script)
		{
			$content .= $script;
		}
		
		$this->content = $content;
	}
}
?>