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
# English                                          #
####################################################
 
//Module title
$LANG['bugs.module_title'] = 'Bugtracker';

//Notice
$LANG['bugs.notice.no_one'] = 'No one';
$LANG['bugs.notice.none'] = 'None';
$LANG['bugs.notice.none_e'] = 'None';
$LANG['bugs.notice.no_bug'] = 'No bug declared';
$LANG['bugs.notice.no_bug_solved'] = 'No bug solved';
$LANG['bugs.notice.no_bug_fixed'] = 'No bug fixed in this version';
$LANG['bugs.notice.no_version'] = 'No version';
$LANG['bugs.notice.no_type'] = 'No type declared';
$LANG['bugs.notice.no_category'] = 'No category declared';
$LANG['bugs.notice.no_priority'] = 'No priority declared';
$LANG['bugs.notice.no_severity'] = 'No severity declared';
$LANG['bugs.notice.no_history'] = 'This bug has no history';
$LANG['bugs.notice.contents_update'] = 'Contents update';
$LANG['bugs.notice.new_comment'] = 'New comment';
$LANG['bugs.notice.reproduction_method_update'] = 'Reproduction method update';
$LANG['bugs.notice.not_defined'] = 'Not defined';
$LANG['bugs.notice.not_defined_e_date'] = 'Date not defined';
$LANG['bugs.notice.require_login'] = 'Please enter a login!';
$LANG['bugs.notice.require_type'] = 'Please enter a name for the new type!';
$LANG['bugs.notice.require_category'] = 'Please enter a name for the new category!';
$LANG['bugs.notice.require_priority'] = 'Please enter a name for the new priority!';
$LANG['bugs.notice.require_severity'] = 'Please enter a name for the new severity!';
$LANG['bugs.notice.require_version'] = 'Please enter a name for the new version!';
$LANG['bugs.notice.require_choose_type'] = 'Please choose the type of your bug!';
$LANG['bugs.notice.require_choose_category'] = 'Please choose the category of your bug!';
$LANG['bugs.notice.require_choose_priority'] = 'Please choose the priority of your bug!';
$LANG['bugs.notice.require_choose_severity'] = 'Please choose the severity of your bug!';
$LANG['bugs.notice.require_choose_detected_in'] = 'Please choose the version concerned by your bug!';
$LANG['bugs.notice.joker'] = 'Use * for a joker';

//Actions
$LANG['bugs.actions'] = 'Actions';
$LANG['bugs.actions.add'] = 'New bug';
$LANG['bugs.actions.delete'] = 'Delete bug';
$LANG['bugs.actions.edit'] = 'Edit bug';
$LANG['bugs.actions.history'] = 'Bug\'s history';
$LANG['bugs.actions.reject'] = 'Reject bug';
$LANG['bugs.actions.reopen'] = 'Reopen bug';
$LANG['bugs.actions.confirm.del_bug'] = 'Delete this bug? (All the history related to this bug will be deleted)';
$LANG['bugs.actions.confirm.del_version'] = 'Delete this version?';
$LANG['bugs.actions.confirm.del_type'] = 'Delete this type?';
$LANG['bugs.actions.confirm.del_category'] = 'Delete this category?';
$LANG['bugs.actions.confirm.del_priority'] = 'Delete this priority?';
$LANG['bugs.actions.confirm.del_severity'] = 'Delete this severity?';

//Titles
$LANG['bugs.titles.add_bug'] = 'New bug';
$LANG['bugs.titles.add_version'] = 'Add a new version';
$LANG['bugs.titles.add_type'] = 'Add a new type';
$LANG['bugs.titles.add_category'] = 'Add a new category';
$LANG['bugs.titles.add_priority'] = 'Add a new priority';
$LANG['bugs.titles.add_severity'] = 'Add a new severity';
$LANG['bugs.titles.edit_bug'] = 'Bug edition';
$LANG['bugs.titles.history_bug'] = 'Bug history';
$LANG['bugs.titles.view_bug'] = 'Bug';
$LANG['bugs.titles.roadmap'] = 'Roadmap';
$LANG['bugs.titles.bugs_infos'] = 'Bug\'s informations';
$LANG['bugs.titles.bugs_stats'] = 'Statistics';
$LANG['bugs.titles.bugs_treatment'] = 'Bug\'s treatment';
$LANG['bugs.titles.bugs_treatment_state'] = 'Bug\'s treatment state';
$LANG['bugs.titles.versions'] = 'Versions';
$LANG['bugs.titles.types'] = 'Types';
$LANG['bugs.titles.categories'] = 'Categories';
$LANG['bugs.titles.priorities'] = 'Priorities';
$LANG['bugs.titles.severities'] = 'Severities';
$LANG['bugs.titles.admin.management'] = 'Bugtracker management';
$LANG['bugs.titles.admin.config'] = 'Configuration';
$LANG['bugs.titles.admin.authorizations'] = 'Authorizations';
$LANG['bugs.titles.choose_version'] = 'Version to display';
$LANG['bugs.titles.solved_bugs'] = 'Fixed bugs';
$LANG['bugs.titles.unsolved_bugs'] = 'Unresolved bugs';
$LANG['bugs.titles.contents_value_title'] = 'Request default description';
$LANG['bugs.titles.contents_value'] = 'Default description';

