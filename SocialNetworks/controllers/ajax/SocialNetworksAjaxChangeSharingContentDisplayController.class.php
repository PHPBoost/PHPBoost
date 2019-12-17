<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 04 14
 * @since       PHPBoost 5.1 - 2018 04 11
*/

class SocialNetworksAjaxChangeSharingContentDisplayController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_value('id', '');

		$display = -1;
		if ($id !== '')
		{
			$config = SocialNetworksConfig::load();
			$enabled_content_sharing = $config->get_enabled_content_sharing();
			if (in_array($id, $enabled_content_sharing))
			{
				unset($enabled_content_sharing[array_search($id, $enabled_content_sharing)]);
				$display = 0;
			}
			else
			{
				$enabled_content_sharing[] = $id;
				$display = 1;
			}
			$config->set_enabled_content_sharing($enabled_content_sharing);

			SocialNetworksConfig::save();
		}

		return new JSONResponse(array('id' => $id, 'display' => $display));
	}
}
?>
