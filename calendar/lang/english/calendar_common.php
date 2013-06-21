<?php
/*##################################################
 *                              calendar_common.php
 *                            -------------------
 *   begin                : November 20, 2012
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

$lang = array();

//Module title
$lang['calendar.module_title'] = 'Calendar';

//Messages divers
$lang['calendar.notice.no_current_action'] = 'No events scheduled for this date';

//Actions
$lang['calendar.actions.confirm.del_event'] = 'Delete event?';

//Titles
$lang['calendar.titles.admin.config'] = 'Configuration';
$lang['calendar.titles.admin.authorizations'] = 'Authorizations';
$lang['calendar.titles.add_event'] = 'Add event';
$lang['calendar.titles.edit_event'] = 'Edit event';
$lang['calendar.titles.events'] = 'Events';
$lang['calendar.titles.event'] = 'Event';

//Labels
$lang['calendar.labels.title'] = 'Title';
$lang['calendar.labels.contents'] = 'Description';
$lang['calendar.labels.location'] = 'Location';
$lang['calendar.labels.created_by'] = 'Created by';
$lang['calendar.labels.category'] = 'Category';
$lang['calendar.labels.registration_authorized'] = 'Active members registration for the event';
$lang['calendar.labels.max_registred_members'] = 'Maximum participant number';
$lang['calendar.labels.max_registred_members.explain'] = 'Set to 0 for no limit';
$lang['calendar.labels.repeat_type'] = 'Repeat';
$lang['calendar.labels.repeat_number'] = 'Repeat number';
$lang['calendar.labels.repeat.never'] = 'Never';
$lang['calendar.labels.repeat.daily'] = 'All days in the week';
$lang['calendar.labels.repeat.daily_not_weekend'] = 'All days in the week (from monday to friday)';
$lang['calendar.labels.repeat.weekly'] = 'Weekly';
$lang['calendar.labels.repeat.monthly'] = 'Monthly';
$lang['calendar.labels.repeat.yearly'] = 'Yearly';
$lang['calendar.labels.start_date'] = 'Start date';
$lang['calendar.labels.end_date'] = 'End date';
$lang['calendar.labels.approved'] = 'Approved';
$lang['calendar.labels.contribution'] = 'Contribution';
$lang['calendar.labels.contribution.explain'] = 'You aren\'t authorized to add an event, however you can contribute by submitting one. Your contribution will be processed by a moderator.';
$lang['calendar.labels.contribution.description'] = 'Contribution counterpart';
$lang['calendar.labels.contribution.description.explain'] = 'Tell us why you want us to add this event. This field is not required, but it may help the moderator to make his decision.';
$lang['calendar.labels.contribution.entitled'] = '[Calendar] :title';
$lang['calendar.labels.birthday_title'] = 'Birthday of';

//Explications
$lang['calendar.explain.date'] = '<span class="text_small">(mm/dd/yy)</span>';

//Administration
$lang['calendar.config.category.color'] = 'Color';
$lang['calendar.config.category.manage'] = 'Manage categories';
$lang['calendar.config.category.add'] = 'Add category';
$lang['calendar.config.category.edit'] = 'Edit category';
$lang['calendar.config.category.delete'] = 'Delete category';
$lang['calendar.config.items_number_per_page'] = 'Events number per page';
$lang['calendar.config.comments_enabled'] = 'Enable comments';
$lang['calendar.config.location_enabled'] = 'Enable location choice for each event';
$lang['calendar.config.members_birthday_enabled'] = 'Display members birthday';
$lang['calendar.config.birthday_color'] = 'Birthday color';
$lang['calendar.config.authorizations.read'] = 'Read permissions';
$lang['calendar.config.authorizations.write'] = 'Write permissions';
$lang['calendar.config.authorizations.contribution'] = 'Contribution permissions';
$lang['calendar.config.authorizations.moderation'] = 'Moderation permissions';

//Feed name
$lang['calendar.feed.name'] = 'Events';

//Success
$lang['calendar.success.config'] = 'The configuration has been modified';

//Errors
$lang['calendar.error.e_unexist_event'] = 'The selected event doesn\'t exist';
$lang['calendar.error.e_invalid_date'] = 'Invalid date';
$lang['calendar.error.e_invalid_start_date'] = 'Invalid start date';
$lang['calendar.error.e_invalid_end_date'] = 'Invalid end date';
?>
