<?php
/**
 * @package     Util
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 02 01
 * @since       PHPBoost 3.0 - 2009 10 03
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Debug
{
    const STRICT_MODE = 'strict_mode';
    const DISPLAY_DATABASE_QUERY = 'display_database_query';

    private static bool $enabled = true;
    private static array $options = [];
    private static bool $html_output = true;

    public static function __static(): void
    {
        $file = new File(PATH_TO_ROOT . '/cache/debug.php');
        if ($file->exists())
        {
            include $file->get_path();
            self::$enabled = $enabled ?? self::$enabled;
            self::$options = $options ?? self::$options;
        }
    }

    /**
     * Enables the debug mode for the current script only.
     * @param array $options see <code>self::enabled_debug_mode()</code> for information on this parameter
     */
    public static function enabled_current_script_debug(array $options = []): void
    {
        self::$enabled = true;
        self::$options = $options;
    }

    /**
     * Enables the debug mode
     * @param array $options Here is a description of the optional debug parameters that can
     * be passed in the options array
     * <ul>
     *     <li><code>self::STRICT_MODE</code>: boolean - If true, page processing will stop
     *     <li><code>self::DISPLAY_DATABASE_QUERY</code>: boolean - If true, display database query
     *     at the first notice / warning / error encountered</li>
     * </ul>
     */
    public static function enabled_debug_mode(array $options = []): void
    {
        self::$enabled = true;
        self::$options = $options;
        self::write_debug_file();
    }

    /**
     * Disables the debug mode
     */
    public static function disable_debug_mode(): void
    {
        self::$enabled = false;
        self::$options = [];
        self::write_debug_file();
    }

    private static function write_debug_file(): void
    {
        $file = new File(PATH_TO_ROOT . '/cache/debug.php');
        $file->write('<?php ' . "\n" .
            '$enabled = ' . var_export(self::$enabled, true) . ';' . "\n" .
            '$options = ' . var_export(self::$options, true) . ';' . "\n" .
            ' ?>');
        $file->close();
    }

    /**
     * Tells whether the debug mode is enabled
     * @return bool true if enabled, false otherwise
     */
    public static function is_debug_mode_enabled(): bool
    {
        return self::$enabled;
    }

    /**
     * Returns true if the strict debug mode is enabled.
     * If true, the page processing will be stopped if any notice, warning or error is encountered.
     * @return bool true if the strict debug mode is enabled
     */
    public static function is_strict_mode_enabled(): bool
    {
        return self::is_debug_mode_enabled() && self::get_option(self::STRICT_MODE, false);
    }

    /**
     * Returns true if the display database query is enabled.
     * If true, the page display a database query with the Debug::dump() function and display stacktrace
     * @return bool true if the display database query is enabled.
     */
    public static function is_display_database_query_enabled(): bool
    {
        return self::is_debug_mode_enabled() && self::get_option(self::DISPLAY_DATABASE_QUERY, false);
    }

    private static function get_option(string $key, $default)
    {
        if (array_key_exists($key, self::$options))
        {
            return self::$options[$key];
        }
        return $default;
    }

    /**
     * Returns true if the page is rendered in a browser mode.
     * @return bool true if the page is rendered in a browser mode
     */
    public static function is_output_html(): bool
    {
        return self::$html_output;
    }

    /**
     * Inform PHPBoost that the debug message have to be rendered in a non html (plain text) mode.
     */
    public static function set_plain_text_output_mode(): void
    {
        self::$html_output = false;
    }

    /**
     * Displays information on an exception and exits
     * @param Throwable $exception the exception to display information on
     */
    public static function fatal(?Throwable $exception): void
    {
        if (!self::$html_output)
        {
            $message = get_class($exception) . ': ' . $exception->getMessage();
            if (empty($message))
            {
                $message .= 'An exception has been thrown';
            }
            echo $message . "\n--------------------------------------------------------------------------------\n";
            Debug::print_stacktrace(0, $exception);
        }
        else
        {
            $printer = new HTTPFatalExceptionPrinter($exception);
            echo $printer->render();
        }
        exit;
    }

    /**
     * Prints the stacktrace and exits
     * @param mixed $object
     */
    public static function stop($object = null): void
    {
        if ($object !== null)
        {
            Debug::dump($object);
        }
        self::print_stacktrace();
        exit;
    }

    /**
     * Returns the current exception
     * @return Exception the current exception
     */
    public static function get_exception_context(): Exception
    {
        return new Exception();
    }

    /**
     * Returns the current stacktrace
     * @return array the current stacktrace
     */
    public static function get_stacktrace(): array
    {
        $stack = self::get_exception_context()->getTrace();
        unset($stack[0]);
        return array_merge($stack, []);
    }

    /**
     * Gets the current stacktrace as a string
     * @param int $start_trace_index
     * @param Throwable $exception
     * @return string
     */
    public static function get_stacktrace_as_string(int $start_trace_index = 0, ?Throwable $exception = null): string
    {
        $string_stacktrace = '';
        $stacktrace = null;
        if ($exception === null)
        {
            $stacktrace = self::get_stacktrace();
        }
        else
        {
            $start_trace_index--;
            $stacktrace = $exception->getTrace();
        }
        $stacktrace_size = count($stacktrace);
        $start_trace_index = $start_trace_index + 1;
        foreach ($stacktrace as $i => $trace)
        {
            if ($i >= $start_trace_index)
            {
                $string_stacktrace .= '[' . ($i - $start_trace_index) . '] ' . ExceptionUtils::get_file($trace) .
                    ':' . ExceptionUtils::get_line($trace) . ' - ' . ExceptionUtils::get_method_prototype($trace) . "\n";
            }
        }

        $string_stacktrace .= '[URL] ' . $_SERVER['REQUEST_URI'];

        if (self::is_output_html())
        {
            $string_stacktrace = str_replace("\n", '<br />', $string_stacktrace);
        }
        return $string_stacktrace;
    }

    /**
     * Prints the current stacktrace
     * @param int $start_trace_index
     * @param Throwable $exception
     */
    public static function print_stacktrace(int $start_trace_index = 0, ?Throwable $exception = null): void
    {
        if ($exception !== null)
        {
            $start_trace_index--;
        }
        echo self::get_stacktrace_as_string($start_trace_index + 1, $exception);
    }

    /**
     * Executes a <code>print_r()</code> in an html &lt;pre&gt; block
     * @param mixed $object the object to see using print_r
     */
    public static function dump($object): void
    {
        if (self::$html_output)
        {
            echo '<pre class="debugger">'; print_r($object); echo '</pre>';
        }
        else
        {
            echo "\n"; print_r($object); echo "\n";
        }
    }
}
?>
