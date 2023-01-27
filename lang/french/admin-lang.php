<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 27
 * @since       PHPBoost 3.0 - 2010 08 14
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

####################################################
#                     French                      #
####################################################

$lang['admin.administration']     = 'Administration';
$lang['admin.kernel']             = 'Noyau';
$lang['admin.warning']            = 'Attention';
$lang['admin.priority']           = 'Priorité';
$lang['admin.priority.very.high'] = 'Immédiat';
$lang['admin.priority.high']      = 'Urgent';
$lang['admin.priority.medium']    = 'Moyenne';
$lang['admin.priority.low']       = 'Faible';
$lang['admin.priority.very.low']  = 'Très faible';
$lang['admin.code']               = 'Code';

$lang['admin.unknown.robot'] = 'Robot inconnu';

// Advice
$lang['admin.advice'] = 'Conseils';
$lang['admin.advice.modules.management']           = '<a href="' . AdminModulesUrlBuilder::list_installed_modules()->rel() . '">Désactivez ou désinstallez les modules</a> que vous n\'utilisez pas pour économiser les ressources du site.';
$lang['admin.advice.check.modules.authorizations'] = 'Vérifiez les autorisations d\'accès de tous vos modules et menus avant de mettre le site en ligne pour éviter que les visiteurs ou des utilisateurs non autorisés aient accès à des sections protégées du site.';
$lang['admin.advice.disable.debug.mode']           = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Désactivez le mode debug</a> pour ne pas afficher les erreurs aux utilisateurs (les erreurs sont quand même logguées dans les <a href="' . AdminErrorsUrlBuilder::logged_errors()->rel() . '">Erreurs archivées</a>).';
$lang['admin.advice.disable.maintenance']          = '<a href="' . AdminMaintainUrlBuilder::maintain()->rel() . '">Désactivez la maintenance</a> pour que les utilisateurs puissent afficher le site.';
$lang['admin.advice.enable.url.rewriting']         = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Activez la réécriture des URL</a> pour que les URL de votre site soient plus lisibles (très utile pour le référencement).';
$lang['admin.advice.enable.output.gz']             = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Activez la compression des pages</a> pour gagner en performance.';
$lang['admin.advice.enable.apcu.cache']            = '<a href="' . AdminCacheUrlBuilder::configuration()->rel() . '">Activez le cache APCu</a> pour permettre de charger le cache en RAM sur le serveur et non sur le disque-dur et ainsi gagner d\'avantage en performance.';
$lang['admin.advice.save.database']                = 'Pensez à sauvegarder votre base de données régulièrement.';
$lang['admin.advice.optimize.database.tables']     = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Activez l\'optimisation automatique des tables</a> ou optimisez de temps en temps vos tables dans le module <strong>Base de données</strong> (s\'il est installé) ou dans votre outil de gestion de base de données pour récupérer de la place perdue en base.';
$lang['admin.advice.password.security']            = 'Augmentez la complexité et la longueur des mots de passe dans la <a href="' . AdminMembersUrlBuilder::configuration()->rel() . '">configuration des membres</a> pour renforcer la sécurité.';
$lang['admin.advice.upgrade.php.version']          = '
    La version PHP ' . ServerConfiguration::get_phpversion() . ' configurée sur votre serveur est obsolète, elle ne reçoit plus de mise à jour de sécurité et peut potentiellement contenir des vulnérabilités permettant à une personne mal intentionnée d\'attaquer votre site.
    <br />Mettez à jour votre version PHP pour passer en ' . ServerConfiguration::RECOMMENDED_PHP_VERSION . ' minimum si votre hébergeur le permet, les nouvelles versions sont plus rapides et sécurisées.
';

