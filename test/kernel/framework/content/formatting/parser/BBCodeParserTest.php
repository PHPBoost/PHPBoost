<?php
class BBCodeParserTest extends PHPBoostUnitTestCase
{
	public function test_img()
	{
		$input = '[img]/folder/img.png[/img]';
		self::assertEquals('<img src="/folder/img.png" alt="" />', $this->parse($input));
		$input = '[img style="border:1px;"]/folder/img.png[/img]';
		self::assertEquals('<img src="/folder/img.png" alt="" style="border:1px;" />', $this->parse($input));
	}
	
	private function parse($content)
	{
		$parser = new BBCodeParser();
		$parser->set_content($content);
		$parser->parse();
		return $parser->get_content();
	}
}