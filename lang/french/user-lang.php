<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 18
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

// Dashboard
$lang['user.private.messaging']  = 'Messagerie privée';
$lang['user.admin.panel']        = 'Panneau d\'administration';
$lang['user.moderation.panel']   = 'Panneau de modération';
$lang['user.contribution.panel'] = 'Panneau de contribution';
$lang['user.dashboard']          = 'Tableau de bord';
$lang['user.my.account']         = 'Mon compte';
$lang['user.my.profile']         = 'Mon profil';

// Ranks
$lang['user.rank']               = 'Rang';
$lang['user.rank.robot']         = 'Robot';
$lang['user.rank.visitor']       = 'Visiteur';
$lang['user.rank.member']        = 'Membre';
$lang['user.rank.moderator']     = 'Modérateur';
$lang['user.rank.administrator'] = 'Administrateur';

// S.E.O.
$lang['user.seo.profile']            = 'Toutes les informations de profil de    :name.';
$lang['user.seo.list']               = 'Tableau de la liste des utilisateurs du site.';
$lang['user.seo.groups']             = 'Utilisateurs de chaque groupe du site.';
$lang['user.seo.comments']           = 'Tous les commentaires.';
$lang['user.seo.comments.user']      = 'Tous les commentaires de l\'utilisateur :name.';
$lang['user.seo.messages']           = 'Tous les messages de l\'utilisateur     :name.';
$lang['user.seo.registration']       = 'Renseignez toutes les informations demandées pour créer un compte.';
$lang['user.seo.login']              = 'Connectez-vous au site pour accéder au contenu protégé.';
$lang['user.seo.forgotten.password'] = 'Renseignez toutes les informations demandées pour recevoir un lien pour changer de mot de passe.';
$lang['user.seo.about.cookie']       = 'Toutes les informations relatives aux cookies sur le site.';

// Labels
$lang['user.user']            = 'Utilisateur';
$lang['user.users']           = 'Utilisateurs';
$lang['user.profile']         = 'Profil';
$lang['user.profile.of']      = 'Profil de :name';
$lang['user.profile.edit']    = 'Edition du profil';
$lang['user.message']         = 'Message';
$lang['user.messages']        = 'Messages';
$lang['user.last.message']    = 'Dernier message';
$lang['user.user.messages']   = 'Messages de l\'utilisateur';
$lang['user.maintain']        = 'Maintenance';
$lang['user.welcome']         = 'Bienvenue';
$lang['user.about.author']    = 'À propos de l\'auteur';
$lang['user.robot']           = 'Robot';
$lang['user.robots']          = 'Robots';
$lang['user.guest']           = 'Visiteur';
$lang['user.guests']          = 'Visiteurs';
$lang['user.member']          = 'Membre';
$lang['user.members']         = 'Membres';
$lang['user.moderator']       = 'Modérateur';
$lang['user.moderators']      = 'Modérateurs';
$lang['user.administrator']   = 'Administrateur';
$lang['user.administrators']  = 'Administrateurs';

$lang['user.members.all']       = 'Tous les membres';
$lang['user.members.list']      = 'Liste des membres';
$lang['user.member.management'] = 'Gestion du membre';
$lang['user.search.member']     = 'Rechercher un membre';
$lang['user.search']            = 'Rechercher';
$lang['user.validate']          = 'Valider';

$lang['user.profile.edit.password.error']       = 'Le mot de passe que vous avez entré n\'est pas correct';
$lang['user.external.auth.account.exists']      = 'Vous avez déjà un compte sur le site. Pour utiliser cette méthode de connexion, veuillez vous connecter et vous rendre dans l\'édition de votre profil';
$lang['user.external.auth.email.not.found']     = 'L\'adresse email de votre compte n\'a pas pu être récupérée, votre compte ne peut pas être associé.';
$lang['user.external.auth.user.data.not.found'] = 'Les informations de votre compte n\'ont pas pu être récupérées, votre compte ne peut pas être créé.';

// Contributions
$lang['user.my.items']                    = 'Mes contributions';
$lang['user.contribution']                = 'Contribution';
$lang['user.contribution.member.edition'] = 'Modification de contribution par l\'auteur';
$lang['user.contribution.clue'] = '
    Votre contribution sera traitée dans le panneau de contribution.
    <span class="error text-strong">La modification est possible tant que la contribution n\'a pas été approuvée.</span>
    Vous pouvez, dans le champ suivant, justifier votre contribution de façon à expliquer votre démarche à un modérateur.
';
$lang['user.contribution.extended.clue'] = '
    Votre contribution sera traitée dans le panneau de contribution.
    <span class="error text-strong">La modification est possible avant son approbation ainsi qu\'après.</span>
    Vous pouvez, dans le champ suivant, justifier votre contribution de façon à expliquer votre démarche à un modérateur.
    Si vous modifiez votre contribution <span class="text-strong">après approbation</span>, elle sera retraitée dans le panneau de contribution, en attente d\'une nouvelle approbation.
