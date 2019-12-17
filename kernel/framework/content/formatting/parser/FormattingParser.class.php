<?php
/**
 * @package     Content
 * @subpackage  Formatting\parser
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 12 20
*/

interface FormattingParser
{
    /**
     * Parses the content of the parser.
     */
    function parse();

    /**
     * Returns the content of the parser. If you called a method which parses the content, this content will be parsed.
     * @return string The content of the parser.
     */
    function get_content();

    /**
     * Sets the content of the parser. When you will call a parse method, it will deal with this content.
     * @param string $content Content
     */
    function set_content($content);

    /**
     * Sets the reference path for relative URL
     * @param string $path Path
     */
    function set_path_to_root($path);

    /**
     * Returns the path to root attribute.
     * @return string The path
     */
    function get_path_to_root();

    /**
     * Sets the page path
     * @param string $page_path Page path
     */
    function set_page_path($page_path);

    /**
     * Returns the page path
     * @return string path
     */
    function get_page_path();
}
?>
