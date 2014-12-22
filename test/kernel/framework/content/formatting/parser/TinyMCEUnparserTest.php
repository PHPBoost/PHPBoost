<?php
class TinyMCEUnparserTest extends PHPBoostUnitTestCase
{
	public function test_img()
	{
		$input = '<img src="/folder/img.png" alt="" style="" />';
		self::assertEquals($this->get_expected_value('<img src="../folder/img.png" alt="" />'), $this->unparse($input));
		$input = '<img src="/folder/img.png" alt="" style="width:45px;height:22px;" />';
		self::assertEquals($this->get_expected_value('<img src="../folder/img.png" alt="" width="45" height="22" />'), $this->unparse($input));
		$input = '<img src="/folder/img.png" alt="" style="border:5px solid black;width:45px;height:22px;" />';
		self::assertEquals($this->get_expected_value('<img style="border:5px solid black;" src="../folder/img.png" alt="" width="45" height="22" />'), $this->unparse($input));
	}
	
	private function unparse($content)
	{
		$parser = new TinyMCEUnparser();
		$parser->set_content($content);
		$parser->parse();
		return $parser->get_content();
	}
	
	private function get_expected_value($html)
	{
		return htmlspecialchars('<p>' . $html . '</p>');
	}
}