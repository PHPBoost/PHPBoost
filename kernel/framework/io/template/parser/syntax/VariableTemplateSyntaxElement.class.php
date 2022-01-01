<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 06
 * @since       PHPBoost 3.0 - 2010 07 08
*/

class VariableTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	public static function is_element(StringInputStream $input)
	{
		return $input->assert_next('\s*(?:@(?:H\|)?)?(?:[a-z0-9A-Z_]\w+\.)*[a-z0-9A-Z_]\w+\s*');
	}

	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
	{
        $this->register($context, $input, $output);
		$input->consume_next('\s*');
		$element = null;
		if (LangVarTemplateSyntaxElement::is_element($input))
		{
			$element = new LangVarTemplateSyntaxElement();
		}
		elseif (LoopVarTemplateSyntaxElement::is_element($input))
		{
			$element = new LoopVarTemplateSyntaxElement();
		}
		elseif (SimpleVarTemplateSyntaxElement::is_element($input))
		{
			$element = new SimpleVarTemplateSyntaxElement();
		}
		else
		{
			throw new TemplateRenderingException('invalid variable', $input);
		}
		$this->parse_elt($element);
		$input->consume_next('\s*');
	}
}
?>
