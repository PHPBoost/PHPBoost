<?php
/*##################################################
 *                             Template.class.php
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
 * @package {@package}
 * @desc This class represents a PHPBoost template. Templates are used to generate text which
 * have a common structure. You just have to write your text with tags at the place you want to insert
 * values and assign values in the objet, when you will display them, the tags will be replaced by
 * the corresponding value.
 * PHPBoost's template engine is home made and has its own syntax which is the described below:
 * <h1>Variable assignation</h1>
 * <h2>Simple variables</h2>
 * A simple variable is accessible with the {NAME} syntax where NAME is its template name. If the variable is not assigned, nothing will be displayed (no error message).
 * Simple variables are assigned by the assign_vars() method.
 * <h1>Lang variables</h1>
 * To speed the development up, you can avoid setting all the localized variables to use. You just have to associate one or more lang map
 * (lang_identifier => localized lang) with the {@link add_lang()} method. In the template, you just have to
 * add the 'L_' prefix before the variable name and it will be searched in the langs map.
 * <h2>Loops</h2>
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
 * <h2>Conditions</h2>
 * When you want to display something only in particular case, you can use some condition tests.
 * # IF C_MY_TEST #
 * This text will be displayed only if the C_MY_TEST variable is true
 * # ENDIF #
 * You can nest some conditions.
 * <h3>Nesting templates</h3>
 * You can embed a template in another one. For that, you have to use the INCLUDE instruction like that: # INCLUDE template #
 * where template is the identifier of a template added with the add_subtemplate() method. When the template will be displayed, this
 * instruction will be replaced by the content of the template you have attached to this identifier or nothing if the template hasn't been set.
 * You also can include templates in a loop, for that you have to place them in the third parameter of the asssign_block_vars() method.
 * <h1>Variables using</h1>
 * By default, when you call the variable by it's name, it'll be returnes as it was assigned. But you can do little implicit treatments of variables
 * directly in the template. Here are the prefixes to use before the variable names:
 * <ul>
 *	<li><E_: the variable will be searched without its prefix and will be escaped using <code>htmlspecialchars()</code></li>
 *	<li><J_: the variable will be searched without its prefix and will be escaped using <code>TextHelper::to_js_string()</code></li>
 *	<li><L_: the variable will be searched without its prefix in every languages maps registered using <code>Template->add_lang($language)</code></li>
 *	<li><EL_: the variable will be searched without its prefix like languages variables and will be escaped using <code>htmlspecialchars()</code></li>
 *	<li><JL_: the variable will be searched without its prefix like languages variables and will be escaped using <code>TextHelper::to_js_string()</code></li>
 * </ul>
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
interface Template extends View
{
	/**
	 * @desc Enables the strict mode. If a variable that does not exist in the object is requested,
	 *   then an exception will be raised.
	 */
	function enable_strict_mode();

	/**
	 * @desc Disables the strict mode. If a variable that does not exist in the object is requested,
	 *   then an empty value will be returned.
	 */
	function disable_strict_mode();

	/**
	 * @desc Assigns the value <code>$code</code> to the template variable of name <code>$key</code>
	 * @param $key the template parameter name
	 * @param $value the template parameter value
	 */
	function put($key, $value);

	/**
     * @desc Assigns template variables. It could be simple variables, loop or subtemplates.
     * @param mixed[string] $vars A map key => value where <code>$value</code> will be assigned to the template variable of name <code>$key</code>
     */
    function put_all(array $vars);

    /**
     * @deprecated Use <code>put_all</code> or <code>put</code> instead
     * @desc Assigns some simple template vars.  Those variables will be accessed in your template with the {var_name} syntax.
     * @param string[] $array_vars A map var_name => var_value. Generally, var_name is written in caps characters.
     */
    function assign_vars(array $array_vars);

	/**
	 * @desc Assigns a template block. A block represents a loop and has a name which be used in your template file to indicate which loop you want to browse.
	 * To know what syntax to use to browse a loop, see the class description, there are examples.
	 * @param string $block_name Block name.
	 * @param string[] $array_vars A map var_name => var_value. Generally, var_name is written in caps characters.
	 * @param Template[] $subtemplates A list
	 */
	function assign_block_vars($block_name, array $array_vars, array $subtemplates = array());

	/**
	 * @desc Displays the template.
	 */
	function display();

	/**
     * @desc Adds a lang map to the template map list in which template variables beginning by L_ will be searched for of not already registered
     * @param string[string] $lang the language map
     */
    function add_lang(array $lang);

	/**
     * @deprecated Use <code>put_all</code> or <code>put</code> instead
	 * @desc Adds a subtemplate to embed with the INCLUDE instruction
	 * @param string $identifier the identifier
	 * @param Template $template the template to include (variables must be set in this template)
	 */
	function add_subtemplate($identifier, Template $template);

	/**
	 * @desc Inject data into the template. This is for internal use only.
	 * @param TemplateData the data to inject into the template
	 */
	function set_data(TemplateData $data);

	/**
	 * @desc Returns the template data
	 * @return TemplateData
	 */
	function get_data();
	
	/**
	 * @desc Returns the pictures data path
	 * @return String
	 */
	function get_pictures_data_path();
}
?>