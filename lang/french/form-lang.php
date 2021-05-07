<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 02
 * @since       PHPBoost 6.0 - 2021 04 18
*/

####################################################
#                     French                       #
####################################################

$lang['form.required.fields'] = 'Les champs marqués * sont obligatoires !';

// Autorizations
$lang['form.authorizations']              = 'Autorisations';
$lang['form.authorizations.read']         = 'Autorisation de lecture';
$lang['form.authorizations.write']        = 'Autorisation d\'écriture';
$lang['form.authorizations.contribution'] = 'Autorisation de contribution';
$lang['form.authorizations.moderation']   = 'Autorisation de modération';
$lang['form.authorizations.categories']   = 'Autorisation de gérer les catégories';
$lang['form.authorizations.menu']         = 'Autorisation d\'afficher le menu';
$lang['form.authorizations.specials']     = 'Autorisations spéciales';
$lang['form.authorizations.default']      = 'Autorisations par défaut';
$lang['form.authorizations.clue']         = 'Autorisations globales du module. Vous pouvez changer ces autorisations localement sur chaque catégorie.';
$lang['form.authorizations.read.profile'] = 'Autorisations de lecture du champ dans le profil';
$lang['form.authorizations.actions']      = 'Autorisations de lecture du champ dans la création ou la modification d\'un profil';

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
$lang['form.apply']   = 'Appliquer';
$lang['form.insert']  = 'Insérer';

// Configuration
$lang['form.home']                      = 'Accueil';
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

// Extended Fields
$lang['form.profile.fields.management']             = 'Gestion des champs du profil';
$lang['form.extended.field.add']                    = 'Ajouter un champ au profil';
$lang['form.extended.field.edit']                   = 'Editer un champ du profil';
$lang['form.extended.field']                        = 'Champs du profil';
$lang['form.extended.fields.management']            = 'Gestion des champs du profil';
$lang['form.extended.fields.error.already.exists']  = 'Le champ existe déjà.';
$lang['form.extended.fields.error.phpboost.config'] = 'Les champs utilisés par défaut par PHPBoost ne peuvent pas être créés plusieurs fois, veuillez choisir un autre type de champ.';

// Fields
$lang['form.fields.management']   = 'Gestion des champs';
$lang['form.field.add']           = 'Ajouter un champ';
$lang['form.field.edit']          = 'Editer un champ';
$lang['form.field.type']          = 'Type de champ';
$lang['form.default.field']       = 'Champ par défaut';
$lang['form.short.text']          = 'Texte court (max 255 caractères)';
$lang['form.long.text']           = 'Texte long (illimité)';
$lang['form.half.text']           = 'Texte semi long';
$lang['form.simple.select']       = 'Sélection unique (parmi plusieurs valeurs)';
$lang['form.multiple.select']     = 'Sélection multiple (parmi plusieurs valeurs)';
$lang['form.simple.check']        = 'Choix unique (parmi plusieurs valeurs)';
$lang['form.multiple.check']      = 'Choix multiples (parmi plusieurs valeurs)';
$lang['form.date']                = 'Date';
$lang['form.themes.choice']       = 'Choix des thèmes';
$lang['form.langs.choice']        = 'Choix des langues';
$lang['form.birthdate']           = 'Date de naissance';
$lang['form.pm.to.email']         = 'Notification par email à la réception d\'un message privé';
$lang['form.editor.choice']       = 'Choix de l\'éditeur';
$lang['form.timezone.choice']     = 'Choix du fuseau horaire';
$lang['form.sex.choice']          = 'Choix du sexe';
$lang['form.avatar.management']   = 'Gestion de l\'avatar';
$lang['form.required.field']      = 'Champ requis';
$lang['form.required.field.clue'] = 'Obligatoire dans le profil du membre et à son inscription.';
$lang['form.possible.values']     = 'Valeurs possibles';

