<?php
/*##################################################
 *                             FormFieldMemberSanction.class.php
 *                            -------------------
 *   begin                : December 26, 2010
 *   copyright            : (C) 2010 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @desc
 * @package {@package}
 */
class FormFieldMemberSanction extends FormFieldSimpleSelectChoice
{
	private $lang;
	private $timestamp = 0;
	
    /**
     * @desc Constructs a FormFieldMemberSanction.
     * @param string $id Field id
     * @param string $label Field label
     * @param timestamp $value Default value
     * @param string[] $field_options Map of the field options (this field has no specific option, there are only the inherited ones)
     * @param FormFieldConstraint List of the constraints
     */
    public function __construct($id, $label, $value = 0, $field_options = array(), array $constraints = array())
    {
    	$this->load_lang();
    	$this->timestamp = $value;
        parent::__construct($id, $label, $this->get_time_value(), $this->generate_options(), $field_options, $constraints);
    }

    private function generate_options()
	{
		$sanctions = $this->get_sanctions_duration();
		$options = array();
		foreach ($sanctions as $duration => $name)
		{
			$options[] = new FormFieldSelectChoiceOption($name, $duration);
		}
		return $options;
	}
	
	private function load_lang()
	{
		$this->lang = LangLoader::get('date-common');
	}
	
	private function get_time_value()
	{
		$difference = ($this->timestamp - time());	

		$times = array_keys($this->get_sanctions_duration());
		if ($difference > 0)
		{
			for ($i = count($times) - 1; $i > 0; $i--)
			{
				$t = ceil(($times[$i] + $times[$i - 1]) /2);

				if ($difference >= $t)
				{
					return $times[$i];
				}
			}
			return 0;
		}
	}
	
	private function get_sanctions_duration()
	{
		return array(
			'0' => LangLoader::get_message('no', 'common'), 
			'60' => '1 ' . $this->lang['minute'], 
			'300' => '5 ' . $this->lang['minutes'],
			'900' => '15 ' . $this->lang['minutes'], 
			'1800' => '30 ' . $this->lang['minutes'], 
			'3600' => '1 ' . $this->lang['hour'], 
			'7200' => '2 ' . $this->lang['hours'],
			'86400' => '1 ' . $this->lang['day'], 
			'172800' => '2 ' . $this->lang['days'], 
			'604800' => '1 ' . $this->lang['week'], 
			'1209600' => '2 ' . $this->lang['weeks'], 
			'2419200' => '1 ' . $this->lang['month'], 
			'4838400' => '2 ' . $this->lang['month'],
			'326592000' => LangLoader::get_message('illimited', 'main')
		);
	}
}
?>