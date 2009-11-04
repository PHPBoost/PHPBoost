<?php

import('core/ClassLoader');

class UTClassLoader extends PHPBoostUnitTestCase {

	public function test_generate_classlist()
	{
		var_export(ClassLoader::generate_classlist());
	}
}
