<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 25
 * @since       PHPBoost 3.0 - 2010 08 14
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     French                      #
####################################################

// Title
$lang['members.config-members'] = 'Configuration des membres';
$lang['members.members-management'] = 'Gestion des membres';
$lang['members.add-member'] = 'Ajouter un membre';
$lang['members.members-punishment'] = 'Gestion des sanctions';
$lang['members.edit-member'] = 'Edition d\'un membre';
$lang['members.rules'] = 'Règlement';

//Configuration
$lang['members.config.registration-activation'] = 'Activer l\'inscription des membres';
$lang['members.config.type-activation'] = 'Mode d\'activation du compte membre';
$lang['members.config.unactivated-accounts-timeout'] = 'Nombre de jours après lequel les membres non activés sont effacés';
$lang['members.config.unactivated-accounts-timeout-explain'] = 'Laisser vide pour ignorer cette option (Non pris en compte si validation par administrateur)';
$lang['members.config.allow_users_to_change_display_name'] = 'Autoriser les membres à changer leur Nom d\'affichage';
$lang['members.config.allow_users_to_change_email'] = 'Autoriser les membres à changer leur Email';
$lang['members.config.upload-avatar-server-authorization'] = 'Autoriser l\'upload d\'avatar sur le serveur';
$lang['members.config.activation-resize-avatar'] = 'Activer le redimensionnement automatique des images';
$lang['members.activation-resize-avatar-explain'] = 'Attention votre serveur doit avoir l\'extension GD chargée';
$lang['members.config.maximal-width-avatar'] = 'Largeur maximale de l\'avatar';
$lang['members.config.maximal-width-avatar-explain'] = 'Par défaut 120';
$lang['members.config.maximal-height-avatar'] = 'Hauteur maximale de l\'avatar';
$lang['members.config.maximal-height-avatar-explain'] = 'Par défaut 120';
$lang['members.config.maximal-weight-avatar'] = 'Poids maximal de l\'avatar en Ko';
$lang['members.config.maximal-weight-avatar-explain'] = 'Par défaut 20';
$lang['members.config.default-avatar-activation'] = 'Activer l\'avatar par défaut';
$lang['members.config.default-avatar-activation-explain'] = 'Met un avatar aux membres qui n\'en ont pas';
$lang['members.config.default-avatar-link'] = 'Adresse de l\'avatar par défaut';
$lang['members.default-avatar-link-explain'] = 'Mettre dans le dossier images de votre thème';
$lang['members.config.authorization-read-member-profile'] = 'Vous définissez ici les autorisations de lecture de la liste des groupes et des membres ainsi que certaines informations personnelles des utilisateurs comme leurs emails, messages et profils.';
$lang['members.config.welcome-message'] = 'Message à tous les membres';
$lang['members.config.welcome-message-content'] = 'Message de bienvenue affiché dans le profil du membre';

//Security Configuration
$lang['members.config-security'] = 'Sécurité';
$lang['security.config.internal-password-min-length'] = 'Longueur minimale des mots de passe';
$lang['security.config.internal-password-strength'] = 'Complexité des mots de passe';
$lang['security.config.internal-password-strength.weak'] = 'Faible';
$lang['security.config.internal-password-strength.medium'] = 'Moyenne (lettres et chiffres)';
$lang['security.config.internal-password-strength.strong'] = 'Forte (minuscules, majuscules et chiffres)';
$lang['security.config.internal-password-strength.very-strong'] = 'Très forte (minuscules, majuscules, chiffres et caractères spéciaux)';
$lang['security.config.login-and-email-forbidden-in-password'] = 'Interdire l\'adresse email et l\'identifiant de connexion dans le mot de passe';
$lang['security.config.forbidden-mail-domains'] = 'Liste des noms de domaines interdits';
$lang['security.config.forbidden-mail-domains.explain'] = 'Domaines interdits dans les adresses mail des utilisateurs (séparés par des virgules). Exemple : <b>domain.com</b>';

$lang['members.config-authentication'] = 'Configuration des moyens d\'authentification';

//Other fieldset configuration title
$lang['members.config.avatars-management'] = 'Gestion des avatars';
$lang['members.config.authorization'] = 'Autorisations';

//Activation type
$lang['members.config.type-activation.auto'] = 'Automatique';
$lang['members.config.type-activation.mail'] = 'Mail';
$lang['members.config.type-activation.admin'] = 'Administrateur';

