<?php

class TemplateSyntaxCheckerTest extends PHPBoostUnitTestCase
{
	public function test_no_tpl_syntax_check()
	{
		$tpl = 'toto';
		$this->launch_test($tpl);
	}
	
	private function launch_test($string)
	{
		$checker = new TemplateSyntaxChecker($string);
		$checker->check_syntax();
	}
}

?>