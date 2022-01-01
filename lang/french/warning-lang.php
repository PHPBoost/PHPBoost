<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 25
 * @since       PHPBoost 1.5 - 2006 06 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     French                       #
####################################################

$lang['warning.success'] = 'Succès';
$lang['warning.error']   = 'Erreur';

$lang['warning.fatal']    = 'Fatale';
$lang['warning.notice']   = 'Suggestion';
$lang['warning.warning']  = 'Avertissement';
$lang['warning.question'] = 'Question';
$lang['warning.unknown']  = 'Inconnue';

$lang['warning.username']     = 'Veuillez entrer un nom d\'utilisateur !';
$lang['warning.password']     = 'Veuillez entrer un mot de passe !';
$lang['warning.title']        = 'Veuillez entrer un titre !';
$lang['warning.text']         = 'Veuillez entrer un texte !';
$lang['warning.email']        = 'Veuillez entrer un email valide !';
$lang['warning.subcat']       = 'Veuillez sélectionner une sous-catégorie !';
$lang['warning.url']          = 'Veuillez entrer une url valide !';
$lang['warning.recipient']    = 'Veuillez entrer le destinataire du message !';
$lang['warning.integer']      = 'Veuillez entrer nombre entier !';
$lang['warning.items.number'] = 'Veuillez entrer un nombre d\'éléments !';
$lang['warning.irreversible'] = 'Cette action est irréversible !';

// BBcode
$lang['warning.bbcode.member']       = 'Ce cadre cache du contenu réservé aux membres.';
$lang['warning.bbcode.moderator']    = 'Ce cadre cache du contenu réservé aux modérateurs.';
$lang['warning.bbcode.teaser']       = 'Seuls les membres peuvent lire l\'intégralité du contenu suivant.';
$lang['warning.bbcode.is.member']    = 'Le contenu suivant est réservé aux membres.';
$lang['warning.bbcode.is.moderator'] = 'Le contenu suivant est réservé aux modérateurs.';
$lang['warning.bbcode.is.teaser']    = 'Le contenu suivant, dont le début est affiché aux visiteurs, est réservé aux membres.';

// Captcha
$lang['warning.captcha.validation.error'] = 'Le champ de vérification visuel n\'a pas été saisi correctement !';
$lang['warning.captcha.is.default']       = 'Le captcha que vous souhaitez désinstaller ou désactiver est défini sur le site, veuillez d\'abord sélectionner un autre captcha dans la configuration du contenu.';
$lang['warning.captcha.last.installed']   = 'Dernier captcha, vous ne pouvez pas le supprimer ou le désactiver. Veuillez d\'abord en installer un autre.';

// Content
$lang['warning.locked.content.description']  = 'L\'élément est actuellement ouvert par :user_display_name, vous ne pouvez pas y accéder, réessayez plus tard.';
$lang['warning.locked.content.another_user'] = 'un autre utilisateur';

// Contributions
$lang['warning.delete.contribution'] = 'Etes-vous sûr de vouloir supprimer cette contribution ?';

// Editor
$lang['warning.code.too.long.error']   = 'Le code que vous voulez colorer est trop long et consommerait trop de ressources pour être interprété. Merci de réduire sa taille ou de l\'éclater en plusieurs morceaux.';
$lang['warning.feed.tag.error']        = 'Le flux du module <em>:module</em> que vous souhaitez afficher n\'a pas pu être trouvé ou les options que vous avez rentrées ne sont pas correctes.';
$lang['warning.is.default.editor']     = 'L\'éditeur que vous souhaitez désinstaller ou désactiver, est défini par défaut, veuillez d\'abord sélectionner un autre éditeur par défaut';
$lang['warning.last.editor.installed'] = 'Dernier éditeur de texte, vous ne pouvez pas le supprimer ou le désactiver. Veuillez d\'abord en installer un autre.';

// Element
$lang['warning.element.already.exists'] = 'L\'élément existe déjà.';
$lang['warning.element.unexists']       = 'L\'élément que vous demandez n\'existe pas.';
$lang['warning.element.not.visible']    = 'Cet élément n\'est pas encore ou n\'est plus approuvé, il n\'est pas affiché pour les autres utilisateurs du site.';

