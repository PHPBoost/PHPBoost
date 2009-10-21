<?php

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/begin.php';
require_once PATH_TO_ROOT . '/kernel/header.php';

import('/blog/model/blog');
import('mvc/model/SQLDAO');

require_once PATH_TO_ROOT . '/kernel/footer.php';

?>