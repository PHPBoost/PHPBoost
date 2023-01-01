<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 01
 * @since       PHPBoost 4.1 - 2015 02 10
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                       English                    #
####################################################

$lang['gallery.module.title']        = 'Gallery';
$lang['gallery.config.module.title'] = 'Gallery module configuration';
$lang['gallery.random.items']        = 'Random pictures';
$lang['gallery.no.random.item']      = 'No random pictures';

// Configuration
$lang['gallery.max.height']                   = 'Picture max height';
$lang['gallery.max.height.clue']              = '600px by default';
$lang['gallery.max.width']                    = 'Picture max width';
$lang['gallery.max.width.clue']               = '800px by default';
$lang['gallery.thumbnails.max.height']        = 'Thumbnails max height';
$lang['gallery.thumbnails.max.width']         = 'Thumbnails max width';
$lang['gallery.thumbnails.max.size.clue']     = '150px by default';
$lang['gallery.weight.max']                   = 'Picture max weight';
$lang['gallery.weight.max.clue']              = '1024 kb by default';
$lang['gallery.thumbnails.quality']           = 'Thumbnails quality';
$lang['gallery.thumbnails.quality.clue']      = '80% by default';
$lang['gallery.items.per.row.clue']           = '4 by default - Pictures and categories';
$lang['gallery.items.per.page']               = 'Number of pictures per page';
$lang['gallery.item.resizing.mode']           = 'Picture resizing mode';
$lang['gallery.new.page']                     = 'New page';
$lang['gallery.resizing']                     = 'Resizing';
$lang['gallery.popup']                        = 'Popup';
$lang['gallery.popup.full.screen']            = 'Full screen';
$lang['gallery.enable.title']                 = 'Activate the titles';
$lang['gallery.enable.title.clue']            = 'Picture name above thumbnail';
$lang['gallery.enable.contributor']           = 'Activate contributor';
$lang['gallery.enable.contributor.clue']      = 'Contributor name is displayed under the thumbnail';
$lang['gallery.enable.views.counter']         = 'Activate views counter';
$lang['gallery.enable.views.counter.clue']    = 'Picture views number is displayed under its thumbnail';
$lang['gallery.enable.notes.number']          = 'Display number of rating';
$lang['gallery.items.protection']             = 'Picture protection';
$lang['gallery.enable.logo']                  = 'Activate logo';
$lang['gallery.enable.logo.clue']             = 'Inlay on the picture';
$lang['gallery.logo.url']                     = 'Logo url';
$lang['gallery.logo.url.clue']                = 'Put into /gallery';
$lang['gallery.horizontal.distance']          = 'Horizontal distance';
$lang['gallery.vertical.distance']            = 'Vertical distance';
$lang['gallery.from.bottom.right.clue']       = 'From the bottom right corner';
$lang['gallery.logo.trans']                   = 'Logo transparency';
$lang['gallery.logo.trans.clue']              = '40% by default, only for jpg logo';
$lang['gallery.items.upload']                 = 'Picture upload';
$lang['gallery.members.items.number']         = 'Max number of pictures';
$lang['gallery.members.items.number.clue']    = 'Members (unlimited if guests are authorized)';
$lang['gallery.moderators.items.number']      = 'Max number of pictures';
$lang['gallery.moderators.items.number.clue'] = 'Moderator';
$lang['gallery.mini.module']                  = 'Mini module';
$lang['gallery.thumbnails.number']            = 'Number of thumbnails';
$lang['gallery.scroll.speed']                 = 'Scrolling speed';
$lang['gallery.scroll.speed.clue']            = 'Max speed: 10';
$lang['gallery.scroll.type']                  = 'Scrolling type';
$lang['gallery.no.scroll']                    = 'No scrolling';
$lang['gallery.static.scroll']                = 'Static scrolling';
$lang['gallery.vertical.scroll']              = 'Vertical dynamic scrolling';
$lang['gallery.horizontal.scroll']            = 'Horizontal dynamic scrolling';
$lang['gallery.cache']                        = 'Thumbnails cache';
$lang['gallery.cache.clue']                   = 'Regeneration of the thumbnails<br />Empties the cache in case of thumbnails configuration modification, and recount number of pictures in each categories.';
    // add
$lang['gallery.upload.items']            = 'Upload pictures';
$lang['gallery.server.item']             = 'Pictures available on the server';
$lang['gallery.select.all.items']        = 'Select all pictures';
$lang['gallery.deselect.all.items']      = 'Deselect all pictures';
$lang['gallery.category.selection']      = 'All selected pictures category';
$lang['gallery.category.selection.clue'] = 'Allows to change category for all selected pictures';
    // manager
$lang['gallery.items.in.category']          = 'Pictures in the catégorie';
$lang['gallery.category.items.number.clue'] = 'Pictures number<br />(<em>(hidden ones)</em>)';

// Errors
$lang['e_no_gd']                = 'Gallery -> Gd Library not loaded';
$lang['e_unabled_create_pics']  = 'Gallery -> Error creating picture';
$lang['e_no_graphic_support']   = 'Gallery -> No graphic support on this server';
$lang['e_no_getimagesize']      = 'Gallery -> Function getimagesize() unsupported, please contact your internet provider';
$lang['e_unsupported_format']   = 'Gallery -> Unsupported format (jpg, gif, png, webp only)';
$lang['e_unabled_incrust_logo'] = 'Gallery -> Unable to inlay logo, deactivate it in the gallery configuration';
$lang['e_error_resize']         = 'Gallery -> Resizing error';
$lang['e_unable_display_pics']  = 'Gallery -> Unabled to display picture!';
$lang['e_delete_thumbnails']    = 'Gallery -> Delete thumbnail error';
$lang['e_error_img']            = 'Picture error';
$lang['e_unexist_img']          = 'This picture doesn\'t exist';

// Labels
$lang['gallery.item']         = 'Picture';
$lang['gallery.items']        = 'Pictures';
$lang['gallery.date.added']   = 'Added on';
$lang['gallery.thumbnails']   = 'Thumbnails';
$lang['gallery.upload.limit'] = 'Upload limit';

// Message helper
$lang['gallery.warning.success.upload'] = 'Pictures succesfully uploaded!';

// Notices
$lang['gallery.no.ftp.item'] = 'No additional picture present on the server';

// Tree links
$lang['gallery.add.items']  = 'Add pictures';
$lang['gallery.management'] = 'Pictures management';

// Tools menu
$lang['gallery.top.views'] = 'Top views';
$lang['gallery.top.rated'] = 'Top rated';

// S.E.O.
$lang['gallery.seo.description.root'] = 'All :site\'s gallery pictures.';

// Warnings
$lang['gallery.warning.height']              = 'Please enter maximum height for the thumbnails!';
$lang['gallery.warning.height.max']          = 'Please enter maximum height for the pictures!';
$lang['gallery.warning.width.max']           = 'Please enter maximum width for the pictures!';
$lang['gallery.warning.width']               = 'Please enter maximum width for the thumbnails!';
$lang['gallery.warning.weight.max']          = 'Please enter maximum weight for the pictures!';
$lang['gallery.warning.categories.per.page'] = 'Please enter the number of categories per page!';
$lang['gallery.warning.row']                 = 'Please enter the number of columns for the gallery!';
$lang['gallery.warning.items.per.page']      = 'Please enter the number of pictures per page!';
$lang['gallery.warning.quality']             = 'Please enter the thumbtails quality!';
?>