// Errors
$lang['warning.incomplete']              = 'Tous les champs obligatoires doivent être remplis !';
$lang['warning.readonly']                = 'Vous ne pouvez exécuter cette action, car vous avez été placé en lecture seule !';
$lang['warning.flood']                   = 'Vous ne pouvez pas encore poster, réessayez dans quelques instants';
$lang['warning.link.flood']              = 'Nombre maximum de liens internet autorisés dans votre message : %d';
$lang['warning.auth']                    = 'Vous n\'avez pas le niveau requis !';
$lang['warning.auth.guest']              = 'Le contenu de cette page est protégé. Veuillez vous inscrire ou vous connecter sur le site pour y accéder.';
$lang['warning.registration.disabled']   = 'L\'inscription de nouveaux membres est désactivée sur le site.';
$lang['warning.page.forbidden']          = 'L\'accès à ce dossier est interdit !';
$lang['warning.page.unexists']           = 'La page que vous demandez n\'existe pas !';
$lang['warning.unauthorized.action']     = 'Action non autorisée !';
$lang['warning.module.uninstalled']      = 'Ce module n\'est pas installé !';
$lang['warning.module.disabled']         = 'Ce module n\'est pas activé !';
$lang['warning.invalid.archive.content'] = 'Le contenu de l\'archive est incorrect !';
$lang['warning.404.message']             = 'Il semblerait qu\'une tornade soit passée par ici.<br />Il ne reste malheureusement plus rien à voir.';
$lang['warning.403.message']             = 'Il semblerait qu\'une tornade soit passée par ici.<br />L\'accès est interdit au public.';
$lang['warning.csrf.invalid.token']      = 'Jeton de session invalide. Veuillez essayer de recharger la page car l\'opération n\'a pas pu être effectuée.';

// Forbidden
$lang['warning.file.forbidden.chars'] = 'Le nom du fichier ne peut contenir aucun des caractères suivants : \\\ / . | ? < > \"';

// Groups
$lang['warning.already.group'] = 'Le membre appartient déjà au groupe';

// Members
$lang['warning.display.name.auth']  = 'Le nom d\'affichage entré est déjà utilisé !';
$lang['warning.pseudo.auth']        = 'Le pseudo entré est déjà utilisé !';
$lang['warning.email.auth']         = 'L\'adresse email entrée est déjà utilisée !';
$lang['warning.member.ban']         = 'Vous avez été banni! Vous pourrez vous reconnecter dans';
$lang['warning.member.ban.contact'] = 'Vous avez été banni pour un comportement abusif! Contactez l\'administrateur s\'il s\'agit d\'une erreur.';
$lang['warning.unactive.member']    = 'Votre compte n\'a pas encore été activé !';

// Private messages
$lang['warning.delete.message'] = 'Supprimer le.s message.s ?';
$lang['warning.pm.full']        = 'Votre boite de messages privés est pleine, vous avez <strong>%d</strong> conversation(s) en attente, supprimez d\'anciennes conversations pour pouvoir la/les lire.';
$lang['warning.pm.full.post']   = 'Votre boite de messages privés est pleine, supprimez d\'anciennes conversations pour pouvoir en envoyer de nouvelles.';
$lang['warning.unexist.user']   = 'L\'utilisateur sélectionné n\'existe pas !';
$lang['warning.pm.del']         = 'Le destinataire a supprimé la conversation, vous ne pouvez plus poster';
$lang['warning.pm.no.edit']     = 'Le destinataire a déjà lu votre message, vous ne pouvez plus l\'éditer';
$lang['warning.pm.no.del']      = 'Le destinataire a déjà lu votre message, vous ne pouvez plus le supprimer';

// Process
$lang['warning.process.success'] = 'L\'opération s\'est déroulée avec succès';
$lang['warning.process.error']   = 'Une erreur s\'est produite lors de l\'opération';

