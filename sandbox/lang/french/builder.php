<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 09
 * @since       PHPBoost 4.0 - 2013 12 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                    French                        #
####################################################

$lang['builder.clueription'] = '
    <p>Cette page présente le rendu des éléments de formulaire</p>
    <p>Créés avec le constructeur php, vous pouvez également les <a class="pinned bgc link-color offload" href="#markup-view">ajouter en html <i class="fa fa-caret-down"></i></a>.</p>
    <p>Pour accéder aux déclarations PHP de chaque élément, vous pouvez lire <a class="offload" href="https://github.com/PHPBoost/PHPBoost/blob/master/sandbox/controllers/SandboxBuilderController.class.php#L110">le fichier</a> fourni avec ce module, ou la documentation de l\'API sur le site de <a href="https://www.phpboost.com/api"><i class="fa iboost fa-iboost-phpboost"></i> PHPHBoost</a></p>
';
$lang['builder.title']   = 'Formulaire';
$lang['builder.preview'] = 'Prévisualisation';

$lang['builder.text.fields']     = 'Champs texte';
$lang['builder.textarea']        = 'Textarea';
$lang['builder.checked.choices'] = 'Radio / checkbox';
$lang['builder.selects']         = 'Sélecteurs';
$lang['builder.buttons']         = 'Boutons';
$lang['builder.title.upload']    = 'Upload';
$lang['builder.gmap']            = 'Google Maps';
$lang['builder.dates']           = 'Dates';

// Text fields
$lang['builder.text.field']                       = 'Champ texte';
$lang['builder.text.field.clue']                  = 'Contraintes: lettres, chiffres et tiret bas';
$lang['builder.text.field.lorem']                 = 'Lorem ipsum';
$lang['builder.text.field.disabled']              = 'Champ désactivé';
$lang['builder.text.field.disabled.clue']         = 'Désactivé';
$lang['builder.text.field.readonly']              = 'Champ en lecture seule';
$lang['builder.url.field']                        = 'Site web';
$lang['builder.url.field.clue']                   = 'Url valide';
$lang['builder.url.field.placeholder']            = 'https://www.phpboost.com';
$lang['builder.email.field']                      = 'Email';
$lang['builder.email.field.clue']                 = 'Email valide';
$lang['builder.email.field.placeholder']          = 'lorem@phpboost.com';
$lang['builder.email.field.multiple']             = 'Email multiple';
$lang['builder.email.field.multiple.clue']        = 'Emails valides, séparés par une virgule';
$lang['builder.email.field.multiple.placeholder'] = 'lorem@phpboost.com,ipsum@phpboost.com';
$lang['builder.phone.field']                      = 'Numéro de téléphone';
$lang['builder.phone.field.clue']                 = 'Numéro de téléphone valide';
$lang['builder.phone.field.placeholder']          = '0123456789';
$lang['builder.text.field.required']              = 'Champ requis';
$lang['builder.text.field.required.filled']       = 'Champ requis rempli';
$lang['builder.text.field.required.empty']        = 'Champ requis vide';
$lang['builder.number.field']                     = 'Nombre';
$lang['builder.number.field.clue']                = 'intervalle: de 10 à 100';
$lang['builder.number.field.placeholder']         = '20';
$lang['builder.number.field.decimal']             = 'Nombre décimal';
$lang['builder.number.field.decimal.clue']        = 'Utiliser la virgule';
$lang['builder.number.field.decimal.placeholder'] = '5.5';
$lang['builder.slider.field']                     = 'Slider';
$lang['builder.slider.field.clue']                = 'Faites glisser';
$lang['builder.slider.field.placeholder']         = '4';
$lang['builder.password.field']                   = 'Mot de passe';
$lang['builder.password.field.clue']              = ' caractères minimum';
$lang['builder.password.field.placeholder']       = 'aaaaaa';
$lang['builder.password.field.confirm']           = 'Confirmation du mot de passe';

// Textareas
$lang['builder.multiline.medium']      = 'Champ texte multi lignes moyen';
$lang['builder.multiline']             = 'Champ texte multi lignes';
$lang['builder.multiline.clue']        = 'Description';
$lang['builder.multiline.lorem']       = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ut tempor lacus.';
$lang['builder.rich.text']             = 'Champ texte avec éditeur';
$lang['builder.rich.text.placeholder'] = 'Lorem ipsum dolor sit <strong>amet</strong>';

