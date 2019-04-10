<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.3 - last update: 2019 04 09
 * @since   	PHPBoost 5.1 - 2018 01 27
*/

class GalleryModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('gallery');
		
		$this->delete_old_files_list = array(
			'/phpboost/GalleryNewContent.class.php',
			'/phpboost/GalleryNotation.class.php'
		);
	}
}
?>
