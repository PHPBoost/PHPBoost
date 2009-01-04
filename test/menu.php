<?php

require_once('../kernel/begin.php');
require_once('../kernel/header.php');
import('core/menu_service');
import('menu/links/links_menu');
$auth = array('r2' => 1, '1' => 0);
$menu = new LinksMenu('Google', 'http://www.google.com', '', VERTICAL_SCROLLING_MENU);
$menu1 = new LinksMenu('Menu 1', 'http://www.google.com');
$menu2 = new LinksMenu('Menu 2', 'http://www.google.com');
$menu3 = new LinksMenu('Menu 3', 'http://www.google.com');
$menu4 = new LinksMenu('Menu 4', 'http://www.google.com');
$menu5 = new LinksMenu('Menu 5', 'http://www.google.com');
$menu6 = new LinksMenu('Menu 6', 'http://www.google.com');
$menu7 = new LinksMenu('Menu 7', 'http://www.google.com');
$element1 = new LinksMenuLink('Element 1', '/forum/index.php');
$element1->set_auth($auth);
$element2 = new LinksMenuLink('Element 2', 'http://www.google.com');

$aelts0 = array($element1, $element2, $element1, $element1);
$menu7->add_array($aelts0);
$aelts1 = array($menu7, $element1, $element1, $element1, $element1);
$menu6->add_array($aelts1);
$aelts2 = array($menu6, $element1, $element1, $element2, $element2);
$aelts3 = array($element1, $element1, $element2, $element2);
$aelts4 = array($element2, $element2, $element1, $element1);
$aelts5 = array($element2, $element2, $element2, $element2);

$menu1->add_array($aelts1);
$menu2->add_array($aelts2);
$menu3->add_array($aelts3);
$menu4->add_array($aelts4);
$menu5->add_array($aelts5);

$amenu = array($menu1, $menu2, $menu3, $menu4, $menu5, $element1, $element2);
$menu->add_array($amenu);
echo '<hr />';
//
////echo '<pre>'; print_r(MenuService::get_menu_list()); echo '</pre>';
////echo '<pre>'; print_r(MenuService::get_menu_list(LINKS_MENU__CLASS)); echo '</pre>';
////echo '<pre>'; print_r(MenuService::get_menu_list(MINI_MENU__CLASS, BLOCK_POSITION__TOP_CENTRAL, MENU_ENABLED)); echo '</pre>';
////echo '<pre>'; print_r(MenuService::get_menus_map()); echo '</pre>';
//
echo 'Saving Menu...';
MenuService::save($menu);
echo '<hr />';
//
////echo 'Loading Menu: ' . ( ($object = MenuService::load('Google')) !== false ? 'true' : 'false') . '<hr />';
////echo '<pre>'; print_r($object); echo '</pre>';
////echo 'Deleting Menu: ' . (MenuService::delete($object) ? 'true' : 'false') . '<hr />';
//
////echo '<hr />' . $menu->display() . '<br /><br /><hr />';
//
////echo '<textarea id="debug" rows="20" cols="160">' . $menu->display() . '</textarea>';
//
////echo '<pre>'; print_r($object); echo '</pre>';
////echo 'Moving Menu... ' . $object->get_title();
////MenuService::move($object, BLOCK_POSITION__TOP_CENTRAL);
////echo '<hr />';
//$a = $MENUS;

//include('../cache/menuss.php');
//foreach ($MENUS as $zone)
//    echo $zone;
//$MENUS= $a;

$content = new ContentMenu('Test de Content Menu');
$content->set_content('<strong>Test <i>de</i> contenu</strong>...');
//MenuService::save($content);
//echo $content->cache_export();

//$search_mini = new ModuleMiniMenu('Search');
//echo $search_mini->cache_export();
require_once('../kernel/footer.php');


?>