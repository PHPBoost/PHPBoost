<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 09
 * @since       PHPBoost 4.0 - 2013 12 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                    French                        #
####################################################

$lang['builder.explain'] = '
<p>Cette page présente le rendu des éléments créés avec le constructeur php.</p>
<p>Pour accéder aux déclarations PHP de chaque élément, vous pouvez lire <a href="https://github.com/PHPBoost/PHPBoost/blob/master/sandbox/controllers/SandboxBuilderController.class.php#L110">le fichier</a> fourni avec ce module, ou la documentation de l\'API sur le site de <a href="https://www.phpboost.com/api"><i class="fa iboost fa-iboost-phpboost"></i> PHPHBoost</a></p>
';
$lang['builder.title'] = 'Formulaire';

$lang['builder.title.inputs'] = 'Champs texte';
$lang['builder.title.textarea'] = 'Textarea';
$lang['builder.title.choices'] = 'Radio / checkbox';
$lang['builder.title.select'] = 'Sélecteurs';
$lang['builder.title.buttons'] = 'Boutons';
$lang['builder.title.upload'] = 'Upload';
$lang['builder.title.gmap'] = 'Google Maps';
$lang['builder.title.date'] = 'Date';
$lang['builder.title.authorization'] = 'Autorisation';
$lang['builder.title.orientation'] = 'Orientation';

// Text fields
$lang['builder.input.text'] = 'Champ texte';
$lang['builder.input.text.desc'] = 'Contraintes: lettres, chiffres et tiret bas';
$lang['builder.input.text.lorem'] = 'Lorem ipsum';
$lang['builder.input.text.disabled'] = 'Champ désactivé';
$lang['builder.input.text.disabled.desc'] = 'Désactivé';
$lang['builder.input.text.readonly'] = 'Champ en lecture seule';
$lang['builder.input.url'] = 'Site web';
$lang['builder.input.url.desc'] = 'Url valide';
$lang['builder.input.url.placeholder'] = 'https://www.phpboost.com';
$lang['builder.input.email'] = 'Email';
$lang['builder.input.email.desc'] = 'Email valide';
$lang['builder.input.email.placeholder'] = 'lorem@phpboost.com';
$lang['builder.input.email.multiple'] = 'Email multiple';
$lang['builder.input.email.multiple.desc'] = 'Emails valides, séparés par une virgule';
$lang['builder.input.email.multiple.placeholder'] = 'lorem@phpboost.com,ipsum@phpboost.com';
$lang['builder.input.phone'] = 'Numéro de téléphone';
$lang['builder.input.phone.desc'] = 'Numéro de téléphone valide';
$lang['builder.input.phone.placeholder'] = '0123456789';
$lang['builder.input.text.required'] = 'Champ requis';
$lang['builder.input.text.required.filled'] = 'Champ requis rempli';
$lang['builder.input.text.required.empty'] = 'Champ requis vide';
$lang['builder.input.number'] = 'Nombre';
$lang['builder.input.number.desc'] = 'intervalle: de 10 à 100';
$lang['builder.input.number.placeholder'] = '20';
$lang['builder.input.number.decimal'] = 'Nombre décimal';
$lang['builder.input.number.decimal.desc'] = 'Utiliser la virgule';
$lang['builder.input.number.decimal.placeholder'] = '5.5';
$lang['builder.input.length'] = 'Slider';
$lang['builder.input.length.desc'] = 'Faites glisser';
$lang['builder.input.length.placeholder'] = '4';
$lang['builder.input.password'] = 'Mot de passe';
$lang['builder.input.password.desc'] = ' caractères minimum';
$lang['builder.input.password.placeholder'] = 'aaaaaa';
$lang['builder.input.password.confirm'] = 'Confirmation du mot de passe';

