<?php

class UTUpload extends PHPBoostUnitTestCase
{
	function test_constructor()
	{
		$up = new Upload();
		$this->assertEquals($up->base_directory, 'upload');
		unset($up);

		$up = new Upload('ici');
		$this->assertEquals($up->base_directory, 'ici');
	}

	function test_file()
	{
		$_FILES = array();
		$_FILES = array(
			'username' => 'username',
			'userfile' => array('name' => 'angry.gif', 'type' => 'type', 'size' => 1, 'tmp_name' => 'tmp_name', 'error' => 0));
	
		$file = dirname(__FILE__) . '/angry.gif';
		$up = new Upload(dirname($file));

		$_FILES['userfile']['size'] = 0;
		$ret = $up->file('userfile');
		$this->assertFalse($ret);
		$this->assertEquals($up->get_error(), 'e_upload_error');

		$_FILES['userfile']['size'] = 1024*10;
		
		$_FILES['userfile']['error'] = 0;
		$ret = $up->file('userfile', '', FALSE, 1);
		$this->assertFalse($ret);
		$this->assertEquals($up->get_error(), 'e_upload_max_weight');
		
		$ret = $up->file('userfile', '/bidon/', FALSE, 200);
		$this->assertFalse($ret);
		$this->assertEquals($up->get_error(), 'e_upload_invalid_format');
		
		$ret = $up->file('userfile', '', FALSE, 200, TRUE);
		$this->assertFalse($ret);
		$this->assertEquals($up->get_error(), 'e_upload_error');
		
		echo '<br />TODO Faire un test avec tout OK<br />';
	}
	
	function test_validate_img()
	{
		$file = dirname(__FILE__) . '/upload/angry.gif';
		@mkdir(dirname($file));
		@copy(dirname(__FILE__) . '/angry.gif', $file);

		$up = new Upload(dirname($file));
		$this->assertEquals($up->base_directory, dirname($file));
		
		$ret = $up->validate_img($file, 100, 100, FALSE);
		$this->assertEquals($ret, '');
		
		$ret = $up->validate_img($file, 1, 1, FALSE);
		$this->assertEquals($ret, 'e_upload_max_dimension');

		$ret = $up->validate_img($file, 1, 1, TRUE);
		$this->assertEquals($ret, 'e_upload_max_dimension');
		$this->assertFalse(file_exists($file));
		@rmdir(dirname($file));
	}
	
	function test__check_file()
	{
		$file = dirname(__FILE__) . '/angry.gif';
		$up = new Upload(dirname($file));
		$this->assertEquals($up->base_directory, dirname($file));
		
		$ret = $up->_check_file('test.php', '/.*/');
		$this->assertFalse($ret);

		$ret = $up->_check_file('test.img', '/[a-z]+.img/');
		$this->assertTrue($ret);
	}
	
	function test__clean_filename()
	{
		$file = dirname(__FILE__) . '/angry.gif';
		$up = new Upload(dirname($file));
		
		$ret = $up->_clean_filename('TEST PHP');
		$this->assertEquals($ret, 'test_php');
		
		$ret = $up->_clean_filename(' éèêàâùüûïîôç');
		$this->assertEquals($ret, 'eeeaauuuiioc');
		
		$ret = $up->_clean_filename('a"\'#_{}()');
		$this->assertEquals($ret, 'a');
	}
	
	function test__generate_file_info()
	{
		$file = 'angry.gif';
		$up = new Upload(dirname(__FILE__) . '/');
		
		$up->_generate_file_info('test', 'name_upload', FALSE);
		$this->assertEquals($up->filename['name_upload'], 'test');
		$this->assertEquals($up->extension['name_upload'], '');
		
		$up->_generate_file_info(basename($file), 'name_upload', FALSE);
		$this->assertEquals($up->filename['name_upload'], basename($file));	
		$this->assertEquals($up->extension['name_upload'], 'gif');
		
		echo '<br />';
		$up->_generate_file_info('angry.gif', 'name_upload', TRUE);
		var_dump($up->filename['name_upload']);
		$this->assertPattern('/angry_[0-9A-Za-z]+/', $up->filename['name_upload']);		
		$this->assertEquals($up->extension['name_upload'], 'gif');

		echo '<br />';		
		$up->_generate_file_info('vide', 'name_upload', TRUE);
		var_dump($up->filename['name_upload']);
		$this->assertPattern('/vide_[0-9A-Za-z]+/', $up->filename['name_upload']);		
		$this->assertEquals($up->extension['name_upload'], '');
		echo '<br />';
	}
	
	function test__error_manager()
	{
		$up = new Upload();
		$ret = $up->_error_manager(0);
		$this->assertEquals($ret, '');

		$ret = $up->_error_manager(1);
		$this->assertEquals($ret, 'e_upload_max_weight');

		$ret = $up->_error_manager(2);
		$this->assertEquals($ret, 'e_upload_max_weight');
		
		$ret = $up->_error_manager(3);
		$this->assertEquals($ret, 'e_upload_error');

		$ret = $up->_error_manager(-1);
		$this->assertEquals($ret, 'e_upload_error');
		
		$ret = $up->_error_manager(10);
		$this->assertEquals($ret, 'e_upload_error');
	}
	
}
