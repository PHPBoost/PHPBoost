<?php
global $CONFIG_USER, $CONTRIBUTION_PANEL_UNREAD, $ADMINISTRATOR_ALERTS;
$CONFIG_USER['activ_register'] = 1;
$CONFIG_USER['msg_mbr'] = '';
$CONFIG_USER['msg_register'] = '';
$CONFIG_USER['activ_mbr'] = 1;
$CONFIG_USER['verif_code'] = 1;
$CONFIG_USER['verif_code_difficulty'] = 0;
$CONFIG_USER['delay_unactiv_max'] = 30;
$CONFIG_USER['force_theme'] = 0;
$CONFIG_USER['activ_up_avatar'] = 1;
$CONFIG_USER['width_max'] = 120;
$CONFIG_USER['height_max'] = 120;
$CONFIG_USER['weight_max'] = 20;
$CONFIG_USER['activ_avatar'] = 1;
$CONFIG_USER['avatar_url'] = 'no_avatar.jpg';
$CONTRIBUTION_PANEL_UNREAD = array (
  'r2' => 0,
  'r1' => 0,
  'r0' => 0,
);
$ADMINISTRATOR_ALERTS = array (
  'unread' => '0',
  'all' => '0',
);
?>