<?php
/**
 * @package     IO
 * @subpackage  Optimization
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Maxence CAUDERLIER <mxkoder@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 07 06
 * @since       PHPBoost 6.0 - 2024 07 06
*/

class JSFileOptimizer 
{
    /**
     * Scripts to be minified
     * @var array
     */
    protected $files = [];

    /**
     * Scripts who cannot be minified
     * @var array
     */
    protected $ignored_scripts = [];

    /**
     * Minified content
     * @var string
     */
    protected $content = '';

    /**
     * Const Delete comments, tabulations, spaces and newline
    */
    const HIGH_OPTIMIZATION = 'high';

    /**
     * Const Remove comments, white-space(s) outside the string and regex
    */
    const LOW_OPTIMIZATION = 'low';

    /**
     * Minify content
     * @param string $intensity LOW_OPTIMIZATION || HIGH_OPTIMIZATION
     * @return void
     */
    public function optimize($intensity = self::HIGH_OPTIMIZATION):void
    {
        $this->assemble_files();
        $content = preg_replace(
            [
                // Remove comment(s)
                '#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
                // Remove white-space(s) outside the string and regex
                '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s'
            ],
            [
                '$1',
                '$1$2',
            ], 
        $this->content);
        if ($intensity === self::HIGH_OPTIMIZATION) 
        {
            $content = preg_replace(
                [
                    // Remove the last semicolon
                    '#;+\}#',
                    // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
                    '#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
                    // --ibid. From `foo['bar']` to `foo.bar`
                    '#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'
                ],
                [
                    '}',
                    '$1$3',
                    '$1.$3'
                ], 
            $content);
        }
        $this->content = trim($content);
    }


    /**
     * Get Optimized JS content
     * @return string
     */
    public function export()
    {
        return $this->content;
    }

    /**
     * Exports optimized content to a file.
     */
    public function export_to_file($location)
    {
        if (!empty($this->files) || !empty($this->ignored_scripts))
        {
            $file = new File($location);
            $file->delete();
            $file->open(File::WRITE);
            $file->lock();
            $file->write($this->export());
            $file->unlock();
            $file->close();
            $file->change_chmod(0666);
        }
    }

    /**
     * This class allows you to add file to optimize. Required path file
     */
    public function add_file($path_file)
    {
        $this->files[] = $path_file;
    }

    /**
     * Add a script that will be don't optimize
     * @param mixed $script
     * @return void
     */
    public function add_ignored_script($script)
    {
        $this->ignored_scripts[] = $script;
    }

    /**
     * Get all files content and assemble them into content
     * @return void
     */
    public function assemble_files():void
    {
        $content = '';
        foreach ($this->files as $file)
        {
            if (filter_var($file, FILTER_VALIDATE_URL)) 
            {
                $remote_content = FileSystemHelper::get_remote_file_content($file);
                if ($remote_content === false)
                {
                    // Maybe we can't get the file cause cUrl isn't available, it will be called normally
                    $this->ignored_scripts[] = $file;
                    
                }
                else
                {
                    $content .= $remote_content;
                }
            }
            elseif (file_exists($file))
            {
                $content .= file_get_contents($file);
            }
        }
        $this->content = $content;
    }

    /**
     * Get array of scripts who aren't minified
     * @return array
     */
    public function get_ignored_scripts():array
    {
        return $this->ignored_scripts;
    }
}