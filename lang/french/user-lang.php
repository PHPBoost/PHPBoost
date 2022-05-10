<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 05 10
 * @since       PHPBoost 3.0 - 2011 10 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     French                       #
####################################################

// Connexion panel
$lang['user.username']           = 'Identifiant de connexion';
$lang['user.username.tooltip']   = 'Si vous n\'avez pas coché "Choisir un identifiant de connexion" dans votre profil, connectez-vous avec l\'adresse email que vous avez déclarée.';
$lang['user.password']           = 'Mot de passe';
$lang['user.sign.in']            = 'Connexion';
$lang['user.connect']            = 'Connecter';
$lang['user.stay.connected']     = 'Rester connecté';
$lang['user.auto.connect']       = 'Connexion auto';
$lang['user.sign.out']           = 'Déconnexion';
$lang['user.sign.up']            = 'S\'inscrire';
$lang['user.forgotten.password'] = 'Mot de passe oublié';

// Dashboard
$lang['user.private.messaging']  = 'Messagerie privée';
$lang['user.admin.panel']        = 'Panneau d\'administration';
$lang['user.moderation.panel']   = 'Panneau de modération';
$lang['user.contribution.panel'] = 'Panneau de contribution';
$lang['user.dashboard']          = 'Tableau de bord';
$lang['user.my.account']         = 'Mon compte';
$lang['user.my.profile']         = 'Mon profil';

// Forgotten password
$lang['user.change.password']            = 'Changement de mot de passe';
$lang['user.forgotten.password.select']  = 'Sélectionnez le champ que vous voulez renseigner (email ou pseudo)';
$lang['user.forgotten.password.success'] = 'Un email vous a été envoyé avec un lien pour changer votre mot de passe';
$lang['user.forgotten.password.error']   = 'Les informations fournies ne sont pas correctes, veuillez les rectifier et réessayer';
$lang['user.forgotten.password.email.content'] = 'Cher(e) :pseudo,

