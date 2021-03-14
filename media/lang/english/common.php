<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 14
 * @since       PHPBoost 4.1 - 2015 02 04
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                      English                     #
####################################################

$lang['module.title'] = 'Multimedia';

$lang['items'] = 'Files';
$lang['item'] = 'File';

$lang['media.actions.add'] = 'Add a file';
$lang['media.manage'] = 'Manage files';
$lang['media.hide.file'] = 'Hide the file';

// Categories
$lang['media.content.type'] = 'Type of files allowed';
$lang['media.content.type.music.and.video'] = 'Music and Video';
$lang['media.content.type.music'] = 'Music';
$lang['media.content.type.video'] = 'Video';

// Action
$lang['media.add.item'] = 'Add a file';
$lang['media.delete.item'] = 'Delete a file';
$lang['media.edit.item'] = 'Edit a file';
$lang['media.approval'] = 'Approval';
$lang['media.description'] = 'Description file';
$lang['media.moderation'] = 'Moderation';
$lang['media.title'] = 'Title file';
$lang['media.file.url'] = 'Link file';
$lang['media.poster'] = 'Poster of video';
$lang['media.height'] = 'Height video';
$lang['media.width'] = 'Width video';
$lang['media.require.title'] = 'Please enter a title for your file!';
$lang['media.require.file.url'] = 'Please enter a link for your file!';
$lang['media.additional.contribution'] = 'Contribution counterpart';
$lang['media.additional.contribution.description'] = 'Explain why you want to submit your file. This field is not required but it can help the validators who will take care of your contribution.';
$lang['media.contribution'] = 'Contribute a file';
$lang['media.contribution.notice'] = '
    You aren\'t authorized to create a file, however you can contribute by submitting a file.
    <span class="error text-strong">Amendment is possible until the contribution has been approved.
    </span> Your contribution will be processed by an validator. It will happen in the contribution panel.
    In the following field, you can justify your contribution to an administrator.
';

// Moderation
$lang['media.all.files'] = 'All files';
$lang['media.confirm.delete.all.files'] = 'Do you really want to delete this file?';
$lang['media.display.files'] = 'Display the files';
$lang['media.filter'] = 'Filter';
$lang['media.include.sub.categories'] = ', include the sub-categories:';
$lang['media.visible'] = 'Approved';
$lang['media.invisible'] = 'Invisibles';
$lang['media.disapproved'] = 'Disapproved';
$lang['media.disapproved.description'] = 'File disapproved';
$lang['media.invisible.description'] = 'Hidden file';
$lang['media.visible.description'] = 'Approved and visible file';

// Configuration
$lang['config.max.video.width'] = 'Maximum video width';
$lang['config.max.video.height'] = 'Maximum video height';
$lang['config.root.category.media.content.type'] = 'Multimedia root category type of files allowed';
$lang['config.constant.host'] = 'Trusted hosts';
$lang['config.constant.host.peertube'] = 'Peertube';
$lang['config.constant.host.peertube.desc'] = '<a href="https://joinpeertube.org">Joinpeertube.org</a> - <a href="https://instances.joinpeertube.org/instances/">List of instances</a>';

// SEO
$lang['media.seo.description.root'] = 'All :site\'s files.';

// Message helper
$lang['e_mime_disable_media'] = 'The type of is disabled!';
$lang['e_mime_unknow_media'] = 'The type of file could not be determined!';
$lang['e_link_empty_media'] = 'Please enter a link for your file!';
$lang['e_link_invalid_media'] = 'Please enter a valid link for your file!';
$lang['e_unexist_media'] = 'The file requested doesn\'t exist!';
$lang['e.bad.url.odysee'] = '
    The Odysee url entered is not valid. <br />
    In the share tab
    In the <span class="pinned question">Share</span> tab under the video, choose one of the following two urls:
    <ul>
        <li><span class="pinned question">Embed this content</span> / url provided in <span class="pinned question">Embedded</span></li>
        <li><span class="pinned question">Links</span> / url provided in <span class="pinned question">Download link</span></li>
    </ul>
';
$lang['e.bad.url.peertube'] = 'The PeerTube url entrered is not valid. It does not correspond to the url entered in the module configuration.';
?>
