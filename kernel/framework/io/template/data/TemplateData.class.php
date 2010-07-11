<?php
/*##################################################
 *                          TemplateData.class.php
 *                            -------------------
 *   begin                : February 19, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @package {@package}
 * @subpackage template/data
 * @desc This interface describes data which are assigned to a template. There are two types of data:
 * <ul>
 * 	<li>Variables: there are string that are directly displayed</li>
 * 	<li>Templates: you can embed a template in another one. When a subtemplate is displayed,
 * it's parsed using its own data and the result is inserted where the subtemplate is embedded.</li>
 * </ul>
 * A template contains global variables which are available in the whole template and local variable
 * which are only defined into a loop. To define a loop, you just have to use assign_block_vars with the
 * same loop identifier for each loop iteration. Loops can be nested and embed both variables and subtemplates.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
interface TemplateData
{
	/**
	 * @desc Assigns some simple template vars.  Those variables will be accessed in your template with the {var_name} syntax.
	 * @param string[] $array_vars A map var_name => var_value. Generally, var_name is written in caps characters.
	 */
	function assign_vars(array $array_vars);

	/**
	 * @desc Assigns a template block. A block represents a loop and has a name which be used in your template file to indicate which loop you want to browse.
	 * To know what syntax to use to browse a loop, see the class description, there are examples.
	 * @param string $block_name Block name.
	 * @param string[] $array_vars A map var_name => var_value. Generally, var_name is written in caps characters.
	 * @param Template[] $subtemplates The list of subtemplates to embed in the loop's iteration.
	 */
	function assign_block_vars($block_name, array $array_vars, array $subtemplates = array());

	/**
	 * @desc Adds a lang map to the template map list in which template variables beginning by L_ will be searched for of not already registered
	 * @param string[string] $lang the language map
	 */
	function add_lang(array $lang);

	/**
	 * @desc Adds a subtemplate that could be used using the following template code <code># include identifier #</code>
	 * @param string $identifier the identifier
	 * @param Template $template the template
	 */
	function add_subtemplate($identifier, Template $template);

	/**
	 * @desc Returns the subtemplate identified by the $identifier tag
	 * @param string $identifier the template identifier
	 * @return Template the subtemplate identified by the $identifier tag
	 */
	function get_subtemplate($identifier);

	/**
	 * @desc Returns the subtemplate identified by the $identifier tag
	 * @param string $identifier the template identifier
	 * @param Template[string] $list the template list in which we will the search for the
	 * template identifier
	 * @return Template the subtemplate identified by the $identifier tag
	 */
	function get_subtemplate_from_list($identifier, $list);


	/**
	 * @desc Returns the block $blockname in the template block list
	 * @param string $blockname the blockname of the block to retrieve
	 * @return mixed[] the requested block
	 */
	function get_block($blockname);

	/**
	 * @desc Returns the block $blockname in the parent_block
	 * @param string $blockname the blockname of the block to retrieve
	 * @param mixed[] $parent_block the parent block in which $blockname will be searched for
	 * @return mixed[] The requested block
	 */
	function get_block_from_list($blockname, $parent_block);

	/**
	 * @desc Tells whether a condition is true. Conditions are global boolean variables.
	 * @param string $varname the name of the variable to check if it is true
	 * @return bool true if the variable $varname exists and is not considered as false
	 */
	function is_true($varname);

	/**
	 * @desc Tells whether a condition is true in a block. It works for loop conditions.
	 * @param string $varname the name of the variable to check if it is true
	 * @param mixed[] $list the array in which we varname will be searched for
	 * @return bool true if the variable $varname exists and is not considered as false
	 */
	function is_true_from_list($varname, $list);

	/**
	 * @desc Returns the $varname variable content searched in from the $list
	 * @param string $varname the name of the variable to retrieve
	 * @param mixed[] $list the list in which the variable will be searched for
	 * @return string the $varname variable content
	 */
	function get_var($varname);

	/**
	 * @desc Similar from the get_var method but it works with loop variables instead of global variables.
	 * @param string $varname The name of the variable to retrieve
	 * @param mixed[] $list The list into which retrieve the variable
	 * @return string The variable content
	 */
	function get_var_from_list($varname, &$list);

	/**
	 * @desc Returns the var $varname ready to be placed in Javascript string definition.
	 * @param string $varname The name of the variable to retrieve.
	 * @return string The variable's value
	 */
	function get_js_var($varname);

	/**
	 * @desc Does the same thing as {@link get_js_var()} but for a loop.
	 * @param string $varname Name of the var
	 * @param mixed[] $list The list into which look for
	 * @return string The variable's value
	 */
	function get_js_var_from_list($varname, &$list);

	/**
	 * @desc Returns an javascript-ready lang variable (similar to get_lang_var).
	 * @param string $varname Name of the variable
	 * @return string The variable's value
	 */
	function get_js_lang_var($varname);

	/**
	 * @desc Similar to {@link get_js_lang_var()} but for a loop.
	 * @param string $varname Name of the variable
	 * @param mixed[] $list The list into which the variable should be
	 * @return string The variable's value
	 */
	function get_js_lang_var_from_list($varname, &$list);

	/**
	 * @desc Returns a HTML escaped lang variable (" becomes &quote; for instance).
	 * @param string $varname Name of the variable
	 * @return string The variable's value
	 */
	function get_htmlescaped_lang_var($varname);

	/**
	 * @desc Similar to {@link get_htmlescaped_lang_var()} but for a loop.
	 * @param string $varname Name of the variable
	 * @param mixed[] $list The list into which the variable should be.
	 * @return string The variable's value
	 */
	function get_htmlescaped_lang_var_from_list($varname, &$list);

	/**
	 * @desc Returns the HTML escaped variable.
	 * @param string $varname Name of the variable
	 * @return string The variable's value
	 */
	function get_htmlescaped_var($varname);

	/**
	 * @desc Similar to {@link get_htmlescaped_var()} but for a loop.
	 * @param string $varname Name of the variable
	 * @param mixed $list The list into which the variable should be
	 * @return string The variable's value
	 */
	function get_htmlescaped_var_from_list($varname, &$list);

	/**
	 * @desc Returns a language variable assigned by the {@link add_lang()} method.
	 * @param string $varname Name of the variable
	 * @return string The variable's value
	 */
	function get_lang_var($varname);

	/**
	 * @desc Similar to {@link get_lang_var()} but for a loop.
	 * @param string $varname Name of the variable
	 * @param mixed[] $list The list into which the variable should be
	 * @return string The variable's value
	 */
	function get_lang_var_from_list($varname, &$list);

	/**
	 * @desc Loads the most common vars which are useful in the whole PHPBoost templates. The variables are:
	 * <ul>
	 * 	<li>SID for the session id</li>
	 * 	<li>THEME the theme used by the current user</li>
	 * 	<li>LANG the lang used by the current user</li>
	 * 	<li>C_USER_CONNECTED tells whether the user is connected (member, moderator or administrator)</li>
	 * 	<li>C_USER_NOTCONNECTED is the negation of C_USER_CONNECTED</li>
	 * 	<li>PATH_TO_ROOT is the path which starts from the domain root (in HTTP context) and goes to the PHPBoost
	 * root. For instance if PHPBoost is installed at www.example.com/directory/, its value will be /directory.</li>
	 * 	<li>PHP_PATH_TO_ROOT is the server side path, it's the path which goes to the PHPBoost's root.</li>
	 * 	<li>TOKEN is the CSRF protection token. It's to use in the critical actions to show that the user really
	 * intended doing the action</li>
	 * </ul>
	 */
	function auto_load_frequent_vars();

	/**
	 * @desc Binds vars on another {@link TemplateData} object. The two instances will share the same data.
	 * @param TemplateData $data The data to use.
	 */
	function bind_vars(TemplateData $data);
}
?>