<?php
/**
 * @package     Content
 * @subpackage  Category
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 06
 * @since       PHPBoost 4.0 - 2013 01 29
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Category
{
	protected int $id = 0;
	protected string $name = '';
	protected string $rewrited_name = '';
	protected ?int $order = null;
	protected bool $special_authorizations = false;
	protected array $auth = [];
	protected ?int $id_parent = null;
	protected int|array $elements_number = 0;
	protected bool $allowed_to_have_childs = true;

	protected array $additional_attributes_values = [];
	protected array $additional_attributes_list = [];
	protected array $additional_attributes_categories_table_fields = [];
	protected array $additional_attributes_categories_table_options = [];

	const READ_AUTHORIZATIONS = 1;
	const WRITE_AUTHORIZATIONS = 2;
	const CONTRIBUTION_AUTHORIZATIONS = 4;
	const DUPLICATION_AUTHORIZATIONS = 8;
	const MODERATION_AUTHORIZATIONS = 16;
	const CATEGORIES_MANAGEMENT_AUTHORIZATIONS = 32;

	const ROOT_CATEGORY = 0;

	public function __construct()
	{
		$this->set_kernel_additional_attributes_list();
		$this->set_additional_attributes_list();
	}

	public function get_id(): int
	{
		return $this->id;
	}

	public function set_id(int $id): void
	{
		$this->id = $id;
	}

	public function get_name(): string
	{
		return $this->name;
	}

	public function set_name(string $name): void
	{
		$this->name = $name;
	}

	public function get_rewrited_name(): string
	{
		return $this->rewrited_name;
	}

	public function set_rewrited_name(string $rewrited_name): void
	{
		$this->rewrited_name = $rewrited_name;
	}

	public function rewrited_name_is_personalized(): bool
	{
		return $this->rewrited_name != Url::encode_rewrite($this->name);
	}

	public function get_order(): ?int
	{
		return $this->order;
	}

	public function set_order(?int $order): void
	{
		$this->order = $order;
	}

	public function increment_order(): void
	{
		$this->order++;
	}

	public function has_special_authorizations(): bool
	{
		return $this->special_authorizations;
	}

	public function set_special_authorizations(bool $special_authorizations): void
	{
		$this->special_authorizations = (bool)$special_authorizations;
	}

	public function get_authorizations(): array
	{
		return $this->auth;
	}

	public function set_authorizations(array $auth): void
	{
		$this->auth = $auth;
	}

	public function auth_is_empty(): bool
	{
		return empty($this->auth);
	}

	public function auth_is_equals(array $auth): bool
	{
		return $this->auth === $auth;
	}

	public function get_id_parent(): int
	{
		return $this->id_parent;
	}

	public function set_id_parent(int $id_parent): void
	{
		$this->id_parent = $id_parent;
	}

	public function get_elements_number(): int|array
	{
		return $this->elements_number;
	}

	public function set_elements_number(int|array $elements_number): void
	{
		$this->elements_number = $elements_number;
	}

	public function is_allowed_to_have_childs(): bool
	{
		return $this->allowed_to_have_childs;
	}

	public function check_auth(int $bit): mixed
	{
		return AppContext::get_current_user()->check_auth($this->auth, $bit);
	}

	protected function add_additional_attribute(string $id, array $parameters): void
	{
		if (isset($parameters['key']))
		{
			if ($parameters['key'] == true)
            {
				$this->additional_attributes_categories_table_options[$id] = ['type' => 'key', 'fields' => $id];
            }

			unset($parameters['key']);
		}

		$this->additional_attributes_list[$id] = ['is_url' => false];
		if (isset($parameters['is_url']))
		{
			$this->additional_attributes_list[$id]['is_url'] = $parameters['is_url'];

			unset($parameters['is_url']);
		}
		if (isset($parameters['attribute_field_parameters']))
		{
			$this->additional_attributes_list[$id]['attribute_field_parameters'] = $parameters['attribute_field_parameters'];

			unset($parameters['attribute_field_parameters']);
		}

		$this->additional_attributes_categories_table_fields[$id] = $parameters;
	}

	public function get_additional_attributes_list(): array
	{
		return $this->additional_attributes_list;
	}

	protected function set_kernel_additional_attributes_list(): void {}

	protected function set_additional_attributes_list(): void {}

	public function get_additional_attributes_categories_table_fields(): array
	{
		return $this->additional_attributes_categories_table_fields;
	}

	public function get_additional_attributes_categories_table_options(): array
	{
		return $this->additional_attributes_categories_table_options;
	}

	public function get_properties(): array
	{
		return array_merge([
			'id' => $this->get_id(),
			'name' => TextHelper::htmlspecialchars($this->get_name()),
			'rewrited_name' => TextHelper::htmlspecialchars($this->get_rewrited_name()),
			'c_order' => $this->get_order(),
			'special_authorizations' => (int)$this->has_special_authorizations(),
			'auth' => !$this->auth_is_empty() ? TextHelper::serialize($this->get_authorizations()) : '',
			'id_parent' => $this->get_id_parent()
		], $this->get_additional_properties());
	}

	protected function get_additional_properties(): array
	{
		$properties = [];

		foreach ($this->additional_attributes_list as $id => $attribute)
		{
			if ($attribute['is_url'])
			{
				if ($this->additional_attributes_values[$id] instanceof Url)
					$properties[$id] = $this->additional_attributes_values[$id]->relative();
				else
				{
					$value = new Url($this->additional_attributes_values[$id]);
					$properties[$id] = $value->relative();
				}
			}
			else
				$properties[$id] = $this->additional_attributes_values[$id];
		}

		return $properties;
	}

	public function get_additional_property(string $id): mixed
	{
		return $this->additional_attributes_values[$id];
	}

	public function set_properties(array $properties): void
	{
		$this->set_id($properties['id']);
		$this->set_name($properties['name']);
		$this->set_rewrited_name($properties['rewrited_name']);
		$this->set_order($properties['c_order']);
		$this->set_special_authorizations($properties['special_authorizations']);
		$this->set_authorizations(!empty($properties['auth']) ? TextHelper::unserialize($properties['auth']) : []);
		$this->set_id_parent($properties['id_parent']);
		$this->set_additional_properties($properties);
	}

	protected function set_additional_properties(array $properties): void
	{
		foreach ($this->additional_attributes_list as $id => $attribute)
		{
			if (isset($properties[$id]))
			{
				$this->set_additional_property($id, $properties[$id], $attribute['is_url']);
			}
		}
	}

	public function set_additional_property(string $id, mixed $value, bool $is_url = false)
	{
		if ($is_url)
        {
			$this->additional_attributes_values[$id] = new Url($value);
        }
		else
        {
			$this->additional_attributes_values[$id] = $value;
        }
	}

	public static function create_categories_table(string $table_name): void
	{
		$class_name = get_called_class();
		$object = new $class_name();

		$fields = array_merge([
			'id'                     => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'name'                   => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'rewrited_name'          => ['type' => 'string', 'length' => 250, 'default' => "''"],
			'c_order'                => ['type' => 'integer', 'length' => 11, 'unsigned' => 1, 'notnull' => 1, 'default' => 0],
			'special_authorizations' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0],
			'auth'                   => ['type' => 'text', 'length' => 65000],
			'id_parent'              => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0]
		], $object->get_additional_attributes_categories_table_fields());

		$options = array_merge([
			'primary'   => ['id'],
			'id_parent' => ['type' => 'key', 'fields' => 'id_parent']
		], $object->get_additional_attributes_categories_table_options());

		PersistenceContext::get_dbms_utils()->create_table($table_name, $fields, $options);
	}
}
?>
