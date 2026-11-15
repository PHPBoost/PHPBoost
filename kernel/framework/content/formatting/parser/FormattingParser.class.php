<?php
/**
 * @package     Content
 * @subpackage  Formatting\parser
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 09
 * @since       PHPBoost 3.0 - 2009 12 20
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface FormattingParser
{
    /**
     * Parses the content of the parser.
     */
    function parse(): void;

    /**
     * Returns the content of the parser. If you called a method which parses the content, this content will be parsed.
     * @return string The content of the parser.
     */
    function get_content(): mixed;

    /**
     * Sets the content of the parser. When you will call a parse method, it will deal with this content.
     * @param string $content Content
     */
    function set_content(mixed $content): void;

    /**
     * Sets the reference path for relative URL
     * @param string $path Path
     */
    function set_path_to_root(string $path): void;

    /**
     * Returns the path to root attribute.
     * @return string The path
     */
    function get_path_to_root(): string;

    /**
     * Sets the page path
     * @param string $page_path Page path
     */
    function set_page_path(string $page_path): void;

    /**
     * Returns the page path
     * @return string path
     */
    function get_page_path(): string;
}
?>
