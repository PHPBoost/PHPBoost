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
$LANG['bugs.notice.no_version'] = 'No version';
$LANG['bugs.notice.no_type'] = 'No type declared';
$LANG['bugs.notice.no_category'] = 'No category declared';
$LANG['bugs.notice.no_history'] = 'This bug has no history';
$LANG['bugs.notice.contents_update'] = 'Contents update';
$LANG['bugs.notice.reproduction_method_update'] = 'Reproduction method update';
$LANG['bugs.notice.not_defined'] = 'Not defined';
$LANG['bugs.notice.require_login'] = 'Please enter a login !';
$LANG['bugs.notice.require_type'] = 'Please enter a type !';
$LANG['bugs.notice.require_category'] = 'Please enter a category !';
$LANG['bugs.notice.require_version'] = 'Please enter a version !';
$LANG['bugs.notice.joker'] = 'Use * for a joker';

//Actions
$LANG['bugs.actions'] = 'Actions';
$LANG['bugs.actions.add'] = 'Post a new bug';
$LANG['bugs.actions.delete'] = 'Delete bug';
$LANG['bugs.actions.edit'] = 'Edit bug';
$LANG['bugs.actions.history'] = 'Bug\'s history';
$LANG['bugs.actions.confirm.del_bug'] = 'Delete this bug? (All the history related to this bug will be deleted)';
$LANG['bugs.actions.confirm.del_version'] = 'Delete this version?';
$LANG['bugs.actions.confirm.del_type'] = 'Delete this type?';
$LANG['bugs.actions.confirm.del_category'] = 'Delete this category?';

//Titles
$LANG['bugs.titles.add_bug'] = 'Add a new bug';
$LANG['bugs.titles.add_version'] = 'Add a new version';
$LANG['bugs.titles.add_type'] = 'Add a new type';
$LANG['bugs.titles.add_category'] = 'Add a new category';
$LANG['bugs.titles.edit_bug'] = 'Bug edition';
$LANG['bugs.titles.history_bug'] = 'Bug history';
$LANG['bugs.titles.view_bug'] = 'Bug view';
$LANG['bugs.titles.bugs_list'] = 'Bugs list';
$LANG['bugs.titles.bugs_infos'] = 'Bug\'s informations';
$LANG['bugs.titles.bugs_treatment_state'] = 'Bug\'s treatment state';
$LANG['bugs.titles.disponible_versions'] = 'Disponible versions';
$LANG['bugs.titles.disponible_types'] = 'Disponible types';
$LANG['bugs.titles.disponible_categories'] = 'Disponible categories';
$LANG['bugs.titles.admin.management'] = 'Bugtracker management';
$LANG['bugs.titles.admin.config'] = 'Configuration Bugtracker';
$LANG['bugs.titles.edit_type'] = 'Type edition';
$LANG['bugs.titles.edit_category'] = 'Category edition';
$LANG['bugs.titles.edit_version'] = 'Version edition';

//Labels
$LANG['bugs.labels.fields.id'] = 'ID';
$LANG['bugs.labels.fields.title'] = 'Title';
$LANG['bugs.labels.fields.contents'] = 'Description';
$LANG['bugs.labels.fields.author_id'] = 'Detected by';
$LANG['bugs.labels.fields.submit_date'] = 'Detected on';
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

//Priorities
$LANG['bugs.priority.none'] = 'None';
$LANG['bugs.priority.low'] = 'Low';
$LANG['bugs.priority.normal'] = 'Normal';
$LANG['bugs.priority.high'] = 'High';
$LANG['bugs.priority.urgent'] = 'Urgent';
$LANG['bugs.priority.immediate'] = 'Immediate';

//Status
$LANG['bugs.status.new'] = 'New';
$LANG['bugs.status.assigned'] = 'Assigned';
$LANG['bugs.status.fixed'] = 'Fixed';
$LANG['bugs.status.verified'] = 'Verified';
$LANG['bugs.status.closed'] = 'Closed';
$LANG['bugs.status.reopen'] = 'Reopen';
$LANG['bugs.status.rejected'] = 'Rejected';
$LANG['bugs.status.pending'] = 'Pending';

//Severities
$LANG['bugs.severity.minor'] = 'Minor';
$LANG['bugs.severity.major'] = 'Major';
$LANG['bugs.severity.critical'] = 'Critical';

//Configuration
$LANG['bugs.config.items_per_page'] = 'Bugs number per page'; 
$LANG['bugs.config.severity_color_label'] = 'Severity color';
$LANG['bugs.config.closed_bug_color_label'] = 'Closed bug line color';
$LANG['bugs.config.activ_com'] = 'Active comments';

//Explainations
$LANG['bugs.explain.type'] = 'Types des demandes. Examples : Anomaly, Evolution...<br />
<br />
Remarques : <br />
- Si le tableau est vide, cette option ne sera pas visible lors de la signalisation d\'un bug<br />
- Si le tableau ne contient qu\'une seule valeur, cette option ne sera pas non plus visible et sera attribuée par défaut au bug';
$LANG['bugs.explain.category'] = 'Catégorie des demandes. Exemples : Noyau, Module...<br />
<br />
Remarques : <br />
- Si le tableau est vide, cette option ne sera pas visible lors de la signalisation d\'un bug<br />
- Si le tableau ne contient qu\'une seule valeur, cette option ne sera pas non plus visible et sera attribuée par défaut au bug';
$LANG['bugs.explain.version'] = 'Liste des versions du produit.<br />
<br />
Remarques :<br />
- Si le tableau est vide, l\'option "Détecté dans la version" ne sera pas visible lors de la signalisation d\'un bug<br />
- Si le tableau ne contient qu\'une seule valeur, cette option ne sera pas non plus visible et sera attribuée par défaut au bug';

//Search
$LANG['bugs.search.where'] = 'Where?';
$LANG['bugs.search.where.title'] = 'Title';
$LANG['bugs.search.where.contents'] = 'Content';

//Permissions
$LANG['bugs.config.auth'] = 'Permissions';
$LANG['bugs.config.auth.read'] = 'Permission to display the bugs list';
$LANG['bugs.config.auth.create'] = 'Permission to post a bug';
$LANG['bugs.config.auth.create_advanced'] = 'Advanced permission to post a bug';
$LANG['bugs.config.auth.create_advanced_explain'] = 'Permits to choose the severity and the priority of the bug';

//Errors
$LANG['bugs.error.require_items_per_page'] = 'The field \"Bugs number per page\" must not be empty';
$LANG['bugs.error.e_bad_status'] = 'The new status selected is incorrect';
$LANG['bugs.error.e_no_user_assigned'] = 'There is no user assigned for this bug, the status can\'t be "' . $LANG['bugs.status.assigned'] . '"';
$LANG['bugs.error.e_config_success'] = 'The configuration has successfully been modified';
$LANG['bugs.error.e_edit_success'] = 'The bug has successfully been updated';
$LANG['bugs.error.e_edit_type_success'] = 'The type has successfully been updated';
$LANG['bugs.error.e_edit_category_success'] = 'The category has successfully been updated';
$LANG['bugs.error.e_edit_version_success'] = 'The version has successfully been updated';
?>