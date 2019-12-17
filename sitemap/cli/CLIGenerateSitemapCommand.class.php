<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 06 02
*/

class CLIGenerateSitemapCommand implements CLICommand
{
	public function short_description()
	{
		return 'Generates the sitemap.xml file.';
	}

	public function help(array $args)
	{
		CLIOutput::writeln('Generates the sitemap.xml file. If is already exists, it is overridden.');
	}

	public function execute(array $args)
	{
		try
		{
			SitemapXMLFileService::try_to_generate();
			CLIOutput::writeln('The sitemap.xml file has been successfully generated');
		}
		catch(IOException $e)
		{
			CLIOutput::writeln('The sitemap.xml couldn\'t be generated probably because it\'s unwritable.');
		}
	}
}
?>
