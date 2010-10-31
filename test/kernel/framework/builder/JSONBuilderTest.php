<?php
/*##################################################
 *                           JSONBuilder.class.php
 *                            -------------------
 *   begin                : October 31 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : horn@phpboost.com
 *
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
 * @desc
 * @author Loic Rouchon horn@phpboost.com
 */
class JSONBuilderTest extends PHPBoostUnitTestCase
{
	public function test_empty_array()
	{
		$object = array();
		$json = "{}";
		$this->assert_json($json, $object);
	}

	public function test_list()
	{
		$object = array(1, 2, 3);
		$json = "{1,2,3}";
		$this->assert_json($json, $object);
	}

	public function test_map()
	{
		$object = array('1' => 'coucou', '2' => 'toto', 'tata' => 'titi');
		$json = "{'1':'coucou','2':'toto','tata':'titi'}";
		$this->assert_json($json, $object);
	}

	public function test_types()
	{
		$object = array(true, 0, 1, 1.42, 'string with \' slashes');
		$json = "{true,0,1,1.42,'string with \' slashes'}";
		$this->assert_json($json, $object);
	}

	public function test_string()
	{
		$object = array('string with \' slashes, quotes ",
		lines breaks, ...');
		$json = "{'string with \' slashes, quotes &quot;,\\n		lines breaks, ...'}";
		$this->assert_json($json, $object);
	}



	public function test_imbrications()
	{
		$object = array(
			'Person' => array(
				'firstName' => 'John',
				'lastName' => 'Smith',
				'age' => 25,
				'Address' => array(
					'streetAddress' => '21 2nd Street',
					'city' => 'New York',
					'state' => 'NY',
					'postalCode' => '10021'
				),
				'PhoneNumbers' => array(
					'home' => '212 555-1234',
					'fax' => '646 555-4567'
				)
			)
		);
		$json = "{'Person':{'firstName':'John','lastName':'Smith','age':25,'Address':" .
			"{'streetAddress':'21 2nd Street','city':'New York','state':'NY','postalCode':'10021'}," .
     		"'PhoneNumbers':{'home':'212 555-1234','fax':'646 555-4567'}}}";
		$this->assert_json($json, $object);
	}

	private function assert_json($expected_json, array $array)
	{
		$this->assertEquals($expected_json, JSONBuilder::build($array));
	}
}
?>