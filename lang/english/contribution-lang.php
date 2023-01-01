<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 23
 * @since       PHPBoost 6.0 - 2021 04 20
*/

####################################################
#                     English                      #
####################################################

$lang['contribution.panel']          = 'Contributions panel';
$lang['contribution.my.items']       = 'My contributions';
$lang['contribution.contribution']   = 'Contribution';
$lang['contribution.contributions']  = 'Contributions';
$lang['contribution.details']        = 'Contribution details';
$lang['contribution.process']        = 'Process the contribution';
$lang['contribution.not.processed']  = 'Not processed';
$lang['contribution.in.progress']    = 'In progress';
$lang['contribution.processed']      = 'Processed';
$lang['contribution.edition']        = 'Editing a contribution';
$lang['contribution.contributor']    = 'Contributor';
$lang['contribution.closing.date']   = 'Closing date';
$lang['contribution.change.status']  = 'Modify the contribution status';
$lang['contribution.delete']         = 'Delete the contribution';
$lang['contribution.list']           = 'Contribution list';
$lang['contribution.contribute']     = 'Contribute';
$lang['contribution.member.edition'] = 'Contribution modification by the author';

$lang['contribution.contribute.in.modules']     = 'The modules above allow to contribute. Click on one of them to go to its contribution interface.';
$lang['contribution.contribute.in.module.name'] = 'Contribute in %s';
$lang['contribution.no.module.to.contribute']   = 'No module in which you can contribute is installed.';

$lang['contribution.warning'] = '
    Your contribution will be treated in the contribution panel.
    <span class="error text-strong">Amendment is possible until the contribution has been approved.</span>
    In the next field, you can justify your contribution to explain your approach to a moderator.';
$lang['contribution.extended.warning'] = '
    Your contribution will be treated in the contribution panel.
    <span class="error text-strong">Amendment is possible until the contribution has been approved or after.</span>
    In the next field, you can justify your contribution to explain your approach to a moderator.
    If you modify your contribution after <span class="text-strong">its approbation</span>, it will be sent back to the contribution panel, waiting for a new approbation.';
$lang['contribution.edition.warning'] = '
    You are about to modify your contribution. It will be sent back to the contribution panel to be treated
    and a new alert will be sent to approvers.';

$lang['contribution.description']                     = 'Additional contribution';
$lang['contribution.description.clue']                = 'Explain the reasons of your contribution. This field is not required but it may help the moderator to make his decision.';
$lang['contribution.edition.description']      = 'Additional modification';
$lang['contribution.edition.description.desc'] = 'Please explain what you have modified to help approvers.';
$lang['contribution.confirmed']                       = 'Your contribution has been saved.';
$lang['contribution.confirmed.messages'] = '
    <p>
        You can follow it into the <a class="offload" href="' . UserUrlBuilder::contribution_panel()->rel() . '">contribution panel</a>
        and possibly discuss with the validators if their choice is not straightforward.</p><p>Thank you for participating in the life of the site!
    </p>
';
$lang['contribution.pm.title']    = 'The contribution <strong>:title</strong> has been commented';
$lang['contribution.pm.content'] = '
    :author add a comment to the contribution <strong>:title</strong>.
    <p>
        <h6>Comment :</h6>
        :comment
    </p>
    <a class="offload" href=":contribution_url">See the contribution</a>
';

// Dead link
$lang['contribution.report.dead.link']       = 'Report a dead link';
$lang['contribution.dead.link.confirmation'] = 'Do you really want to report this link as dead?';
$lang['contribution.dead.link.name']         = 'Dead link : :link_name';
$lang['contribution.dead.link.clue']         = 'One member reported this link as dead. Please check the link and change it if necessary.';
?>
