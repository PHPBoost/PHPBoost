<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 08
 * @since       PHPBoost 3.0 - 2011 10 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     French                       #
####################################################

$lang['user'] = 'Utilisateur';
$lang['users'] = 'Utilisateurs';
$lang['profile'] = 'Profil';
$lang['profile_of'] = 'Profil de :name';
$lang['profile.edit'] = 'Edition du profil';
$lang['dashboard'] = 'Tableau de bord';
$lang['messages'] = 'Messages de l\'utilisateur';
$lang['maintain'] = 'Maintenance';
$lang['welcome'] = 'Bienvenue';
$lang['about.author'] = 'À propos de l\'auteur';

$lang['members_list'] = 'Liste des membres';
$lang['member-management'] = 'Gestion du membre';
$lang['punishment-management'] = 'Gestion des sanctions';

$lang['profile.edit.password.error'] = 'Le mot de passe que vous avez entré n\'est pas correct';
$lang['external-auth.account-exists'] = 'Vous avez déjà un compte sur le site. Pour utiliser cette méthode de connexion, veuillez vous connecter et vous rendre dans l\'édition de votre profil';
$lang['external-auth.email-not-found'] = 'L\'adresse email de votre compte n\'a pas pu être récupérée, votre compte ne peut pas être associé.';
$lang['external-auth.user-data-not-found'] = 'Les informations de votre compte n\'ont pas pu être récupérées, votre compte ne peut pas être créé.';

//Contribution
$lang['contribution'] = 'Contribution';
$lang['contribution.explain'] = 'Votre contribution suivra le parcours classique et sera traitée dans le panneau de contribution. <span class="error text-strong">La modification est possible tant que la contribution n\'a pas été approuvée.</span> Vous pouvez, dans le champ suivant, justifier votre contribution de façon à expliquer votre démarche à un approbateur.';
$lang['contribution.description'] = 'Complément de contribution';
$lang['contribution.description.explain'] = 'Expliquez les raisons de votre contribution. Ce champ est facultatif mais il peut aider un approbateur à prendre sa décision.';
$lang['contribution.confirmed'] = 'Votre contribution a bien été enregistrée.';
$lang['contribution.confirmed.messages'] = '<p>Vous pourrez la suivre dans le <a href="' . UserUrlBuilder::contribution_panel()->rel() . '">panneau de contribution</a>
et éventuellement discuter avec les validateurs si leur choix n\'est pas franc.</p><p>Merci d\'avoir participé à la vie du site !</p>';
$lang['contribution.pm.title'] = 'La contribution <strong>:title</strong> a été commentée';
$lang['contribution.pm.contents'] = ':author a ajouté un commentaire à la contribution <strong>:title</strong>.<br />
<br />
Commentaire :<br />
:comment<br />
<br />
<a href=":contribution_url">Accéder à la contribution</a>';

//User fields
$lang['display_name'] = 'Nom d\'affichage';
$lang['display_name.explain'] = 'Nom affiché sur chacun des éléments que vous ajoutez.';
$lang['login'] = 'Identifiant de connexion';
$lang['login.explain'] = 'Adresse email ou votre login personnalisé si vous en avez choisi un.';
$lang['login.custom'] = 'Choisir un identifiant de connexion';
$lang['login.custom.explain'] = '<span class="error">Si non coché, vous devrez vous connecter avec votre adresse email</span>';
$lang['login.tooltip'] = 'Si vous n\'avez pas coché "Choisir un identifiant de connexion" dans votre profil, connectez-vous avec l\'adresse email que vous avez déclarée.';
$lang['password.custom'] = 'Définir un mot de passe';
$lang['password.custom.explain'] = 'Par défaut un mot de passe est généré automatiquement';
$lang['password'] = 'Mot de passe';
$lang['password.new'] = 'Nouveau mot de passe';
$lang['password.old'] = 'Ancien mot de passe';
$lang['password.old.explain'] = 'Remplir seulement en cas de modification';
$lang['password.confirm'] = 'Confirmer le mot de passe';
$lang['password.explain'] = 'Longueur minimale du mot de passe : :number caractères';
$lang['email'] = 'Email';
$lang['email.hide'] = 'Cacher l\'email';
$lang['theme'] = 'Thème';
$lang['theme.preview'] = 'Prévisualiser le thème';
$lang['text-editor'] = 'Editeur de texte';
$lang['lang'] = 'Langue';
$lang['timezone.'] = 'Fuseau horaire';
$lang['timezone.choice'] = 'Choix du fuseau horaire';
$lang['timezone.choice.explain'] = 'Permet d\'ajuster l\'heure à votre localisation';
$lang['level'] = 'Rang';
$lang['approbation'] = 'Approbation';

