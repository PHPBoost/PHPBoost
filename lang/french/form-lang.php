<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 22
 * @since       PHPBoost 6.0 - 2021 04 18
*/

####################################################
#                     French                       #
####################################################

$lang['form.required.fields'] = 'Les champs marqués * sont obligatoires !';

// Buttons
$lang['form.submit']  = 'Valider';
$lang['form.reset']   = 'Défaut';
$lang['form.preview'] = 'Prévisualisation';
$lang['form.empty']   = 'Vider';
$lang['form.close']   = 'Fermer';
$lang['form.update']  = 'Mettre à jour';
$lang['form.upload']  = 'Uploader';
$lang['form.search']  = 'Rechercher';
$lang['form.save']    = 'Sauvegarder';
$lang['form.go']      = 'Go';
$lang['form.ok']      = 'OK';
$lang['form.refresh'] = 'Rafraichir';

// Configuration
$lang['form.documentation']             = 'Documentation';
$lang['form.configuration']             = 'Configuration';
$lang['form.module.title']              = 'Configuration du module :module_name';
$lang['form.categories.per.page']       = 'Nombre de catégories par page';
$lang['form.categories.per.row']        = 'Nombre de catégories par ligne';
$lang['form.items.per.row']             = 'Nombre d\'éléments par ligne';
$lang['form.items.per.page']            = 'Nombre d\'éléments par page';
$lang['form.enable.comments']           = 'Activer les commentaires';
$lang['form.enable.notation']           = 'Activer la notation';
$lang['form.notation.scale']            = 'Echelle de notation';
$lang['form.forbidden.tags']            = 'Formats interdits';
$lang['form.display']                   = 'Afficher';
$lang['form.display.not']               = 'Ne pas afficher';
$lang['form.displayed']                 = 'Affiché';
$lang['form.displayed.not']             = 'Non affiché';
$lang['form.display.type']              = 'Type d\'affichage';
$lang['form.display.type.grid']         = 'Affichage en grille';
$lang['form.display.type.list']         = 'Affichage en liste';
$lang['form.display.type.table']        = 'Affichage en tableau';
$lang['form.display.full.item']         = 'Afficher les éléments en entier';
$lang['form.characters.number.to.cut']  = 'Nombre de caractères pour couper l\'élément';
$lang['form.display.summary.to.guests'] = 'Afficher le résumé des éléments aux visiteurs non autorisés en lecture';
$lang['form.display.author']            = 'Afficher le nom de l\'auteur';
$lang['form.display.date']              = 'Afficher la date de publication';
$lang['form.display.update.date']       = 'Afficher la date de mise à jour';
$lang['form.display.views.number']      = 'Afficher le nombre de vues';
$lang['form.items.default.sort']        = 'Tri par défaut des éléments';
$lang['form.items.default.sort.mode']   = 'Sens du tri par défaut des éléments';
$lang['form.display.sort.form']         = 'Afficher le formulaire de tri des éléments';
$lang['form.root.category.description'] = 'Description de la catégorie racine';
$lang['form.item.default.content']      = 'Contenu par défaut d\'un élément';
$lang['form.authorizations.clue']       = 'Autorisations globales du module. Vous pouvez changer ces autorisations localement sur chaque catégorie.';

// Extended Fields
$lang['form.profile.fields.management']             = 'Gestion des champs du profil';
$lang['form.extended.field.add']                    = 'Ajouter un champ au profil';
$lang['form.extended.field.edit']                   = 'Editer un champ du profil';
$lang['form.extended.field']                        = 'Champs du profil';
$lang['form.extended.fields.management']            = 'Gestion des champs du profil';
$lang['form.extended.fields.error.already.exists']  = 'Le champ existe déjà.';
$lang['form.extended.fields.error.phpboost.config'] = 'Les champs utilisés par défaut par PHPBoost ne peuvent pas être créés plusieurs fois, veuillez choisir un autre type de champ.';

// Fields
$lang['form.fields.management']              = 'Gestion des champs';
$lang['form.field.add']                      = 'Ajouter un champ';
$lang['form.field.edit']                     = 'Editer un champ';
$lang['form.field.type']                     = 'Type de champ';
$lang['form.default.field']                  = 'Champ par défaut';
$lang['form.short.text']                     = 'Texte court (max 255 caractères)';
$lang['form.long.text']                      = 'Texte long (illimité)';
$lang['form.half.text']                      = 'Texte semi long';
$lang['form.simple.select']                  = 'Sélection unique (parmi plusieurs valeurs)';
$lang['form.multiple.select']                = 'Sélection multiple (parmi plusieurs valeurs)';
$lang['form.simple.check']                   = 'Choix unique (parmi plusieurs valeurs)';
$lang['form.multiple.check']                 = 'Choix multiples (parmi plusieurs valeurs)';
$lang['form.date']                           = 'Date';
$lang['form.themes.choice']                  = 'Choix des thèmes';
$lang['form.langs.choice']                   = 'Choix des langues';
$lang['form.birthdate']                      = 'Date de naissance';
$lang['form.pm.to.email']                    = 'Notification par email à la réception d\'un message privé';
$lang['form.editor.choice']                  = 'Choix de l\'éditeur';
$lang['form.timezone.choice']                = 'Choix du fuseau horaire';
$lang['form.sex.choice']                     = 'Choix du sexe';
$lang['form.avatar.management']              = 'Gestion de l\'avatar';
$lang['form.required.field']                 = 'Champ requis';
$lang['form.required.field.clue']            = 'Obligatoire dans le profil du membre et à son inscription.';
$lang['form.possible.values']                = 'Valeurs possibles';
$lang['form.possible.values.is.default']     = 'Par défaut';
$lang['form.possible.values.delete.default'] = 'Supprimer la valeur par défaut';
$lang['form.default.value']                  = 'Valeur par défaut';
$lang['form.read.authorizations']            = 'Autorisations de lecture du champ dans le profil';
$lang['form.actions.authorizations']         = 'Autorisations de lecture du champ dans la création ou la modification d\'un profil';

