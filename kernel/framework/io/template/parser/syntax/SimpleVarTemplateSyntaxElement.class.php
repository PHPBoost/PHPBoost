<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 07 10
*/

class SimpleVarTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	public static function is_element(StringInputStream $input)
	{
		return $input->assert_next('\w+');
	}

	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
	{
		$matches = array();
		if ($input->consume_next('(?P<var>\w+)', '', $matches))
		{
			$varname = $matches['var'];
			$output->write(TemplateSyntaxElement::DATA . '->get(\'' . $varname . '\')');
		}
		else
		{
			throw new TemplateRenderingException('invalid simple variable name', $input);
		}
	}
}
?>