$lang['registration_date'] = 'Date d\'inscription';
$lang['last_connection'] = 'Dernière connexion';
$lang['number_messages'] = 'Nombre de messages';
$lang['private_message'] = 'Message privé';
$lang['delete-account'] = 'Supprimer le compte';
$lang['delete-account.confirmation.member'] = 'Etes-vous sûr de vouloir supprimer votre compte ?';
$lang['delete-account.confirmation.admin'] = 'Etes-vous sûr de vouloir supprimer le compte ?';
$lang['avatar'] = 'Avatar';

//Groups
$lang['groups'] = 'Groupes';
$lang['groups.list'] = 'Liste des groupes';
$lang['groups.select'] = 'Sélectionner un groupe';
$lang['group.of_group'] = 'du groupe :';
$lang['group.view_list_members'] = 'Voir la liste des membres du groupe';
$lang['group.hide_list_members'] = 'Masquer la liste des membres du groupe';
$lang['admins.list'] = 'Liste des administrateurs';
$lang['modos.list'] = 'Liste des modérateurs';
$lang['no_member'] = 'Aucun membre dans ce groupe';

//Other
$lang['caution'] = 'Avertissement';
$lang['readonly'] = 'Lecture seule';
$lang['banned'] = 'Banni';
$lang['connection'] = 'Connexion';
$lang['autoconnect'] = 'Connexion auto';
$lang['disconnect'] = 'Se déconnecter';

$lang['internal_connection'] = 'Connexion interne';
$lang['create_internal_connection'] = 'Créer un compte interne';
$lang['edit_internal_connection'] = 'Editer votre compte interne';
$lang['associate_account'] = 'Associer votre compte';
$lang['associate_account_admin'] = 'Associer un compte';
$lang['dissociate_account'] = 'Dissocier votre compte';
$lang['dissociate_account_admin'] = 'Dissocier le compte';

$lang['share'] = 'Partager';
$lang['share_on'] = 'Partager sur';
$lang['share_by'] = 'Partager par';
$lang['share.menu'] = 'Menu réseaux sociaux';
$lang['share.sms'] = 'SMS';

// Ranks
$lang['rank'] = 'Rang';
$lang['robot'] = 'Robot';
$lang['visitor'] = 'Visiteur';
$lang['member'] = 'Membre';
$lang['moderator'] = 'Modérateur';
$lang['administrator'] = 'Administrateur';

//Forget password
$lang['forget-password'] = 'Mot de passe oublié';
$lang['forget-password.select'] = 'Sélectionnez le champ que vous voulez renseigner (email ou pseudo)';
$lang['forget-password.success'] = 'Un email vous a été envoyé avec un lien pour changer votre mot de passe';
$lang['forget-password.error'] = 'Les informations fournies ne sont pas correctes, veuillez les rectifier et réessayer';
$lang['change-password'] = 'Changement de mot de passe';
$lang['forget-password.mail.content'] = 'Cher(e) :pseudo,

