<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 28
 * @since       PHPBoost 3.0 - 2010 09 10
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class PHPTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	private $ended = false;
	private $escaped = false;

	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
	{
        $this->register($context, $input, $output);
		$this->do_parse();
	}

	private function do_parse()
	{
		$matches = array();
		if ($this->input->consume_next('\?php\s*(?P<php>[^\s].*[^\*])\s*\?>', 'Us', $matches))
		{
			$this->process_php($matches['php']);
		}
		else
		{
			throw new TemplateRenderingException('Missing php code ends: "?>"', $this->input);
		}
	}

	private function process_php($php)
	{
        $this->output->write('\';$_ob_length=ob_get_length();');
        $this->output->write($php);
        $this->output->write('if(ob_get_length()>$_ob_length){$_content=ob_get_clean();' . TemplateSyntaxElement::RESULT .
            '.=TextHelper::substr($_content, $_ob_length);echo TextHelper::substr($_content, 0, $_ob_length);}');
        $this->output->write(TemplateSyntaxElement::RESULT . '.=\'');
	}
}
?>
