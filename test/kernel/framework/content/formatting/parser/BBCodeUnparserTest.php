<?php
class BBCodeUnparserTest extends PHPBoostUnitTestCase
{
	public function test_img()
	{
		$input = '<img src="/folder/img.png" alt="" />';
		self::assertEquals('[img]/folder/img.png[/img]', $this->unparse($input));
		$input = '<img src="/folder/img.png" alt="" style="border:1px;" />';
		self::assertEquals('[img style="border:1px;"]/folder/img.png[/img]', $this->unparse($input));
	}
	
	private function unparse($content)
	{
		$parser = new BBCodeUnparser();
		$parser->set_content($content);
		$parser->parse();
		return $parser->get_content();
	}
}