';
$lang['user.contribution.member.edition.clue'] = '
    Vous êtes sur le point de modifier votre contribution. Elle va être déplacée dans les éléments en attente afin d\'être traitée
    et une nouvelle alerte sera envoyée aux administrateurs.
';
$lang['user.contribution.description']                     = 'Complément de contribution';
$lang['user.contribution.description.clue']                = 'Expliquez les raisons de votre contribution. Ce champ est facultatif mais il peut aider un approbateur à prendre sa décision.';
$lang['user.contribution.member.edition.description']      = 'Complément de modification';
$lang['user.contribution.member.edition.description.desc'] = 'Expliquez ce que vous avez modifié pour un meilleur traitement d\'approbation.';
$lang['user.contribution.confirmed']                       = 'Votre contribution a bien été enregistrée.';
$lang['user.contribution.confirmed.messages'] = '
    <p>
        Vous pourrez la suivre dans le <a href="' . UserUrlBuilder::contribution_panel()->rel() . '">panneau de contribution</a>
        et éventuellement discuter avec les validateurs si leur choix n\'est pas franc.</p><p>Merci d\'avoir participé à la vie du site !
    </p>
';
$lang['user.contribution.pm.title']    = 'La contribution <strong>:title</strong> a été commentée';
$lang['user.contribution.pm.content'] = '
    :author a ajouté un commentaire à la contribution <strong>:title</strong>.
    <p>
        <h6>Commentaire :</h6>
        :comment
    </p>
    <a href=":contribution_url">Accéder à la contribution</a>
';

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
$lang['user.illimited']            = 'Illimité';

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

//Groups
$lang['user.groups']         = 'Groupes';
$lang['user.groups.list']    = 'Liste des groupes';
$lang['user.groups.select']  = 'Sélectionner un groupe';
$lang['user.groups.all']     = 'Tous les groupes';
$lang['user.group.of.group'] = 'du groupe :';
$lang['user.admins.list']    = 'Liste des administrateurs';
$lang['user.modos.list']     = 'Liste des modérateurs';
$lang['user.no.member']      = 'Aucun membre dans ce groupe';
$lang['user.group.view.list.members'] = 'Voir les membres du groupe';
$lang['user.group.hide.list.members'] = 'Masquer les membres du groupe';

//Other
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

$lang['user.share']      = 'Partager';
$lang['user.share.on']   = 'Partager sur';
$lang['user.share.by']   = 'Partager par';
$lang['user.share.menu'] = 'Menu réseaux sociaux';
$lang['user.share.sms']  = 'SMS';

//Registration
$lang['user.register']     = 'S\'inscrire';
$lang['user.registration'] = 'Inscription';

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

// Messages
$lang['user.message.success.add']           = 'L\'utilisateur <b>:name</b> a été ajouté';
$lang['user.message.success.edit']          = 'Le profil a été modifié';
$lang['user.message.success.delete']        = 'L\'utilisateur <b>:name</b> a été supprimé';
$lang['user.message.success.delete.member'] = 'Votre compte a été supprimé';

// Extended Fields
$lang['user.extended.field.sex']      = 'Sexe';
$lang['user.extended.field.sex.clue'] = '';

$lang['user.extended.field.pmtomail']      = 'Recevoir une notification par mail à la réception d\'un message privé';
$lang['user.extended.field.pmtomail.clue'] = '';

$lang['user.extended.field.date.birth']      = 'Date de naissance';
$lang['user.extended.field.date.birth.clue'] = '';

$lang['user.extended.field.avatar']                    = 'Avatar';
$lang['user.extended.field.avatar.clue']               = '';
$lang['user.extended.field.avatar.current.avatar']     = 'Avatar actuel';
$lang['user.extended.field.avatar.upload.avatar']      = 'Uploader un avatar';
$lang['user.extended.field.avatar.upload.avatar.clue'] = 'Avatar directement hébergé sur le serveur';
$lang['user.extended.field.avatar.link']               = 'Lien de l\'avatar';
$lang['user.extended.field.avatar.link.clue']          = 'Adresse directe de l\'avatar';
$lang['user.extended.field.avatar.delete']             = 'Supprimer l\'avatar courant';
$lang['user.extended.field.avatar.no.avatar']          = 'Aucun avatar';

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

// Moderation panel
$lang['user.contact.pm']     = 'Contacter par message privé';
$lang['user.alternative.pm'] = 'Message privé envoyé au membre <span class="small text-italic">(Laisser vide pour aucun message privé)</span>. <br />Le membre averti ne pourra pas répondre à ce message, et ne connaîtra pas l\'expéditeur.';

// Punishments management
$lang['user.punishments']           = 'Sanctions';
$lang['user.punishment.management'] = 'Gestion des sanctions';
$lang['user.punish.until']          = 'Sanction jusqu\'au';
$lang['user.no.punish']             = 'Il n\'y a aucun utilisateur sanctionné.';
$lang['user.readonly.clue']         = 'Membre en lecture seule, celui-ci peut lire mais ne peut plus poster sur la totalité du site (commentaires, etc.)';
$lang['user.life']                  = 'A vie';
$lang['user.readonly.user']         = 'Membre en lecture seule';
$lang['user.read.only.title']       = 'Sanction';
$lang['user.readonly.changed'] = 'Vous avez été mis en lecture seule par un membre de l\'équipe de modération, vous ne pourrez plus poster pendant %date%.


