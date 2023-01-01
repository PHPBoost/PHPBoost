<?php
/**
 * @package     Builder
 * @subpackage  Table\filter\sql
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 20
 * @since       PHPBoost 6.0 - 2021 03 22
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class HTMLTableCategorySQLFilter extends AbstractHTMLTableFilter implements SQLFragmentBuilder
{
	private static $param_id_index = 0;

	private $db_field;
	private $options = array();

	public function __construct($name, $label = '', $db_field = 'id_category')
	{
		$this->db_field = $db_field;
		$label = !empty($label) ? $label : LangLoader::get_message('category.category', 'category-lang');

		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
		$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);

		$this->options = FormFieldCategoriesSelect::generate_options('', $search_category_children_options, true);

		$select = CategoriesService::get_categories_manager()->get_select_categories_form_field($db_field, $label, '', $search_category_children_options, array(), $this->options);

		parent::__construct($name, $select);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_sql()
	{
		$parameter_name = $this->get_sql_value_parameter_prefix() . '_' . $this->db_field;
		$query = $this->get_value()->get_raw_value() != 'all' ? $this->db_field . ' = :' . $parameter_name : '';
		$parameters = array($parameter_name => $this->get_value()->get_raw_value());
		return new SQLFragment($query, $parameters);
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_value_allowed($value)
	{
		if (is_numeric($value))
		{
			$this->set_value($value);
			return true;
		}
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function set_value($value)
	{
		foreach ($this->options as $option)
		{
			if ($option->get_raw_value() == $value)
			{
				parent::set_value($value);
				break;
			}
		}
	}

    protected function get_sql_value_parameter_prefix()
    {
        return __CLASS__ . '_' . self::$param_id_index++;
    }
}

?>
