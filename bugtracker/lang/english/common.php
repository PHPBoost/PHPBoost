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
 
$lang = array();

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
$lang['bugs.notice.no_version'] = 'No version';
$lang['bugs.notice.no_type'] = 'No type declared';
$lang['bugs.notice.no_category'] = 'No category declared';
$lang['bugs.notice.no_priority'] = 'No priority declared';
$lang['bugs.notice.no_severity'] = 'No severity declared';
$lang['bugs.notice.no_history'] = 'This bug has no history';
$lang['bugs.notice.contents_update'] = 'Contents update';
$lang['bugs.notice.new_comment'] = 'New comment';
$lang['bugs.notice.reproduction_method_update'] = 'Reproduction method update';
$lang['bugs.notice.not_defined'] = 'Not defined';
$lang['bugs.notice.not_defined_e_date'] = 'Date not defined';
$lang['bugs.notice.joker'] = 'Use * for a joker';

//Actions
$lang['bugs.actions'] = 'Actions';
$lang['bugs.actions.add'] = 'New bug';
$lang['bugs.actions.delete'] = 'Delete bug';
$lang['bugs.actions.edit'] = 'Edit bug';
$lang['bugs.actions.history'] = 'Bug\'s history';
$lang['bugs.actions.reject'] = 'Reject bug';
$lang['bugs.actions.reopen'] = 'Reopen bug';
$lang['bugs.actions.confirm.del_bug'] = 'Delete this bug? (All the history related to this bug will be deleted)';
$lang['bugs.actions.confirm.reopen_bug'] = 'Reopen this bug?';
$lang['bugs.actions.confirm.reject_bug'] = 'Reject this bug?';
$lang['bugs.actions.confirm.del_version'] = 'Delete this version?';
$lang['bugs.actions.confirm.del_type'] = 'Delete this type?';
$lang['bugs.actions.confirm.del_category'] = 'Delete this category?';
$lang['bugs.actions.confirm.del_priority'] = 'Delete this priority?';
$lang['bugs.actions.confirm.del_severity'] = 'Delete this severity?';
$lang['bugs.actions.confirm.del_default_value'] = 'Delete this default value?';
$lang['bugs.actions.confirm.del_filter'] = 'Delete this filter?';

//Titles
$lang['bugs.titles.add_bug'] = 'New bug';
$lang['bugs.titles.add_version'] = 'Add a new version';
$lang['bugs.titles.add_type'] = 'Add a new type';
$lang['bugs.titles.add_category'] = 'Add a new category';
$lang['bugs.titles.add_priority'] = 'Add a new priority';
$lang['bugs.titles.add_severity'] = 'Add a new severity';
$lang['bugs.titles.edit_bug'] = 'Bug edition';
$lang['bugs.titles.history_bug'] = 'Bug history';
$lang['bugs.titles.history'] = 'History';
$lang['bugs.titles.view_bug'] = 'Bug';
$lang['bugs.titles.roadmap'] = 'Roadmap';
$lang['bugs.titles.bugs_infos'] = 'Bug\'s informations';
$lang['bugs.titles.bugs_stats'] = 'Statistics';
$lang['bugs.titles.bugs_treatment'] = 'Bug\'s treatment';
$lang['bugs.titles.bugs_treatment_state'] = 'Bug\'s treatment state';
$lang['bugs.titles.versions'] = 'Versions';
$lang['bugs.titles.types'] = 'Types';
$lang['bugs.titles.categories'] = 'Categories';
$lang['bugs.titles.priorities'] = 'Priorities';
$lang['bugs.titles.severities'] = 'Severities';
$lang['bugs.titles.admin.config'] = 'Configuration';
$lang['bugs.titles.admin.authorizations'] = 'Authorizations';
$lang['bugs.titles.admin.module_config'] = 'Bugtracker module configuration';
$lang['bugs.titles.admin.module_authorizations'] = 'Bugtracker module authorizations configuration';
$lang['bugs.titles.choose_version'] = 'Version to display';
$lang['bugs.titles.solved_bugs'] = 'Fixed bugs';
$lang['bugs.titles.unsolved_bugs'] = 'Unresolved bugs';
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
$lang['bugs.labels.fields.version_detected_in'] = 'Display in the list "Detected in version"';
$lang['bugs.labels.fields.version_fixed_in'] = 'Display in the list "Fixed in version"';
$lang['bugs.labels.fields.version_detected'] = 'Detected version';
$lang['bugs.labels.fields.version_fixed'] = 'Fixed version';
$lang['bugs.labels.fields.version_release_date'] = 'Release date';
$lang['bugs.labels.fields.version_release_date.explain'] = 'Format : dd/mm/yyyy';
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
$lang['bugs.labels.type_mandatory'] = 'Section "Type" mandatory?';
$lang['bugs.labels.category_mandatory'] = 'Section "Category" mandatory?';
$lang['bugs.labels.severity_mandatory'] = 'Section "Severity" mandatory?';
$lang['bugs.labels.priority_mandatory'] = 'Section "Priority" mandatory?';
$lang['bugs.labels.detected_in_mandatory'] = 'Section "Detected in version" mandatory?';
$lang['bugs.labels.date_format'] = 'Date display format';
$lang['bugs.labels.date_time'] = 'Date and time';
$lang['bugs.labels.detected'] = 'Detected';
$lang['bugs.labels.fixed'] = 'Fixed';
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
$lang['bugs.explain.roadmap'] = 'Displays the fixed bug list for each version.<br />The roadmap is displayed if there is at least one version int the list.';
$lang['bugs.explain.type'] = 'Demands types. Examples : Anomaly, Evolution...';
$lang['bugs.explain.category'] = 'Demands categories. Examples : Kernel, Module...';
$lang['bugs.explain.severity'] = 'Demands severities. Examples : Minor, Major, Critical...';
$lang['bugs.explain.priority'] = 'Demands priorities. Examples : Low, Normal, High...';
$lang['bugs.explain.version'] = 'Liste des versions du produit.';
$lang['bugs.explain.remarks'] = 'Remarks : <br />
- If the table is empty, this option will not be visible on the post bug page<br />
- If the table contains only one value, this option will not be visible too and will automatically be assigned to the bug<br /><br />';
$lang['bugs.explain.contents_value'] = 'Enter the default description to display for a new bug below. Leave empty if you don\'t want to fill the description.';