Ceci est un message semi-automatique.';

// Warning management
$lang['user.warnings']           = 'Avertissements';
$lang['user.warning.management'] = 'Gestion des avertissements';
$lang['user.warning.level']      = 'Niveau d\'avertissement';
$lang['user.no.user.warning']    = 'Il n\'y a aucun utilisateur averti.';
$lang['user.warning.clue']       = 'Niveau d\'avertissement du membre. Vous pouvez le modifier, mais sachez qu\'à 100% le membre est banni.';
$lang['user.warning.title']      = 'Avertissement';
$lang['user.warning.user']       = 'Membre averti';
$lang['user.warning.level.changed'] = 'Vous avez été averti par un membre de l\'équipe de modération, votre niveau d\'avertissement est passé à %level%%. Attention à votre comportement, si vous atteignez 100% vous serez banni définitivement.


Ceci est un message semi-automatique.';

// Bans management.
$lang['user.bans']            = 'Bannissements';
$lang['user.ban.management']  = 'Gestion des bannissements';
$lang['user.ban.until']       = 'Banni jusqu\'au';
$lang['user.no.ban']          = 'Il n\'y a aucun utilisateur banni.';
$lang['user.ban.delay']       = 'Durée du bannissement';
$lang['user.ban.title.email'] = 'Banni';
$lang['user.ban.email'] = 'Bonjour,

Vous avez été banni sur : %s !
S\'il s\'agit d\'une erreur veuillez contacter l\'administrateur du site.


%s';

// Private messaging
$lang['user.private.message']       = 'Message privé';
$lang['user.private.messages']      = 'Messages privés';
$lang['user.pm.box']                = 'Boîte de réception';
$lang['user.recipient']             = 'Destinataire';
$lang['user.post.new.conversation'] = 'Créer une nouvelle conversation';
$lang['user.new.pm']                = 'Nouveau message privé';
$lang['user.pm.conversation.link']  = 'Consulter la conversation';
$lang['user.pm.status']             = 'Statut du message';
$lang['user.pm.track']              = 'Non lu par le destinataire';
$lang['user.not.read']              = 'Non lu';
$lang['user.read']                  = 'Lu';
$lang['user.last.message']          = 'Dernier message';
$lang['user.mark.pm.as.read']       = 'Marquer tous les messages comme lus';
$lang['user.participants']          = 'Participant(s)';
$lang['user.quote.last.message']    = 'Reprise du message précédent';
$lang['user.select.all.messages']   = 'Sélectionner tous les messages';

//Cookies bar
$lang['user.cookiebar.cookie']                    = 'Cookie';
$lang['user.cookiebar.cookie.management']         = 'Gestion des Cookies';
$lang['user.cookiebar.message.notracking']        = 'En poursuivant votre navigation sur ce site internet, vous acceptez l\'utilisation de Cookies afin de gérer votre connexion, vos préférences, ainsi que l\'enregistrement de statistiques anonymes des visites.';
$lang['user.cookiebar.message.tracking']          = 'En poursuivant votre navigation sur ce site internet, vous acceptez l\'utilisation de Cookies ou autres traceurs pour vous proposer une navigation adaptée (publicités ciblées, partage réseaux sociaux, etc...).';
$lang['user.cookiebar.message.aboutcookie.title'] = 'A propos des Cookies';
$lang['user.cookiebar.message.aboutcookie']  = 'Pour assurer le bon fonctionnement de ce site, nous devons parfois enregistrer de petits fichiers de données sur l\'équipement de nos utilisateurs.<br />La majorité des sites web font de même.

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
Pour en savoir plus, consultez le site <a href="https://www.aboutcookies.org">aboutcookies.org</a>.<br />
Vous avez la possibilité de supprimer tous les cookies déjà stockés sur votre ordinateur et de configurer la plupart des navigateurs pour qu\'ils les bloquent. Toutefois, dans ce cas, vous devrez peut-être indiquer vous-mêmes certaines préférences chaque fois que vous vous rendrez sur le site, et certains services et fonctionnalités risquent de ne pas être accessibles.
';
$lang['user.cookiebar.understand']    = 'J\'ai compris';
$lang['user.cookiebar.allowed']       = 'Autoriser';
$lang['user.cookiebar.declined']      = 'Bloquer';
$lang['user.cookiebar.more.title']    = 'Explications sur la gestion des cookies et de la "cookie-bar" (En savoir plus)';
$lang['user.cookiebar.more']          = 'En savoir plus';
$lang['user.cookiebar.cookies']       = 'Cookies';
$lang['user.cookiebar.change.choice'] = 'Modifier vos préférences';

//Menu
$lang['user.menu.link.to'] = 'Lien vers la page ';
?>
