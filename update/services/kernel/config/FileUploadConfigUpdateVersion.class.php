<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 01 27
 * @since       PHPBoost 6.0 - 2021 01 15
*/

class FileUploadConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('kernel-maintenance', false);
	}

	protected function build_new_config()
	{
		$old_config = $this->get_old_config();

		$config = FileUploadConfig::load();
		
		$authorized_extensions = $old_config->get_property('authorized_extensions');
		
		if (!in_array('webp', $authorized_extensions))
		{
			$config->set_property('authorized_extensions', array_merge($authorized_extensions, array('webp')));
			$this->save_new_config('kernel-file-upload-config', $config);
		}

		return true;
	}
}
?>
