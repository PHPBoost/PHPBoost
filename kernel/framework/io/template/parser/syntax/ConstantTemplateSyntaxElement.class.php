<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 09 04
*/

class ConstantTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	public static function is_element(StringInputStream $input)
	{
		return $input->assert_next('(?:[0-9]+(?:\.[0-9]+)?)|(?:\'[^\']*\')|(?:true)|(?:false)');
	}

	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
	{
		$matches = array();
		if ($input->consume_next('(?P<constant>(?:[0-9]+(?:\.[0-9]+)?)|(?:\'[^\']*\')|(?:true)|(?:false))', '', $matches))
		{
			$constant = $matches['constant'];
			$output->write($constant);
		}
		else
		{
			throw new TemplateRenderingException('invalid constant variable', $input);
		}
	}
}
?>
