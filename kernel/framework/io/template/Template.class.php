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
	const DONOT_LOAD_FREQUENT_VARS = false;

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
	 */
	function assign_block_vars($block_name, array $array_vars, array $subtemplates = array());

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
	 * @desc Returns the template data
	 * @return TemplateData
	 */
	function get_data();
}
?>