Vous recevez cet e-mail parce que vous (ou quelqu\'un qui prétend l\'être) avez demandé à ce qu\'un nouveau mot de passe vous soit envoyé pour votre compte sur :host.
Si vous n\'avez pas demandé de changement de mot de passe, veuillez l\'ignorer. Si vous continuez à le recevoir, veuillez contacter l\'administrateur du site.

Pour changer de mot de passe, cliquez sur le lien fourni ci-dessous et suivez les indications sur le site.

:change_password_link

Si vous rencontrez des difficultés, veuillez contacter l\'administrateur du site.

:signature';

// Configuration
$lang['user.members.config']            = 'Configuration des membres';
$lang['user.display.type']              = 'Affichage de la liste des membres';
$lang['user.registration.activation']   = 'Activer l\'inscription des membres';
$lang['user.activation.mode']           = 'Mode d\'activation du compte membre';
$lang['user.activation.auto']           = 'Automatique';
$lang['user.activation.mail']           = 'Mail';
$lang['user.activation.admin']          = 'Administrateur';
$lang['user.unactivated.timeout']       = 'Nombre de jours après lequel les membres non activés sont effacés';
$lang['user.unactivated.timeout.clue']  = 'Laisser vide pour ignorer cette option (Non pris en compte si validation par administrateur)';
$lang['user.allow.display.name.change'] = 'Autoriser les membres à changer leur Nom d\'affichage';
$lang['user.allow.email.change']        = 'Autoriser les membres à changer leur Email';
$lang['user.authorization.description'] = 'Vous définissez ici les autorisations de lecture de la liste des groupes et des membres ainsi que certaines informations personnelles des utilisateurs comme leurs emails, messages et profils.';
    // Security
$lang['user.security']                      = 'Sécurité';
$lang['user.password.min.length']           = 'Longueur minimale des mots de passe';
$lang['user.password.strength']             = 'Complexité des mots de passe';
$lang['user.password.strength.weak']        = 'Faible';
$lang['user.password.strength.medium']      = 'Moyenne (lettres et chiffres)';
$lang['user.password.strength.strong']      = 'Forte (minuscules, majuscules et chiffres)';
$lang['user.password.strength.very.strong'] = 'Très forte (minuscules, majuscules, chiffres et caractères spéciaux)';
$lang['user.password.forbidden.tag']        = 'Interdire l\'adresse email et l\'identifiant de connexion dans le mot de passe';
$lang['user.forbidden.email.domains']       = 'Liste des noms de domaines interdits';
$lang['user.forbidden.email.domains.clue']  = 'Domaines interdits dans les adresses mail des utilisateurs (séparés par des virgules). Exemple : <b>domain.com</b>';
$lang['user.authentication']                = 'Configuration des moyens d\'authentification';
    // Avatars
$lang['user.avatars.management']     = 'Gestion des avatars';
$lang['user.allow.avatar.upload']    = 'Autoriser l\'upload d\'avatar sur le serveur';
$lang['user.enable.avatar.resizing'] = 'Activer le redimensionnement automatique des images';
$lang['user.avatar.resizing.clue']   = 'Attention votre serveur doit avoir l\'extension GD chargée';
$lang['user.avatar.max.width']       = 'Largeur maximale de l\'avatar';
$lang['user.avatar.max.width.clue']  = 'Par défaut 120';
$lang['user.avatar.max.height']      = 'Hauteur maximale de l\'avatar';
$lang['user.avatar.max.height.clue'] = 'Par défaut 120';
$lang['user.avatar.max.weight']      = 'Poids maximal de l\'avatar en Ko';
$lang['user.avatar.max.weight.clue'] = 'Par défaut 20';
$lang['user.default.avatar']         = 'Avatar par défaut';
    // Welcome
$lang['user.welcome.message']         = 'Message à tous les membres';
$lang['user.welcome.message.content'] = 'Message de bienvenue affiché dans le profil du membre';
    // Rules
$lang['user.rules']             = 'Règlement';
$lang['user.rules.description'] = 'Entrez ci-dessous le règlement à afficher lors de l\'enregistrement des membres, ils devront l\'accepter pour s\'enregistrer. Laissez vide pour aucun règlement.';
$lang['user.rules.content']     = 'Contenu du règlement';

// Groups
$lang['user.groups']                  = 'Groupes';
$lang['user.groups.list']             = 'Liste des groupes';
$lang['user.groups.select']           = 'Sélectionner un groupe';
$lang['user.groups.all']              = 'Tous les groupes';
$lang['user.group.of.group']          = 'du groupe :';
$lang['user.admins.list']             = 'Liste des administrateurs';
$lang['user.modos.list']              = 'Liste des modérateurs';
$lang['user.no.member']               = 'Aucun membre dans ce groupe';
$lang['user.group.view.list.members'] = 'Voir les membres du groupe';
$lang['user.group.hide.list.members'] = 'Masquer les membres du groupe';
    // Configuration
$lang['user.groups.management']    = 'Gestion des groupes';
$lang['user.edit.group']           = 'Modifier le groupe';
$lang['user.add.group']            = 'Ajouter un groupe';
$lang['user.flood']                = 'Autorisation de flooder';
$lang['user.pm.limit']             = 'Limite de messages privés';
$lang['user.pm.limit.clue']        = 'Mettre -1 pour illimité';
$lang['user.data.limit']           = 'Taille de l\'espace de stockage des fichiers';
$lang['user.data.limit.clue']      = 'En Mo. Mettre -1 pour illimité';
$lang['user.group.color']          = 'Couleur associée au groupe';
$lang['user.delete.group.color']   = 'Supprimer la couleur associée au groupe';
$lang['user.group.thumbnail']      = 'Image associée au groupe';
$lang['user.group.thumbnail.clue'] = 'Mettre dans le dossier images/group/';
$lang['user.add.group.member']     = 'Ajouter un membre au groupe';
$lang['user.group.members']        = 'Membres du groupe';
$lang['user.upload.thumbnail']     = 'Uploader une image';

// Labels
$lang['user.user']           = 'Utilisateur';
$lang['user.users']          = 'Utilisateurs';
$lang['user.profile']        = 'Profil';
$lang['user.profile.of']     = 'Profil de :name';
$lang['user.profile.edit']   = 'Edition du profil';
$lang['user.contact']        = 'Contact';
$lang['user.contact.email']  = 'Contacter par email';
$lang['user.message']        = 'Message';
$lang['user.messages']       = 'Messages';
$lang['user.last.message']   = 'Dernier message';
$lang['user.user.messages']  = 'Messages de l\'utilisateur';
$lang['user.welcome']        = 'Bienvenue';
$lang['user.about.author']   = 'À propos de l\'auteur';
$lang['user.robot']          = 'Robot';
$lang['user.robots']         = 'Robots';
$lang['user.guest']          = 'Visiteur';
$lang['user.guests']         = 'Visiteurs';
$lang['user.member']         = 'Membre';
$lang['user.members']        = 'Membres';
$lang['user.moderator']      = 'Modérateur';
$lang['user.moderators']     = 'Modérateurs';
$lang['user.administrator']  = 'Administrateur';
$lang['user.administrators'] = 'Administrateurs';
$lang['user.referee']        = 'Responsable';
$lang['user.sex']            = 'Sexe';
$lang['user.male']           = 'Homme';
$lang['user.female']         = 'Femme';

$lang['user.members.management'] = 'Gestion des membres';
$lang['user.members.punishment'] = 'Gestion des sanctions';
$lang['user.add.member']         = 'Ajouter un membre';
$lang['user.filter.members']     = 'Filtrer les membres';
$lang['user.members.all']        = 'Tous les membres';
$lang['user.members.list']       = 'Liste des membres';
$lang['user.member.management']  = 'Gestion du membre';
$lang['user.search.member']      = 'Rechercher un membre';
$lang['user.search.joker']       = 'Utiliser * pour remplacer une lettre';

$lang['user.profile.edit.password.error']       = 'Le mot de passe que vous avez entré n\'est pas correct';
$lang['user.external.auth.account.exists']      = 'Vous avez déjà un compte sur le site. Pour utiliser cette méthode de connexion, veuillez vous connecter et vous rendre dans l\'édition de votre profil';
$lang['user.external.auth.email.not.found']     = 'L\'adresse email de votre compte n\'a pas pu être récupérée, votre compte ne peut pas être associé.';
$lang['user.external.auth.user.data.not.found'] = 'Les informations de votre compte n\'ont pas pu être récupérées, votre compte ne peut pas être créé.';

// Ranks
$lang['user.rank']               = 'Rang';
$lang['user.ranks']              = 'Rangs';
$lang['user.rank.robot']         = 'Robot';
$lang['user.rank.visitor']       = 'Visiteur';
$lang['user.rank.member']        = 'Membre';
$lang['user.rank.moderator']     = 'Modérateur';
$lang['user.rank.administrator'] = 'Administrateur';

// S.E.O.
$lang['user.seo.profile']            = 'Toutes les informations de profil de :name.';
$lang['user.seo.list']               = 'Tableau de la liste des utilisateurs du site.';
$lang['user.seo.groups']             = 'Utilisateurs de chaque groupe du site.';
$lang['user.seo.comments']           = 'Tous les commentaires.';
$lang['user.seo.comments.user']      = 'Tous les commentaires de l\'utilisateur :name.';
$lang['user.seo.messages']           = 'Tous les messages de l\'utilisateur :name.';
$lang['user.seo.registration']       = 'Renseignez toutes les informations demandées pour créer un compte.';
$lang['user.seo.login']              = 'Connectez-vous au site pour accéder au contenu protégé.';
$lang['user.seo.forgotten.password'] = 'Renseignez toutes les informations demandées pour recevoir un lien pour changer de mot de passe.';
$lang['user.seo.about.cookie']       = 'Toutes les informations relatives aux cookies sur le site.';

// User fields
$lang['user.display.name']         = 'Nom d\'affichage';
$lang['user.display.name.clue']    = 'Nom affiché sur chacun des éléments que vous ajoutez.';
$lang['user.username.clue']        = 'Adresse email ou votre identifiant personnalisé si vous en avez choisi un.';
$lang['user.username.custom']      = 'Choisir un identifiant de connexion';
$lang['user.username.custom.clue'] = '<span class="error">Si non coché, vous devrez vous connecter avec votre adresse email</span>';
$lang['user.password.custom']      = 'Définir un mot de passe';
$lang['user.password.custom.clue'] = 'Par défaut, un mot de passe est généré automatiquement';
$lang['user.password.new']         = 'Nouveau mot de passe';
$lang['user.password.old']         = 'Ancien mot de passe';
$lang['user.password.old.clue']    = 'Remplir seulement en cas de modification';
$lang['user.password.confirm']     = 'Confirmer le mot de passe';
$lang['user.password.clue']        = 'Longueur minimale du mot de passe : :number caractères';
$lang['user.email']                = 'Email';
$lang['user.email.hide']           = 'Cacher l\'email';
$lang['user.theme']                = 'Thème';
$lang['user.theme.preview']        = 'Prévisualiser le thème';
$lang['user.text.editor']          = 'Editeur de texte';
$lang['user.lang']                 = 'Langue';
$lang['user.timezone.']            = 'Fuseau horaire';
$lang['user.timezone.choice']      = 'Choix du fuseau horaire';
$lang['user.timezone.choice.clue'] = 'Permet d\'ajuster l\'heure à votre localisation';
$lang['user.level']                = 'Rang';
$lang['user.approbation']          = 'Approbation';
$lang['user.unlimited']            = 'Illimité';

$lang['user.avatar']              = 'Avatar';
$lang['user.registration.date']   = 'Date d\'inscription';
$lang['user.last.connection']     = 'Dernière connexion';
$lang['user.my.publications']     = 'Mes publications';
$lang['user.publications']        = 'Publications';
$lang['user.view.publications']   = 'Voir les publications de l\'utilisateur';
$lang['user.private.message']     = 'Message privé';
$lang['user.delete.account']      = 'Supprimer le compte';
$lang['user.delete.account.confirmation.member'] = 'Etes-vous sûr de vouloir supprimer votre compte ?';
$lang['user.delete.account.confirmation.admin']  = 'Etes-vous sûr de vouloir supprimer le compte ?';

// Other
$lang['user.caution']  = 'Avertissement';
$lang['user.readonly'] = 'Lecture seule';
$lang['user.banned']   = 'Banni';

$lang['user.internal.connection']        = 'Connexion interne';
$lang['user.create.internal.connection'] = 'Créer un compte interne';
$lang['user.edit.internal.connection']   = 'Editer votre compte interne';
$lang['user.associate.account']          = 'Associer votre compte';
$lang['user.associate.account.admin']    = 'Associer un compte';
$lang['user.dissociate.account']         = 'Dissocier votre compte';
$lang['user.dissociate.account.admin']   = 'Dissocier le compte';

$lang['user.change.profile.field.description'] = 'Le champ :field a été changé de :old_value à :new_value.';

// Registration
$lang['user.registered']    = 'Inscrit';
$lang['user.registered.on'] = 'Inscrit le';
$lang['user.register']      = 'S\'inscrire';
$lang['user.registration']  = 'Inscription';

$lang['user.registration.validation.email.clue']         = 'Vous devrez activer votre compte dans l\'email qui vous sera envoyé avant de pouvoir vous connecter';
$lang['user.registration.validation.administrator.clue'] = 'Un administrateur devra activer votre compte avant de pouvoir vous connecter';

$lang['user.registration.confirm.success'] = 'Votre compte a été validé avec succès';
$lang['user.registration.confirm.error']   = 'Un problème est survenu lors de votre activation, vérifiez que votre clé est bien valide';

$lang['user.registration.success.administrator.validation'] = 'Vous vous êtes enregistré avec succès. Cependant un administrateur doit valider votre compte avant de pouvoir l\'utiliser';
$lang['user.registration.success.email.validation']         = 'Vous vous êtes enregistré avec succès. Cependant il vous faudra cliquer sur le lien d\'activation contenu dans le mail qui vous a été envoyé';

$lang['user.registration.email.automatic.validation']     = 'Vous pouvez désormais vous connecter à votre compte directement sur le site.';
$lang['user.registration.email.validation.link']          = 'Vous devez activer votre compte avant de pouvoir vous connecter en cliquant sur ce lien : :validation_link';
$lang['user.registration.email.administrator.validation'] = 'Attention : Votre compte devra être activé par un administrateur avant de pouvoir vous connecter. Merci de votre patience.';
$lang['user.registration.email.administrator.validation.content'] = 'Cher(e) :pseudo,

Nous avons le plaisir de vous informer que votre compte sur :site_name vient d\'être validé par un administrateur.

Vous pouvez dès à présent vous connecter au site à l\'aide des identifiants fournis dans le précédent email.

:signature';

$lang['user.registration.pending.approval']   = 'Un nouveau membre s\'est inscrit. Son compte doit être approuvé avant de pouvoir être utilisé.';
$lang['user.registration.not.approved']       = 'Votre compte doit être approuvé par un administrateur avant de pouvoir être utilisé.';
$lang['user.registration.email.subject']      = 'Confirmation d\'inscription sur :site_name';
$lang['user.registration.lost.password.link'] = 'Si vous perdez votre mot de passe, vous pouvez en générer un nouveau à partir de ce lien : :lost_password_link';
$lang['user.registration.password']           = 'Mot de passe : :password';
$lang['user.registration.content.email'] = 'Cher(e) :pseudo,

Tout d\'abord, merci de vous être inscrit sur :site_name. Vous faites partie, dès maintenant, des membres du site.
En vous inscrivant sur :site_name, vous pouvez accéder à la zone membre qui vous offre plusieurs avantages. Vous pourrez, entre autre, être reconnu automatiquement sur tout le site, pour poster des messages, modifier la langue et/ou le thème par défaut, éditer votre profil, accéder à des catégories réservées aux membres... Bref vous accédez à toute la communauté du site.

Pour vous connecter, il vous faudra retenir votre identifiant et votre mot de passe.

Nous vous rappelons vos identifiants de connexion.

Identifiant : :login
:lost_password_link

:accounts_validation_explain

A bientôt sur :host

:signature';
$lang['user.registration.content.email.admin'] = 'Cher(e) :pseudo,

Vous avez été inscrit sur le site :site_name par un administrateur. Vous faites maintenant partie des membres du site.
Vous obtenez un accès à la zone membre qui vous offre plusieurs avantages. Vous pourrez, entre autre, être reconnu automatiquement sur tout le site, pour poster des messages, modifier la langue et/ou le thème par défaut, éditer votre profil, accéder à des catégories réservées aux membres... Bref vous accédez à toute la communauté du site.

Pour vous connecter, il vous faudra retenir votre identifiant et votre mot de passe.

Nous vous rappelons vos identifiants de connexion.

Identifiant : :login
:lost_password_link

:accounts_validation_explain

A bientôt sur :host

:signature';

$lang['user.agreement']                = 'Règlement';
$lang['user.agreement.agree']          = 'J\'accepte le règlement';
$lang['user.agreement.agree.required'] = 'Vous devez accepter le règlement pour vous inscrire';
$lang['user.registration.agreement']   = 'Vous vous apprêtez à vous enregistrer sur le site. Nous vous demandons d\'être poli et courtois dans vos interventions.
Merci, l\'équipe du site.';
$lang['user.site.member.message']      = 'Bienvenue sur le site. Vous êtes membre du site, vous pouvez accéder à tous les espaces nécessitant un compte utilisateur, éditer votre profil et voir vos contributions.';

// Messages
$lang['user.message.success.add']           = 'L\'utilisateur <b>:name</b> a été ajouté';
$lang['user.message.success.edit']          = 'Le profil a été modifié';
$lang['user.message.success.delete']        = 'L\'utilisateur <b>:name</b> a été supprimé';
$lang['user.message.success.delete.member'] = 'Votre compte a été supprimé';

// Extended Fields
$lang['user.extended.fields.management']           = 'Gestion des champs du profil';
$lang['user.extended.field.add']                   = 'Ajouter un champ au profil';
$lang['user.extended.field.edit']                  = 'Editer un champ du profil';
$lang['user.extended.field']                       = 'Champs du profil';
$lang['user.extended.field.error.already.exists']  = 'Le champ existe déjà.';
$lang['user.extended.field.error.phpboost.config'] = 'Les champs utilisés par défaut par PHPBoost ne peuvent pas être créés plusieurs fois, veuillez choisir un autre type de champ.';

$lang['user.extended.field.sex']      = 'Sexe';
$lang['user.extended.field.sex.clue'] = '';

$lang['user.extended.field.pm.to.mail']      = 'Recevoir une notification par mail à la réception d\'un message privé';
$lang['user.extended.field.pm.to.mail.clue'] = '';

$lang['user.extended.field.birth.date']      = 'Date de naissance';
$lang['user.extended.field.birth.date.clue'] = '';

$lang['user.extended.field.avatar']             = 'Avatar';
$lang['user.extended.field.no.avatar']          = 'Aucun avatar';
$lang['user.extended.field.avatar.clue']        = '';
$lang['user.extended.field.current.avatar']     = 'Avatar actuel';
$lang['user.extended.field.upload.avatar']      = 'Uploader un avatar';
$lang['user.extended.field.upload.avatar.clue'] = 'Avatar directement hébergé sur le serveur';
$lang['user.extended.field.avatar.link']        = 'Lien de l\'avatar';
$lang['user.extended.field.avatar.link.clue']   = 'Adresse directe de l\'avatar';
$lang['user.extended.field.avatar.delete']      = 'Supprimer l\'avatar courant';

$lang['user.extended.field.location']      = 'Localisation';
$lang['user.extended.field.location.clue'] = '';

$lang['user.extended.field.job']      = 'Emploi';
$lang['user.extended.field.job.clue'] = '';

$lang['user.extended.field.entertainement']      = 'Loisirs';
$lang['user.extended.field.entertainement.clue'] = '';

$lang['user.extended.field.biography']      = 'Biographie';
$lang['user.extended.field.biography.clue'] = '';
$lang['user.extended.field.no.biography']   = 'Ce membre n\'a pas renseigné la biographie de son profil';
$lang['user.extended.field.no.member']      = 'Ce membre n\'est plus inscrit';

$lang['user.extended.field.website']      = 'Site internet';
$lang['user.extended.field.website.clue'] = 'Veuillez renseigner un site web valide (ex : https://www.phpboost.com)';

// Fields type
$lang['user.field.type.short.text']      = 'Texte court (max 255 caractères)';
$lang['user.field.type.long.text']       = 'Texte long (illimité)';
$lang['user.field.type.half.text']       = 'Texte semi long';
$lang['user.field.type.simple.select']   = 'Sélection unique (parmi plusieurs valeurs)';
$lang['user.field.type.multiple.select'] = 'Sélection multiple (parmi plusieurs valeurs)';
$lang['user.field.type.simple.check']    = 'Choix unique (parmi plusieurs valeurs)';
$lang['user.field.type.multiple.check']  = 'Choix multiples (parmi plusieurs valeurs)';
$lang['user.field.type.date']            = 'Date';
$lang['user.field.type.theme.choice']    = 'Choix des thèmes';
$lang['user.field.type.lang.choice']     = 'Choix des langues';
$lang['user.field.type.born']            = 'Date de naissance';
$lang['user.field.type.pm.email']        = 'Notification par email à la réception d\'un MP';
$lang['user.field.type.editor']          = 'Choix de l\'éditeur';
$lang['user.field.type.timezone']        = 'Choix du fuseau horaire';
$lang['user.field.type.sex']             = 'Choix du sexe';
$lang['user.field.type.avatar']          = 'Gestion de l\'avatar';

// Moderation
    // Moderation panel
$lang['user.contact.pm']          = 'Contacter par message privé';
$lang['user.alternative.pm']      = 'Message privé envoyé au membre';
$lang['user.alternative.pm.clue'] = 'Laisser vide pour aucun message privé. <br />Le membre averti ne pourra pas répondre à ce message, et ne connaîtra pas l\'expéditeur.';

    // Bans management.
$lang['user.ban']             = 'Bannissement';
$lang['user.bans']            = 'Bannissements';
$lang['user.bans.management'] = 'Gestion des bannissements';
$lang['user.ban.until']       = 'Banni jusqu\'au';
$lang['user.no.ban']          = 'Il n\'y a aucun utilisateur banni.';
$lang['user.ban.delay']       = 'Durée du bannissement';
$lang['user.ban.title.email'] = 'Banni';
$lang['user.ban.email'] = 'Bonjour,

Vous avez été banni sur : %s !
S\'il s\'agit d\'une erreur veuillez contacter l\'administrateur du site.


%s';

    // Punishments management
$lang['user.punishments']            = 'Sanctions';
$lang['user.punishments.management'] = 'Gestion des sanctions';
$lang['user.punish.until']           = 'Sanction jusqu\'au';
$lang['user.no.punished.user']       = 'Il n\'y a aucun utilisateur sanctionné.';
$lang['user.life']                   = 'A vie';
$lang['user.readonly']               = 'Membre en lecture seule';
$lang['user.readonly.clue']          = 'Celui-ci peut lire mais ne peut plus poster sur la totalité du site (commentaires, etc.)';
$lang['user.read.only.title']        = 'Sanction';
$lang['user.readonly.changed']       = 'Vous avez été mis en lecture seule par un membre de l\'équipe de modération, vous ne pourrez plus poster pendant %date%.


Ceci est un message semi-automatique.';

    // Warnings management
$lang['user.warning']               = 'Avertissement';
$lang['user.warnings']              = 'Avertissements';
$lang['user.warnings.management']   = 'Gestion des avertissements';
$lang['user.warning.level']         = 'Niveau d\'avertissement';
$lang['user.no.user.warning']       = 'Il n\'y a aucun utilisateur averti.';
$lang['user.warning.clue']          = 'Niveau d\'avertissement du membre. Vous pouvez le modifier, mais sachez qu\'à 100% le membre est banni.';
$lang['user.warning.user']          = 'Membre averti';
$lang['user.warning.level.changed'] = 'Vous avez été averti par un membre de l\'équipe de modération, votre niveau d\'avertissement est passé à %level%%.
Attention à votre comportement, si vous atteignez 100% vous serez banni définitivement.


Ceci est un message semi-automatique.';

// Private messaging
$lang['user.private.message']               = 'Message privé';
$lang['user.private.messages']              = 'Messages privés';
$lang['user.pm']                            = 'MP';
$lang['user.pm.box']                        = 'Boîte de réception';
$lang['user.recipient']                     = 'Destinataire';
$lang['user.post.new.conversation']         = 'Créer une nouvelle conversation';
$lang['user.post.new.private.conversation'] = 'Créer une nouvelle conversation privée';
$lang['user.new.pm']                        = 'Nouveau message privé';
$lang['user.pm.conversation.link']          = 'Consulter la conversation';
$lang['user.pm.status']                     = 'Statut du message';
$lang['user.pm.track']                      = 'Non lu par le destinataire';
$lang['user.not.read']                      = 'Non lu';
$lang['user.read']                          = 'Lu';
$lang['user.last.message']                  = 'Dernier message';
$lang['user.mark.pm.as.read']               = 'Marquer tous les messages comme lus';
$lang['user.participants']                  = 'Participant(s)';
$lang['user.quote.last.message']            = 'Reprise du message précédent';
$lang['user.select.all.messages']           = 'Sélectionner tous les messages';

// Cookies bar
$lang['user.cookiebar.cookie']                    = 'Cookie';
$lang['user.cookiebar.cookie.management']         = 'Gestion des Cookies';
$lang['user.cookiebar.message.notracking']        = 'En poursuivant votre navigation sur ce site internet, vous acceptez l\'utilisation de Cookies afin de gérer votre connexion, vos préférences, ainsi que l\'enregistrement de statistiques anonymes des visites.';
$lang['user.cookiebar.message.tracking']          = 'En poursuivant votre navigation sur ce site internet, vous acceptez l\'utilisation de Cookies ou autres traceurs pour vous proposer une navigation adaptée (publicités ciblées, partage réseaux sociaux, etc...).';
$lang['user.cookiebar.message.aboutcookie.title'] = 'A propos des Cookies';
$lang['user.cookiebar.message.aboutcookie']       = 'Pour assurer le bon fonctionnement de ce site, nous devons parfois enregistrer de petits fichiers de données sur l\'équipement de nos utilisateurs.<br />La majorité des sites web font de même.

<h2 class="formatter-title">Qu\'est-ce qu\'un cookie ?</h2>
Un cookie est un petit fichier texte que les sites web sauvegardent sur votre ordinateur ou appareil mobile lorsque vous les consultez.<br />
Il permet à ces sites de mémoriser vos actions et préférences (nom d\'utilisateur, langue, taille des caractères et autres paramètres d\'affichage) pendant un temps donné, pour que vous n\'ayez pas à réindiquer ces informations à chaque fois que vous consultez ces sites ou naviguez d\'une page à une autre.<br />

<h2 class="formatter-title">Les cookies techniques : </h2>
De base, ' . GeneralConfig::load()->get_site_name() . ' utilise les cookies pour :<br />
<ul class="formatter-ul">
    <li class="formatter-li"> gérer le système d\'identification (indispensable si vous souhaitez vous connecter),
    </li><li class="formatter-li"> enregistrer des statistiques anonymes pour le site web (pas indispensable, mais permet aux webmasters de savoir combien de visites concernent le site).
    </li>
</ul>

<h2 class="formatter-title">Les autres cookies :</h2>
' . GeneralConfig::load()->get_site_name() . ' n\'utilise aucun système de traceurs. En revanche, l\'utilisation du module google analytics et des boutons réseaux sociaux, nécessite des cookies traceurs.

<h2 class="formatter-title">Comment contrôler les cookies ?</h2>
Vous pouvez contrôler et/ou supprimer des cookies comme vous le souhaitez.<br />
Pour en savoir plus, consultez le site <a class="offload" href="https://www.aboutcookies.org">aboutcookies.org</a>.<br />
Vous avez la possibilité de supprimer tous les cookies déjà stockés sur votre ordinateur et de configurer la plupart des navigateurs pour qu\'ils les bloquent. Toutefois, dans ce cas, vous devrez peut-être indiquer vous-mêmes certaines préférences chaque fois que vous vous rendrez sur le site, et certains services et fonctionnalités risquent de ne pas être accessibles.
';
$lang['user.cookiebar.understand']    = 'J\'ai compris';
$lang['user.cookiebar.allowed']       = 'Autoriser';
$lang['user.cookiebar.declined']      = 'Bloquer';
$lang['user.cookiebar.more.title']    = 'Explications sur la gestion des cookies et de la "cookie-bar" (En savoir plus)';
$lang['user.cookiebar.more']          = 'En savoir plus';
$lang['user.cookiebar.cookies']       = 'Cookies';
$lang['user.cookiebar.change.choice'] = 'Modifier vos préférences';

// Status
$lang['user.online.users']   = 'Utilisateurs en ligne';
$lang['user.ip.address']     = 'Adresse ip';
$lang['user.online']         = 'En ligne';
$lang['user.offline']        = 'Hors connexion';
$lang['user.no.user.online'] = 'Aucun utilisateur en ligne';

// Menu
$lang['user.menu.link.to'] = 'Lien vers la page ';
?>
