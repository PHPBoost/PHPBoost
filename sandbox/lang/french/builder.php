<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 01
 * @since       PHPBoost 4.0 - 2013 12 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                    French                        #
####################################################

$lang['sandbox.builder.description'] = '
    <p>Cette page présente le rendu des éléments de formulaire</p>
    <p>Créés avec le constructeur php, vous pouvez également les <a class="pinned bgc link-color offload" href="#markup-view">ajouter en html <i class="fa fa-caret-down"></i></a>.</p>
    <p>Pour accéder aux déclarations PHP de chaque élément, vous pouvez lire <a class="offload" href="https://github.com/PHPBoost/PHPBoost/blob/master/sandbox/controllers/SandboxBuilderController.class.php#L110">le fichier</a> fourni avec ce module, ou la documentation de l\'API sur le site de <a href="https://www.phpboost.com/api"><i class="fa iboost fa-iboost-phpboost"></i> PHPHBoost</a></p>
';
$lang['sandbox.builder.title']   = 'Formulaire';
$lang['sandbox.builder.preview'] = 'Prévisualisation';

$lang['sandbox.builder.text.fields']     = 'Champs texte';
$lang['sandbox.builder.textarea']        = 'Textarea';
$lang['sandbox.builder.checked.choices'] = 'Radio / checkbox';
$lang['sandbox.builder.selects']         = 'Sélecteurs';
$lang['sandbox.builder.buttons']         = 'Boutons';
$lang['sandbox.builder.title.upload']    = 'Upload';
$lang['sandbox.builder.gmap']            = 'Google Maps';
$lang['sandbox.builder.dates']           = 'Dates';

// Text fields
$lang['sandbox.builder.text.field']                       = 'Champ texte';
$lang['sandbox.builder.text.field.clue']                  = 'Contraintes: lettres, chiffres et tiret bas';
$lang['sandbox.builder.text.field.lorem']                 = 'Lorem ipsum';
$lang['sandbox.builder.text.field.disabled']              = 'Champ désactivé';
$lang['sandbox.builder.text.field.disabled.clue']         = 'Désactivé';
$lang['sandbox.builder.text.field.readonly']              = 'Champ en lecture seule';
$lang['sandbox.builder.url.field']                        = 'Site web';
$lang['sandbox.builder.url.field.clue']                   = 'Url valide';
$lang['sandbox.builder.url.field.placeholder']            = 'https://www.phpboost.com';
$lang['sandbox.builder.email.field']                      = 'Email';
$lang['sandbox.builder.email.field.clue']                 = 'Email valide';
$lang['sandbox.builder.email.field.placeholder']          = 'lorem@phpboost.com';
$lang['sandbox.builder.email.field.multiple']             = 'Email multiple';
$lang['sandbox.builder.email.field.multiple.clue']        = 'Emails valides, séparés par une virgule';
$lang['sandbox.builder.email.field.multiple.placeholder'] = 'lorem@phpboost.com,ipsum@phpboost.com';
$lang['sandbox.builder.phone.field']                      = 'Numéro de téléphone';
$lang['sandbox.builder.phone.field.clue']                 = 'Numéro de téléphone valide';
$lang['sandbox.builder.phone.field.placeholder']          = '0123456789';
$lang['sandbox.builder.text.field.required']              = 'Champ requis';
$lang['sandbox.builder.text.field.required.filled']       = 'Champ requis rempli';
$lang['sandbox.builder.text.field.required.empty']        = 'Champ requis vide';
$lang['sandbox.builder.number.field']                     = 'Nombre';
$lang['sandbox.builder.number.field.clue']                = 'intervalle: de 10 à 100';
$lang['sandbox.builder.number.field.placeholder']         = '20';
$lang['sandbox.builder.number.field.decimal']             = 'Nombre décimal';
$lang['sandbox.builder.number.field.decimal.clue']        = 'Utiliser la virgule';
$lang['sandbox.builder.number.field.decimal.placeholder'] = '5.5';
$lang['sandbox.builder.slider.field']                     = 'Slider';
$lang['sandbox.builder.slider.field.clue']                = 'Faites glisser';
$lang['sandbox.builder.slider.field.placeholder']         = '4';
$lang['sandbox.builder.password.field']                   = 'Mot de passe';
$lang['sandbox.builder.password.field.clue']              = ' caractères minimum';
$lang['sandbox.builder.password.field.placeholder']       = 'aaaaaa';
$lang['sandbox.builder.password.field.confirm']           = 'Confirmation du mot de passe';

// Textareas
$lang['sandbox.builder.multiline.medium']      = 'Champ texte multi lignes moyen';
$lang['sandbox.builder.multiline']             = 'Champ texte multi lignes';
$lang['sandbox.builder.multiline.clue']        = 'Description';
$lang['sandbox.builder.multiline.lorem']       = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ut tempor lacus.';
$lang['sandbox.builder.rich.text']             = 'Champ texte avec éditeur';
$lang['sandbox.builder.rich.text.placeholder'] = 'Lorem ipsum dolor sit <strong>amet</strong>';

