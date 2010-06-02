<?php
/*##################################################
 *                      CLIGenerateSitemapCommand.class.php
 *                            -------------------
 *   begin                : June 02, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

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