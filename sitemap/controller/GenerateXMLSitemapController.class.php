<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2009 12 08
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class GenerateXMLSitemapController extends DefaultAdminModuleController
{
	protected function get_template_to_use()
	{
		return new FileTemplate('sitemap/GenerateXMLSitemapController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		try
		{
			SitemapXMLFileService::try_to_generate();
		}
		catch(IOException $ex)
		{
			$this->view->put_all(
				array('C_GOT_ERROR' => true)
			);
		}

		$this->view->put_all(array(
			'U_GENERATE' => SitemapUrlBuilder::get_xml_file_generation()->rel()
		));

		$response = new AdminSitemapResponse($this->view);
		$response->get_graphical_environment()->set_page_title($this->lang['sitemap.generate.xml'], $this->lang['sitemap.module.title']);
		return $response;
	}
}
?>