//Labels
$LANG['bugs.labels.fields.id'] = 'ID';
$LANG['bugs.labels.fields.title'] = 'Title';
$LANG['bugs.labels.fields.contents'] = 'Description';
$LANG['bugs.labels.fields.author_id'] = 'Detected by';
$LANG['bugs.labels.fields.submit_date'] = 'Detected on';
$LANG['bugs.labels.fields.fix_date'] = 'Fixed on';
$LANG['bugs.labels.fields.status'] = 'Status';
$LANG['bugs.labels.fields.type'] = 'Type';
$LANG['bugs.labels.fields.category'] = 'Category';
$LANG['bugs.labels.fields.reproductible'] = 'Reproductible';
$LANG['bugs.labels.fields.reproduction_method'] = 'Reproduction method';
$LANG['bugs.labels.fields.severity'] = 'Level';
$LANG['bugs.labels.fields.priority'] = 'Priority';
$LANG['bugs.labels.fields.detected_in'] = 'Detected in version';
$LANG['bugs.labels.fields.fixed_in'] = 'Fixed in version';
$LANG['bugs.labels.fields.assigned_to_id'] = 'Assigned to';
$LANG['bugs.labels.fields.updater_id'] = 'Updated by';
$LANG['bugs.labels.fields.update_date'] = 'Updated on';
$LANG['bugs.labels.fields.updated_field'] = 'Updated field';
$LANG['bugs.labels.fields.old_value'] = 'Old value';
$LANG['bugs.labels.fields.new_value'] = 'New value';
$LANG['bugs.labels.fields.change_comment'] = 'Comment';
$LANG['bugs.labels.fields.version'] = 'Version';
$LANG['bugs.labels.fields.version_detected_in'] = 'Display in the list "Detected in version"';
$LANG['bugs.labels.fields.version_fixed_in'] = 'Display in the list "Fixed in version"';
$LANG['bugs.labels.fields.version_detected'] = 'Detected version';
$LANG['bugs.labels.fields.version_fixed'] = 'Fixed version';
$LANG['bugs.labels.color'] = 'Color';
$LANG['bugs.labels.number'] = 'Bugs number';
$LANG['bugs.labels.number_corrected'] = 'Corrected bugs number';
$LANG['bugs.labels.top_10_posters'] = 'Top 10: posters';
$LANG['bugs.labels.default'] = 'Default value';
$LANG['bugs.labels.del_default_value'] = 'Delete default value';
$LANG['bugs.labels.type_mandatory'] = 'Section "Type" mandatory?';
$LANG['bugs.labels.category_mandatory'] = 'Section "Category" mandatory?';
$LANG['bugs.labels.severity_mandatory'] = 'Section "Severity" mandatory?';
$LANG['bugs.labels.priority_mandatory'] = 'Section "Priority" mandatory?';
$LANG['bugs.labels.detected_in_mandatory'] = 'Section "Detected in version" mandatory?';
$LANG['bugs.labels.date_format'] = 'Date display format';
$LANG['bugs.labels.date_time'] = 'Date and time';
$LANG['bugs.labels.fixed'] = 'Fixed';
$LANG['bugs.labels.release_date'] = 'Release date';

//Status
$LANG['bugs.status.new'] = 'New';
$LANG['bugs.status.assigned'] = 'Assigned';
$LANG['bugs.status.fixed'] = 'Fixed';
$LANG['bugs.status.reopen'] = 'Reopen';
$LANG['bugs.status.rejected'] = 'Rejected';