//PM
$lang['bugs.pm.assigned.title'] = '[Bugtracker] The bug #:id has been assigned to you by :author';
$lang['bugs.pm.assigned.contents'] = 'Clic here to display the detail of the bug :
:link';
$lang['bugs.pm.comment.title'] = '[Bugtracker] A new comment has been posted for the bug #:id by :author';
$lang['bugs.pm.comment.contents'] = ':author add the following comment to the bug #:id:

:comment

Bug link :
:link';
$lang['bugs.pm.edit.title'] = '[Bugtracker] The bug #:id has been updated by :author';
$lang['bugs.pm.edit.contents'] = ':author has updated the following fields in the bug #:id :

:fields

Bug link :
:link';
$lang['bugs.pm.reopen.title'] = '[Bugtracker] The bug #:id has been reopen by :author';
$lang['bugs.pm.reopen.contents'] = ':author a ré-ouvert le bug #:id.
Bug link :
:link';
$lang['bugs.pm.reject.title'] = '[Bugtracker] The bug #:id has been rejected by :author';
$lang['bugs.pm.reject.contents'] = ':author a rejeté le bug #:id.
Bug link :
:link';
$lang['bugs.pm.delete.title'] = '[Bugtracker] The bug #:id has been deleted by :author';
$lang['bugs.pm.delete.contents'] = ':author a supprimé le bug #:id.';

//Search
$lang['bugs.search.where'] = 'Where?';
$lang['bugs.search.where.title'] = 'Title';
$lang['bugs.search.where.contents'] = 'Content';

//Configuration
$lang['bugs.config.items_per_page'] = 'Bugs number displayed per page'; 
$lang['bugs.config.rejected_bug_color_label'] = 'Rejected bug line color';
$lang['bugs.config.fixed_bug_color_label'] = 'Fixed bug line color';
$lang['bugs.config.activ_com'] = 'Active comments';
$lang['bugs.config.activ_roadmap'] = 'Active roadmap';
$lang['bugs.config.activ_stats'] = 'Active statistics';
$lang['bugs.config.activ_stats_top_posters'] = 'Display top posters list';
$lang['bugs.config.stats_top_posters_number'] = 'Number user displayed';
$lang['bugs.config.activ_progress_bar'] = 'Active progress bar';
$lang['bugs.config.activ_admin_alerts'] = 'Active admin alerts';
$lang['bugs.config.admin_alerts_levels'] = 'Bug severity to send the alert';
$lang['bugs.config.admin_alerts_fix_action'] = 'Action when fixing a bug';
$lang['bugs.config.activ_cat_in_title'] = 'Display category in bug title';
$lang['bugs.config.pm'] = 'PM';
$lang['bugs.config.activ_pm'] = 'Active PM send';
$lang['bugs.config.activ_pm.comment'] = 'Send a PM when a new comment is posted';
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
$lang['bugs.error.require_items_per_page'] = 'The field \"Bugs number per page\" must not be empty';
$lang['bugs.error.e_no_fixed_version'] = 'Please select the correction version before choosing the status "' . $lang['bugs.status.fixed'] . '"';
$lang['bugs.error.e_unexist_bug'] = 'This bug does not exist';
$lang['bugs.error.e_unexist_parameter'] = 'This parameter does not exist';
$lang['bugs.error.e_unexist_type'] = 'This type does not exist';
$lang['bugs.error.e_unexist_category'] = 'This category does not exist';
$lang['bugs.error.e_unexist_severity'] = 'This severity does not exist';
$lang['bugs.error.e_unexist_priority'] = 'This priority does not exist';
$lang['bugs.error.e_unexist_version'] = 'This version does not exist';
$lang['bugs.error.e_already_rejected_bug'] = 'This bug is already rejected';
$lang['bugs.error.e_already_reopen_bug'] = 'This bug is already reopen';
$lang['bugs.error.e_unexist_pm_type'] = 'This type of PM does not exist';

//Success
$lang['bugs.success.config'] = 'The configuration has been modified';
$lang['bugs.success.add'] = 'The bug #:id has been committed';
$lang['bugs.success.edit'] = 'The bug #:id has been updated';
$lang['bugs.success.fixed'] = 'The bug #:id has been fixed';
$lang['bugs.success.delete'] = 'The bug #:id has been deleted';
$lang['bugs.success.reject'] = 'The bug #:id has been rejected';
$lang['bugs.success.reopen'] = 'The bug #:id has been reopen';
?>
