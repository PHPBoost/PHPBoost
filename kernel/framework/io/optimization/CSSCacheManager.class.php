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
*/

class CSSCacheManager
{
	private $css_optimizer;
	private $cache_file_location = '';

	public function __construct()
	{
		$this->css_optimizer = new CSSFileOptimizer();
	}

	/* This function return address is stored in the file where the css sent to the function */
	public static function get_css_path($files)
	{
		if (!empty($files))
		{
			$template_folder = new Folder(PATH_TO_ROOT . '/cache/css/' . AppContext::get_current_user()->get_theme());
			if (!$template_folder->exists())
				mkdir(PATH_TO_ROOT . '/cache/css/' . AppContext::get_current_user()->get_theme());

			if (is_array($files))
			{
				$cache_file_location = '/cache/css/' . AppContext::get_current_user()->get_theme() . '/css-cache-'. md5(implode(';', $files)) .'.css';
			}
			else
			{
				$files = str_replace('{THEME}', AppContext::get_current_user()->get_theme(), $files);
				$cache_file_location = '/cache/css/' . AppContext::get_current_user()->get_theme() . '/css-cache-'. md5($files) .'.css';
				$files = explode(';', $files);
			}

			foreach ($files as $file)
			{
				if (basename($file) == '@import.css')
				{
					foreach (self::extract_css_urls(implode(' ', file(PATH_TO_ROOT . $file))) as $url)
						$files[] = $url;
					
					unset($files[array_search($file, $files)]);
				}
			}

			$css_cache = new CSSCacheManager();
			$css_cache->set_files($files);
			$css_cache->set_cache_file_location(PATH_TO_ROOT . $cache_file_location);
			$css_cache->execute(CSSCacheConfig::load()->get_optimization_level());

			return TPL_PATH_TO_ROOT . $cache_file_location;
		}
	}

	/**
	 * Extract URLs from CSS text.
	 */
	public static function extract_css_urls($text)
	{
		$urls = array();
		
		$url_pattern     = '(([^\\\\\'", \(\)]*(\\\\.)?)+)';
		$urlfunc_pattern = 'url\(\s*[\'"]?' . $url_pattern . '[\'"]?\s*\)';
		$pattern         = '/(' .
			'(@import\s*[\'"]' . $url_pattern     . '[\'"])' .
			'|(@import\s*'      . $urlfunc_pattern . ')'      .
			'|('                . $urlfunc_pattern . ')'      .  ')/iu';
		if ( !preg_match_all( $pattern, $text, $matches ) )
			return $urls;
		
		foreach ( array_merge($matches[3], $matches[7]) as $match )
		{
			if ( !empty($match) )
			{
				$url = preg_replace( '/\\\\(.)/u', '\\1', $match );
				$urls[] = preg_match( '/^..\/..\//', $url) ? str_replace('../../', '/templates/', $url) : '/templates/' . AppContext::get_current_user()->get_theme() . '/theme/' . $url;
			}
		}
		
		return $urls;
	}

	public function set_files(Array $files)
	{
		foreach ($files as $file)
		{
			$this->css_optimizer->add_file(PATH_TO_ROOT . $file);
		}
	}

	public function set_cache_file_location($location)
	{
		$this->cache_file_location = $location;
	}

	public function execute($intensity = CSSFileOptimizer::LOW_OPTIMIZATION)
	{
		if (!file_exists($this->cache_file_location))
		{
			$this->force_regenerate_cache($intensity);
		}
		else
		{
			$files = $this->css_optimizer->get_files();
			$cache_file_time = filemtime($this->cache_file_location);
			foreach ($files as $file)
			{
				if (file_exists($file) && filemtime($file) > $cache_file_time)
				{
					$this->force_regenerate_cache($intensity);
					break;
				}
			}
		}
	}

	public function get_cache_file_location()
	{
		return $this->cache_file_location;
	}

	public function force_regenerate_cache($intensity)
	{
		$this->css_optimizer->optimize($intensity);
		$this->css_optimizer->export_to_file($this->cache_file_location);
	}
}
?>