//Explainations
$LANG['bugs.explain.contents'] = 'Useful details to treat the bug';
$LANG['bugs.explain.roadmap'] = 'Displays the fixed bug list for each version';
$LANG['bugs.explain.pm'] = 'A PM will be send in the following situations :<br />
- New bug comment<br />
- Bug edition<br />
- A bug is deleted<br />
- A bug is assigned<br />
- A bug is rejected<br />
- A bug is reopened<br />';
$LANG['bugs.explain.type'] = 'Demands types. Examples : Anomaly, Evolution...';
$LANG['bugs.explain.category'] = 'Demands categories. Examples : Kernel, Module...';
$LANG['bugs.explain.severity'] = 'Demands severities. Examples : Minor, Major, Critical...';
$LANG['bugs.explain.priority'] = 'Demands priorities. Examples : Low, Normal, High...';
$LANG['bugs.explain.version'] = 'Liste des versions du produit.';
$LANG['bugs.explain.remarks'] = 'Remarks : <br />
- If the table is empty, this option will not be visible on the post bug page<br />
- If the table contains only one value, this option will not be visible too and will automatically be assigned to the bug<br /><br />';
$LANG['bugs.explain.contents_value'] = 'Enter the default description to display for a new bug below. Leave empty if you don\'t want to fill the description.';

//PM
$LANG['bugs.pm.assigned.title'] = '[%s] The bug #%d has been assigned to you by %s';
$LANG['bugs.pm.assigned.contents'] = 'Clic here to display the detail of the bug :
%s';
$LANG['bugs.pm.comment.title'] = '[%s] A new comment has been posted for the bug #%d by %s';
$LANG['bugs.pm.comment.contents'] = '%s add the following comment to the bug #%d:

%s

Bug link :
%s';
$LANG['bugs.pm.edit.title'] = '[%s] The bug #%d has been updated by %s';
$LANG['bugs.pm.edit.contents'] = '%s has updated the following fields in the bug #%d :

%s

Bug link :
%s';
$LANG['bugs.pm.reopen.title'] = '[%s] The bug #%d has been reopen by %s';
$LANG['bugs.pm.reopen.contents'] = '%s a ré-ouvert le bug #%d.
Bug link :
%s';
$LANG['bugs.pm.reject.title'] = '[%s] The bug #%d has been rejected by %s';
$LANG['bugs.pm.reject.contents'] = '%s a rejeté le bug #%d.
Bug link :
%s';
$LANG['bugs.pm.delete.title'] = '[%s] The bug #%d has been deleted by %s';
$LANG['bugs.pm.delete.contents'] = '%s a suppriméé le bug #%d.';

//Search
$LANG['bugs.search.where'] = 'Where?';
$LANG['bugs.search.where.title'] = 'Title';
$LANG['bugs.search.where.contents'] = 'Content';

//Configuration
$LANG['bugs.config.items_per_page'] = 'Bugs number per page'; 
$LANG['bugs.config.rejected_bug_color_label'] = 'Rejected bug line color';
$LANG['bugs.config.fixed_bug_color_label'] = 'Fixed bug line color';
$LANG['bugs.config.activ_com'] = 'Active comments';
$LANG['bugs.config.activ_roadmap'] = 'Active roadmap';
$LANG['bugs.config.activ_cat_in_title'] = 'Display category in bug title';
$LANG['bugs.config.activ_pm'] = 'Active PM send';

//Permissions
$LANG['bugs.config.auth'] = 'Permissions';
$LANG['bugs.config.auth.read'] = 'Permission to display the bugs list';
$LANG['bugs.config.auth.create'] = 'Permission to post a bug';
$LANG['bugs.config.auth.create_advanced'] = 'Advanced permission to post a bug';
$LANG['bugs.config.auth.create_advanced_explain'] = 'Permits to choose the severity and the priority of the bug';
$LANG['bugs.config.auth.moderate'] = 'Permission to moderate the Bugtracker';

//Errors
$LANG['bugs.error.require_items_per_page'] = 'The field \"Bugs number per page\" must not be empty';
$LANG['bugs.error.e_no_user_assigned'] = 'There is no user assigned for this bug, the status can\'t be "' . $LANG['bugs.status.assigned'] . '"';
$LANG['bugs.error.e_no_fixed_version'] = 'Please select the correction version before choosing the status "' . $LANG['bugs.status.fixed'] . '"';
$LANG['bugs.error.e_config_success'] = 'The configuration has successfully been modified';
$LANG['bugs.error.e_types_success'] = 'The types list has successfully been modified';
$LANG['bugs.error.e_categories_success'] = 'The categories list has successfully been modified';
$LANG['bugs.error.e_severities_success'] = 'The severities list has successfully been modified';
$LANG['bugs.error.e_priorities_success'] = 'The priorities list has successfully been modified';
$LANG['bugs.error.e_versions_success'] = 'The versions list has successfully been modified';
$LANG['bugs.error.e_edit_success'] = 'The bug has successfully been updated';
$LANG['bugs.error.e_delete_success'] = 'The bug has successfully been deleted';
$LANG['bugs.error.e_reject_success'] = 'The bug has been rejected';
$LANG['bugs.error.e_reopen_success'] = 'The bug has been reopen';
$LANG['bugs.error.e_unexist_bug'] = 'This bug does not exist';

?>