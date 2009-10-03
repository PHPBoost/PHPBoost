<?php

define('PATH_TO_ROOT', '../../../../..');
require_once PATH_TO_ROOT . '/test/header.php';

import('mvc/model/sql_dao');

class UTSQLDAO extends PHPBoostUnitTestCase {

    private $result_format_regex = '`[0-9]+\.[0-9]{%d}`';
    
    public function test()
    {
        $bench = new Bench();
        $this->check_methods($bench);
    }
}
?>