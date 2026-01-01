<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 04 13
 * @since       PHPBoost 1.6 - 2007 10 14
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 */

####################################################
#                       English                    #
####################################################

// Module titles
$lang['wiki.module.title'] = 'Wikis';
$lang['wiki.menu.title']   = 'Wiki tree';
$lang['wiki.explorer']     = 'Explorer';
$lang['wiki.overview']     = 'Summary';

// TreeLinks
$lang['item']              = 'sheet';
$lang['items']             = 'sheets';
$lang['items.reorder']     = 'Reorder sheets';
$lang['items.reordering']  = 'Reorganization of sheets';

// Table of contents
$lang['wiki.contents.table']        = 'Table of contents';
$lang['wiki.name']                  = 'Wiki name';
$lang['wiki.sticky.contents.table'] = 'Display the table of contents in a fixed position';

// Titles
$lang['wiki.root']             = 'No categories';
$lang['wiki.add.item']         = 'Add a sheet';
$lang['wiki.edit.item']        = 'Edit a sheet';
$lang['wiki.duplicate.item']   = 'Duplicate a sheet';
$lang['wiki.my.items']         = 'My sheets';
$lang['wiki.my.tracked']       = 'My favorites';
$lang['wiki.member.items']     = 'Sheets published by';
$lang['wiki.pending.items']    = 'Pending sheets';
$lang['wiki.filter.items']     = 'Filter sheets';
$lang['wiki.items.management'] = 'sheets management';
$lang['wiki.item.history']     = 'History of the sheet';
$lang['wiki.restore.item']     = 'Restore this version';
$lang['wiki.confirm.restore']  = 'Do you really want to restore this version ?';
$lang['wiki.history.init']     = 'Initialization';
$lang['wiki.current.version']  = 'Current version';
$lang['wiki.delete.version']   = 'Delete this version';
$lang['wiki.archive']          = 'Archive';
$lang['wiki.archived.item']    = 'Consult';
$lang['wiki.archived.content'] = 'This sheet has been updated, your are watching an archive !';
$lang['wiki.track']            = 'Follow this sheet';
$lang['wiki.untrack']          = 'Unfollow this sheet';

// Levels
$lang['wiki.level'] = 'Trust level';

$lang['wiki.level.trust']  = 'Trusted content';
$lang['wiki.level.claim']  = 'Disputed content';
$lang['wiki.level.redo']   = 'Content to redo';
$lang['wiki.level.sketch'] = 'Incomplete content';
$lang['wiki.level.wip']    = 'Content under construction';

$lang['wiki.level.trust.message']  = 'This sheet is of high quality, it is complete and reliable.';
$lang['wiki.level.claim.message']  = 'This sheet has been discussed and its content does not seem correct. You can possibly consult the discussions on this subject and perhaps bring your knowledge to it.';
$lang['wiki.level.redo.message']   = 'This file is to be redone, its content is not very reliable.';
$lang['wiki.level.sketch.message'] = 'This sheet lacks sources.<br />Your knowledge is welcome in order to complete it.';
$lang['wiki.level.wip.message']    = 'This sheet is under construction, modifications are in progress, do not hesitate to come back to consult it later';

$lang['wiki.level.custom']         = 'Custom level';
$lang['wiki.level.custom.content'] = 'Description of the custom level';

// Form
$lang['wiki.change.reason']       = 'Type of modification';
$lang['wiki.suggestions.number']  = 'Number of suggested items to display';
$lang['wiki.homepage']            = 'Homepage type';
$lang['wiki.display.description'] = 'Display sub-categories description';
$lang['wiki.menu.configuration']  = 'Configuration of the mini module';
$lang['wiki.menu.title.name']     = 'Name of the mini module';
$lang['wiki.config.root']         = 'Wiki root (Categories and items of module root)';
$lang['wiki.config.explorer']     = 'Explorer (All categories and items)';
$lang['wiki.config.overview']     = 'Summary (All categories)';

// Authorizations
$lang['wiki.config.manage.history'] = 'Manage history permissions';

// SEO
$lang['wiki.seo.description.root']    = 'All :site\'s wiki sheets.';
$lang['wiki.seo.description.tag']     = 'All wikis on :subject.';
$lang['wiki.seo.description.pending'] = 'All pending wikis.';
$lang['wiki.seo.description.member']  = 'All :author\'s wikis.';
$lang['wiki.seo.description.tracked'] = 'All tracked sheets for :author.';
$lang['wiki.seo.description.history'] = 'History of the sheet :item.';

// Messages helper
$lang['wiki.message.success.add']            = 'The sheet <b>:title</b> has been added';
$lang['wiki.message.success.edit']           = 'The sheet <b>:title</b> has been modified';
$lang['wiki.message.success.delete']         = 'The sheet <b>:title</b> has been deleted';
$lang['wiki.message.success.delete.content'] = 'The content :content of the sheet <b>:title</b> has been deleted';
$lang['wiki.message.success.restore']        = 'The content :content of the sheet <b>:title</b> has been deleted';
$lang['wiki.message.draft'] = '
    <div class="message-helper bgc warning">
    Editing a file automatically places it in <b>draft</b>. This allows several validations without excessively multiplying the archives.
        <br /><br />
        <p>Remember to change the publication status at the end of the work!</p>
    </div>
';
?>
