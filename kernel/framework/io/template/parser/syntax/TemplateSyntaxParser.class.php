<?php
/**
 * template = (variableExpression | expression | condition | loop | include | text)*
 * variableExpression = "{", variable, "}"
 * expression = "${", expressionContent, "}"
 * condition = "# IF ", "NOT "?, expression, "#", template, ("# ELSE #, template)?, "# ENDIF #"
 * loop = "# START ", expression, " #", template, "# END (?:name)? #"
 * include = "# INCLUDE ", name, " #"
 * text = .+
 * expressionContent = array | function | variable | constant
 * function = "\(\w+::\)?\w+\(", parameters, "\)"
 * parameters = expressionContent | (expressionContent, (",", expressionContent)+)
 * variable = simpleVar | loopVar
 * constant = "'.+'" | [0-9]+ | array
 * array = "array(", arrayContent, ")"
 * arrayContent = arrayElement | (arrayElement, (",", arrayElement)+)
 * arrayElement = expressionContent | ("'\w+'\s*=>\s*", expressionContent)
 * simpleVar = "\w+"
 * loopVar = "(\w+\.)+\w+"
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 06 17
 * @contributor Loic ROUCHON <horn@phpboost.com>
*/

class TemplateSyntaxParser implements TemplateParser
{
	/**
	 * @var StringInputStream
	 */
	private $input;
	/**
	 * @var StringOutputStream
	 */
	private $output;

	public function parse($content)
	{
		$this->input = new StringInputStream($content);
		$this->output = new StringOutputStream();
		$this->output->write('<?php ' . TemplateSyntaxElement::RESULT . '=\'');
		$this->do_parse();
		$this->output->write('\'; ?>');
		return $this->output->to_string();
	}

	private function do_parse()
	{
		$template_element = new TextTemplateSyntaxElement();
		$template_element->parse(new TemplateSyntaxParserContext(), $this->input, $this->output);
		if ($this->input->has_next())
		{
			throw new TemplateRenderingException('Unknown statement', $this->input);
		}
	}
}
?>
