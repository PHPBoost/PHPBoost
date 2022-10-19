<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 6.0 - 2020 05 14
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class PollItem extends RichItem
{
	const MODULE_ID = 'poll';

	const DEFAULT_VALUE_QUESTION = '';
	const DEFAULT_VALUE_ANSWERS_TYPE = '1';
	const DEFAULT_VALUE_ANSWERS = array();
	const DEFAULT_VALUE_VOTES = array();
	const DEFAULT_VALUE_VOTES_NUMBER = 0;
	const DEFAULT_VALUE_COUNTDOWN_DISPLAY = 2;

	const COUNTDOWN_DISPLAY_WITHOUT_S = '2';
	const COUNTDOWN_DISPLAY_WITH_S = '1';
	const COUNTDOWN_NO_DISPLAY = '0';

	public $current_user_id;
	public $lang;

	public function __construct()
	{
		$this->content_field_enabled = false;
		$this->summary_field_enabled = false;
		$this->lang = LangLoader::get_all_langs(self::MODULE_ID);
		$this->current_user_id = AppContext::get_current_user()->get_id();

		parent::__construct(self::MODULE_ID);
	}

	protected function set_additional_attributes_list()
	{
		$this->add_additional_attribute('question', array('type' => 'text', 'length' => 65000, 'notnull' => 1, 'attribute_pre_content_field_parameters' => array(
			'field_class' => 'FormFieldRichTextEditor',
			'label'       => $this->lang['poll.form.question'],
			'value'       => self::DEFAULT_VALUE_QUESTION,
			'options'     => array('required' => true)
			)
		));

		$this->add_additional_attribute('answers_type', array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 1, 'attribute_pre_content_field_parameters' => array(
			'field_class'    => 'FormFieldRadioChoice',
			'label'          => $this->lang['poll.form.answers.type'],
			'value'          => self::DEFAULT_VALUE_ANSWERS_TYPE,
			'options'        => array(
									new FormFieldRadioChoiceOption($this->lang['poll.form.single'], '1'),
									new FormFieldRadioChoiceOption($this->lang['poll.form.multiple'], '2')
								),
			'field_options'  => array('required' => true, 'class' => 'inline-radio')
			)
		));

		$this->add_additional_attribute('answers', array('type' => 'text', 'length' => 65000, 'notnull' => 1, 'is_array' => true, 'attribute_pre_content_field_parameters' => array(
			'field_class' => 'FormFieldPossibleValues',
			'label'       => $this->lang['poll.form.answers'],
			'value'       => self::DEFAULT_VALUE_ANSWERS,
			'options'     => array('required' => true, 'unique_input_value' => true, 'min_input' => 2, 'display_default' => false, 'placeholder' => $this->lang['poll.form.answer.placeholder'])
			)
		));

		$this->add_additional_attribute('votes', array('type' => 'text', 'length' => 65000, 'notnull' => 1));

		$this->add_additional_attribute('votes_number', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));

		$this->add_additional_attribute('countdown_display', array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 2));

		$this->add_additional_attribute('close_poll', array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0));
	}

	public function get_question()
	{
		return $this->get_additional_property('question');
	}

	public function set_question($value)
	{
		$this->set_additional_property('question', $value);
	}

	public function get_answers_type()
	{
		return $this->get_additional_property('answers_type');
	}

	public function set_answers_type($value)
	{
		$this->set_additional_property('answers_type', $value);
	}

	public function get_answers()
	{
		return $this->get_additional_property('answers');
	}

	public function get_answers_list()
	{
		$answers_list = array();
		foreach ($this->get_answers() as $answer)
		{
			$answers_list[] = stripslashes($answer['title']);
		}
		return $answers_list;
	}

	public function get_votes()
	{
		return TextHelper::unserialize($this->get_additional_property('votes'));
	}

	public function has_votes()
	{
		return $this->get_votes_number() > 0;
	}

	public function set_votes(array $value)
	{
		$value = TextHelper::serialize($value);
		$this->set_additional_property('votes', $value);
	}

	public function get_votes_number()
	{
		return $this->get_additional_property('votes_number');
	}

	public function set_votes_number(int $value)
	{
		$this->set_additional_property('votes_number', $value);
	}

	public function get_close_poll()
	{
		return $this->get_additional_property('close_poll');
	}

	public function is_closed()
	{
		return $this->get_close_poll() > 0;
	}

	public function set_close_poll(int $value)
	{
		$this->set_additional_property('close_poll', $value);
	}

	public function get_mini_module_map()
	{
		return $this->load_conf_parameters()->get_mini_module_selected_items();
	}

	public function is_in_mini_module_map()
	{
		return in_array($this->get_id(), $this->get_mini_module_map());
	}

	public function set_item_in_mini_module_map()
	{
		if (!$this->is_in_mini_module_map())
		{
			$update_mini_module_map = $this->get_mini_module_map();
			$update_mini_module_map[] = $this->get_id();

			$this->load_conf_parameters()->set_mini_module_selected_items(array_unique($update_mini_module_map));
			$this->save_conf_parameters();
		}
	}

	public function unset_item_in_mini_module_map()
	{
		if ($this->is_in_mini_module_map())
		{
			$update_mini_module_map = $this->get_mini_module_map();
			$item_id_key = array_search($this->get_id(), $update_mini_module_map);
			unset($update_mini_module_map[$item_id_key]);

			$this->load_conf_parameters()->set_mini_module_selected_items($update_mini_module_map);
			$this->save_conf_parameters();
		}
	}

	public function get_countdown_display()
	{
		return $this->get_additional_property('countdown_display');
	}

	public function set_countdown_display(int $value)
	{
		$this->set_additional_property('countdown_display', $value);
	}

	public function user_has_vote_authorization()
	{
		return PollAuthorizationsService::check_authorizations()->vote();
	}

	public function user_has_voted()
	{
		return $this->has_votes() ? ItemsService::get_items_manager()->user_has_voted($this->current_user_id, $this->get_id()) : false;
	}

	public function user_is_empowered_to_vote()
	{
		return !$this->is_closed()
				&& $this->is_published()
				&& $this->user_has_vote_authorization()
				&& !$this->user_has_voted();
	}
	// evolution
	public function get_voter_id()
	{
		return ItemsService::get_items_manager()->get_voter($this->get_id(), $this->current_user_id)['id'];
	}
	// evolution
	public function get_voter_user_id()
	{
		return ItemsService::get_items_manager()->get_voter($this->get_id(), $this->current_user_id)['voter_id'];
	}
	// evolution
	public function get_voter_ip()
	{
		return ItemsService::get_items_manager()->get_voter($this->get_id(), $this->current_user_id)['voter_ip'];
	}
	// evolution
	public function get_voter_vote_date()
	{
		return ItemsService::get_items_manager()->get_voter($this->get_id(), $this->current_user_id)['vote_timestamp'];
	}

	protected function default_properties()
	{
		$this->set_votes(self::DEFAULT_VALUE_VOTES);
		$this->set_votes_number(self::DEFAULT_VALUE_VOTES_NUMBER);
		$this->set_close_poll(FormFieldCheckbox::UNCHECKED);
		$this->set_countdown_display(self::DEFAULT_VALUE_COUNTDOWN_DISPLAY);
	}

	public function load_conf_parameters()
	{
		return $this->poll_module()->get_configuration()->get_configuration_parameters();
	}

	public function save_conf_parameters()
	{
		$configuration_class_name = $this->poll_module()->get_configuration()->get_configuration_name();
		$configuration_class_name::save(self::MODULE_ID);
	}

	public function poll_module()
	{
		return ModulesManager::get_module(self::MODULE_ID);
	}

	protected function get_additional_sorting_fields()
	{
		return array('close_poll' => array('database_field' => 'close_poll', 'label' => $this->lang['poll.sorting.field.closed'], 'icon' => 'fas fa-window-close'));
	}

	public function get_additional_template_vars()
	{
		return array('C_COMPLETED' => $this->is_closed());
	}
}
?>
