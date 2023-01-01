<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 20
 * @since       PHPBoost 4.0 - 2013 08 01
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

####################################################
#                      French                      #
####################################################

$lang['contact.module.title']        = 'Contact';

// Form
$lang['contact.form.message']         = 'Message';
$lang['contact.send.another.email']   = 'Envoyer un autre email';
$lang['contact.tracking.number']      = 'Numéro de suivi';
$lang['contact.acknowledgment.title'] = 'Confirmation';
$lang['contact.acknowledgment']       = 'Votre message a été envoyé correctement.';

// Configuration
$lang['contact.form.title']                      = 'Titre du formulaire';
$lang['contact.informations.enabled']            = 'Afficher la zone d\'informations';
$lang['contact.informations.clue']               = 'Cette zone permet d\'afficher des informations supplémentaires à gauche, en haut, à droite ou en dessous du formulaire de contact.';
$lang['contact.informations.content']            = 'Contenu de la zone d\'informations';
$lang['contact.informations.position']           = 'Position de la zone d\'informations';
$lang['contact.informations.position.left']      = 'Gauche';
$lang['contact.informations.position.top']       = 'Haut';
$lang['contact.informations.position.right']     = 'Droite';
$lang['contact.informations.position.bottom']    = 'Bas';
$lang['contact.tracking.number.enabled']         = 'Générer un numéro de suivi pour chaque email envoyé';
$lang['contact.date.in.tracking.number.enabled'] = 'Afficher la date du jour dans le numéro de suivi';
$lang['contact.date.in.tracking.number.clue']    = 'Génère un numéro de suivi de la forme <b>aaaammjj-numéro</b>';
$lang['contact.sender.acknowledgment.enabled']   = 'Envoyer une copie de l\'email à l\'émetteur';
$lang['contact.authorizations.read']             = 'Autorisation d\'afficher le formulaire de contact';
$lang['contact.authorizations.display.field']    = 'Autorisation d\'afficher le champ';
    // Default 
$lang['contact.fieldset.title']     = 'Contacter les gestionnaires du site';
$lang['contact.email.address']      = 'Adresse email';
$lang['contact.email.address.clue'] = 'Votre adresse email doit être valide pour que vous puissiez obtenir une réponse';
$lang['contact.subject']            = 'Objet';
$lang['contact.subject.clue']       = 'Résumez en quelques mot l\'objet de votre demande';
$lang['contact.recipients']         = 'Destinataire(s)';
$lang['contact.recipients.admins']  = 'Administrateurs';
$lang['contact.message']            = 'Message';

// Map
$lang['contact.map.location']        = 'Localisation sur une carte';
$lang['contact.map.enabled']         = 'Afficher la carte';
$lang['contact.map.position']        = 'Position de la carte';
$lang['contact.map.position.top']    = 'Au dessus du formulaire';
$lang['contact.map.position.bottom'] = 'En dessous du formulaire';
$lang['contact.map.markers']         = 'Marqueur.s';

// Fields
$lang['contact.fields.add.field']        = 'Ajout d\'un nouveau champ';
$lang['contact.fields.add.field.title']  = 'Ajout d\'un nouveau champ dans le formulaire du module contact';
$lang['contact.fields.edit.field']       = 'Edition d\'un champ';
$lang['contact.fields.edit.field.title'] = 'Edition d\'un champ dans le formulaire du module contact';

// Field
$lang['contact.possible.values.email']          = 'Adresses email';
$lang['contact.possible.values.email.clue']     = 'Il est possible d\'indiquer plusieurs adresses email séparées par une virgule';
$lang['contact.possible.values.subject']        = 'Objet';
$lang['contact.possible.values.recipient']      = 'Destinataire.s';
$lang['contact.possible.values.recipient.clue'] = 'L\'email sera envoyé au.x destinataire.s sélectionné.s si le champ destinataire n\'est pas affiché';

// SEO
$lang['contact.seo.description'] = 'Formulaire de contact du site :site.';

// Alert messages
$lang['contact.message.success.add']             = 'Le champ <b>:name</b> a été ajouté';
$lang['contact.message.success.edit']            = 'Le champ <b>:name</b> a été modifié';
$lang['contact.message.field.name.already.used'] = 'Le nom du champ entré est déjà utilisé !';
$lang['contact.message.success.email']           = 'Votre message a été envoyé avec succès.';
$lang['contact.message.acknowledgment']          = 'Un message de confirmation vous a été envoyé par email.';
$lang['contact.message.error.email']             = 'Désolé, votre email n\'a pas pu être envoyé pour des raisons techniques.';
?>
