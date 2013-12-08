<?php
/*##################################################
 *                            common.php
 *                            -------------------
 *   begin                : August 1, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
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
 #						French						#
 ####################################################

//Titre
$lang['module_title'] = 'Contact';
$lang['module_config_title'] = 'Configuration du module contact';

//Contact form
$lang['contact.form.message'] = 'Message';

//Admin
$lang['admin.config.title'] = 'Titre du formulaire';
$lang['admin.config.informations_bloc'] = 'Zone d\'informations';
$lang['admin.config.display_informations_bloc'] = 'Afficher la zone d\'informations';
$lang['admin.config.informations_content'] = 'Contenu de la zone d\'informations';
$lang['admin.config.informations.explain'] = 'Cette zone permet d\'afficher des informations (exemple un numéro de téléphone, etc.) à gauche, en haut, à droite ou en dessous du formulaire de contact.';
$lang['admin.config.informations_position'] = 'Position de la zone d\'informations';
$lang['admin.config.informations.position_left'] = 'Gauche';
$lang['admin.config.informations.position_top'] = 'Haut';
$lang['admin.config.informations.position_right'] = 'Droite';
$lang['admin.config.informations.position_bottom'] = 'Bas';
$lang['admin.authorizations.read']  = 'Autorisation d\'afficher le formulaire de contact';
$lang['admin.authorizations.display_field']  = 'Autorisation d\'afficher le champ';

//Fields
$lang['admin.fields.manage'] = 'Gestion des champs';
$lang['admin.fields.manage.page_title'] = 'Gestion des champs du formulaire du module contact';
$lang['admin.fields.title.add_field'] = 'Ajout d\'un nouveau champ';
$lang['admin.fields.title.add_field.page_title'] = 'Ajout d\'un nouveau champ dans le formulaire du module contact';
$lang['admin.fields.title.edit_field'] = 'Edition d\'un champ';
$lang['admin.fields.title.edit_field.page_title'] = 'Edition d\'un champ dans le formulaire du module contact';
$lang['admin.fields.action.add_field'] = 'Ajouter un champ';
$lang['admin.fields.action.edit_field'] = 'Modifier le champ';
$lang['admin.fields.action.delete_field'] = 'Supprimer le champ';
$lang['admin.fields.delete_field.confirm'] = 'Souhaitez vous vraiment supprimer ce champ ?';
$lang['admin.fields.update_fields_position'] = 'Valider la position des champs';
$lang['admin.fields.no_field'] = 'Aucun champ';
$lang['admin.fields.move_field_up'] = 'Monter le champ';
$lang['admin.fields.move_field_down'] = 'Descendre le champ';

//Field
$lang['admin.field.name'] = 'Nom';
$lang['admin.field.description'] = 'Description';
$lang['admin.field.type'] = 'Type de champ';
$lang['admin.field.regex'] = 'Contrôle de la forme de l\'entrée';
$lang['admin.field.regex-explain'] = 'Permet d\'effectuer un contrôle sur la forme de ce que l\'utilisateur a entré. Par exemple, si il s\'agit d\'une adresse mail, on peut contrôler que sa forme est correcte. <br />Vous pouvez effectuer un contrôle personnalisé en tapant une expression régulière (utilisateurs expérimentés seulement).';
$lang['admin.field.predefined-regex'] = 'Forme prédéfinie';
$lang['admin.field.required'] = 'Champ requis';
$lang['admin.field.possible-values'] = 'Valeurs possibles';
$lang['admin.field.possible_values.is_default'] = 'Par défaut';
$lang['admin.field.possible_values.email'] = 'Adresse(s) email';
$lang['admin.field.possible_values.email.explain'] = 'Il est possible d\'indiquer plusieurs adresses email séparées par un point-virgule';
$lang['admin.field.possible_values.recipient'] = 'Destinataire(s)';
$lang['admin.field.possible_values.recipient.explain'] = 'Le mail sera envoyé au(x) destinataire(s) sélectionné(s) si le champ destinataire n\'est pas affiché';
$lang['admin.field.display'] = 'Afficher';
$lang['admin.field.not_display'] = 'Ne pas afficher';
$lang['admin.field.yes'] = 'Oui';
$lang['admin.field.no'] = 'Non';

//Field type
$lang['field.type.short-text'] = 'Texte court (max 255 caractères)';
$lang['field.type.long-text'] = 'Texte long (illimité)';
$lang['field.type.half-text'] = 'Text semi long';
$lang['field.type.simple-select'] = 'Sélection unique (parmi plusieurs valeurs)';
$lang['field.type.multiple-select'] = 'Sélection multiple (parmi plusieurs valeurs)';
$lang['field.type.simple-check'] = 'Choix unique (parmi plusieurs valeurs)';
$lang['field.type.multiple-check'] = 'Choix multiples (parmi plusieurs valeurs)';
$lang['field.type.date'] = 'Date';

// Regex
$lang['regex.figures'] = 'Chiffres';
$lang['regex.letters'] = 'Lettres';
$lang['regex.figures-letters'] = 'Chiffres et lettres';
$lang['regex.word'] = 'Mot';
$lang['regex.website'] = 'Site web';
$lang['regex.mail'] = 'Mail';
$lang['regex.personnal-regex'] = 'Expression régulière personnalisée';

//Messages
$lang['message.field_name_already_used'] = 'Le nom du champ entré est déjà utilisé !';
$lang['message.success_mail'] = 'Votre message a été envoyé avec succès';
$lang['message.error_mail'] = 'Désolé, votre mail n\'a pas pu être envoyé pour des raisons techniques';
?>