// Alerts
$lang['admin.alerts']               = 'Alertes';
$lang['admin.alerts.list']          = 'Liste des alertes';
$lang['admin.no.unread.alert']      = 'Aucune alerte en attente';
$lang['admin.unread.alerts']        = 'Des alertes non traitées sont en attente.';
$lang['admin.no.alert']             = 'Aucune alerte existante';
$lang['admin.display.all.alerts']   = 'Voir toutes les alertes';
$lang['admin.fix.alert']            = 'Passer l\'alerte en réglée';
$lang['admin.unfix.alert']          = 'Passer l\'alerte en non réglée';
$lang['admin.warning.delete.alert'] = 'Etes-vous sûr de vouloir supprimer cette alerte ?';
$lang['admin.unread.alerts']        = 'Des alertes non traitées sont en attente.';
$lang['admin.see.all.alerts']       = 'Voir toutes les alertes';

// Cache
$lang['admin.clear.cache'] = 'Vider le cache';

$lang['admin.cache']                  = 'Cache';
$lang['admin.cache.data.description'] = '
    <p>PHPBoost met en cache un certain nombre d\'informations, ce qui permet d\'améliorer considérablement ses performances.
    Toutes les données manipulées par PHPBoost sont stockées en base de données mais chaque accès à la base de données coûte cher en ressource. Les données auxquelles on accéde de façon régulière (notamment la configuration) sont ainsi conservées par le serveur
    de façon à ne pas avoir à les demander à la base de données.</p>
    <p>En contre partie, cela signifie que certaines données sont présentes à deux endroits : dans la base de données et sur le serveur web. Si vous modifiez des données dans la base de données, la modification ne se fera peut-être pas immédiatement car le fichier de cache contient encore les anciennes données.
    Dans ce cas, il faut vider le cache à la main via cette page de configuration de façon à ce que PHPBoost soit obligé de générer de nouveaux fichiers de cache contenant les données à jour.
    L\'emplacement de référence des données est la base de données. Si vous modifiez un fichier cache, dès qu\'il sera invalidé car la base de données aura changé, les modifications seront perdues.</p>
';
    // Syndication
$lang['admin.cache.syndication']             = 'Cache syndication';
$lang['admin.cache.syndication.description'] = '
    <p>PHPBoost met en cache l\'ensemble des flux de données (RSS ou ATOM) qui lui sont demandés. En pratique, la première fois qu\'on lui demande un flux, il va le chercher en base de données, l\'enregistre sur le serveur web et n\'accède plus à la base de données les fois suivantes pour
    éviter des requêtes qui ralentissent considérablement l\'affichage des pages.</p>
    <p>Via cette page de l\'administration de PHPBoost, vous pouvez vider le cache de façon à forcer PHPBoost à rechercher les données dans la base de données. C\'est particulièrement utile si vous avez modifié certaines choses manuellement dans la base de données. En effet, elles ne seront pas prises en compte car le cache aura toujours les valeurs précédentes.</p>
';
    // CSS
$lang['admin.cache.css']             = 'Cache CSS';
$lang['admin.cache.css.description'] = '
    <p>PHPBoost met en cache l\'ensemble des fichiers CSS fournis par les thèmes et modules.
    En temps normal, à l\'affichage du site, un ensemble de fichiers css va être chargé. Le système de cache CSS quant à lui, va d\'abord optimiser les fichiers pour ensuite créer un seul et même fichier CSS condensé.</p>
    <p>Via cette page de l\'administration de PHPBoost, vous pouvez vider le cache CSS de façon à forcer PHPBoost à recréer les fichiers CSS optimisés. </p>
';
    // Configuration
$lang['admin.cache.configuration'] = 'Configuration du cache';
$lang['admin.php.accelerator']     = 'Accélérateur PHP';
$lang['admin.php.description']     = '
    <p>Il existe des modules complémentaires à PHP permettant d\'améliorer nettement la vitesse d\'exécution des applications PHP.
    A l\'heure actuelle, PHPBoost supporte <abbr aria-label="Alternative PHP Cache">APCu</abbr> qui est un système de cache additionnel pour améliorer le temps de chargement des pages.</p>
    <p>Par défaut le cache est enregistré dans le système de fichier (arborescence de fichiers du serveur) dans le dossier /cache. Un système tel que APCu permet de stocker ces données directement en mémoire centrale (RAM) qui propose des temps d\'accès incomparablement plus faibles.</p>
