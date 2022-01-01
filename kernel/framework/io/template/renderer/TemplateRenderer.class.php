<?php
/**
 * Represents a template renderer as its names shows. Its able to get the result of the template
 * interpration from a TemplateLoader which gives it the template source and a TemplateData which
 * contains the data to assign in the template.
 * @package     IO
 * @subpackage  Template\renderer
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 02 06
*/

interface TemplateRenderer
{
	/**
	 * Returns the result of the interpretation of a template
	 * @param TemplateData $data The data
	 * @param TemplateLoader $loader The loader to use
	 * @return string The parsed template
	 */
	function render(TemplateData $data, TemplateLoader $loader);

    /**
     * Adds a lang map to the template map list in which template variables beginning by L_ will be searched for of not already registered
     * @param string[string] $lang the language map
     */
    function add_lang(array $lang);
}
?>