// Labels
$lang['form.name']                      = 'Nom';
$lang['form.title']                     = 'Titre';
$lang['form.content']                   = 'Contenu';
$lang['form.description']               = 'Description';
$lang['form.summary']                   = 'Résumé';
$lang['form.enable.summary']            = 'Personnaliser le résumé';
$lang['form.summary.clue']              = 'Si non coché, la description est automatiquement coupée à :number caractères et le formatage du texte supprimé.';
$lang['form.enable.author.custom.name'] = 'Personnaliser le nom de l\'auteur';
$lang['form.author.custom.name']        = 'Nom de l\'auteur';
$lang['form.category']                  = 'Emplacement';
$lang['form.rewrited.name']             = 'Nom réécrit dans l\'url';
$lang['form.rewrited.name.clue']        = 'Contient uniquement des lettres minuscules, des chiffres et des traits d\'union.';
$lang['form.rewrited.name.personalize'] = 'Personnaliser le nom dans l\'url';
$lang['form.approve']                   = 'Approuver';
$lang['form.publication']               = 'Publication';
$lang['form.publication.draft']         = 'Garder en brouillon';
$lang['form.publication.now']           = 'Publier maintenant';
$lang['form.publication.deffered']      = 'Publication differée';
$lang['form.date.selector']             = 'Ouvrir/fermer le sélecteur de date';
$lang['form.start.date']                = 'A partir du';
$lang['form.enable.end.date']           = 'Définir une date de fin de publication';
$lang['form.end.date']                  = 'Jusqu\'au';
$lang['form.creation.date']             = 'Date de création';
$lang['form.update.creation.date']      = 'Mettre à jour la date de création avec la date du jour';
$lang['form.last.update']               = 'Dernière modification';
$lang['form.url']                       = 'Adresse';
$lang['form.other']                     = 'Autre';
$lang['form.parameters']                = 'Paramètres';
$lang['form.options']                   = 'Options';
$lang['form.keywords']                  = 'Mots clés';
$lang['form.keywords.clue']             = 'Un seul mot clé par ligne';
$lang['form.thumbnail']                 = 'Image';
$lang['form.thumbnail.preview']         = 'Prévisualisation de l\'image';
$lang['form.picture']                   = 'Image';
$lang['form.picture.preview']           = 'Prévisualisation de l\'image';
$lang['form.sources']                   = 'Source(s)';
$lang['form.add.source']                = 'Ajouter une source';
$lang['form.delete.source']             = 'Supprimer la source';
$lang['form.source.name']               = 'Nom de la source';
$lang['form.source.url']                = 'Adresse de la source';
$lang['form.captcha']                   = 'Code de vérification';
$lang['form.default']                   = 'Défaut';
$lang['form.is.default']                = 'Par défaut';
$lang['form.default.value']             = 'Valeur par défaut';
$lang['form.delete.default.value']      = 'Supprimer la valeur par défaut';

// Messages
$lang['form.message.success.add']  = 'Le champ du profil <b>:name</b> a été ajouté';
$lang['form.message.success.edit'] = 'Le champ du profil <b>:name</b> a été modifié';

// Modules
$lang['form.forbidden.module']                  = 'Modules interdits';
$lang['form.comments.forbidden.module.clue']    = 'Sélectionnez les modules dans lesquels vous ne souhaitez pas activer les commentaires';
$lang['form.notation.forbidden.module.clue']    = 'Sélectionnez les modules dans lesquels vous ne souhaitez pas activer la notation';
$lang['form.new.content.forbidden.module.clue'] = 'Sélectionnez les modules dans lesquels vous ne souhaitez pas activer les tags de nouveau contenu';
$lang['form.hide.left.column']                  = 'Masquer les blocs de gauche du site sur le module :module';
$lang['form.hide.right.column']                 = 'Masquer les blocs de droite du site sur le module :module';

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

// Thumbnails
$lang['form.image']             = 'Image';
$lang['form.thumbnail']         = 'Vignette';
$lang['form.thumbnail.none']    = 'Aucune vignette';
$lang['form.thumbnail.default'] = 'Vignette par défaut';
$lang['form.thumbnail.custom']  = 'Vignette personnalisée';
$lang['form.thumbnail.preview'] = 'Previsualisation de la vignette';
?>
