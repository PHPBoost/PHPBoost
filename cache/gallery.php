<?php
global $CONFIG_GALLERY;
$CONFIG_GALLERY['width'] = 145;
$CONFIG_GALLERY['height'] = 145;
$CONFIG_GALLERY['width_max'] = 800;
$CONFIG_GALLERY['height_max'] = 600;
$CONFIG_GALLERY['weight_max'] = 1024;
$CONFIG_GALLERY['quality'] = 80;
$CONFIG_GALLERY['trans'] = 40;
$CONFIG_GALLERY['logo'] = 'logo.jpg';
$CONFIG_GALLERY['activ_logo'] = 1;
$CONFIG_GALLERY['d_width'] = 5;
$CONFIG_GALLERY['d_height'] = 5;
$CONFIG_GALLERY['nbr_column'] = 4;
$CONFIG_GALLERY['nbr_pics_max'] = 16;
$CONFIG_GALLERY['note_max'] = 5;
$CONFIG_GALLERY['activ_title'] = 1;
$CONFIG_GALLERY['activ_com'] = 1;
$CONFIG_GALLERY['activ_note'] = 1;
$CONFIG_GALLERY['display_nbrnote'] = 1;
$CONFIG_GALLERY['activ_view'] = 1;
$CONFIG_GALLERY['activ_user'] = 1;
$CONFIG_GALLERY['limit_member'] = 10;
$CONFIG_GALLERY['limit_modo'] = 25;
$CONFIG_GALLERY['display_pics'] = 3;
$CONFIG_GALLERY['scroll_type'] = 0;
$CONFIG_GALLERY['nbr_pics_mini'] = 2;
$CONFIG_GALLERY['speed_mini_pics'] = 6;
$CONFIG_GALLERY['auth_root'] = array (
  'r-1' => 1,
  'r0' => 3,
  'r1' => 7,
  'r2' => 7,
);

global $CAT_GALLERY;
$CAT_GALLERY['1']['id_left'] = '1';
$CAT_GALLERY['1']['id_right'] = '2';
$CAT_GALLERY['1']['level'] = '0';
$CAT_GALLERY['1']['name'] = 'test';
$CAT_GALLERY['1']['aprob'] = '1';
$CAT_GALLERY['1']['auth'] = array (
  'r-1' => 1,
  'r0' => 3,
  'r1' => 7,
  'r2' => 7,
);

global $_array_random_pics;
$_array_random_pics = array(array(
'id' => '5',
'name' => '',
'path' => 'eaed75f3228268d7c5070c0e46ac4e15.jpg',
'width' => 145,
'height' => 19,
'idcat' => '1',
'auth' => 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;s:2:"r2";i:7;}'),
array(
'id' => '4',
'name' => 'Test',
'path' => 'bfe750bdfb36ccec47f2255470f6a9ba.jpg',
'width' => 113,
'height' => 145,
'idcat' => '1',
'auth' => 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;s:2:"r2";i:7;}'),
array(
'id' => '6',
'name' => 'jghjh',
'path' => 'f670a9459d82dc702d42b1e37b0ce336.jpg',
'width' => 145,
'height' => 109,
'idcat' => '1',
'auth' => 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;s:2:"r2";i:7;}'),
);
?>