// Choices
$lang['sandbox.builder.checkbox']                = 'Case à cocher';
$lang['sandbox.builder.multiple.checkbox']       = 'Case à cocher multiple';
$lang['sandbox.builder.radio']                   = 'Boutons radio';
$lang['sandbox.builder.select']                  = 'Liste déroulante';
$lang['sandbox.builder.select.to.list']          = 'Liste déroulante avec images/icônes';
$lang['sandbox.builder.multiple.select']         = 'Liste déroulante multiple';
$lang['sandbox.builder.multiple.select.to.list'] = 'Liste déroulante multiple avec images/icônes';
$lang['sandbox.builder.choice']                  = 'Choix ';
$lang['sandbox.builder.choice.group']            = 'Groupe ';
$lang['sandbox.builder.timezone']                = 'TimeZone';
$lang['sandbox.builder.user.completion']         = 'Auto complétion utilisateurs';

// Miscellaneaous
$lang['sandbox.builder.clue']                 = 'Ceci est une description';
$lang['sandbox.builder.spacer']               = 'Ceci est un saut de ligne, il peut être affiché sans texte';
$lang['sandbox.builder.subtitle']             = 'Sous titre de formulaire';
$lang['sandbox.builder.hidden']               = 'Champ caché';
$lang['sandbox.builder.free.html']            = 'Champ libre';
$lang['sandbox.builder.date']                 = 'Date';
$lang['sandbox.builder.date.hm']              = 'Date/heure/minutes';
$lang['sandbox.builder.sources']              = 'Ajouter des sources';
$lang['sandbox.builder.possible.values']      = 'Ajout d\'options possibles';
$lang['sandbox.builder.color']                = 'Couleur';
$lang['sandbox.builder.search']               = 'Recherche';
$lang['sandbox.builder.file.picker']          = 'Fichier';
$lang['sandbox.builder.multiple.file.picker'] = 'Plusieurs fichiers';
$lang['sandbox.builder.thumbnail.picker']     = 'Vignette';
$lang['sandbox.builder.file.upload']          = 'Lien vers un fichier';
$lang['sandbox.builder.captcha']              = 'Il faut être déconnecté pour voir ce champs en bas de page, avant la liste de codes source.';

// Links
$lang['sandbox.builder.links.menu'] = 'Menus de liens';
$lang['sandbox.builder.links.list'] = 'Liste de liens';
$lang['sandbox.builder.link.icon']  = 'Item avec icône';
$lang['sandbox.builder.link.img']   = 'Item avec image';
$lang['sandbox.builder.link']       = 'Item de Liste';
$lang['sandbox.builder.modal.menu'] = 'Menu modal';
$lang['sandbox.builder.tabs.menu']  = 'Menus tabulaire';
$lang['sandbox.builder.panel']      = 'Panneau';

// Googlemap
$lang['sandbox.builder.googlemap']                  = 'Champs du module GoogleMaps';
$lang['sandbox.builder.googlemap.simple.address']   = 'Adresse simple';
$lang['sandbox.builder.googlemap.map.address']      = 'Adresse avec carte';
$lang['sandbox.builder.googlemap.simple.marker']    = 'Marqueur';
$lang['sandbox.builder.googlemap.multiple.markers'] = 'Marqueurs multiples';

// Authorizations
$lang['sandbox.builder.authorization']        = 'Autorisation';
$lang['sandbox.builder.authorization.1']      = 'Action 1';
$lang['sandbox.builder.authorization.1.clue'] = 'Autorisations pour l\'action 1';
$lang['sandbox.builder.authorization.2']      = 'Action 2';

// Orientations
$lang['sandbox.builder.vertical.clue']   = 'Formulaire vertical';
$lang['sandbox.builder.horizontal.clue'] = 'Formulaire horizontal';

// Buttons
$lang['sandbox.builder.all.buttons']           = 'Tous les boutons sont pré-définis avec la classe .button <br /><br />';
$lang['sandbox.builder.send.button']           = 'Envoyer';
$lang['sandbox.builder.button']                = 'Bouton';
$lang['sandbox.builder.button.sizes']          = 'Avec une taille';
$lang['sandbox.builder.button.colors']         = 'Avec une couleur';
$lang['sandbox.builder.button.link']           = 'Avec un lien';
$lang['sandbox.builder.button.picture']        = 'Avec une image';
$lang['sandbox.builder.button.icon']           = 'Avec une icône';
$lang['sandbox.builder.button.confirm']        = 'Avec une confirmation';
$lang['sandbox.builder.button.confirm.alert']  = 'Ce lien vous redirige vers le site officiel.';
$lang['sandbox.builder.button.alternate.send'] = 'Boutons alternatifs de validation';
?>