Vous recevez cet e-mail parce que vous (ou quelqu\'un qui prétend l\'être) avez demandé à ce qu\'un nouveau mot de passe vous soit envoyé pour votre compte sur :host.
Si vous n\'avez pas demandé de changement de mot de passe, veuillez l\'ignorer. Si vous continuez à le recevoir, veuillez contacter l\'administrateur du site.

Pour changer de mot de passe, cliquez sur le lien fourni ci-dessous et suivez les indications sur le site.

:change_password_link

Si vous rencontrez des difficultés, veuillez contacter l\'administrateur du site.

:signature';

//Registration
$lang['register'] = 'S\'inscrire';
$lang['registration'] = 'Inscription';

$lang['registration.validation.mail.explain'] = 'Vous devrez activer votre compte dans l\'email qui vous sera envoyé avant de pouvoir vous connecter';
$lang['registration.validation.administrator.explain'] = 'Un administrateur devra activer votre compte avant de pouvoir vous connecter';

$lang['registration.confirm.success'] = 'Votre compte a été validé avec succès';
$lang['registration.confirm.error'] = 'Un problème est survenu lors de votre activation, vérifiez que votre clé est bien valide';

$lang['registration.success.administrator-validation'] = 'Vous vous êtes enregistré avec succès. Cependant un administrateur doit valider votre compte avant de pouvoir l\'utiliser';
$lang['registration.success.mail-validation'] = 'Vous vous êtes enregistré avec succès. Cependant il vous faudra cliquer sur le lien d\'activation contenu dans le mail qui vous a été envoyé';

$lang['registration.email.automatic-validation'] = 'Vous pouvez désormais vous connecter à votre compte directement sur le site.';
$lang['registration.email.mail-validation'] = 'Vous devez activer votre compte avant de pouvoir vous connecter en cliquant sur ce lien : :validation_link';
$lang['registration.email.administrator-validation'] = 'Attention : Votre compte devra être activé par un administrateur avant de pouvoir vous connecter. Merci de votre patience.';
$lang['registration.email.mail-administrator-validation'] = 'Cher(e) :pseudo,

Nous avons le plaisir de vous informer que votre compte sur :site_name vient d\'être validé par un administrateur.

Vous pouvez dès à présent vous connecter au site à l\'aide des identifiants fournis dans le précédent email.

:signature';

$lang['registration.pending-approval'] = 'Un nouveau membre s\'est inscrit. Son compte doit être approuvé avant de pouvoir être utilisé.';
$lang['registration.not-approved'] = 'Votre compte doit être approuvé par un administrateur avant de pouvoir être utilisé.';
$lang['registration.subject-mail'] = 'Confirmation d\'inscription sur :site_name';
$lang['registration.lost-password-link'] = 'Si vous perdez votre mot de passe, vous pouvez en générer un nouveau à partir de ce lien : :lost_password_link';
$lang['registration.password'] = 'Mot de passe : :password';
$lang['registration.content-mail'] = 'Cher(e) :pseudo,

Tout d\'abord, merci de vous être inscrit sur :site_name. Vous faites partie dès maintenant des membres du site.
En vous inscrivant sur :site_name, vous obtenez un accès à la zone membre qui vous offre plusieurs avantages. Vous pourrez, entre autre, être reconnu automatiquement sur tout le site, pour poster des messages, modifier la langue et/ou le thème par défaut, éditer votre profil, accéder à des catégories réservées aux membres... Bref vous accédez à toute la communauté du site.

Pour vous connecter, il vous faudra retenir votre identifiant et votre mot de passe.

Nous vous rappelons vos identifiants.

Identifiant : :login
:lost_password_link

:accounts_validation_explain

A bientôt sur :host

:signature';
$lang['registration.content-mail.admin'] = 'Cher(e) :pseudo,

Vous avez été inscrit sur le site :site_name par un administrateur. Vous faites maintenant partie des membres du site.
Vous obtenez un accès à la zone membre qui vous offre plusieurs avantages. Vous pourrez, entre autre, être reconnu automatiquement sur tout le site, pour poster des messages, modifier la langue et/ou le thème par défaut, éditer votre profil, accéder à des catégories réservées aux membres... Bref vous accédez à toute la communauté du site.

Pour vous connecter, il vous faudra retenir votre identifiant et votre mot de passe.

Nous vous rappelons vos identifiants.

Identifiant : :login
:lost_password_link

:accounts_validation_explain

A bientôt sur :host

:signature';

$lang['agreement'] = 'Règlement';
$lang['agreement.agree'] = 'J\'accepte les conditions';
$lang['agreement.agree.required'] = 'Vous devez accepter le règlement pour vous inscrire';

//Messages
$lang['user.message.success.add'] = 'L\'utilisateur <b>:name</b> a été ajouté';
$lang['user.message.success.edit'] = 'Le profil a été modifié';
$lang['user.message.success.delete'] = 'L\'utilisateur <b>:name</b> a été supprimé';
$lang['user.message.success.delete.member'] = 'Votre compte a été supprimé';

//SEO
$lang['seo.user.profile'] = 'Toutes les informations de profil de :name.';
$lang['seo.user.list'] = 'Tableau de la liste des utilisateurs du site.';
$lang['seo.user.groups'] = 'Utilisateurs de chaque groupe du site.';
$lang['seo.user.comments'] = 'Tous les commentaires.';
$lang['seo.user.comments.user'] = 'Tous les commentaires de l\'utilisateur :name.';
$lang['seo.user.messages'] = 'Tous les messages de l\'utilisateur :name.';
$lang['seo.user.registration'] = 'Renseignez toutes les informations demandées pour créer un compte.';
$lang['seo.user.login'] = 'Connectez-vous au site pour accéder au contenu protégé.';
$lang['seo.user.forget-password'] = 'Renseignez toutes les informations demandées pour recevoir un lien pour changer de mot de passe.';
$lang['seo.user.about-cookie'] = 'Toutes les informations relatives aux cookies sur le site.';

############## Extended Field ##############

$lang['extended-field.field.sex'] = 'Sexe';
$lang['extended-field.field.sex-explain'] = '';

$lang['extended-field.field.pmtomail'] = 'Recevoir une notification par mail à la réception d\'un message privé';
$lang['extended-field.field.pmtomail-explain'] = '';

$lang['extended-field.field.date-birth'] = 'Date de naissance';
$lang['extended-field.field.date-birth-explain'] = '';

$lang['extended-field.field.avatar'] = 'Avatar';
$lang['extended-field.field.avatar-explain'] = '';
$lang['extended-field.field.avatar.current_avatar'] = 'Avatar actuel';
$lang['extended-field.field.avatar.upload_avatar'] = 'Uploader un avatar';
$lang['extended-field.field.avatar.upload_avatar-explain'] = 'Avatar directement hébergé sur le serveur';
$lang['extended-field.field.avatar.link'] = 'Lien avatar';
$lang['extended-field.field.avatar.link-explain'] = 'Adresse directe de l\'avatar';
$lang['extended-field.field.avatar.delete'] = 'Supprimer l\'avatar courant';
$lang['extended-field.field.avatar.no_avatar'] = 'Aucun avatar';

$lang['extended-field.field.location'] = 'Localisation';
$lang['extended-field.field.location-explain'] = '';

$lang['extended-field.field.job'] = 'Emploi';
$lang['extended-field.field.job-explain'] = '';

$lang['extended-field.field.entertainement'] = 'Loisirs';
$lang['extended-field.field.entertainement-explain'] = '';

$lang['extended-field.field.biography'] = 'Biographie';
$lang['extended-field.field.biography-explain'] = '';
$lang['extended-field.field.no-biography'] = 'Ce membre n\'a pas renseigné la biographie de son profil';
$lang['extended-field.field.no-member'] = 'Ce membre n\'est plus inscrit';

//Scroll to
$lang['scroll-to.top'] = 'haut de la page';
$lang['scroll-to.bottom'] = 'bas de la page';

//Cookies bar
$lang['cookiebar.cookie'] = 'Cookie';
$lang['cookiebar.cookie.management'] = 'Gestion des Cookies';
$lang['cookiebar-message.notracking']  = 'En poursuivant votre navigation sur ce site internet, vous acceptez l\'utilisation de Cookies afin de gérer votre connexion, vos préférences, ainsi que l\'enregistrement de statistiques anonymes des visites.';
$lang['cookiebar-message.tracking']  = 'En poursuivant votre navigation sur ce site internet, vous acceptez l\'utilisation de Cookies ou autres traceurs pour vous proposer une navigation adaptée (publicités ciblées, partage réseaux sociaux, etc...).';
$lang['cookiebar-message.aboutcookie.title']  = 'A propos des Cookies';
$lang['cookiebar-message.aboutcookie']  = 'Pour assurer le bon fonctionnement de ce site, nous devons parfois enregistrer de petits fichiers de données sur l\'équipement de nos utilisateurs.<br />La majorité des sites web font de même.

<h2 class="formatter-title">Qu\'est-ce qu\'un cookie ?</h2>
Un cookie est un petit fichier texte que les sites web sauvegardent sur votre ordinateur ou appareil mobile lorsque vous les consultez.<br />
Il permet à ces sites de mémoriser vos actions et préférences (nom d\'utilisateur, langue, taille des caractères et autres paramètres d\'affichage) pendant un temps donné, pour que vous n\'ayez pas à réindiquer ces informations à chaque fois que vous consultez ces sites ou naviguez d\'une page à une autre.<br />

<h2 class="formatter-title">Les cookies techniques : </h2>
De base, ' . GeneralConfig::load()->get_site_name() . ' utilise les cookies pour :<br />
<ul class="formatter-ul">
<li class="formatter-li"> gérer le système d\'identification (indispensable si vous souhaitez vous connecter),
</li><li class="formatter-li"> sauvegarder les préférences de la BBCode (pas indispensable, mais vous devrez ré-ouvrir la BBCode à chaque visite),
</li><li class="formatter-li"> enregistrer des statistiques anonymes pour le site web (pas indispensable, mais permet aux webmasters de savoir combien de visites concernent le site).
</li></ul>
<h2 class="formatter-title">Les autres cookies :</h2>
' . GeneralConfig::load()->get_site_name() . ' n\'utilise aucun système de traceurs. En revanche, l\'utilisation du module google analytics et des boutons réseaux sociaux, nécessite des cookies traceurs.

<h2 class="formatter-title">Comment contrôler les cookies ?</h2>
Vous pouvez contrôler et/ou supprimer des cookies comme vous le souhaitez.<br />
Pour en savoir plus, consultez le site web <a href="http://www.aboutcookies.org">aboutcookies.org</a>.<br />
Vous avez la possibilité de supprimer tous les cookies déjà stockés sur votre ordinateur et de configurer la plupart des navigateurs pour qu\'ils les bloquent. Toutefois, dans ce cas, vous devrez peut-être indiquer vous-mêmes certaines préférences chaque fois que vous vous rendrez sur le site, et certains services et fonctionnalités risquent de ne pas être accessibles.
';
$lang['cookiebar.understand']  = 'J\'ai compris';
$lang['cookiebar.allowed']  = 'Autoriser';
$lang['cookiebar.declined']  = 'Bloquer';
$lang['cookiebar.more-title']  = 'Explications sur la gestion des cookies et de la "cookie-bar" (En savoir plus)';
$lang['cookiebar.more']  = 'En savoir plus';
$lang['cookiebar.cookies'] = 'Cookies';
$lang['cookiebar.change-choice'] = 'Modifier vos préférences';

//Menu
$lang['menu.link-to'] = 'Lien vers la page ';
?>
