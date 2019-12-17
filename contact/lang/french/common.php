<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 12 20
 * @since       PHPBoost 4.0 - 2013 08 01
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

####################################################
#                      French                      #
####################################################

//Titre
$lang['module_title'] = 'Contact';
$lang['module_config_title'] = 'Configuration du module contact';

//Contact form
$lang['contact.form.message'] = 'Message';
$lang['contact.send_another_mail'] = 'Envoyer un autre email';
$lang['contact.tracking_number'] = 'Numéro de suivi';
$lang['contact.acknowledgment_title'] = 'Confirmation';
$lang['contact.acknowledgment'] = 'Votre message a été envoyé correctement.';

//Admin
$lang['admin.config.title'] = 'Titre du formulaire';
$lang['admin.config.informations_bloc'] = 'Zone d\'informations';
$lang['admin.config.informations_enabled'] = 'Afficher la zone d\'informations';
$lang['admin.config.informations_content'] = 'Contenu de la zone d\'informations';
$lang['admin.config.informations.explain'] = 'Cette zone permet d\'afficher des informations (exemple un numéro de téléphone, etc.) à gauche, en haut, à droite ou en dessous du formulaire de contact.';
$lang['admin.config.informations_position'] = 'Position de la zone d\'informations';
$lang['admin.config.informations.position_left'] = 'Gauche';
$lang['admin.config.informations.position_top'] = 'Haut';
$lang['admin.config.informations.position_right'] = 'Droite';
$lang['admin.config.informations.position_bottom'] = 'Bas';
$lang['admin.config.tracking_number_enabled'] = 'Générer un numéro de suivi pour chaque mail envoyé';
$lang['admin.config.date_in_date_in_tracking_number_enabled'] = 'Afficher la date du jour dans le numéro de suivi';
$lang['admin.config.date_in_date_in_tracking_number_enabled.explain'] = 'Permet de générer un numéro de suivi de la forme <b>aaaammjj-numéro</b>';
$lang['admin.config.sender_acknowledgment_enabled'] = 'Envoyer une copie du mail à l\'émetteur';
$lang['admin.authorizations.read']  = 'Autorisation d\'afficher le formulaire de contact';
$lang['admin.authorizations.display_field']  = 'Autorisation d\'afficher le champ';

//Map
$lang['admin.config.map'] = 'Localisation sur une carte';
$lang['admin.config.map_enabled'] = 'Afficher la carte';
$lang['admin.config.map_position'] = 'Position de la carte';
$lang['admin.config.map.position_top'] = 'Au dessus du formulaire';
$lang['admin.config.map.position_bottom'] = 'En dessous du formulaire';
$lang['admin.config.map.markers'] = 'Adresse(s)';

//Fields
$lang['admin.fields.manage'] = 'Gestion des champs';
$lang['admin.fields.manage.page_title'] = 'Gestion des champs du formulaire du module contact';
$lang['admin.fields.title.add_field'] = 'Ajout d\'un nouveau champ';
$lang['admin.fields.title.add_field.page_title'] = 'Ajout d\'un nouveau champ dans le formulaire du module contact';
$lang['admin.fields.title.edit_field'] = 'Edition d\'un champ';
$lang['admin.fields.title.edit_field.page_title'] = 'Edition d\'un champ dans le formulaire du module contact';

//Field
$lang['field.possible_values.email'] = 'Adresse(s) email';
$lang['field.possible_values.email.explain'] = 'Il est possible d\'indiquer plusieurs adresses email séparées par une virgule';
$lang['field.possible_values.subject'] = 'Objet';
$lang['field.possible_values.recipient'] = 'Destinataire(s)';
$lang['field.possible_values.recipient.explain'] = 'Le mail sera envoyé au(x) destinataire(s) sélectionné(s) si le champ destinataire n\'est pas affiché';

//SEO
$lang['contact.seo.description'] = 'Formulaire de contact du site :site.';

//Messages
$lang['message.success.add'] = 'Le champ <b>:name</b> a été ajouté';
$lang['message.success.edit'] = 'Le champ <b>:name</b> a été modifié';
$lang['message.field_name_already_used'] = 'Le nom du champ entré est déjà utilisé !';
$lang['message.success_mail'] = 'Votre message a été envoyé avec succès.';
$lang['message.acknowledgment'] = 'Un message de confirmation vous a été envoyé par mail.';
$lang['message.error_mail'] = 'Désolé, votre mail n\'a pas pu être envoyé pour des raisons techniques.';
?>
