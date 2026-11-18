<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 18
 * @since       PHPBoost 3.0 - 2010 07 10
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SimpleVarTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	public static function is_element(StringInputStream $input): bool
	{
		return $input->assert_next('\w+');
	}

	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output): void
	{
		$matches = [];
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
