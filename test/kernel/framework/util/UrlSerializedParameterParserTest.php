<?php
/*##################################################
 *                     UrlSerializedParameterParserTest.class.php
 *                            -------------------
 *   begin                : February 27, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 */
class UrlSerializedParameterParserTest extends PHPBoostUnitTestCase
{
	public function test__get_parameters_empty()
	{
		$unserialized_map = array();
		$serialized_string = '';
		self::assertSerializeEquals($unserialized_map, $serialized_string);
	}

	public function test__get_parameters_a_single_simple_param()
	{
		$unserialized_map = array('p1' => 'toto');
		$serialized_string = 'p1:toto';
		self::assertSerializeEquals($unserialized_map, $serialized_string);
	}

	public function test__get_parameters_a_multiples_simple_params()
	{
		$unserialized_map = array('p1' => 'toto', 'p2' => 'tata');
		$serialized_string = 'p1:toto,p2:tata';
		self::assertSerializeEquals($unserialized_map, $serialized_string);
	}

	public function test__get_parameters_a_single_composed_param()
	{
		$unserialized_map = array('p' => array('toto', 'tata', 'titi'));
		$serialized_string = 'p:{toto,tata,titi}';
		self::assertSerializeEquals($unserialized_map, $serialized_string);
	}

	public function test__get_parameters_multiples_composed_params()
	{
		$unserialized_map = array(
            'p1' => array('toto', 'tata', 'titi'),
            'p2' => array('toto'),
            'p3' => array('shiqdh', 'dhis', 'ds')
		);
		$serialized_string = 'p1:{toto,tata,titi},p2:{toto},p3:{shiqdh,dhis,ds}';
		self::assertSerializeEquals($unserialized_map, $serialized_string);
	}

	public function test__get_parameters_multiples_composed_named_params()
	{
		$unserialized_map = array(
            'p1' => array('t1' => 'toto', 'tata', 't2' => 'titi'),
            'p2' => array('toto'),
            'p3' => array('shiqdh', 't1000' => 'dhis', 'ds')
		);
		$serialized_string = 'p1:{t1:toto,tata,t2:titi},p2:{toto},p3:{shiqdh,t1000:dhis,ds}';
		self::assertSerializeEquals($unserialized_map, $serialized_string);
	}

	public function test__get_parameters_multiples_params()
	{
		$unserialized_map = array(
            'xxx',
            'p1' => array('t1' => 'toto', 'tata', 't2' => 'titi'),
            'zz' => 'kktoto',
            'p2' => array('toto'),
            'p3' => array('shiqdh', 't1000' => 'dhis', 'ds')
		);
		$serialized_string = 'xxx,p1:{t1:toto,tata,t2:titi},zz:kktoto,p2:{toto},p3:{shiqdh,t1000:dhis,ds}';
		self::assertSerializeEquals($unserialized_map, $serialized_string);
	}

	public function test__get_parameters_a_multiples_imbricated_params()
	{
		$unserialized_map = array('p1' => array('toto', array('c' => '1', 'd' => '2', 'z4x2' => array('1', 'x' => '2', '3'))), 'p2' => 'tata');
		$serialized_string = 'p1:{toto,{c:1,d:2,z4x2:{1,x:2,3}}},p2:tata';
		self::assertSerializeEquals($unserialized_map, $serialized_string);
	}

	public function test__get_parameters_a_escaped_chars()
	{
		$unserialized_map = array('p1' => array('t{o},\\t:o', array('c' => '1', 'd' => '2', 'z4x2' => array('1', 'x' => '2', '3'))), 'p2' => 'tata');
		$serialized_string = 'p1:{t\\{o\\}\\,\\\\t\\:o,{c:1,d:2,z4x2:{1,x:2,3}}},p2:tata';
		self::assertSerializeEquals($unserialized_map, $serialized_string);
	}

	private static function assertSerializeEquals($unserialized_map, $serialized_string)
	{
		$uspp = new UrlSerializedParameterParser($serialized_string);
		self::assertEquals($unserialized_map, $uspp->get_parameters());
	}
}

?>
