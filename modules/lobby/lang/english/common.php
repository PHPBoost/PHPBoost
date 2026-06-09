<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

####################################################
#                      English                     #
####################################################

$lang['lobby.title']               = 'Welcome';
$lang['lobby.config.module.title'] = 'Homepage module configuration';
$lang['lobby.modules.position']    = 'Elements position';

// Module detection
$lang['lobby.add.modules']             = 'Add some elements';
$lang['lobby.add.modules.warning']     = '
    <p class="text-strong">:modules_list</p>
    <p>Click on <strong>Submit</strong> to add the new modules to the <strong>Homepage</strong>.</p>
';
$lang['lobby.incomplete.module.warning'] = '
    <p>The module <strong>:module_name</strong> declares the <code>lobby</code> feature but the following files are missing: <code>:missing_files</code>.</p>
    <p>This module cannot be added to Homepage until these files are present.</p>
';
$lang['lobby.new.modules']             = 'New modules detected';
$lang['lobby.no.new.module']           = '<p>New modules have been successfully added to the <strong>Homepage</strong>.</p>';
$lang['lobby.back.to.configuration']   = '<p>The <a href="' . LobbyUrlBuilder::configuration()->rel() . '">configuration</a> of the new modules is now available.</p>';
$lang['lobby.new.modules.description'] = '
    <p>New modules compatible with the <strong>Homepage</strong> have been installed and activated on the site:</p>
    <p class="text-strong">:modules_list</p>
';

// Messages
$lang['lobby.posted.in.topic']  = 'Posted in topic:';
$lang['lobby.posted.in.module'] = 'Posted in module:';

// Modules labels
$lang['lobby.see.module']          = 'See module';
$lang['lobby.display.module']      = 'Display the module';
$lang['lobby.module.carousel']     = 'Carousel';
$lang['lobby.module.anchors.menu'] = 'Homepage menu';
$lang['lobby.module.edito']        = 'Edito';
$lang['lobby.module.lastcoms']     = 'Comments';

// Anchors menu
$lang['lobby.anchors.title'] = 'Homepage menu';

// Carousel
$lang['lobby.carousel.no.alt'] = 'Carousel item';

// Contact
$lang['lobby.link.to.contact'] = 'See contact page';

// Configuration
$lang['lobby.label.module.title']      = 'Module title';
$lang['lobby.label.module.title.clue'] = 'Displays the module title on the page, breadcrumb and page tab';

$lang['lobby.menus.display']            = 'Displaying menu locations';
$lang['lobby.show.menus']               = 'Menus display is only for the homepage';
$lang['lobby.show.menu.left']           = 'Show the left menu column';
$lang['lobby.show.menu.right']          = 'Show the right menu column';
$lang['lobby.show.menu.top.central']    = 'Show the top central menu';
$lang['lobby.show.menu.bottom.central'] = 'Show the bottom central menu';
$lang['lobby.show.menu.top.footer']     = 'Show the top footer menu';

$lang['lobby.items.number']                    = 'Number of items to display';
$lang['lobby.chars.number']                    = 'Limit the number of characters';
$lang['lobby.category']                        = 'Category';
$lang['lobby.subcategories.content.displayed'] = 'Display subcategories content';

// Configuration Anchors Menu
$lang['lobby.config.anchors']       = 'Homepage menu display';
$lang['lobby.display.anchors']      = 'Display the homepage menu';
$lang['lobby.display.anchors.clue'] = 'This menu allows quick navigation within the homepage';

// Configuration Edito
$lang['lobby.config.edito']  = 'Edito display';
$lang['lobby.display.edito'] = 'Display the edito';
$lang['lobby.edito.content'] = 'Content of the edito';

// Configuration Lastcoms
$lang['lobby.config.lastcoms']  = 'Comments display';
$lang['lobby.display.lastcoms'] = 'Display the last comments';

// Configuration Carousel
$lang['lobby.config.carousel']      = 'Slideshow display';
$lang['lobby.display.carousel']     = 'Display the slideshow';
$lang['lobby.carousel.content']     = 'Content of the slideshow';
$lang['lobby.carousel.speed']       = 'Speed of picture switching (ms)';
$lang['lobby.carousel.time']        = 'Display duration of an image (ms)';
$lang['lobby.carousel.number']      = 'Number of displayed pictures';
$lang['lobby.carousel.number.clue'] = '0px < 1 image < 768px < 2 images < 1024px < choice';
$lang['lobby.carousel.auto']        = 'Autoplay';
$lang['lobby.carousel.hover']       = 'Pause on hover';
$lang['lobby.carousel.enabled']     = 'Enabled';
$lang['lobby.carousel.disabled']    = 'Disabled';
// Carousel content
$lang['lobby.carousel.description'] = 'Description of the slide';
$lang['lobby.carousel.link.url']    = 'Address of the link';
$lang['lobby.carousel.picture.url'] = 'Address of the picture';
$lang['lobby.carousel.upload']      = 'Open the file manager';
$lang['lobby.carousel.add']         = 'Add a picture';
$lang['lobby.carousel.del']         = 'Delete the slide';

// Module-specific config hints
$lang['lobby.calendar.clue'] = 'Only displays upcoming events';
$lang['lobby.flux.clue']     = 'Displays the most recent feed items from all feeds';
$lang['lobby.web.clue']      = 'Displays only the partner links';
?>
