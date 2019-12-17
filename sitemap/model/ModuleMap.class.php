<?php
/**
 * The ModuleMap class represents the map of a module. It has a description
 * (generally the module description) and contains some elements which can be
 * some simple links or some sections (which can match the categories for example).
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 24
 * @since       PHPBoost 2.0 - 2008 06 16
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class ModuleMap extends SitemapSection
{
	/**
	 * @var string Description of the module
	 */
	private $description;
	/**
	 * @var string id of the corresponding module
	 */
	private $module_id;

	/**
	 * @desc Builds a ModuleMap object
	 * @param SitemapLink $link Link associated to the root of the module
	 * @param string $module_id Id of the corresponding module
	 */
	public function __construct(SitemapLink  $link, $module_id = '')
	{
		parent::__construct($link);
		$this->module_id = $module_id;
	}

	/**
	 * @desc Return the module description
	 * @return string
	 */
	public function get_description()
	{
		return $this->description;
	}

	/**
	 * @desc Sets the description of the module
	 * @param string $description Description of the module
	 */
	public function set_description($description)
	{
		$this->description = $description;
	}

	/**
	 * @desc Exports the sitemap (according to a configuration of templates).
	 * In your template, you will be able to use the following variables:
	 * <ul>
	 * 	<li>MODULE_ID which contains the id of the module</li>
	 *  <li>C_MODULE_ID tells whether the module identifier is known</li>
	 * 	<li>MODULE_NAME which contains the name of the module</li>
	 *  <li>MODULE_DESCRIPTION which contains the description of the module</li>
	 *  <li>MODULE_URL which contains the URL of the module root page</li>
	 *  <li>DEPTH which is the depth of the module map in the sitemap (generally 1).
	 *  It might be usefull to apply different CSS styles to each level of depth.</li>
	 *  <li>LINK_CODE which contains the code of the link associated to the module root exported with the same configuration.</li>
	 *  <li>C_MODULE_MAP which is a boolean whose value is true, this will enable you to use a single template for the whole export configuration</li>
	 *  <li>The loop "element" for which the variable CODE contains the code of each sub element of the module (for example categories)</li>
	 *  </ul>
	 * @param SitemapExportConfig $export_config export configuration
	 * @return Template the template
	 */
	public function export(SitemapExportConfig $export_config)
	{
		//We get the stream in which we are going to write
		$template = $export_config->get_module_map_stream();

		$template->put_all(array(
			'MODULE_ID' => $this->get_module_id(),
			'C_MODULE_ID' => $this->get_module_id() != '',
			'MODULE_NAME' => TextHelper::htmlspecialchars($this->get_name(), ENT_QUOTES),
			'MODULE_DESCRIPTION' => FormatingHelper::second_parse($this->description),
            'MODULE_URL' => !empty($this->link) ? $this->link->get_url() : '',
		    'DEPTH' => $this->depth,
            'C_MODULE_MAP' => true
		));

		if ($this->link != null)
		{
			$template->put('LINK', $this->link->export($export_config));
		}

		//We export all the elements contained by the module map
		foreach ($this->elements as $element)
		{
			$template->assign_block_vars('element', array(), array(
				'ELEMENT' => $element->export($export_config)
			));
		}
		return $template;
	}

	/**
	 * Returns the corresponding module's identifier
	 * @return string The identifier
	 */
	public function get_module_id()
	{
		return $this->module_id;
	}
}
?>
