<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 06
 * @since       PHPBoost 6.0 - 2021 01 15
*/

class FileUploadConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('kernel', false, 'kernel-file-upload-config');
	}

	protected function build_new_config()
	{
		$old_config = $this->get_old_config();

		if ($old_config)
		{
			$config = FileUploadConfig::load();
			
			if ($old_config->has_property('authorized_extensions'))
			{
				$authorized_extensions = array_diff($old_config->get_property('authorized_extensions'), array('flv'));
				
				if (!in_array('webp', $authorized_extensions))
					$authorized_extensions[] = 'webp';
				
				$config->set_authorized_extensions($authorized_extensions);
				FileUploadConfig::save();

				return true;
			}
		}
		return false;
	}
}
?>
