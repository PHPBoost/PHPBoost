<?php
/*##################################################
 *                              common.php
 *                            -------------------
 *   begin                : November 09, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
 *
 *  
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

####################################################
# English                                          #
####################################################
 
//Module title
$lang['bugs.module_title'] = 'Bugtracker';

//Notice
$lang['bugs.notice.no_one'] = 'No one';
$lang['bugs.notice.none'] = 'None';
$lang['bugs.notice.none_e'] = 'None';
$lang['bugs.notice.no_bug'] = 'No bug declared';
$lang['bugs.notice.no_bug_solved'] = 'No bug solved';
$lang['bugs.notice.no_bug_fixed'] = 'No bug fixed in this version';
$lang['bugs.notice.no_bug_in_progress'] = 'No bug being corrected in this version';
$lang['bugs.notice.no_bug_matching_filter'] = 'No bug matching the selected filter';
$lang['bugs.notice.no_bug_matching_filters'] = 'No bug matching the selected filters';
$lang['bugs.notice.no_version_roadmap'] = 'Please add at least one version in the configuration to display the roadmap.';
$lang['bugs.notice.no_version'] = 'No version';
$lang['bugs.notice.no_type'] = 'No type';
$lang['bugs.notice.no_category'] = 'No category';
$lang['bugs.notice.no_history'] = 'This bug has no history';
$lang['bugs.notice.contents_update'] = 'Contents update';
$lang['bugs.notice.new_comment'] = 'New comment';
$lang['bugs.notice.reproduction_method_update'] = 'Reproduction method update';
$lang['bugs.notice.not_defined'] = 'Not defined';
$lang['bugs.notice.not_defined_e_date'] = 'Date not defined';

//Actions
$lang['bugs.actions'] = 'Actions';
$lang['bugs.actions.add'] = 'New bug';
$lang['bugs.actions.history'] = 'History';
$lang['bugs.actions.reject'] = 'Reject';
$lang['bugs.actions.reopen'] = 'Reopen';
$lang['bugs.actions.assign'] = 'Assign';
$lang['bugs.actions.fix'] = 'Fix';
$lang['bugs.actions.confirm.del_version'] = 'Delete this version?';
$lang['bugs.actions.confirm.del_type'] = 'Delete this type?';
$lang['bugs.actions.confirm.del_category'] = 'Delete this category?';
$lang['bugs.actions.confirm.del_priority'] = 'Delete this priority?';
$lang['bugs.actions.confirm.del_severity'] = 'Delete this severity?';
$lang['bugs.actions.confirm.del_default_value'] = 'Delete this default value?';
$lang['bugs.actions.confirm.del_filter'] = 'Delete this filter?';

//Titles
$lang['bugs.titles.add'] = 'New bug';
$lang['bugs.titles.add_version'] = 'Add a new version';
$lang['bugs.titles.add_type'] = 'Add a new bug type';
$lang['bugs.titles.add_category'] = 'Add a new category';
$lang['bugs.titles.edit'] = 'Bug edition';
$lang['bugs.titles.reject'] = 'Bug reject';
$lang['bugs.titles.reopen'] = 'Bug reopen';
$lang['bugs.titles.fix'] = 'Bug fixing';
$lang['bugs.titles.delete'] = 'Bug suppression';
$lang['bugs.titles.assign'] = 'Bug assignment';
$lang['bugs.titles.history'] = 'History';
$lang['bugs.titles.detail'] = 'Bug';
$lang['bugs.titles.roadmap'] = 'Roadmap';
$lang['bugs.titles.bugs_infos'] = 'Bug\'s informations';
$lang['bugs.titles.stats'] = 'Statistics';
$lang['bugs.titles.bugs_treatment_state'] = 'Bug\'s treatment state';
$lang['bugs.titles.versions'] = 'Versions';
$lang['bugs.titles.types'] = 'Types';
$lang['bugs.titles.categories'] = 'Categories';
$lang['bugs.titles.priorities'] = 'Priorities';
$lang['bugs.titles.severities'] = 'Severities';
$lang['bugs.titles.admin.config'] = 'Configuration';
$lang['bugs.titles.admin.authorizations'] = 'Authorizations';
$lang['bugs.titles.admin.authorizations.manage'] = 'Manage authorizations';
$lang['bugs.titles.admin.module_config'] = 'Bugtracker module configuration';
$lang['bugs.titles.admin.module_authorizations'] = 'Bugtracker module authorizations configuration';
$lang['bugs.titles.choose_version'] = 'Version to display';
$lang['bugs.titles.solved'] = 'Fixed bugs';
$lang['bugs.titles.unsolved'] = 'Unresolved bugs';
$lang['bugs.titles.contents_value_title'] = 'Request default description';
$lang['bugs.titles.contents_value'] = 'Default description';
$lang['bugs.titles.filter'] = 'Filter';
$lang['bugs.titles.filters'] = 'Filters';
$lang['bugs.titles.legend'] = 'Legend';
$lang['bugs.titles.informations'] = 'Informations';
$lang['bugs.titles.version_informations'] = 'Version Informations';

//Labels
$lang['bugs.labels.fields.id'] = 'ID';
$lang['bugs.labels.fields.title'] = 'Title';
$lang['bugs.labels.fields.contents'] = 'Description';
$lang['bugs.labels.fields.author_id'] = 'Detected by';
$lang['bugs.labels.fields.submit_date'] = 'Detected on';
$lang['bugs.labels.fields.fix_date'] = 'Fixed on';
$lang['bugs.labels.fields.status'] = 'Status';
$lang['bugs.labels.fields.type'] = 'Type';
$lang['bugs.labels.fields.category'] = 'Category';
$lang['bugs.labels.fields.reproductible'] = 'Reproductible';
$lang['bugs.labels.fields.reproduction_method'] = 'Reproduction method';
$lang['bugs.labels.fields.severity'] = 'Level';
$lang['bugs.labels.fields.priority'] = 'Priority';
$lang['bugs.labels.fields.progress'] = 'Progress';
$lang['bugs.labels.fields.detected_in'] = 'Detected in version';
$lang['bugs.labels.fields.fixed_in'] = 'Fixed in version';
$lang['bugs.labels.fields.assigned_to_id'] = 'Assigned to';
$lang['bugs.labels.fields.updater_id'] = 'Updated by';
$lang['bugs.labels.fields.update_date'] = 'Updated on';
$lang['bugs.labels.fields.updated_field'] = 'Updated field';
$lang['bugs.labels.fields.old_value'] = 'Old value';
$lang['bugs.labels.fields.new_value'] = 'New value';
$lang['bugs.labels.fields.change_comment'] = 'Comment';
$lang['bugs.labels.fields.version'] = 'Version';
$lang['bugs.labels.fields.version_detected'] = 'Detected version';
$lang['bugs.labels.fields.version_fixed'] = 'Fixed version';
$lang['bugs.labels.fields.version_release_date'] = 'Release date';
$lang['bugs.labels.page'] = 'Page';
$lang['bugs.labels.color'] = 'Color';
$lang['bugs.labels.number'] = 'Bugs number';
$lang['bugs.labels.number_fixed'] = 'Corrected bugs number';
$lang['bugs.labels.number_in_progress'] = 'Bugs being corrected number';
$lang['bugs.labels.top_posters'] = 'Top posters';
$lang['bugs.labels.login'] = 'Login';
$lang['bugs.labels.default'] = 'Default';
$lang['bugs.labels.default_value'] = 'Default value';
$lang['bugs.labels.del_default_value'] = 'Delete default value';
$lang['bugs.labels.type_mandatory'] = 'Section <b>Type</b> mandatory?';
$lang['bugs.labels.category_mandatory'] = 'Section <b>Category</b> mandatory?';
$lang['bugs.labels.severity_mandatory'] = 'Section <b>Severity</b> mandatory?';
$lang['bugs.labels.priority_mandatory'] = 'Section <b>Priority</b> mandatory?';
$lang['bugs.labels.detected_in_mandatory'] = 'Section <b>Detected in version</b> mandatory?';
$lang['bugs.labels.date_format'] = 'Date display format';
$lang['bugs.labels.date_time'] = 'Date and time';
$lang['bugs.labels.detected'] = 'Detected';
$lang['bugs.labels.fixed'] = 'Fixed';
$lang['bugs.labels.fix_bugs_per_version'] = 'Fixed bugs number per version';
$lang['bugs.labels.release_date'] = 'Release date';
$lang['bugs.labels.not_yet_fixed'] = 'Not yet fixed';
$lang['bugs.labels.alert_fix'] = 'Fix alert';
$lang['bugs.labels.alert_delete'] = 'Delete alert';
$lang['bugs.labels.matching_selected_filter'] = 'matching selected filter';
$lang['bugs.labels.matching_selected_filters'] = 'matching selected filters';
$lang['bugs.labels.save_filters'] = 'Save filters';
$lang['bugs.labels.version_name'] = 'Version name';

//Status
$lang['bugs.status.new'] = 'New';
$lang['bugs.status.assigned'] = 'Assigned';
$lang['bugs.status.in_progress'] = 'In progress';
$lang['bugs.status.fixed'] = 'Fixed';
$lang['bugs.status.reopen'] = 'Reopen';
$lang['bugs.status.rejected'] = 'Rejected';

//Explainations
$lang['bugs.explain.contents'] = 'Useful details to treat the bug';
$lang['bugs.explain.roadmap'] = 'Displays the fixed bug list for each version. Displayed if there is at least one version in the list.';
$lang['bugs.explain.type'] = 'Demands types. Examples : Anomaly, Evolution...';
$lang['bugs.explain.category'] = 'Demands categories. Examples : Kernel, Module...';
$lang['bugs.explain.severity'] = 'Demands severities. Examples : Minor, Major, Critical...';
$lang['bugs.explain.priority'] = 'Demands priorities. Examples : Low, Normal, High...';
$lang['bugs.explain.version'] = 'Product versions list.';
$lang['bugs.explain.remarks'] = 'Remarks : <br />
- If the table is empty, this option will not be visible on the post bug page<br />
- If the table contains only one value, this option will not be visible too and will automatically be assigned to the bug<br /><br />';
$lang['bugs.explain.contents_value'] = 'Enter the default description to display for a new bug below. Leave empty if you don\'t want to fill the description.';
$lang['bugs.explain.reopen_comment'] = 'Optional. Permits to comment the bug and add it in the Private Message if sending is enabled for reopened bugs.';
$lang['bugs.explain.reject_comment'] = 'Optional. Permits to comment the bug and add it in the Private Message if sending is enabled for rejected bugs.';
$lang['bugs.explain.fix_comment'] = 'Optional. Permits to comment the bug and add it in the Private Message if sending is enabled for fixed bugs.';
$lang['bugs.explain.delete_comment'] = 'Optional. Permits to add a comment in the Private Message of bug deleting.';
$lang['bugs.explain.assign_comment'] = 'Optional. Permits to add a comment in the Private Message to the assigned person.';

//PM
$lang['bugs.pm.assigned.title'] = '[Bugtracker] The bug #:id has been assigned to you by :author';
$lang['bugs.pm.assigned.contents'] = ':author assigned you the bug #:id.

Bug link :
<a href=":link">:link_label</a>';
$lang['bugs.pm.assigned.contents_with_comment'] = ':author assigned you the bug #:id.

Comment:
:comment

Bug link :
<a href=":link">:link_label</a>';
$lang['bugs.pm.comment.title'] = '[Bugtracker] A new comment has been posted for the bug #:id by :author';
$lang['bugs.pm.comment.contents'] = ':author add the following comment to the bug #:id:

:comment

Bug link :
<a href=":link">:link_label</a>';
$lang['bugs.pm.edit.title'] = '[Bugtracker] The bug #:id has been updated by :author';
$lang['bugs.pm.edit.contents'] = ':author has updated the following fields in the bug #:id :

:fields

Bug link :
<a href=":link">:link_label</a>';
$lang['bugs.pm.fixed.title'] = '[Bugtracker] The bug #:id has been fixed by :author';
$lang['bugs.pm.fixed.contents'] = ':author has fixed the bug #:id.

Bug link :
<a href=":link">:link_label</a>';
$lang['bugs.pm.fixed.contents_with_comment'] = ':author has fixed the bug #:id.

Comment:
:comment

Bug link :
<a href=":link">:link_label</a>';
$lang['bugs.pm.reopen.title'] = '[Bugtracker] The bug #:id has been reopen by :author';
$lang['bugs.pm.reopen.contents'] = ':author has reopened the bug #:id.

Bug link :
<a href=":link">:link_label</a>';
$lang['bugs.pm.reopen.contents_with_comment'] = ':author has reopened the bug #:id.

Comment:
:comment

Bug link :
<a href=":link">:link_label</a>';
$lang['bugs.pm.reject.title'] = '[Bugtracker] The bug #:id has been rejected by :author';
$lang['bugs.pm.reject.contents'] = ':author has rejected the bug #:id.

Bug link :
<a href=":link">:link_label</a>';
$lang['bugs.pm.reject.contents_with_comment'] = ':author has rejected the bug #:id.

Comment:
:comment

Bug link :
<a href=":link">:link_label</a>';
$lang['bugs.pm.delete.title'] = '[Bugtracker] The bug #:id has been deleted by :author';
$lang['bugs.pm.delete.contents'] = ':author delete the bug #:id.';
$lang['bugs.pm.delete.contents_with_comment'] = ':author delete the bug #:id.

Comment:
:comment';

//Search
$lang['bugs.search.where'] = 'Where?';
$lang['bugs.search.where.title'] = 'Title';
$lang['bugs.search.where.contents'] = 'Content';

//Configuration
$lang['bugs.config.items_per_page'] = 'Bugs number displayed per page'; 
$lang['bugs.config.rejected_bug_color_label'] = '<b>Rejected</b> bug line color';
$lang['bugs.config.fixed_bug_color_label'] = '<b>Fixed</b> bug line color';
$lang['bugs.config.activ_roadmap'] = 'Active roadmap';
$lang['bugs.config.activ_stats'] = 'Active statistics';
$lang['bugs.config.activ_stats_top_posters'] = 'Display top posters list';
$lang['bugs.config.stats_top_posters_number'] = 'Number user displayed';
$lang['bugs.config.progress_bar'] = 'Progress bar';
$lang['bugs.config.activ_progress_bar'] = 'Active progress bar';
$lang['bugs.config.status.new'] = 'Pourcent of a <b>New</b> bug';
$lang['bugs.config.status.assigned'] = 'Pourcent of an <b>Assigned</b> bug';
$lang['bugs.config.status.in_progress'] = 'Pourcent of a bug <b>In progress</b>';
$lang['bugs.config.status.fixed'] = 'Pourcent of a bug <b>Fixed</b>';
$lang['bugs.config.status.reopen'] = 'Pourcent of a bug <b>Reopen</b>';
$lang['bugs.config.status.rejected'] = 'Pourcent of a bug <b>Rejected</b>';
$lang['bugs.config.admin_alerts'] = 'Admin alerts';
$lang['bugs.config.activ_admin_alerts'] = 'Active admin alerts';
$lang['bugs.config.admin_alerts_levels'] = 'Bug severity to send the alert';
$lang['bugs.config.admin_alerts_fix_action'] = 'Action when fixing a bug';
$lang['bugs.config.activ_cat_in_title'] = 'Display category in bug title';
$lang['bugs.config.pm'] = 'Private Messages';
$lang['bugs.config.activ_pm'] = 'Active Private Messages (PM) send';
$lang['bugs.config.activ_pm.comment'] = 'Send a PM when a new comment is posted';
$lang['bugs.config.activ_pm.fix'] = 'Send a PM when a bug is fixed';
$lang['bugs.config.activ_pm.assign'] = 'Send a PM when a bug is assigned';
$lang['bugs.config.activ_pm.edit'] = 'Send a PM when a bug is edited';
$lang['bugs.config.activ_pm.reject'] = 'Send a PM when a bug is rejected';
$lang['bugs.config.activ_pm.reopen'] = 'Send a PM when a bug is reopen';
$lang['bugs.config.activ_pm.delete'] = 'Send a PM when a bug is deleted';

//Authorizations
$lang['bugs.config.auth.read'] = 'Permission to display the bugs list';
$lang['bugs.config.auth.create'] = 'Permission to post a bug';
$lang['bugs.config.auth.create_advanced'] = 'Advanced permission to post a bug';
$lang['bugs.config.auth.create_advanced_explain'] = 'Permits to choose the severity and the priority of the bug';
$lang['bugs.config.auth.moderate'] = 'Permission to moderate the Bugtracker';

//Errors
$lang['bugs.error.e_no_fixed_version'] = 'Do not forget to select a version to put the bug in the roadmap.';
$lang['bugs.error.e_unexist_bug'] = 'This bug does not exist';
$lang['bugs.error.e_unexist_parameter'] = 'This parameter does not exist';
$lang['bugs.error.e_unexist_type'] = 'This type does not exist';
$lang['bugs.error.e_unexist_category'] = 'This category does not exist';
$lang['bugs.error.e_unexist_severity'] = 'This severity does not exist';
$lang['bugs.error.e_unexist_priority'] = 'This priority does not exist';
$lang['bugs.error.e_unexist_version'] = 'This version does not exist';
$lang['bugs.error.e_already_rejected_bug'] = 'This bug is already rejected';
$lang['bugs.error.e_already_reopen_bug'] = 'This bug is already reopen';
$lang['bugs.error.e_already_fixed_bug'] = 'This bug is already fixed';

//Success
$lang['bugs.success.add'] = 'The bug #:id has been committed';
$lang['bugs.success.edit'] = 'The bug #:id has been updated';
$lang['bugs.success.fixed'] = 'The bug #:id has been fixed';
$lang['bugs.success.delete'] = 'The bug #:id has been deleted';
$lang['bugs.success.reject'] = 'The bug #:id has been rejected';
$lang['bugs.success.reopen'] = 'The bug #:id has been reopen';
$lang['bugs.success.assigned'] = 'The bug #:id has been assigned';
?>
