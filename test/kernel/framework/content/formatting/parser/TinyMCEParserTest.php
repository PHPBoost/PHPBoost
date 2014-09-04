<?php
class TinyMCEParserTest extends PHPBoostUnitTestCase
{
	public function test_img()
	{
		$input = '&lt;img src="/folder/img.png" alt="toto" /&gt;';
		self::assertEquals('<img src="/folder/img.png" alt="" style="" />', $this->parse($input));
		$input = '&lt;img src="/folder/img.png" alt="toto" width="45" height="22" /&gt;';
		self::assertEquals('<img src="/folder/img.png" alt="" style="width:45px;height:22px;" />', $this->parse($input));
		$input = '&lt;img style="border: 5px solid black;" src="/folder/img.png" alt="toto" width="45" height="22" /&gt;';
		self::assertEquals('<img src="/folder/img.png" alt="" style="border: 5px solid black;width:45px;height:22px;" />', $this->parse($input));
		$input = '&lt;img style="border: 4px solid black;" src="/folder/img.png" alt="" width="80" height="20" /&gt;';
		self::assertEquals('<img src="/folder/img.png" alt="" style="border: 4px solid black;width:80px;height:20px;" />', $this->parse($input));
	}
	
	private function parse($content)
	{
		$parser = new TinyMCEParser();
		$parser->set_content($content);
		$parser->parse();
		return $parser->get_content();
	}
}