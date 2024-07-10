<?php
/**
 * @package     IO
 * @subpackage  Optimization
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Maxence CAUDERLIER <mxkoder@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 07 10
 * @since       PHPBoost 6.0 - 2024 07 06
*/

class JSCacheManager
{
    /**
     * @var \JSFileOptimizer
     */
    private $js_optimizer;

    /**
     * @var string Cache file location (used for PHP)
     */
    private $cache_file_location = '';

    /**
     * @var string Cache file location (used for TPL)
     */
    private $script_cache_file_location = '';

    /**
     * Array of files to minify
     * @var array
     */
    private $files = [];

    /**
     * Array of scripts that can't be minified
     * @var array
     */
    private $ignored_files = [];

    public function __construct()
    {
        $this->js_optimizer = new JSFileOptimizer();
    }

    /**
     * Setting the files to minify
     * @param array $files
     * @return void
     */
    private function set_files($files):void
    {
        foreach ($files as $file)
        {
            if (filter_var($file, FILTER_VALIDATE_URL)) 
            {
                $this->js_optimizer->add_file($file);
            }
            else
            {
                $this->js_optimizer->add_file(PATH_TO_ROOT . $file);
            }
        }
    }

    /**
     * Process the minifier
     * @param string JSFileOptimizer::LOW_OPTIMIZATION || JSFileOptimizer::HIGH_OPTIMIZATION
     * @return void
     */
    private function execute($intensity = JSFileOptimizer::HIGH_OPTIMIZATION)
    {
        if (!file_exists($this->cache_file_location))
        {
            $this->force_regenerate_cache($intensity);
        }
        else
        {
            $cache_file_time = filemtime($this->cache_file_location);
            // Cache is available for 24hrs
            if ($cache_file_time + 3600 * 24 < time())
            {
                $this->force_regenerate_cache($intensity);
            }
        }
        $this->ignored_files = $this->js_optimizer->get_ignored_scripts();
    }

    /**
     * Force to regenrate cache for our files
     * @param string JSFileOptimizer::LOW_OPTIMIZATION || JSFileOptimizer::HIGH_OPTIMIZATION
     * @return void
     */
    private function force_regenerate_cache($intensity)
    {
        $this->js_optimizer->optimize($intensity);
        $this->js_optimizer->export_to_file($this->cache_file_location);
    }

    /**
     * Get array of scripts who aren't minified
     * @return array
     */
    public function get_ignored_scripts():array
    {
        return $this->ignored_files;
    }

    /**
     * Get script location to call file cache in TPL
     * @return string
     */
    public function get_script_cache_location():string
    {
        return $this->script_cache_file_location;
    }

    /**
     * Return JSCacheManager object with files, false if $files is empty
     * @param array $files
     * @return ?JSCacheManager
     */
    public static function get_js_path($files)
    {
        if (empty($files))
        {
            return false;
        }
        $folder_path = '/cache/js/' . AppContext::get_current_user()->get_theme();
        $template_folder = new Folder(PATH_TO_ROOT . $folder_path);
        if (!$template_folder->exists())
        {
            mkdir(PATH_TO_ROOT . $folder_path, 0775, true);
        }
        $cache_file_location = $folder_path . '/js-cache-' . md5(implode(';', $files)) . '.js';

        $js_cache = new self();
        $js_cache->set_files($files);
        $js_cache->cache_file_location = PATH_TO_ROOT . $cache_file_location;
        $js_cache->execute(CSSCacheConfig::load()->get_optimization_level());
        $js_cache->script_cache_file_location = TPL_PATH_TO_ROOT . $cache_file_location;
        return $js_cache;
    }
}