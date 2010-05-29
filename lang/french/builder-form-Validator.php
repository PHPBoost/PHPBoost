<?php
/*##################################################
 *                        builder-form-Validator.php
 *                            -------------------
 *   begin                : December 20, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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
#                     French                       #
####################################################

$lang = array(
    'doesnt_match_regex' => 'La valeur saisie n\'est pas au bon format',
	'doesnt_match_url_regex' => 'La valeur saisie doit être une url valide',
	'doesnt_match_mail_regex' => 'La valeur saisie doit être un mail valide',
    'doesnt_match_length_intervall' => 'La valeur saisie ne respecte par la longueur définie',
    'doesnt_match_integer_intervall' => 'La valeur saisie ne respecte pas l\'intervalle défini (:lower_bound <= valeur <= :upper_bound)',
    'has_to_be_filled' => 'Ce champ doit être renseigné',
	'captcha_validation_error' => 'Le champs de vérification visuel n\'a pas été saisi correctement !',
	'validation_error' => 'Veuillez corriger les erreurs du formulaire',
	'fields_must_be_equal' => 'Les champs ":field1" et ":field2" doivent être égaux',
	'fields_must_not_be_equal' => 'Les champs ":field1" et ":field2" doivent avoir des valeurs différentes'
);

?>