';
$lang['admin.enable.apc']            = 'Activer le cache d\'APCu';
$lang['admin.apc.available']         = 'Disponibilité de l\'extension APCu';
$lang['admin.apc.available.clue']    = 'L\'extension est disponible sur un nombre assez restreint de serveurs. Si elle n\'est pas disponible, vous ne pouvez malheureusement pas profiter des gains de performances qu\'elle permet d\'obtenir.';
$lang['admin.apcu.cache']            = 'Cache APCu';
$lang['admin.css.cache.description'] = '
    <p>PHPBoost met en cache l\'ensemble des fichiers CSS fournis par les thèmes et modules pour améliorer le temps d\'affichage des pages.
    Vous pouvez à travers cette configuration, choisir d\'activer ou non cette fonctionnalité et son niveau d\'intensité. <br/>
    La désactivation de cette option peut vous permettre de personnaliser plus facilement vos thèmes. </p>
';
$lang['admin.enable.css.cache']   = 'Activer le cache CSS';
$lang['admin.optimization.level'] = 'Niveau d\'optimisation';
$lang['admin.low.level']          = 'Bas';
$lang['admin.high.level']         = 'Haut';
$lang['admin.level.clue']         = 'Le niveau bas permet de ne supprimer que les tabulations et les espaces tandis que le niveau haut optimise totalement vos fichiers CSS.';

// Content
$lang['admin.content.configuration'] = 'Configuration du contenu';
$lang['admin.forbidden.module']      = 'Modules interdits';
    // Format
$lang['admin.formatting.language']         = 'Langage de formatage';
$lang['admin.default.formatting.language'] = 'Langage de formatage du contenu par défaut du site';
$lang['admin.formatting.language.clue']    = 'Chaque utilisateur pourra choisir';
$lang['admin.forbidden.tags']              = 'Types de formatage interdits';
    // HTML
$lang['admin.html.language']            = 'Langage HTML';
$lang['admin.html.authorizations']      = 'Niveau d\'autorisation pour insérer du langage HTML';
$lang['admin.html.authorizations.clue'] = 'Attention : le code HTML peut contenir du code Javascript susceptible de constituer une source de faille de sécurité si quelqu\'un y insère un code malveillant. Veillez donc à autoriser seulement les personnes de confiance à insérer du HTML.';
    // Messages
$lang['admin.messages.management'] = 'Gestion des messages';
$lang['admin.max.pm.number']       = 'Nombre maximum de messages privés';
$lang['admin.max.pm.number.clue']  = 'Illimité pour administrateurs et modérateurs';
$lang['admin.anti.flood']          = 'Anti flood';
$lang['admin.anti.flood.clue']     = 'Empêche les messages trop rapprochés, sauf si les visiteurs sont autorisés';
$lang['admin.flood.delay']         = 'Intervalle minimum de temps entre les messages';
$lang['admin.flood.delay.clue']    = 'En secondes. 7 secondes par défaut.';
    // Share
$lang['admin.sharing.management']      = 'Gestion des options de partage';
$lang['admin.display.content.sharing'] = 'Afficher les liens de partage sur les pages de contenu';
$lang['admin.display.email.sharing']   = 'Afficher le partage par Email';
$lang['admin.display.print.sharing']   = 'Afficher le lien d\'impression de la page';
$lang['admin.print.sharing.clue']      = 'Visible sur ordinateur uniquement';
$lang['admin.display.sms.sharing']     = 'Afficher le partage par SMS';
$lang['admin.sms.sharing.clue']        = 'Visible sur périphérique mobile uniquement';
    // S.E.O.
$lang['admin.opengraph']       = 'Améliorer le référencement avec les balises OpenGraph';
$lang['admin.opengraph.clue']  = 'Permet de donner des informations précises sur les pages aux moteurs de recherche et réseaux sociaux';
$lang['admin.default.picture'] = 'Image par défaut du site pour le référencement';
    // Captcha