// Messages
$lang['form.message.success.add']  = 'Le champ du profil <b>:name</b> a été ajouté';
$lang['form.message.success.edit'] = 'Le champ du profil <b>:name</b> a été modifié';

// Labels
$lang['form.name']                        = 'Nom';
$lang['form.title']                       = 'Titre';
$lang['form.content']                     = 'Contenu';
$lang['form.description']                 = 'Description';
$lang['form.summary']                     = 'Résumé';
$lang['form.custom.summary.enabled']      = 'Personnaliser le résumé';
$lang['form.custom.summary.enabled.clue'] = 'Si non coché, la description est automatiquement coupée à :number caractères et le formatage du texte supprimé.';
$lang['form.short.content.enabled.clue']  = 'Si non coché, la description est automatiquement coupée à :number caractères et le formatage du texte supprimé.'; // To be deleted when all modules will use the new variable
$lang['form.author.custom.name.enabled']  = 'Personnaliser le nom de l\'auteur';
$lang['form.author.custom.name']          = 'Nom de l\'auteur';
$lang['form.category']                    = 'Emplacement';
$lang['form.rewrited.name']               = 'Nom réécrit dans l\'url';
$lang['form.rewrited.name.clue']          = 'Contient uniquement des lettres minuscules, des chiffres et des traits d\'union.';
$lang['form.rewrited.name.personalize']   = 'Personnaliser le nom dans l\'url';
$lang['form.approve']                     = 'Approuver';
$lang['form.approbation']                 = 'Publication';
$lang['form.approbation.not']             = 'Garder en brouillon';
$lang['form.approbation.now']             = 'Publier maintenant';
$lang['form.date.selector']               = 'Ouvrir/fermer le sélecteur de date';
$lang['form.date.start']                  = 'A partir du';
$lang['form.date.end']                    = 'Jusqu\'au';
$lang['form.date.creation']               = 'Date de création';
$lang['form.update.date.creation']        = 'Mettre à jour la date de création avec la date du jour';
$lang['form.date.update']                 = 'Dernière modification';
$lang['form.date.end.enable']             = 'Définir une date de fin de publication';
$lang['form.url']                         = 'Adresse';
$lang['form.other']                       = 'Autre';
$lang['form.parameters']                  = 'Paramètres';
$lang['form.options']                     = 'Options';
$lang['form.keywords']                    = 'Mots clés';
$lang['form.keywords.clue']               = 'Un seul mot clé par ligne';
$lang['form.thumbnail']                   = 'Image';
$lang['form.thumbnail.preview']           = 'Prévisualisation de l\'image';
$lang['form.picture']                     = 'Image';
$lang['form.picture.preview']             = 'Prévisualisation de l\'image';
$lang['form.sources']                     = 'Source(s)';
$lang['form.add.source']                  = 'Ajouter une source';
$lang['form.del.source']                  = 'Supprimer la source';
$lang['form.source.name']                 = 'Nom de la source';
$lang['form.source.url']                  = 'Adresse de la source';
$lang['form.captcha']                     = 'Code de vérification';

// Regex
$lang['form.regex']            = 'Contrôle de la forme de l\'entrée';
$lang['form.figures']          = 'Chiffres';
$lang['form.letters']          = 'Lettres';
$lang['form.figures.letters']  = 'Chiffres et lettres';
$lang['form.word']             = 'Mot';
$lang['form.website']          = 'Site web';
$lang['form.email']            = 'Email';
$lang['form.phone.number']     = 'Numéro de téléphone';
$lang['form.personnal.regex']  = 'Expression régulière personnalisée';
$lang['form.predefined.regex'] = 'Forme prédéfinie';
$lang['form.regex.clue'] = '
    Permet d\'effectuer un contrôle sur la saisie faite par l\'utilisateur. Par exemple, s\'il s\'agit d\'une adresse email, on peut contrôler que sa forme est correcte. <br />
    Vous pouvez effectuer un contrôle personnalisé en tapant une expression régulière (utilisateurs expérimentés seulement).
';
?>
