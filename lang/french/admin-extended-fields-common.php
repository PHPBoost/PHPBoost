<?php
/*##################################################
 *                           admin-extended-fields-common.php
 *                            -------------------
 *   begin                : December 17, 2010
 *   copyright            : (C) 2010 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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
 
$lang = array();

$lang['extended-field-add'] = 'Ajouter un champ membre';
$lang['extended-field-edit'] = 'Editer un champ membre';
$lang['extended-field'] = 'Champs membres';
$lang['extended-fields-management'] = 'Gestion des champs membres';
$lang['extended-fields-sucess-edit'] = 'Le champs étendus à été mise à jour avec succès.';
$lang['extended-fields-sucess-add'] = 'Le champs étendus à été ajouté avec succès.';
$lang['extended-fields-sucess-delete'] = 'Le champs étendus à été supprimé avec succès.';

//Type 
$lang['type.short-text'] = 'Texte court (max 255 caractères)';
$lang['type.long-text'] = 'Texte long (illimité)';
$lang['type.simple-select'] = 'Sélection unique (parmi plusieurs valeurs)';
$lang['type.multiple-select'] = 'Sélection multiple (parmi plusieurs valeurs)';
$lang['type.simple-check'] = 'Choix unique (parmi plusieurs valeurs)';
$lang['type.multiple-check'] = 'Choix multiples (parmi plusieurs valeurs)';
$lang['type.date'] = 'Date';
$lang['type.user-themes-choice'] = 'Choix des thèmes';
$lang['type.user-lang-choice'] = 'Choix des langues';
$lang['type.user_born'] = 'Date de naissance';
$lang['type.avatar'] = 'Gestion de l\'avatar';

$lang['default-field'] = 'Champs par défaut';

$lang['field.name'] = 'Nom';
$lang['field.description'] = 'Description';
$lang['field.type'] = 'Type de champ';
$lang['field.regex'] = 'Contrôle de la forme de l\'entrée';
$lang['field.regex-explain'] = 'Permet d\'effectuer un contrôle sur la forme de ce que l\'utilisateur a entrée. Par exemple, si il s\'agit d\'une adresse mail, on peut contrôler que sa forme est correcte. <br />Vous pouvez effectuer un contrôle personnalié en tapant une expression régulière (utilisateurs expérimentés seulement).';
$lang['field.predefined-regex'] = 'Forme prédéfinie';
$lang['field.required'] = 'Champ requis';
$lang['field.required_explain'] = 'Obligatoire dans le profil du membre et à son inscription.';
$lang['field.possible-values'] = 'Valeurs possibles';
$lang['field.possible-values-explain'] = 'Séparez les différentes valeurs par le symbole |';
$lang['field.default-values'] = 'Valeurs par défaut';
$lang['field.default-values-explain'] = 'Séparez les différentes valeurs par le symbole |';
$lang['field.default-possible-values'] = 'Oui|Non';

// Regex
$lang['regex.figures'] = 'Chiffres';
$lang['regex.letters'] = 'Lettres';
$lang['regex.figures-letters'] = 'Chiffres et lettres';
$lang['regex.website'] = 'Site web';
$lang['regex.mail'] = 'Mail';
$lang['regex.personnal-regex'] = 'Expression régulière personnalisée';


$lang['field.yes'] = 'Oui';
$lang['field.no'] = 'Non';

$lang['field.success'] = 'Succès';
$lang['field.delete_field'] = 'Souhaitez vous vraiment supprimer ce champ ?';
$lang['field.position'] = 'Position';

$lang['field.is-required'] = 'Requis';
$lang['field.is-not-required'] = 'Non requis';




?>