<?php
/*##################################################
 *                           FileTemplate.class.php
 *                            -------------------
 *   begin                : June 18 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

/**
 * @package {@package}
 * @author Loic Rouchon <loic.rouchon@phpboost.com> Régis Viarre <crowkait@phpboost.com>
 * @desc This class allows you to handle a template file.
 * Your template files should have the .tpl extension.
 * To be more efficient, this class uses a cache and parses each file only once.
 * <h1>File identifiers</h1>
 * The web site can have several themes whose files aren't in the same folders. When you load a file, you just have to load the generic file and the right template file will
 * be loaded dynamically.
 * <h2>Kernel template file</h2>
 * When you want to load a kernel template file, the path you must indicate is only the name of the file, for example header.tpl loads /template/your_theme/header.tpl and
 * if it doesn't exist, it will load /template/default/header.tpl.
 * <h2>Module template file</h2>
 * When you want to load a module template file, you must indicate the name of you module and then the name of the file like this: module/file.tpl which will load the
 * /module/templates/file.tpl. If the user themes redefines the file.tpl file for the module module, the file templates/your_theme/modules/module/file.tpl will be loaded.
 * <h2>Menu template file</h2>
 * To load a file of a menu, use this kind of path: menus/my_menu/file.tpl which will load the /menus/my_menu/templates/file.tpl file.
 * <h2>Framework template file</h2>
 * To load a framework file, use a path like this: framework/package/file.tpl which will load /templates/your_theme/framework/package/file.tpl if the theme overrides it,
 * otherwise /templates/default/framework/package/file.tpl will be used.
 */
class FileTemplate extends AbstractTemplate
{
	private $file_identifier;

	/**
	 * @desc Builds a FileTemplate object
	 * @param string $file_identifier The identifier of the file you want to load (see this class' description)
	 * to know how to compose a indentifier.
	 */
	public function __construct($file_identifier)
	{
		$this->file_identifier = $file_identifier;
		$data = new DefaultTemplateData();
		$data->auto_load_frequent_vars();
		$loader = new FileTemplateLoader($this->file_identifier, $data);
		$renderer = new DefaultTemplateRenderer();
		parent::__construct($loader, $renderer, $data);
	}

	/**
	 * {@inheritdoc}
	 */
    public function render()
    {
        try
        {
            return parent::render();
        }
        catch (TemplateRenderingException $exception)
        {
            throw new FileTemplateRenderingException($this->file_identifier, $exception);
        }
        catch (LangNotFoundException $exception)
        {
            throw new FileTemplateRenderingException($this->file_identifier, $exception);
        }
    }
}
?>