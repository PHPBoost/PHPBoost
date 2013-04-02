<?php
/*##################################################
 *                            contact_common.php
 *                            -------------------
 *   begin                : July 29, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
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
 #						French                      #
 ####################################################

//Titre
$lang['title_contact'] = 'Contact';

//Contact
$lang['contact_title'] = 'Contacter les gestionnaires du site';
$lang['your_mail_address'] = 'Votre adresse mail';
$lang['your_mail_address_explain'] = 'L\'adresse doit être valide pour que vous puissiez obtenir une réponse';
$lang['contact_subject'] = 'Objet';
$lang['contact_subject_explain'] = 'Résumez en quelques mot l\'objet de votre demande';
$lang['message'] = 'Message';
$lang['success_mail'] = 'Votre message a été envoyé avec succès.';
$lang['error_mail'] = 'Désolé, votre mail n\'a pas pu être envoyé pour des raisons techniques.';
$lang['left'] = 'Gauche';
$lang['top'] = 'Haut';
$lang['right'] = 'Droite';
$lang['bottom'] = 'Bas';

//Admin
$lang['contact_config'] = 'Configuration';
$lang['contact_config.title'] = 'Titre du formulaire';
$lang['contact_config.informations_bloc'] = 'Zone d\'informations';
$lang['contact_config.display_informations_bloc'] = 'Afficher la zone d\'informations';
$lang['contact_config.informations_content'] = 'Contenu';
$lang['contact_config.informations.explain'] = 'Cette zone permet d\'afficher des informations (exemple un numéro de téléphone, etc.) à gauche, en haut, à droite ou en dessous du formulaire de contact.';
$lang['contact_config.informations_position'] = 'Position';
$lang['contact_config.fields.manage'] = 'Gestion des champs';
$lang['contact_config.fields.add'] = 'Ajouter un champ';
$lang['contact_config.fields.edit'] = 'Edition d\'un champ';
$lang['contact_config.no_field'] = 'Aucun champ';
$lang['contact_config.display_subject_field'] = 'Afficher le champ "Objet" dans le formulaire ?';
$lang['contact_config.subject_field_mandatory'] = 'Champ "Objet" obligatoire ?';
$lang['contact_config.subject_field_type'] = 'Type de champ "Objet"';
$lang['contact_config.text'] = 'Texte';
$lang['contact_config.select'] = 'Liste';
$lang['contact_config.default_value'] = 'Valeur par défaut';
$lang['contact_config.possible_values'] = 'Valeurs possibles';
$lang['contact_config.anti_spam'] = 'Protection anti-spam';
$lang['contact_config.enable_captcha'] = 'Activer la protection anti-spam';
$lang['contact_config.captcha_difficulty'] = 'Difficulté de l\'anti spam';

//Messages
$lang['contact.message.success_saving_config'] = 'La configuration a été enregistrée avec succès';
$lang['contact.message.error_empty_title'] = 'Veuillez remplir le titre du formulaire';
$lang['contact.message.error_default_value'] = 'La valeur par défaut ne fait pas partie de la liste des valeurs possibles';
?>