$lang['admin.captcha']      = 'Captcha';
$lang['admin.used.captcha'] = 'Code de vérification utilisé sur le site';
$lang['admin.captcha.clue'] = 'Le code de vérification permet de vous prémunir contre le spam sur votre site.';
    // New content
$lang['admin.new.content.config']        = 'Gestion du contenu récent';
$lang['admin.enable.new.content']        = 'Activer l\'option sur les nouveaux éléments';
$lang['admin.new.content.clue']          = 'Cette option permet d\'identifier les derniers éléments ajoutés';
$lang['admin.new.content.duration']      = 'Durée d\'affichage du tag';
$lang['admin.new.content.duration.clue'] = 'En jours. 5 jours par défaut.';
$lang['admin.new.content.forbidden.module.clue'] = 'Sélectionnez les modules dans lesquels vous ne souhaitez pas activer les tags de nouveau contenu';
    // Notation
$lang['admin.notation.config'] = 'Configuration de la notation';
$lang['admin.enable.notation'] = 'Activer la notation';
$lang['admin.notation.scale']  = 'Echelle de notation';
$lang['admin.notation.forbidden.module.clue'] = 'Sélectionnez les modules dans lesquels vous ne souhaitez pas activer la notation';
    // ID card
$lang['admin.id.card']        = 'Gestion des informations sur l\'auteur';
$lang['admin.enable.id.card'] = 'Activer l\'affichage des informations sur l\'auteur';
$lang['admin.id.card.clue']   = 'Permet d\'afficher dans un article un encart sur l\'auteur (profil + avatar + biographie)';
$lang['admin.id.card.forbidden.module.clue'] = 'Sélectionnez les modules dans lesquels vous ne souhaitez pas activer des informations sur l\'auteur';

// Errors lists
$lang['admin.errors']               = 'Erreurs';
$lang['admin.clear.list']           = 'Vider la liste';
$lang['admin.warning.clear.errors'] = 'Effacer toutes les erreurs ?';
    // Logged
$lang['admin.logged.errors']      = 'Erreurs archivées';
$lang['admin.logged.errors.list'] = 'Liste des erreurs archivées';
    // 404
$lang['admin.404.errors']        = 'Erreurs 404';
$lang['admin.404.errors.list']   = 'Liste des erreurs 404';
$lang['admin.404.requested.url'] = 'Url demandée';
$lang['admin.404.from.url']      = 'Url de provenance';

// Index
$lang['admin.quick.access']        = 'Accès rapide';
$lang['admin.welcome.title']       = 'Bienvenue sur le tableau de bord d\'administration de votre site';
$lang['admin.welcome.description'] = 'Ce tableau de bord vous permet de gérer le contenu et la configuration de votre site<br />La page d\'accueil recense les actions les plus courantes<br />Prenez le temps de lire les conseils afin d\'optimiser la sécurité de votre site';
$lang['admin.website.management']  = 'Administrer le site';
$lang['admin.customize.website']   = 'Personnaliser le site';
$lang['admin.add.content']         = 'Ajouter du contenu';
$lang['admin.customize.theme']     = 'Personnaliser un thème';
$lang['admin.add.article']         = 'Ajouter un article';
$lang['admin.add.news']            = 'Ajouter une actualité';
$lang['admin.last.comments']       = 'Derniers commentaires';
$lang['admin.writing.pad']         = 'Bloc-note';
$lang['admin.writing.pad.clue']    = 'Cet emplacement est destiné à la saisie vos notes personnelles.';

// Maintenance
$lang['admin.maintenance']         = 'Maintenance';
$lang['admin.maintenance.title']   = 'Site en maintenance';
$lang['admin.maintenance.content'] = 'Le site est actuellement en maintenance. Merci de votre patience.';
$lang['admin.maintenance.delay']   = 'Délai estimé avant réouverture du site :';
$lang['admin.disable.maintenance'] = 'Désactiver la maintenance';
    // Form
