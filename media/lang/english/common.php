<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 24
 * @since       PHPBoost 4.1 - 2015 02 04
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                      English                     #
####################################################

$lang['media.module.title'] = 'Multimedia';

$lang['media.items']       = 'files';
$lang['media.item']        = 'file';
$lang['media.add.item']    = 'Add a file';
$lang['media.delete.item'] = 'Delete a file';
$lang['media.edit.item']   = 'Edit a file';
$lang['media.hide.item']   = 'Hide the file';

// Tree links
$lang['media.management'] = 'Files management';

// Categories
$lang['media.content.type']                 = 'Type of files allowed';
$lang['media.content.type.music.and.video'] = 'Music and Video';
$lang['media.content.type.music']           = 'Music';
$lang['media.content.type.video']           = 'Video';

// Action
$lang['media.file.url'] = 'Link file';
$lang['media.poster']   = 'Poster of video';
$lang['media.height']   = 'Height video';
$lang['media.width']    = 'Width video';

// Moderation
$lang['media.all.files']                = 'All files';
$lang['media.confirm.delete.all.files'] = 'Do you really want to delete this file?';
$lang['media.display.files']            = 'Display the files';
$lang['media.filter']                   = 'Filter';
$lang['media.include.sub.categories']   = ', include the sub-categories:';
$lang['media.visible']                  = 'Approved';
$lang['media.invisible']                = 'Invisibles';
$lang['media.disapproved']              = 'Disapproved';
$lang['media.disapproved.description']  = 'File disapproved';
$lang['media.invisible.description']    = 'Hidden file';
$lang['media.visible.description']      = 'Approved and visible file';

// Configuration
$lang['media.max.video.width']             = 'Maximum video width';
$lang['media.max.video.height']            = 'Maximum video height';
$lang['media.root.content.type']           = 'Multimedia root category type of files allowed';
$lang['media.constant.host']               = 'Trusted hosts';
$lang['media.constant.host.peertube']      = 'Peertube';
$lang['media.constant.host.peertube.desc'] = '<a href="https://joinpeertube.org">Joinpeertube.org</a> - <a href="https://instances.joinpeertube.org/instances/">List of instances</a>';

// SEO
$lang['media.seo.description.root'] = 'All :site\'s files.';

// Message helper
$lang['e_mime_disable_media'] = 'The type of is disabled!';
$lang['e_mime_unknow_media']  = 'The type of file could not be determined!';
$lang['e_link_empty_media']   = 'Please enter a link for your file!';
$lang['e_link_invalid_media'] = 'Please enter a valid link for your file!';
$lang['e_unexist_media']      = 'The file requested doesn\'t exist!';
$lang['e_bad_url_odysee'] = '
    The Odysee url entered is not valid. <br />
    In the share tab
    In the <span class="pinned question">Share</span> tab under the video, choose one of the following two urls:
    <ul>
        <li><span class="pinned question">Embed this content</span> / url provided in <span class="pinned question">Embedded</span></li>
        <li><span class="pinned question">Links</span> / url provided in <span class="pinned question">Download link</span></li>
    </ul>
';
$lang['e_bad_url_peertube'] = 'The PeerTube url entrered is not valid. It does not correspond to the url entered in the module configuration.';
?>
