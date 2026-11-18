<?php
/**
 * @package     PHPBoost
 * @subpackage  Cache
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 18
 * @since       PHPBoost 3.0 - 2010 08 10
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ExtendedFieldsCache implements CacheData
{
	private $extended_fields = [];

	public function synchronize(): void
    {
		$this->extended_fields = [];
		$querier = PersistenceContext::get_querier();

		$result = $querier->select_rows(DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST, ['*'], 'ORDER BY position');
		while ($row = $result->fetch())
		{
			$auth = TextHelper::unserialize($row['auth']);

			$fixed_possible_values = preg_replace_callback( '!s:(\d+):"(.*?)";!u', function($match) {
				return ($match[1] == TextHelper::strlen($match[2])) ? $match[0] : 's:' . TextHelper::strlen($match[2]) . ':"' . $match[2] . '";';
			}, $row['possible_values']);
			$possible_values = TextHelper::unserialize($fixed_possible_values);

			$this->extended_fields[$row['id']] = [
				'id' => $row['id'],
				'position' => !empty($row['position']) ? $row['position'] : '',
				'name' => !empty($row['name']) ? $row['name'] : '',
				'field_name' => !empty($row['field_name']) ? $row['field_name'] : '',
				'description' => !empty($row['description']) ? $row['description'] : '',
				'field_type' => !empty($row['field_type']) ? $row['field_type'] : '',
				'possible_values' => !empty($possible_values) ? $possible_values : [],
				'default_value' => !empty($row['default_value']) ? $row['default_value'] : '',
				'required' => !empty($row['required']) ? (bool)$row['required'] : false,
				'display' => !empty($row['display']) ? (bool)$row['display'] : false,
				'freeze' => !empty($row['freeze']) ? (bool)$row['freeze'] : false,
				'regex' => !empty($row['regex']) ? $row['regex'] : '',
				'auth' => !empty($auth) ? $auth : []
			];
		}
		$result->dispose();
	}

	public function get_extended_fields(): array
	{
		return $this->extended_fields;
	}

	public function get_exist_fields(): mixed
	{
		return (count($this->extended_fields) > 0);
	}

	public function get_extended_field(string $id): mixed
	{
		if (isset($this->extended_fields[$id]))
		{
			return $this->extended_fields[$id];
		}
		return null;
	}

	public function get_extended_field_by_field_name(mixed $field_name): mixed
	{
		$field = null;

		foreach ($this->extended_fields as $id => $field_options)
		{
			if ($field_options['field_name'] == $field_name)
			{
				$field = $this->extended_fields[$id];
			}
		}

		return $field;
	}

	public function get_websites_or_emails_extended_field_field_types(): array
	{
		$list = [];

		foreach ($this->extended_fields as $id => $field_options)
		{
			if ($field_options['regex'] == 4 || $field_options['regex'] == 5)
			{
				$list[] = $field_options['field_name'];
			}
		}

		return $list;
	}

	/**
	 * Loads and returns the extended_fields cached data.
	 * @return ExtendedFieldsCache The cached data
	 */
	public static function load(): ExtendedFieldsCache
	{
		return CacheManager::load(self::class, 'kernel', 'extended-fields');
	}

	/**
	 * Invalidates the current extended_fields cached data.
	 */
	public static function invalidate(): void
	{
		CacheManager::invalidate('kernel', 'extended-fields');
	}
}
?>
