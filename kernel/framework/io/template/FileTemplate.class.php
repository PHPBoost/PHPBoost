<?php
/**
 * This class allows you to handle a template file.
 * Your template files should have the .tpl extension.
 * To be more efficient, this class uses a cache and parses each file only once.
 * <h4>File identifiers</h4>
 * The web site can have several themes whose files aren't in the same folders. When you load a file, you just have to load the generic file and the right template file will
 * be loaded dynamically.
 * <h5>Kernel template file</h5>
 * When you want to load a kernel template file, the path you must indicate is only the name of the file, for example header.tpl loads /template/your_theme/header.tpl and
 * if it doesn't exist, it will load /template/default/header.tpl.
 * <h5>Module template file</h5>
 * When you want to load a module template file, you must indicate the name of you module and then the name of the file like this: module/file.tpl which will load the
 * /module/templates/file.tpl. If the user themes redefines the file.tpl file for the module module, the file templates/your_theme/modules/module/file.tpl will be loaded.
 * <h5>Menu template file</h5>
 * To load a file of a menu, use this kind of path: menus/my_menu/file.tpl which will load the /menus/my_menu/templates/file.tpl file.
 * <h5>Framework template file</h5>
 * To load a framework file, use a path like this: framework/package/file.tpl which will load /templates/your_theme/framework/package/file.tpl if the theme overrides it,
 * otherwise /templates/default/framework/package/file.tpl will be used.
 * @package     IO
 * @subpackage  Template
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2009 06 18
 * @contributor Regis VIARRE <crowkait@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FileTemplate extends AbstractTemplate
{
	private $file_identifier;

	/**
	 * Builds a FileTemplate object
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