// Choices
$lang['builder.checkbox']                = 'Case à cocher';
$lang['builder.multiple.checkbox']       = 'Case à cocher multiple';
$lang['builder.radio']                   = 'Boutons radio';
$lang['builder.select']                  = 'Liste déroulante';
$lang['builder.select.to.list']          = 'Liste déroulante avec images/icônes';
$lang['builder.multiple.select']         = 'Liste déroulante multiple';
$lang['builder.multiple.select.to.list'] = 'Liste déroulante multiple avec images/icônes';
$lang['builder.choice']                  = 'Choix ';
$lang['builder.choice.group']            = 'Groupe ';
$lang['builder.timezone']                = 'TimeZone';
$lang['builder.user.completion']         = 'Auto complétion utilisateurs';

// Miscellaneaous
$lang['builder.clue']                 = 'Ceci est une description';
$lang['builder.spacer']               = 'Ceci est un saut de ligne, il peut être affiché sans texte';
$lang['builder.subtitle']             = 'Sous titre de formulaire';
$lang['builder.hidden']               = 'Champ caché';
$lang['builder.free.html']            = 'Champ libre';
$lang['builder.date']                 = 'Date';
$lang['builder.date.hm']              = 'Date/heure/minutes';
$lang['builder.sources']              = 'Ajouter des sources';
$lang['builder.possible.values']      = 'Ajout d\'options possibles';
$lang['builder.color']                = 'Couleur';
$lang['builder.search']               = 'Recherche';
$lang['builder.file.picker']          = 'Fichier';
$lang['builder.multiple.file.picker'] = 'Plusieurs fichiers';
$lang['builder.thumbnail.picker']     = 'Vignette';
$lang['builder.file.upload']          = 'Lien vers un fichier';
$lang['builder.captcha']              = 'Il faut être déconnecté pour voir ce champs en bas de page, avant la liste de codes source.';

// Links
$lang['builder.links.menu'] = 'Menus de liens';
$lang['builder.links.list'] = 'Liste de liens';
$lang['builder.link.icon']  = 'Item avec icône';
$lang['builder.link.img']   = 'Item avec image';
$lang['builder.link']       = 'Item de Liste';
$lang['builder.modal.menu'] = 'Menu modal';
$lang['builder.tabs.menu']  = 'Menus tabulaire';
$lang['builder.panel']      = 'Panneau';

// Googlemap
$lang['builder.googlemap']                  = 'Champs du module GoogleMaps';
$lang['builder.googlemap.simple.address']   = 'Adresse simple';
$lang['builder.googlemap.map.address']      = 'Adresse avec carte';
$lang['builder.googlemap.simple.marker']    = 'Marqueur';
$lang['builder.googlemap.multiple.markers'] = 'Marqueurs multiples';

// Authorizations
$lang['builder.authorization']        = 'Autorisation';
$lang['builder.authorization.1']      = 'Action 1';
$lang['builder.authorization.1.clue'] = 'Autorisations pour l\'action 1';
$lang['builder.authorization.2']      = 'Action 2';

// Orientations
$lang['builder.vertical.clue']   = 'Formulaire vertical';
$lang['builder.horizontal.clue'] = 'Formulaire horizontal';

// Buttons
$lang['builder.all.buttons']           = 'Tous les boutons sont pré-définis avec la classe .button <br /><br />';
$lang['builder.send.button']           = 'Envoyer';
$lang['builder.button']                = 'Bouton';
$lang['builder.button.sizes']          = 'Avec une taille';
$lang['builder.button.colors']         = 'Avec une couleur';
$lang['builder.button.link']           = 'Avec un lien';
$lang['builder.button.picture']        = 'Avec une image';
$lang['builder.button.icon']           = 'Avec une icône';
$lang['builder.button.confirm']        = 'Avec une confirmation';
$lang['builder.button.confirm.alert']  = 'Ce lien vous redirige vers le site officiel.';
$lang['builder.button.alternate.send'] = 'Boutons alternatifs de validation';
?>
