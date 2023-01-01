<?php
/**
 * @package     IO
 * @subpackage  Optimization
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 28
 * @since       PHPBoost 3.0 - 2011 03 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class CSSFileOptimizer
{
	protected $files = array();
	protected $scripts = array();
	protected $content = '';
	protected $regex_search_files_path = '';
	protected $replace_value_files_path = '';

	/**
	 * Const Delete comments, tabulations, spaces and newline
	*/
	const HIGH_OPTIMIZATION = 'high';

	/**
	 * Const Delete comments, tabulations and spaces
	*/
	const LOW_OPTIMIZATION = 'low';

	public function __construct()
	{
		$this->regex_search_files_path = "`url\([\'\"]?([^';}\)\'\"]+)[\'\"]?\)`u";
		$this->replace_value_files_path = 'url(":path/'. str_replace('"', '', '$1') .'")';
	}

	/**
	 * This class allows you to add file to optimize. Required path file
	 */
	public function add_file($path_file)
	{
		$this->files[] = $path_file;
	}

	/**
	 * This class allows you to add script to optimize. Required script value
	 */
	public function add_script($script)
	{
		if (!empty($script))
		{
			$this->scripts[] = $script;
		}
	}

	/**
	 * This class optimize your code. Parameter $intensity serves to configuration highlest
	 */
	public function optimize($intensity = self::HIGH_OPTIMIZATION)
	{
		$this->assemble_files();

		$content = '';
		if ($intensity == self::LOW_OPTIMIZATION)
		{
			$cleared_file_content = $this->delete_comments($this->content);
			$content = str_replace(array("\t", "  "), '', $cleared_file_content);
		}
		else
		{
			$cleared_file_content = $this->delete_comments($this->content);
			$content = str_replace(array("\r\n", "\n", "\r", "\t", "  "), ' ', $cleared_file_content);
			$content = str_replace(array("( ", " )", ", "), array("(", ")", ","), $content);
			$content = preg_replace(array('`\s*{\s*`', '`\s*}\s*`u', '`\s*:\s*`', '`\s*;\s*`u'), array('{', '}', ':', ';'), $content);
		}

		$this->content = trim($content);
	}

	public function export()
	{
		return $this->content;
	}

	/**
	 * Exports optimized content to a file.
	 */
	public function export_to_file($location)
	{
		if (!empty($this->files) || !empty($this->scripts))
		{
			$file = new File($location);
			$file->delete();
			$file->open(File::WRITE);
			$file->lock();
			$file->write($this->content);
			$file->unlock();
			$file->close();
			$file->change_chmod(0666);
		}
	}

	/**
	 * This class return Array files
	 */
	public function get_files()
	{
		return $this->files;
	}

	/**
	 * This class return Array scripts
	 */
	public function get_scripts()
	{
		return $this->scripts;
	}

	private function delete_comments($value)
	{
		$value = preg_replace('<!\-\- [\/\ a-zA-Z]* \-\->u', '', $value);
		$value = preg_replace('#/\*.*?\*/#su', '', $value);
		return $value;
	}

	private function assemble_files()
	{
		$content = '';
		foreach ($this->files as $file)
		{
			if (file_exists($file))
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
		}

		foreach ($this->scripts as $script)
		{
			$content .= $script;
		}

		$this->content = $content;
	}
}
?>
