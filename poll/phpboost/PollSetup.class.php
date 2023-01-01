<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 08
 * @since       PHPBoost 6.0 - 2020 05 14
*/

class PollSetup extends DefaultModuleSetup
{
	protected function set_additional_tables()
	{
		$this->add_additional_table(PREFIX . 'poll_voters');
	}

	protected function create_additional_tables()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'poll_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'voter_user_id' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'voter_ip' => array('type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''"),
			'vote_timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id')
		);
		self::$dbms_utils->create_table(PREFIX . 'poll_voters', $fields, $options);
		self::$db_querier->insert(PREFIX . 'poll_voters', array(
			'id' => 1,
			'poll_id' => 1,
			'voter_user_id' => -1,
			'voter_ip' => '0.0.0.0',
			'vote_timestamp' => time()
		));
	}
}
?>