//Rules
$lang['members.rules.registration-agreement-description'] = 'Entrez ci-dessous le règlement à afficher lors de l\'enregistrement des membres, ils devront l\'accepter pour s\'enregistrer. Laissez vide pour aucun règlement.';
$lang['members.rules.registration-agreement'] = 'Contenu du règlement';

//Other
$lang['members.valid'] = 'Valide';

############## Extended Field ##############

$lang['extended-field-add'] = 'Ajouter un champ au profil';
$lang['extended-field-edit'] = 'Editer un champ du profil';
$lang['extended-field'] = 'Champs du profil';
$lang['extended-fields-management'] = 'Gestion des champs du profil';
$lang['extended-fields-error-already-exist'] = 'Le champ existe déjà.';
$lang['extended-fields-error-phpboost-config'] = 'Les champs utilisés par défaut par PHPBoost ne peuvent pas être créés plusieurs fois, veuillez choisir un autre type de champ.';

$lang['fields.management'] = 'Gestion des champs du profil';
$lang['fields.action.add_field'] = 'Ajouter un champ';

//Type
$lang['type.short-text'] = 'Texte court (max 255 caractères)';
$lang['type.long-text'] = 'Texte long (illimité)';
$lang['type.half-text'] = 'Texte semi long';
$lang['type.simple-select'] = 'Sélection unique (parmi plusieurs valeurs)';
$lang['type.multiple-select'] = 'Sélection multiple (parmi plusieurs valeurs)';
$lang['type.simple-check'] = 'Choix unique (parmi plusieurs valeurs)';
$lang['type.multiple-check'] = 'Choix multiples (parmi plusieurs valeurs)';
$lang['type.date'] = 'Date';
$lang['type.user-theme-choice'] = 'Choix des thèmes';
$lang['type.user-lang-choice'] = 'Choix des langues';
$lang['type.user_born'] = 'Date de naissance';
$lang['type.user_pmtomail'] = 'Notification par mail à la réception d\'un MP';
$lang['type.user-editor'] = 'Choix de l\'éditeur';
$lang['type.user-timezone'] = 'Choix du fuseau horaire';
$lang['type.user-sex'] = 'Choix du sexe';
$lang['type.avatar'] = 'Gestion de l\'avatar';

$lang['default-field'] = 'Champs par défaut';

$lang['field.name'] = 'Nom';
$lang['field.description'] = 'Description';
$lang['field.type'] = 'Type de champ';
$lang['field.regex'] = 'Contrôle de la forme de l\'entrée';
$lang['field.regex-explain'] = 'Permet d\'effectuer un contrôle sur la saisie faite par l\'utilisateur. Par exemple, s\'il s\'agit d\'une adresse mail, on peut contrôler que sa forme est correcte. <br />Vous pouvez effectuer un contrôle personnalisé en tapant une expression régulière (utilisateurs expérimentés seulement).';
$lang['field.predefined-regex'] = 'Forme prédéfinie';
$lang['field.required'] = 'Champ requis';
$lang['field.required_explain'] = 'Obligatoire dans le profil du membre et à son inscription.';
$lang['field.possible-values'] = 'Valeurs possibles';
$lang['field.possible_values.is_default'] = 'Par défaut';
$lang['field.possible_values.delete_default'] = 'Supprimer la valeur par défaut';
$lang['field.default-value'] = 'Valeur par défaut';
$lang['field.read_authorizations'] = 'Autorisations de lecture du champ dans le profil';
$lang['field.actions_authorizations'] = 'Autorisations de lecture du champ dans la création ou la modification d\'un profil';
$lang['field.refresh'] = 'Rafraichir';
$lang['field.display'] = 'Afficher';
$lang['field.not_display'] = 'Ne pas afficher';

// Regex
$lang['regex.figures'] = 'Chiffres';
$lang['regex.letters'] = 'Lettres';
$lang['regex.figures-letters'] = 'Chiffres et lettres';
$lang['regex.word'] = 'Mot';
$lang['regex.website'] = 'Site web';
$lang['regex.mail'] = 'Mail';
$lang['regex.phone-number'] = 'Numéro de téléphone';
$lang['regex.personnal-regex'] = 'Expression régulière personnalisée';

//Messages
$lang['message.success.add'] = 'Le champ du profil <b>:name</b> a été ajouté';
$lang['message.success.edit'] = 'Le champ du profil <b>:name</b> a été modifié';
?>