$lang['admin.maintenance.type']                   = 'Mettre le site en maintenance';
$lang['admin.maintenance.type.during']            = 'Moins d\'une journée';
$lang['admin.maintenance.type.until']             = 'Plusieurs jours';
$lang['admin.maintenance.type.unlimited']         = 'Pour une durée non spécifiée';
$lang['admin.maintenance.display.duration']       = 'Afficher la durée de la maintenance';
$lang['admin.maintenance.admin.display.duration'] = 'Afficher la durée de la maintenance à l\'administrateur';
$lang['admin.maintenance.text']                   = 'Texte à afficher lorsque la maintenance du site est en cours';
$lang['admin.maintenance.authorization']          = 'Autorisation d\'accès au site durant la maintenance';

// Server report
$lang['admin.server']                     = 'Serveur';
$lang['admin.phpinfo']                    = 'PHP info';
$lang['admin.system.report']              = 'Rapport système';
$lang['admin.php.version']                = 'Version de PHP';
$lang['admin.dbms.version']               = 'Version du SGBD';
$lang['admin.gd.library']                 = 'Librairie GD';
$lang['admin.curl.library']               = 'Extension Curl';
$lang['admin.mbstring.library']           = 'Extension Mbstring (UTF-8)';
$lang['admin.url.rewriting']              = 'Réécriture des URL';
$lang['admin.phpboost.config']            = 'Configuration de PHPBoost';
$lang['admin.kernel.version']             = 'Version du noyau';
$lang['admin.output.gz']                  = 'Compression des pages';
$lang['admin.directories.auth']           = 'Autorisation des répertoires';
$lang['admin.system.report.summary']      = 'Récapitulatif';
$lang['admin.system.report.summary.clue'] = 'Ceci est le récapitulatif du rapport. Cela vous sera particulièrement utile lorsqu\'on vous demandera la configuration de votre système pour du support.';
$lang['admin.copy.report']                = 'Copier le rapport';

// Smileys
$lang['admin.smileys.management']     = 'Gestion des smileys';
$lang['admin.upload.smileys']         = 'Uploader des smileys';
$lang['admin.smiley']                 = 'Smiley';
$lang['admin.add.smileys']            = 'Ajouter des smileys';
$lang['admin.edit.smiley']            = 'Editer un smiley';
$lang['admin.smiley.code']            = 'Code du smiley (ex : :D)';
$lang['admin.available.smileys']      = 'Smileys disponibles';
$lang['admin.available.smileys.clue'] = '/images/smileys';
$lang['admin.smiley.success.add']     = 'Le smiley a été ajouté';

// Updates
$lang['admin.updates']                = 'Mises à jour';
$lang['admin.available.updates']      = 'Mises à jour disponibles';
$lang['admin.available.updates.clue'] = 'Des mises à jours sont disponibles.<br />Veuillez les effectuer au plus vite.';
$lang['admin.available.version']      = 'Le %1$s %2$s est disponible dans sa version %3$s';
$lang['admin.kernel.update']          = 'PHPBoost est disponible dans sa nouvelle version %s';
$lang['admin.download.app']           = 'Téléchargement';
$lang['admin.download.pack']          = 'Pack complet';
$lang['admin.update.pack']            = 'Pack de mise à jour';
$lang['admin.new.features']           = 'Nouvelles Fonctionnalités';
$lang['admin.improvements']           = 'Améliorations';
$lang['admin.security.improvements']  = 'Améliorations de sécurité';
$lang['admin.fixed.bugs']             = 'Corrections de bugs';
$lang['admin.details']                = 'Détails';
$lang['admin.more.details']           = 'Plus de détails';
$lang['admin.download.full.pack']     = 'Télécharger le pack complet';
$lang['admin.download.update.pack']   = 'Télécharger le pack de mise à jour';
$lang['admin.no.available.update']    = 'Aucune mise à jour n\'est disponible pour l\'instant.';
$lang['admin.updates.check']          = 'Vérifier la présence de mises à jour';
$lang['admin.update.verification.impossible'] = 'La vérification de présence de mises à jour est impossible, la fonction n\'est pas disponible sur votre serveur.<br/>Activez l\'extension Curl dans les options PHP de votre serveur pour faire fonctionner la vérification de disponibilité de mises à jour.';

?>