$lang['warning.confirm.delete']          = 'Voulez-vous vraiment supprimer cet élément ?';
$lang['warning.confirm.delete.elements'] = 'Voulez-vous vraiment supprimer ces éléments ?';

$lang['warning.success.config']          = 'La configuration a été modifiée';
$lang['warning.success.position.update'] = 'Les éléments ont été repositionnés';

$lang['warning.download.file.error'] = 'Echec lors du téléchargement du fichier :filename';

$lang['warning.delete.install.and.update.folders'] = 'Par mesure de sécurité nous vous conseillons fortement de supprimer les dossiers <b>install</b> et <b>update</b> et tout ce qu\'ils contiennent, des personnes mal intentionnées pourraient relancer le script d\'installation et écraser certaines de vos données !';
$lang['warning.delete.install.or.update.folders']  = 'Par mesure de sécurité nous vous conseillons fortement de supprimer le dossier <b>:folder</b> et tout ce qu\'il contient, des personnes mal intentionnées pourraient relancer le script d\'installation et écraser certaines de vos données !';

// Regex
$lang['warning.regex']                 = 'La valeur saisie n\'est pas au bon format';
$lang['warning.regex.date']            = 'La valeur saisie doit être une date valide';
$lang['warning.regex.url']             = 'La valeur saisie doit être une url valide';
$lang['warning.regex.email']           = 'La valeur saisie doit être un email valide';
$lang['warning.regex.tel']             = 'La valeur saisie doit être un numéro de téléphone valide';
$lang['warning.regex.letters.numbers'] = 'La valeur saisie doit être une série de lettres et de nombres';
$lang['warning.regex.number']          = 'La valeur saisie doit être un nombre';
$lang['warning.regex.picture.file']    = 'La valeur saisie doit correspondre à une image';
$lang['warning.length.intervall']      = 'La valeur saisie ne respecte par la longueur définie (:lower_bound <= valeur <= :upper_bound)';
$lang['warning.length.min']            = 'La valeur saisie doit faire au moins :lower_bound caractères';
$lang['warning.length.max']            = 'La valeur saisie doit faire au maximum :upper_bound caractères';
$lang['warning.integer.intervall']     = 'La valeur saisie ne respecte pas l\'intervalle définie (:lower_bound <= valeur <= :upper_bound)';
$lang['warning.integer.min']           = 'La valeur saisie doit être supérieure ou égale à :lower_bound';
$lang['warning.integer.max']           = 'La valeur saisie doit être inférieure ou égale à :upper_bound';
$lang['warning.regex.authorized.extensions'] = 'L\'extension du fichier n\'est pas autorisée. Extensions valides : :extensions.';

$lang['warning.medium.password.regex']          = 'Le mot de passe doit comporter au moins une minuscule et une majuscule ou une minuscule et un chiffre';
$lang['warning.strong.password.regex']          = 'Le mot de passe doit comporter au moins une minuscule, une majuscule et un chiffre';
$lang['warning.very.strong.password.regex']     = 'Le mot de passe doit comporter au moins une minuscule, une majuscule, un chiffre et un caractère spécial';
$lang['warning.email.authorized.domains.regex'] = 'Le nom de domaine de cette adresse n\'est pas autorisé sur le site, veuillez choisir une autre adresse email';

$lang['warning.invalid.url']            = 'L\'url n\'est pas valide';
$lang['warning.invalid.picture']        = 'Le fichier indiqué n\'est pas une image';
$lang['warning.unexisting.file']        = 'Le fichier n\'a pas été trouvé, son adresse doit être incorrecte';
$lang['warning.has.to.be.filled']       = 'Le champ ":name" doit être renseigné';
$lang['warning.must.contain.min.input'] = 'Le champ ":name" doit contenir au moins :min_input valeurs';
$lang['warning.must.contain.max.input'] = 'Le champ ":name" ne doit pas contenir plus de :max_input valeurs';
$lang['warning.unique.input.value']     = 'Le champ ":name" ne doit pas contenir de valeurs identiques';
$lang['warning.validation.error']       = 'Veuillez corriger les erreurs du formulaire';

