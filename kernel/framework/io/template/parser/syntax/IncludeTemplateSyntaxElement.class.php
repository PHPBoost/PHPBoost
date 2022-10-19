<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 09 11
*/

class IncludeTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	private static $subtpl = '$_subtpl';

	private $ended = false;

	public static function is_element(StringInputStream $input)
	{
		return $input->assert_next('#\s+INCLUDE');
	}

	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
	{
        $this->register($context, $input, $output);
		$this->do_parse();
	}

	private function do_parse()
	{
		$matches = array();
        $this->output->write('\';');
		if ($this->input->consume_next('#\s+INCLUDEFILE (?P<file>[\w_/.]+)\s+#', '', $matches))
		{
            $this->write_includefile($matches);
		}
		elseif ($this->input->consume_next('#\s+INCLUDE\s+(?:(?P<block>(?:\w+\.)*\w+)\.)?(?P<name>\w+)\s+#', '', $matches))
		{
            $this->write_subtemplate_initialization($matches);
            $this->write_subtemplate_call();
        }
        else
        {
            throw new TemplateRenderingException('invalid include template name', $this->input);
        }
        $this->output->write(TemplateSyntaxElement::RESULT . '.=\'');
	}

	private function write_includefile(array $matches)
    {
        $this->output->write(self::$subtpl . '=new FileTemplate(\'' . $matches['file'] . '\');');
        $this->output->write(self::$subtpl . '->set_data(' . TemplateSyntaxElement::DATA . ');');
        $this->output->write(TemplateSyntaxElement::RESULT . '.=' . self::$subtpl . '->render();');
    }

    private function write_subtemplate_initialization(array $matches)
    {
        $name = $matches['name'];
        $is_in_block = !empty($matches['block']);
        if ($is_in_block)
        {
            $this->write_block_subtemplate_initialization($name, $matches['block']);
        }
        else
        {
            $this->output->write(self::$subtpl . '=' . TemplateSyntaxElement::DATA . '->get(\'' . $name . '\');');
        }
    }

    private function write_block_subtemplate_initialization($name, $block)
    {
        $blocks = explode('.', $block);
        $block_var = '$_tmp_' . array_pop($blocks);
        $this->output->write(self::$subtpl . '=' . TemplateSyntaxElement::DATA . '->get_from_list(\'' . $name . '\', ' . $block_var . ');');
    }

    private function write_subtemplate_call()
    {
        $this->output->write('if(' . self::$subtpl . ' !== null){' . TemplateSyntaxElement::RESULT . '.=' . self::$subtpl . '->render();}');
    }
}
?>