// Textareas
$lang['builder.input.multiline.medium'] = 'Champ texte multi lignes moyen';
$lang['builder.input.multiline'] = 'Champ texte multi lignes';
$lang['builder.input.multiline.desc'] = 'Description';
$lang['builder.input.multiline.lorem'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ut tempor lacus.';
$lang['builder.input.rich.text'] = 'Champ texte avec éditeur';
$lang['builder.input.rich.text.placeholder'] = 'Créer un site <strong>facilement</strong>';

// Choices
$lang['builder.input.choices'] = 'Champs à sélection';
$lang['builder.input.checkbox'] = 'Case à cocher';
$lang['builder.input.multiple.checkbox'] = 'Case à cocher multiple';
$lang['builder.input.radio'] = 'Boutons radio';
$lang['builder.input.select'] = 'Liste déroulante';
$lang['builder.input.fake.select'] = 'Liste déroulante avec images/icônes';
$lang['builder.input.multiple.select'] = 'Liste déroulante multiple';
$lang['builder.input.choice'] = 'Choix ';
$lang['builder.input.choice.group'] = 'Groupe ';
$lang['builder.input.timezone'] = 'TimeZone';
$lang['builder.input.user.completion'] = 'Auto complétion utilisateurs';

// Buttons
$lang['builder.all.buttons'] = 'Tous les boutons sont pré-définis avec la classe .button';
$lang['builder.send.button'] = 'Envoyer';
$lang['builder.send.button.alt'] = 'Envoyer .alt';
$lang['builder.preview'] = 'Prévisualiser';
$lang['builder.button'] = 'Bouton';
$lang['builder.button.sizes'] = 'Avec une taille';
$lang['builder.button.colors'] = 'Avec une couleur';
$lang['builder.buttons'] = 'Boutons';
$lang['builder.button.small'] = 'Bouton .small';
$lang['builder.button.basic'] = 'Bouton .basic';
$lang['builder.button.basic.alt'] = 'Bouton .basic.alt';

// Miscellaneaous
$lang['builder.title.miscellaneous'] = 'Divers';

$lang['builder.desc'] = 'Ceci est une description';
$lang['builder.spacer'] = 'Ceci est un saut de ligne, il peut être affiché sans texte';
$lang['builder.subtitle'] = 'Sous titre de formulaire';
$lang['builder.input.hidden'] = 'Champ caché';
$lang['builder.free.html'] = 'Champ libre';
$lang['builder.date'] = 'Date';
$lang['builder.date.hm'] = 'Date/heure/minutes';
$lang['builder.color'] = 'Couleur';
$lang['builder.search'] = 'Recherche';
$lang['builder.file.picker'] = 'Fichier';
$lang['builder.multiple.file.picker'] = 'Plusieurs fichiers';
$lang['builder.thumbnail.picker'] = 'Vignette';
$lang['builder.file.upload'] = 'Lien vers un fichier';

// Links
$lang['builder.links.menu'] = 'Menus de liens';
$lang['builder.links.list'] = 'Liste de liens';
$lang['builder.link.icon'] = 'Item avec icône';
$lang['builder.link.img'] = 'Item avec image';
$lang['builder.link'] = 'Item de Liste';
$lang['builder.modal.menu'] = 'Menu modal';
$lang['builder.tabs.menu'] = 'Menus tabulaire';
$lang['builder.panel'] = 'Panneau';

// Googlemap
$lang['builder.googlemap'] = 'Champs du module GoogleMaps';
$lang['builder.googlemap.simple_address'] = 'Adresse simple';
$lang['builder.googlemap.map_address'] = 'Adresse avec carte';
$lang['builder.googlemap.simple_marker'] = 'Marqueur';
$lang['builder.googlemap.multiple_markers'] = 'Marqueurs multiples';

// Authorizations
$lang['builder.authorization'] = 'Autorisation';
$lang['builder.authorization.1'] = 'Action 1';
$lang['builder.authorization.1.desc'] = 'Autorisations pour l\'action 1';
$lang['builder.authorization.2'] = 'Action 2';

// Orientations
$lang['builder.vertical.desc'] = 'Formulaire vertical';
$lang['builder.horizontal.desc'] = 'Formulaire horizontal';

?>
