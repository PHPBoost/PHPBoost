<?php

import('core/ClassLoader');

class UTClassLoader extends PHPBoostUnitTestCase {

	public function test_generate_classlist()
	{
		ClassLoader::generate_classlist();
	}
}