$lang['warning.fields.must.be.equal']                                  = 'Les champs ":field1" et ":field2" doivent être égaux';
$lang['warning.fields.must.not.be.equal']                              = 'Les champs ":field1" et ":field2" doivent avoir des valeurs différentes';
$lang['warning.first.field.must.be.inferior.to.second.field']          = 'Le champ ":field2" doit avoir une valeur inférieure au champ ":field1"';
$lang['warning.first.field.must.be.superior.to.second.field']          = 'Le champ ":field2" doit avoir une valeur supérieure au champ ":field1"';
$lang['warning.first.field.must.not.be.contained.in.second.field']     = 'La valeur champ ":field1" ne doit pas être contenue dans le champ ":field2"';
$lang['warning.login.and.email.must.not.be.contained.in.second.field'] = 'Votre email ou votre identifiant de connexion ne doivent pas être contenus dans le champ ":field2"';

// Upload
$lang['warning.file.max.size.exceeded'] = 'La taille maximale du fichier ne doit pas dépasser :max_file_size.';
$lang['warning.file.max.dimension']     = 'Dimensions maximales du fichier dépassées';
$lang['warning.file.max.weight']        = 'Poids maximum du fichier dépassé';
$lang['warning.file.invalid.format']    = 'Format du fichier invalide';
$lang['warning.file.php.code']          = 'Contenu du fichier invalide, le code php est interdit';
$lang['warning.file.upload.error']      = 'Erreur lors de l\'upload du fichier';
$lang['warning.unlink.disabled']        = 'Fonction de suppression des fichiers désactivée sur votre serveur';
$lang['warning.folder.unwritable']      = 'Upload impossible, interdiction d\'écriture dans ce dossier';
$lang['warning.file.already.exists']    = 'Le fichier existe déjà, écrasement non autorisé';
$lang['warning.folder.already.exists']  = 'Le dossier existe déjà.';
$lang['warning.no.selected.file']       = 'Aucun fichier n\'a été sélectionné';
$lang['warning.max.data.reach']         = 'Taille maximale atteinte, supprimez d\'anciens fichiers';
$lang['warning.del.file']               = 'Supprimer ce fichier ?';
$lang['warning.empty.folder']           = 'Vider ce dossier ?';
$lang['warning.empty.folder.content']   = 'Vider tout le contenu de ce dossier ?';
$lang['warning.del.folder']             = 'Supprimer ce dossier ?';
$lang['warning.del.folder.content']     = 'Supprimer ce dossier, et tout son contenu ?';
$lang['warning.file.forbidden.chars']   = 'Le nom du fichier ne peut contenir aucun des caractères suivants : \\\ / . | ? < > \"';
$lang['warning.folder.forbidden.chars'] = 'Le nom du dossier ne peut contenir aucun des caractères suivants : \\\ / . | ? < > \"';
$lang['warning.files.del.failed']       = 'La suppression des fichiers a échoué, veuillez le faire manuellement';
$lang['warning.success.upload']         = 'Votre fichier a bien été enregistré !';
$lang['warning.folder.contains.folder'] = 'Vous essayez de placer ce répertoire dans un de ses sous-répertoire ou dans lui-même, ce qui est impossible !';

// User
$lang['warning.user.not.authorized.during.maintenance'] = 'Vous n\'avez pas l\'autorisation d\'accéder au site pendant la maintenance';
$lang['warning.user.not.exists'] = 'L\'utilisateur n\'existe pas !';
$lang['warning.user.auth.password.flood'] = 'Il vous reste :remaining_tries essai(s) après cela il vous faudra attendre 5 minutes pour obtenir 2 nouveaux essais (10min pour 5)!';
$lang['warning.user.auth.password.flood.max'] = 'Vous avez épuisé tous vos essais de connexion, votre compte est verrouillé pendant 5 minutes.';

// Version
$lang['warning.misfit.php']      = 'Version PHP inadaptée';
$lang['warning.misfit.phpboost'] = 'Version de PHPBoost inadaptée';
?>
