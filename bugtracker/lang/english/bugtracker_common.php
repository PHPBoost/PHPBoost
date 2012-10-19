<?php
/*##################################################
 *                              bugtracker_english.php
 *                            -------------------
 *   begin                : February 01, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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
 #						English						#
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
$lang['bugs.notice.require_login'] = 'Please enter a login!';
$lang['bugs.notice.require_type'] = 'Please enter a name for the new type!';
$lang['bugs.notice.require_category'] = 'Please enter a name for the new category!';
$lang['bugs.notice.require_priority'] = 'Please enter a name for the new priority!';
$lang['bugs.notice.require_severity'] = 'Please enter a name for the new severity!';
$lang['bugs.notice.require_version'] = 'Please enter a name for the new version!';
$lang['bugs.notice.require_choose_type'] = 'Please choose the type of your bug!';
$lang['bugs.notice.require_choose_category'] = 'Please choose the category of your bug!';
$lang['bugs.notice.require_choose_priority'] = 'Please choose the priority of your bug!';
$lang['bugs.notice.require_choose_severity'] = 'Please choose the severity of your bug!';
$lang['bugs.notice.require_choose_detected_in'] = 'Please choose the version concerned by your bug!';
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
$lang['bugs.actions.confirm.del_version'] = 'Delete this version?';
$lang['bugs.actions.confirm.del_type'] = 'Delete this type?';
$lang['bugs.actions.confirm.del_category'] = 'Delete this category?';
$lang['bugs.actions.confirm.del_priority'] = 'Delete this priority?';
$lang['bugs.actions.confirm.del_severity'] = 'Delete this severity?';

//Titles
$lang['bugs.titles.add_bug'] = 'New bug';
$lang['bugs.titles.add_version'] = 'Add a new version';
$lang['bugs.titles.add_type'] = 'Add a new type';
$lang['bugs.titles.add_category'] = 'Add a new category';
$lang['bugs.titles.add_priority'] = 'Add a new priority';
$lang['bugs.titles.add_severity'] = 'Add a new severity';
$lang['bugs.titles.edit_bug'] = 'Bug edition';
$lang['bugs.titles.history_bug'] = 'Bug history';
$lang['bugs.titles.view_bug'] = 'Bug';
$lang['bugs.titles.roadmap'] = 'Roadmap';
$lang['bugs.titles.bugs_infos'] = 'Bug\'s informations';
$lang['bugs.titles.bugs_stats'] = 'Statistics';
$lang['bugs.titles.bugs_treatment'] = 'Bug\'s treatment';
$lang['bugs.titles.bugs_treatment_state'] = 'Bug\'s treatment state';
$lang['bugs.titles.disponible_versions'] = 'Disponible versions';
$lang['bugs.titles.disponible_types'] = 'Disponible types';
$lang['bugs.titles.disponible_categories'] = 'Disponible categories';
$lang['bugs.titles.disponible_priorities'] = 'Disponible priorities';
$lang['bugs.titles.disponible_severities'] = 'Disponible severities';
$lang['bugs.titles.admin.management'] = 'Bugtracker management';
$lang['bugs.titles.admin.config'] = 'Configuration';
$lang['bugs.titles.admin.authorizations'] = 'Authorizations';
$lang['bugs.titles.choose_version'] = 'Version to display';
$lang['bugs.titles.solved_bugs'] = 'Fixed bugs';
$lang['bugs.titles.unsolved_bugs'] = 'Unresolved bugs';
$lang['bugs.titles.contents_value_title'] = 'Request default description';
$lang['bugs.titles.contents_value'] = 'Default description';

//Labels
$lang['bugs.labels.fields.id'] = 'ID';
$lang['bugs.labels.fields.title'] = 'Title';
$lang['bugs.labels.fields.contents'] = 'Description';
$lang['bugs.labels.fields.author_id'] = 'Detected by';
$lang['bugs.labels.fields.submit_date'] = 'Detected on';
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
$lang['bugs.labels.color'] = 'Color';
$lang['bugs.labels.number'] = 'Bugs number';
$lang['bugs.labels.number_corrected'] = 'Corrected bugs number';
$lang['bugs.labels.top_10_posters'] = 'Top 10: posters';
$lang['bugs.labels.default'] = 'Default value';
$lang['bugs.labels.del_default_value'] = 'Delete default value';
$lang['bugs.labels.type_mandatory'] = 'Section "Type" mandatory?';
$lang['bugs.labels.category_mandatory'] = 'Section "Category" mandatory?';
$lang['bugs.labels.severity_mandatory'] = 'Section "Severity" mandatory?';
$lang['bugs.labels.priority_mandatory'] = 'Section "Priority" mandatory?';
$lang['bugs.labels.detected_in_mandatory'] = 'Section "Detected in version" mandatory?';
$lang['bugs.labels.date_format'] = 'Date display format';
$lang['bugs.labels.date_time'] = 'Date and time';

//Status
$lang['bugs.status.new'] = 'New';
$lang['bugs.status.assigned'] = 'Assigned';
$lang['bugs.status.fixed'] = 'Fixed';
$lang['bugs.status.reopen'] = 'Reopen';
$lang['bugs.status.rejected'] = 'Rejected';

//Explainations
$lang['bugs.explain.roadmap'] = 'Displays the fixed bug list for each version';
$lang['bugs.explain.pm'] = 'A PM will be send in the following situations :<br />
- New bug comment<br />
- Bug edition<br />
- A bug is deleted<br />
- A bug is assigned<br />
- A bug is rejected<br />
- A bug is reopened<br />';
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
$lang['bugs.pm.assigned.title'] = '[%s] The bug #%d has been assigned to you by %s';
$lang['bugs.pm.assigned.contents'] = 'Clic here to display the detail of the bug :
%s';
$lang['bugs.pm.comment.title'] = '[%s] A new comment has been posted for the bug #%d by %s';
$lang['bugs.pm.comment.contents'] = '%s add the following comment to the bug #%d:

%s

Bug link :
%s';
$lang['bugs.pm.edit.title'] = '[%s] The bug #%d has been updated by %s';
$lang['bugs.pm.edit.contents'] = '%s has updated the following fields in the bug #%d :

%s

Bug link :
%s';
$lang['bugs.pm.reopen.title'] = '[%s] The bug #%d has been reopen by %s';
$lang['bugs.pm.reopen.contents'] = '%s a ré-ouvert le bug #%d.
Bug link :
%s';
$lang['bugs.pm.reject.title'] = '[%s] The bug #%d has been rejected by %s';
$lang['bugs.pm.reject.contents'] = '%s a rejeté le bug #%d.
Bug link :
%s';
$lang['bugs.pm.delete.title'] = '[%s] The bug #%d has been deleted by %s';
$lang['bugs.pm.delete.contents'] = '%s a suppriméé le bug #%d.
Bug link :
%s';

//Search
$lang['bugs.search.where'] = 'Where?';
$lang['bugs.search.where.title'] = 'Title';
$lang['bugs.search.where.contents'] = 'Content';

//Configuration
$lang['bugs.config.items_per_page'] = 'Bugs number per page'; 
$lang['bugs.config.rejected_bug_color_label'] = 'Rejected bug line color';
$lang['bugs.config.fixed_bug_color_label'] = 'Fixed bug line color';
$lang['bugs.config.activ_com'] = 'Active comments';
$lang['bugs.config.activ_roadmap'] = 'Active roadmap';
$lang['bugs.config.activ_cat_in_title'] = 'Display category in bug title';
$lang['bugs.config.activ_pm'] = 'Active PM send';

//Permissions
$lang['bugs.config.auth'] = 'Permissions';
$lang['bugs.config.auth.read'] = 'Permission to display the bugs list';
$lang['bugs.config.auth.create'] = 'Permission to post a bug';
$lang['bugs.config.auth.create_advanced'] = 'Advanced permission to post a bug';
$lang['bugs.config.auth.create_advanced_explain'] = 'Permits to choose the severity and the priority of the bug';
$lang['bugs.config.auth.moderate'] = 'Permission to moderate the Bugtracker';

//Errors
$lang['bugs.error.require_items_per_page'] = 'The field \"Bugs number per page\" must not be empty';
$lang['bugs.error.e_no_user_assigned'] = 'There is no user assigned for this bug, the status can\'t be "' . $lang['bugs.status.assigned'] . '"';
$lang['bugs.error.e_no_fixed_version'] = 'Please select the correction version before choosing the status "' . $lang['bugs.status.fixed'] . '"';
$lang['bugs.error.e_config_success'] = 'The configuration has successfully been modified';
$lang['bugs.error.e_edit_success'] = 'The bug has successfully been updated';
$lang['bugs.error.e_delete_success'] = 'The bug has successfully been deleted';
$lang['bugs.error.e_reject_success'] = 'The bug has been rejected';
$lang['bugs.error.e_reopen_success'] = 'The bug has been reopen';
$lang['bugs.error.e_unexist_bug'] = 'This bug does not exist';
$lang['admin.success-saving-config'] = 'You have successfully changed the configuration';

?>