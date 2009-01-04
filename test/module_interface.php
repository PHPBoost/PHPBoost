<?php

require_once('../kernel/begin.php');
require_once('../kernel/header.php');
require_once('../kernel/framework/util/menu.class.php');

import('modules/modules_discovery_service');
$m = new ModulesDiscoveryService();
$d = $m->get_module('download');

print_r($d->functionnalities);

require_once('../kernel/footer.php');


?>