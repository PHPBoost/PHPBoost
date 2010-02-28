<?php
/*##################################################
 *                     UrlSerializedParameterTest.class.php
 *                            -------------------
 *   begin                : February 27, 2010
 *   copyright            : (C) 2010 Loïc Rouchon
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
 * @author Loïc Rouchon <loic.rouchon@phpboost.com>
 * @package util
 */
class UrlSerializedParameterTest extends PHPBoostUnitTestCase
{
	private static $params_name = 'testusp';

    public function test__get_parameters_empty()
    {
        $usp = new UrlSerializedParameter(self::$params_name);
        $parameters = $usp->get_parameters();
        self::assertEquals(array(), $parameters);
    }
    
    public function test__get_parameters_a_single_simple_param()
    {
        $this->set_serialized_parameters_string('p1:toto');
        $usp = new UrlSerializedParameter(self::$params_name);
        $parameters = $usp->get_parameters();
        self::assertEquals(array('p1' => 'toto'), $parameters);
    }
    
    public function test__get_parameters_a_multiples_simple_params()
    {
        $this->set_serialized_parameters_string('p1:toto,p2:tata');
        $usp = new UrlSerializedParameter(self::$params_name);
        $parameters = $usp->get_parameters();
        self::assertEquals(array('p1' => 'toto', 'p2' => 'tata'), $parameters);
    }
    
    public function test__get_parameters_a_single_composed_param()
    {
        $this->set_serialized_parameters_string('p:{toto,tata,titi}');
        $usp = new UrlSerializedParameter(self::$params_name);
        $parameters = $usp->get_parameters();
        self::assertEquals(array('p' => array('toto', 'tata', 'titi')), $parameters);
    }
    
    public function test__get_parameters_multiples_composed_params()
    {
        $this->set_serialized_parameters_string('p1:{toto,tata,titi},p2:{toto},p3:{shiqdh,dhis,ds}');
        $usp = new UrlSerializedParameter(self::$params_name);
        $parameters = $usp->get_parameters();
		$expected = array(
            'p1' => array('toto', 'tata', 'titi'),
            'p2' => array('toto'),
            'p3' => array('shiqdh', 'dhis', 'ds')
		);
        self::assertEquals($expected, $parameters);
    }
    
    public function test__get_parameters_multiples_composed_named_params()
    {
        $this->set_serialized_parameters_string('p1:{t1:toto,tata,t2:titi},p2:{toto},p3:{shiqdh,t1000:dhis,ds}');
        $usp = new UrlSerializedParameter(self::$params_name);
        $parameters = $usp->get_parameters();
        $expected = array(
            'p1' => array('t1' => 'toto', 'tata', 't2' => 'titi'),
            'p2' => array('toto'),
            'p3' => array('shiqdh', 't1000' => 'dhis', 'ds')
        );
        self::assertEquals($expected, $parameters);
    }
    
    public function test__get_parameters_multiples_params()
    {
        $this->set_serialized_parameters_string('xxx,p1:{t1:toto,tata,t2:titi},zz:kktoto,p2:{toto},p3:{shiqdh,t1000:dhis,ds}');
        $usp = new UrlSerializedParameter(self::$params_name);
        $parameters = $usp->get_parameters();
        $expected = array(
            'xxx',
            'p1' => array('t1' => 'toto', 'tata', 't2' => 'titi'),
            'zz' => 'kktoto',
            'p2' => array('toto'),
            'p3' => array('shiqdh', 't1000' => 'dhis', 'ds')
        );
        self::assertEquals($expected, $parameters);
    }
    
    public function test__get_parameters_a_multiples_imbricated_params()
    {
        $this->set_serialized_parameters_string('p1:{toto,{c:1,d:2,42:{1,x:2,3}}},p2:tata');
        $usp = new UrlSerializedParameter(self::$params_name);
        $parameters = $usp->get_parameters();
        $expected = array('p1' => array('toto', array('c' => '1', 'd' => '2', '42' => array('1', 'x' => '2', '3'))), 'p2' => 'tata');
        self::assertEquals($expected, $parameters);
    }
    
    public function test__get_parameters_a_escaped_chars()
    {
        $this->set_serialized_parameters_string('p1:{t\\{o\\}\\,\\\\t\\:o,{c:1,d:2,42:{1,x:2,3}}},p2:tata');
        $usp = new UrlSerializedParameter(self::$params_name);
        $parameters = $usp->get_parameters();
        $expected = array('p1' => array('t{o},\\t:o', array('c' => '1', 'd' => '2', '42' => array('1', 'x' => '2', '3'))), 'p2' => 'tata');
        self::assertEquals($expected, $parameters);
    }
	
	private function set_serialized_parameters_string($params)
	{
		AppContext::get_request()->set_getvalue(self::$params_name, $params);
	}
}

?>
