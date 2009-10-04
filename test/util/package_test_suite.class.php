<?php

class PackageTestSuite extends TestSuite
{
	public function __construct($directory, $recursive = false)
	{
		parent::__construct('PackageTestSuite: ' . str_replace(FS_ROOT_DIRECTORY . '/test', '',
		str_replace('\\', '/', $directory)));
		$this->add_test_classes($directory);
	}

	public function run($reporter = null)
	{
		if ($reporter === null)
		{
			$reporter = new HtmlReporter();
		}
		parent::run($reporter);
	}

	private function add_test_classes($directory, $recursive = false)
	{
		import('io/filesystem/folder');
		$folder = new folder($directory);
		foreach ($folder->get_files('`\.class\.php`') as $file)
		{
			$this->addFile($file->get_name());
		}
		if ($recursive)
		{
			foreach ($folder->get_folders() as $folder)
			{
				$this->add_test_classes($folder->get_name(true), true);
			}
		}
	}
}
?>