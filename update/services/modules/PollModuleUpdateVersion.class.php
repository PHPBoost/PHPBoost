<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 06
 * @since       PHPBoost 6.0 - 2021 04 06
*/

class PollModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('poll');

		$this->content_tables = array(PREFIX . 'poll');
		self::$delete_old_files_list = array(
			'/lang/english/poll_english.php',
			'/lang/french/poll_french.php',
			'/phpboost/PollExtensionPointProvider.class.php',
			'/phpboost/PollHomePageExtensionPoint.class.php',
			'/phpboost/PollTreeLinks.class.php',
			'/templates/admin_poll_add.tpl',
			'/templates/admin_poll_config.tpl',
			'/templates/admin_poll_management.tpl',
			'/templates/admin_poll_management2.tpl',
			'/templates/poll.tpl',
			'/templates/poll_mini.tpl',
			'/util/PollUrlBuilder.class.php',
			'/admin_poll.php',
			'/admin_poll_add.php',
			'/admin_poll_config.php',
			'/poll.php',
			'/poll_begin.php',
		);
		
		if (in_array(PREFIX . 'poll_ip', $this->tables_list))
		{
			if (in_array(PREFIX . 'poll_voters', $this->tables_list))
				$this->db_utils->drop(array(PREFIX . 'poll_voters'));
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'poll_ip RENAME ' . PREFIX . 'poll_voters');
		}

		if (!in_array(PREFIX . 'poll_cats', $this->tables_list))
			RichCategory::create_categories_table(PREFIX . 'poll_cats');

		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'poll',
				'columns' => array(
					'question'  => 'question TEXT',
					'type'      => 'answers_type INT(11) NOT NULL DEFAULT 1',
					'user_id'   => 'author_user_id INT(11) NOT NULL DEFAULT 0',
					'timestamp' => 'creation_date INT(11) NOT NULL DEFAULT 0',
					'archive'   => 'close_poll INT(1) NOT NULL DEFAULT 0',
					'visible'   => 'published INT(1) NOT NULL DEFAULT 0',
					'start'     => 'publishing_start_date INT(11) NOT NULL DEFAULT 0',
					'end'       => 'publishing_end_date INT(11) NOT NULL DEFAULT 0',
				)
			),
			array(
				'table_name' => PREFIX . 'poll_voters',
				'columns' => array(
					'ip'        => 'voter_ip VARCHAR(50) NOT NULL DEFAULT ""',
					'user_id'   => 'voter_user_id INT(11) DEFAULT 0',
					'idpoll'    => 'poll_id INT(11) NOT NULL DEFAULT 0',
					'timestamp' => 'vote_timestamp INT(11) NOT NULL DEFAULT 0',
				)
			)
		);

		$this->database_columns_to_add = array(
			array(
				'table_name' => PREFIX . 'poll',
				'columns' => array(
					'title'              => array('type' => 'string',  'length' => 255, 'notnull' => 1, 'default' => "''"),
					'rewrited_title'     => array('type' => 'string',  'length' => 255, 'default' => "''"),
					'id_category'        => array('type' => 'integer', 'length' => 11,  'notnull' => 1, 'default' => 0),
					'author_custom_name' => array('type' => 'string',  'length' => 255, 'default' => "''"),
					'update_date'        => array('type' => 'integer', 'length' => 11,  'notnull' => 1, 'default' => 0),
					'views_number'       => array('type' => 'integer', 'length' => 11,  'default' => 0),
					'thumbnail'          => array('type' => 'string',  'length' => 255, 'notnull' => 1, 'default' => "''"),
					'votes_number'       => array('type' => 'integer', 'length' => 11,  'notnull' => 1, 'default' => 0),
					'countdown_display'  => array('type' => 'integer', 'length' => 1,   'notnull' => 1, 'default' => 2),
				)
			)
		);

		$this->database_keys_to_add = array(
			array(
				'table_name' => PREFIX . 'poll',
				'keys' => array(
					'title'       => true,
					'id_category' => false
				)
			)
		);
	}

	protected function execute_module_specific_changes()
	{
		// Set update_date to creation_date if update_date = 0, title and count actual poll votes
		$result = $this->querier->select('SELECT id, update_date, creation_date, question, answers, votes
			FROM ' . PREFIX . 'poll'
		);

		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX . 'poll', array('title' => $row['question'], 'rewrited_title' => Url::encode_rewrite($row['question'])), 'WHERE title = \'\' AND id = :id', array('id' => $row['id']));
			$this->querier->update(PREFIX . 'poll', array('update_date' => $row['creation_date']), 'WHERE update_date = 0 AND id = :id', array('id' => $row['id']));
			
			if (preg_match('/|/', $row['answers']) || preg_match('/|/', $row['votes']))
			{
				$answers_titles = explode('|', $row['answers']);
				$answers = array();
				foreach ($answers_titles as $answer)
				{
					$answers[Url::encode_rewrite($answer)] = array(
						'is_default' => false,
						'title' => addslashes($answer)
					);
				}

				$votes = array();
				$votes_number = 0;
				foreach (explode('|', $row['votes']) as $id => $vote)
				{
					$votes[$answers_titles[$id]] = (int)$vote;
					$votes_number++;
				}
				$this->querier->update(PREFIX . 'poll', array('answers' => TextHelper::serialize($answers), 'votes_number' => $votes_number, 'votes' => TextHelper::serialize($votes)), 'WHERE id = :id', array('id' => $row['id']));
			}
		}
		$result->dispose();
	}
}
?>
