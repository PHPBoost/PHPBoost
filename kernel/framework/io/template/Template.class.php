<?php
/*##################################################
 *                          Template.class.php
 *                            -------------------
 *   begin                : February 3, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General License for more details.
 *
 * You should have received a copy of the GNU General License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/


/**
 * @desc Here are the PHPBoost template syntax specifications:
 * <h1>Simple variables</h1>
 * A simple variable is accessible with the {NAME} syntax where NAME is its template name. If the variable is not assigned, nothing will be displayed (no error message).
 * Simple variables are assigned by the assign_vars() method.
 * <h1>Loops</h1>
 * You can make some loops to repeat a pattern, those loops can be nested. A loop has a name (name) and each iteration contains some variables, for example, the variable VAR.
 * # START name #
 * My variable is {name.VAR}
 * # END name #
 * To nest loops, here is an example:
 * # START loop1 #
 * I write my loop1 var here: {loop1.VAR}.
 * 	# START loop1.loop2 #
 * I can write my loop2 var here: {loop1.loop2.VAR} but also my loop1 var of the parent loop: {loop1.VAR}.
 * 	# END loop1.loop2 #
 * # END loop1 #
 * To assign the variable, see the assign_block_vars() method which creates one iteration.
 * <h1>Conditions</h1>
 * When you want to display something only in particular case, you can use some condition tests.
 * # IF C_MY_TEST #
 * This text will be displayed only if the C_MY_TEST variable is true
 * # ENDIF #
 * You can nest some conditions.</li>
 * </ul>
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
interface Template
{
	const AUTO_LOAD_FREQUENT_VARS = true;
	const DO_NOT_LOAD_FREQUENT_VARS = false;

	/**
	 * @desc Assigns some simple template vars.  Those variables will be accessed in your template with the {var_name} syntax.
	 * @param string[] $array_vars A map var_name => var_value. Generally, var_name is written in caps characters.
	 */
	function assign_vars($array_vars);

	/**
	 * @desc Assigns a template block. A block represents a loop and has a name which be used in your template file to indicate which loop you want to browse.
	 * To know what syntax to use to browse a loop, see the class description, there are examples.
	 * @param string $block_name Block name.
	 * @param string[] $array_vars A map var_name => var_value. Generally, var_name is written in caps characters.
	 */
	function assign_block_vars($block_name, $array_vars, $subtemplates = array());

	/**
	 * @desc Clones this object.
	 * @return Template A clone of this object.
	 */
	function copy();

	/**
	 * @desc Displays the template. It will use the variables you assigned.
	 */
	function display();

	/**
	 * @desc Returns the result of the template interpretation
	 * @returns string
	 */
	function to_string();

	/**
	 * @desc add a lang map to the template map list in which template variables beginning by L_ will be searched for of not already registered
	 * @param string[string] $lang the language map
	 */
	function add_lang(array $lang);

	/**
	 * @desc add a subtemplate that could be used using the following template code <code># include identifier #</code>
	 * @param string $identifier the identifier
	 * @param Template $template the template
	 */
	function add_subtemplate($identifier, Template $template);

	/**
	 * @desc returns the subtemplate identified by the $identifier tag
	 * @param string $identifier the template identifier
	 * @return Template the subtemplate identified by the $identifier tag
	 */
	function get_subtemplate($identifier);

	/**
	 * @desc returns the subtemplate identified by the $identifier tag
	 * @param string $identifier the template identifier
	 * @param Template[string] $list the template list in which we will the search for the
	 * template identifier
	 * @return Template the subtemplate identified by the $identifier tag
	 */
	function get_subtemplate_from_list($identifier, $list);


	/**
	 * @desc returns the block "blockname" in the template block list
	 * @param string $blockname the blockname of the block to retrieve
	 * @return mixed[] the requested block
	 */
	function get_block($blockname);

	/**
	 * @desc returns the block "blockname" in the parent_block
	 * @param string $blockname the blockname of the block to retrieve
	 * @param mixed[] $parent_block the parent block in which $blockname will be searched for
	 * @return mixed[] the requested block
	 */
	function get_block_from_list($blockname, $parent_block);

	/**
	 * @desc Returns true if the variable $varname exists and is not considered as false
	 * @param string $varname the name of the variable to check if it is true
	 * @return bool true if the variable $varname exists and is not considered as false
	 */
	function is_true($varname);

	/**
	 * @desc rReturns true if the variable $varname exists and is not considered as false
	 * @param string $varname the name of the variable to check if it is true
	 * @param mixed[] $list the array in which we varname will be searched for
	 * @return bool true if the variable $varname exists and is not considered as false
	 */
	function is_true_from_list($varname, $list);

	/**
	 * @desc Returns the $varname variable content
	 * @param string $varname the name of the variable to retrieve
	 * @return string the $varname variable content
	 * @see get_var_from_list($varname, $list)
	 */
	/**
	 * @desc Returns the $varname variable content searched in from the $list
	 * Special operations will be done if the variable is not registered in $list. If $varname begins with
	 * <ul>
	 *	<li><E_: the variable will be search without its prefix and will be escaped using <code>htmlspecialchars()</code></li>
	 *	<li><J_: the variable will be search without its prefix and will be escaped using <code>TextHelper::to_js_string()</code></li>
	 *	<li><L_: the variable will be search without its prefix in every languages maps registered using <code>Template->add_lang($language)</code></li>
	 *	<li><EL_: the variable will be search without its prefix like languages variables and will be escaped using <code>htmlspecialchars()</code></li>
	 *	<li><JL_: the variable will be search without its prefix like languages variables and will be escaped using <code>TextHelper::to_js_string()</code></li>
	 * </ul>
	 * Each time one of these operation is requested, the variable is registered in order to speed up next calls. If nothing is found, then an empty string is returned
	 * @param string $varname the name of the variable to retrieve
	 * @param mixed[] $list the list in which the variable will be searched for
	 * @return string the $varname variable content
	 */

	function get_var($varname);

	function get_var_from_list($varname, &$list);

	function get_js_var($varname);

	function get_js_var_from_list($varname, &$list);

	function get_js_lang_var($varname);

	function get_js_lang_var_from_list($varname, &$list);

	function get_htmlescaped_lang_var($varname);

	function get_htmlescaped_lang_var_from_list($varname, &$list);

	function get_htmlescaped_var($varname);

	function get_htmlescaped_var_from_list($varname, &$list);

	function get_lang_var($varname);

	function get_lang_var_from_list($varname, &$list);
}
?>