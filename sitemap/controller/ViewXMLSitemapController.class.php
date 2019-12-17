<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 12 08
*/

class ViewXMLSitemapController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$sitemap = SitemapService::get_public_sitemap();
		AppContext::get_response()->set_header('content-type', 'text/xml');
		return new SiteNodisplayResponse($sitemap->export(SitemapXMLFileService::get_export_config()));
	}
}
?>
