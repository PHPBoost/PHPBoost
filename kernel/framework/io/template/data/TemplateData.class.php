<?php
/**
 * This interface describes data which are assigned to a template. There are two types of data:
 * <ul>
 * 	<li>Variables: there are string that are directly displayed</li>
 * 	<li>Templates: you can embed a template in another one. When a subtemplate is displayed,
 * it's parsed using its own data and the result is inserted where the subtemplate is embedded.</li>
 * </ul>
 * A template contains global variables which are available in the whole template and local variable
 * which are only defined into a loop. To define a loop, you just have to use assign_block_vars with the
 * same loop identifier for each loop iteration. Loops can be nested and embed both variables and subtemplates.
 * @package     IO
 * @subpackage  Template\data
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 06 25
 * @since       PHPBoost 3.0 - 2010 02 19
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

interface TemplateData
{
	/**
	 * Enables the strict mode. If a variable that does not exist in the object is requested,
	 *   then an exception will be raised.
	 */
	function enable_strict_mode();

	/**
	 * Disables the strict mode. If a variable that does not exist in the object is requested,
	 *   then an empty value will be returned.
	 */
	function disable_strict_mode();

	/**
	 * Loads the most common vars which are useful in the whole PHPBoost templates. The variables are:
	 * <ul>
	 * 	<li>THEME the theme used by the current user</li>
	 * 	<li>LANG the lang used by the current user</li>
	 * 	<li>IS_USER_CONNECTED tells whether the user is connected (member, moderator or administrator)</li>
	 * 	<li>IS_ADMIN tells whether the user is administrator</li>
	 * 	<li>IS_MODERATOR tells whether the user is moderator</li>
	 * 	<li>PATH_TO_ROOT is the path which starts from the domain root (in HTTP context) and goes to the PHPBoost
	 * root. For instance if PHPBoost is installed at www.example.com/directory/, its value will be /directory.</li>
	 * 	<li>PHP_PATH_TO_ROOT is the server side path, it's the path which goes to the PHPBoost's root.</li>
	 * 	<li>TOKEN is the CSRF protection token. It's to use in the critical actions to show that the user really
	 * intended doing the action</li>
	 * 	<li>REWRITED_SCRIPT the current url</li>
	 * </ul>
	 */
	function auto_load_frequent_vars();

	/**
	 * Assigns the value <code>$value</code> to the template variable of name <code>$key</code>
	 * @param $key the template parameter name
	 * @param $value the template parameter value
	 */
	function put($key, $value);

	/**
	 * Assigns template variables. It could be simple variables, loop or subtemplates.
	 * @param mixed[string] $vars A map key => value where <code>$value</code> will be assigned to the template variable of name <code>$key</code>
	 */
	function put_all(array $vars);

	/**
	 * Assigns a template block. A block represents a loop and has a name which be used in your template file to indicate which loop you want to browse.
	 * To know what syntax to use to browse a loop, see the class description, there are examples.
	 * @param string $block_name Block name.
	 * @param string[] $array_vars A map var_name => var_value. Generally, var_name is written in caps characters.
	 * @param Template[] $subtemplates The list of subtemplates to embed in the loop's iteration.
	 */
	function assign_block_vars($block_name, array $array_vars, array $subtemplates = array());

	/**
	 * Returns the block $blockname in the template block list
	 * @param string $blockname the blockname of the block to retrieve
	 * @return mixed[] the requested block
	 */
	function get_block($blockname);

	/**
	 * Returns the block $blockname in the parent_block
	 * @param string $blockname the blockname of the block to retrieve
	 * @param mixed[] $parent_block the parent block in which $blockname will be searched for
	 * @return mixed[] The requested block
	 */
	function get_block_from_list($blockname, $parent_block);

	/**
	 * Tells whether a condition is true. Conditions are global boolean variables.
	 * @param string $varname the name of the variable to check if it is true
	 * @return bool true if the variable $varname exists and is not considered as false
	 */
	function is_true($value);

	/**
	 * Returns the $varname variable content searched in from the $list
	 * @param string $varname the name of the variable to retrieve
	 * @param mixed[] $list the list in which the variable will be searched for
	 * @return string the $varname variable content
	 */
	function get($varname);

	/**
	 * Similar from the get_var method but it works with loop variables instead of global variables.
	 * @param string $varname The name of the variable to retrieve
	 * @param mixed[] $list The list into which retrieve the variable
	 * @return string The variable content
	 */
	function get_from_list($varname, &$list);

	/**
	 * Binds vars on another {@link TemplateData} object. The two instances will share the same data.
	 * @param TemplateData $data The data to use.
	 */
	function bind_vars(TemplateData $data);
}
?>
