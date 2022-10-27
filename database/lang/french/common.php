<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 27
 * @since       PHPBoost 4.1 - 2015 09 30
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                    French                        #
####################################################

$lang['database.management']  = 'Gestion des tables';
$lang['database.sql.queries'] = 'Requêtes SQL';

// Configuration
$lang['database.config.enable.tables.optimization']   = 'Activer l\'optimisation automatique des tables de la base de données';
$lang['database.config.tables.optimization.day']      = 'Jour de l\'optimisation';
$lang['database.config.tables.optimization.day.clue'] = 'Optimisation exécutée dans la nuit';

// Management
$lang['database.creation.date'] = 'Date de création';
$lang['database.management.description'] = '
    Ce panneau vous permet de gérer votre base de données.
    <br />
    Vous pouvez y voir la liste des tables utilisées par PHPBoost, leurs propriétés, ainsi que divers outils qui vous permettront de faire quelques opérations basiques sur certaines tables.
    <br />
    Vous pouvez aussi effectuer une sauvegarde de votre base de données, ou de seulement quelques tables que vous sélectionnerez ici.
';
$lang['database.management.question'] = '
    L\'optimisation de la base de données permet de réorganiser la structure de la table afin de faciliter les opérations au serveur SQL. Cette opération peut être effectuée automatiquement si l\'option est cochée dans l\'administration du module. Vous pouvez optimiser les tables manuellement via ce panneau d\'administration.
    <br />
    La réparation n\'est normalement pas à envisager mais en cas de problème elle peut s\'avérer utile. Le support vous dira de l\'effectuer quand cela sera nécessaire.
    <br />
    <strong>Attention : </strong>C\'est une opération lourde, elle consomme beaucoup de ressources, il est donc conseillé d\'éviter de réparer les tables si ce n\'est pas utile !
';
$lang['database.restore.from.server'] = 'Vous pouvez utiliser les fichiers que vous n\'aviez pas supprimé lors de restaurations antérieures.';
$lang['database.view.file.list']      = 'Voir la liste des fichiers disponibles (<em>cache/backup</em>)';
$lang['database.import.file.description'] = '
    Vous pouvez restaurer votre base de données par un fichier que vous possédez sur votre ordinateur.
    <br />
    Si votre fichier dépasse la taille maximale autorisée par votre serveur, c\'est-à-dire %s, vous devez utiliser l\'autre méthode en envoyant par FTP votre fichier dans le répertoire <em>cache/backup</em>.
';
$lang['database.restore']                = 'Restaurer';
$lang['database.table.list']             = 'Liste des tables';
$lang['database.table.name']             = 'Nom de la table';
$lang['database.table.rows']             = 'Enregistrements';
$lang['database.table.rows.format']      = 'Format';
$lang['database.table.engine']           = 'Type';
$lang['database.table.structure']        = 'Structure';
$lang['database.table.collation']        = 'Interclassement';
$lang['database.table.data']             = 'Taille';
$lang['database.table.index']            = 'Index';
$lang['database.table.field']            = 'Champ';
$lang['database.table.attribute']        = 'Attribut';
$lang['database.table.null']             = 'Null';
$lang['database.table.value']            = 'Valeur';
$lang['database.table.extra']            = 'Extra';
$lang['database.autoincrement']          = 'Suivant autoindex';
$lang['database.table.free']             = 'Perte';
$lang['database.select']                 = 'Sélectionner';
$lang['database.select.all']             = 'Sélectionner toutes les tables';
$lang['database.confirm.empty.table']    = 'Etes-vous sûr de vouloir vider cette table ?';
$lang['database.selected.tables.action'] = 'Actions à réaliser sur la sélection de tables';
$lang['database.optimize']               = 'Optimiser';
$lang['database.repair']                 = 'Réparer';
$lang['database.insert']                 = 'Insérer';
$lang['database.backup']                 = 'Sauvegarder';
$lang['database.compress.file']          = 'Compresser le fichier';
$lang['database.backup.database']        = 'Sauvegarder la base de données';
$lang['database.backup.description'] = '
    Vous pouvez encore modifier la liste des tables que vous souhaitez sélectionner dans le formulaire.
    <br />
    Ensuite vous devez choisir ce que vous souhaitez sauvegarder.
';
$lang['database.backup.all']       = 'Données et structure';
$lang['database.backup.structure'] = 'Structure seulement';
$lang['database.backup.datas']     = 'Données seulement';

// SQL queries
$lang['database.query.execute'] = 'Éxécuter une requête SQL sur la base de données';
$lang['database.query.description'] = '
    Vous pouvez dans ce panneau d\'administration exécuter des requêtes dans la base de données. Cette interface ne devrait servir que lorsque le support vous demande d\'exécuter une requête dans la base de données qui vous sera communiquée.
    <br />
    <strong>Attention :</strong> si cette requête n\'a pas été proposée par le support vous êtes responsable de son exécution et des pertes de données qu\'elle pourrait provoquer. Il est donc fortement déconseillé d\'utiliser ce module si vous ne maîtrisez pas complètement la structure des tables de PHPBoost.
';
$lang['database.submit.query']  = 'Éxécuter';
$lang['database.query.result']  = 'Résultat';
$lang['database.confirm.query'] = 'Voulez-vous vraiment exécuter la requête suivante : ';

// Restore files
$lang['database.file.list']           = 'Liste des fichiers';
$lang['database.restore.file.clue']   = 'Cliquez sur le fichier que vous voulez restaurer.';
$lang['database.confirm.restoration'] = 'Etes-vous sûr de vouloir restaurer votre base de données à partir de la sauvegarde sélectionnée ?';
$lang['database.file.does.not.exist'] = 'Le fichier que vous souhaitez supprimer n\'existe pas ou n\'est pas un fichier SQL';
$lang['database.empty.directory']     = 'Le dossier est vide';
$lang['database.file.name']           = 'Nom du fichier';
$lang['database.file.weight']         = 'Taille du fichier';

// Message helper
$lang['database.backup.success']         = 'Votre base de données a été correctement sauvegardée. Vous pouvez la télécharger en cliquant sur le lien suivant : <a href="admin_database.php?read_file=%s">%s</a>';
$lang['database.restore.success']        = 'La restauration de la base de données a été effectuée avec succès';
$lang['database.restore.error']          = 'Une erreur est survenue pendant la restauration de la base de données';
$lang['database.upload.error']           = 'Une erreur est survenue lors du transfert du fichier à partir duquel vous souhaitez importer votre base de données';
$lang['database.file.already.exists']    = 'Un fichier du répertoire cache/backup porte le même nom que celui que vous souhaitez importer. Merci de renommer un des deux fichiers pour pouvoir l\'importer.';
$lang['database.no.sql.file']            = 'Le fichier à importer n\'est pas la sauvegarde d\'une base de données, merci de fournir un fichier correct à restaurer.';
$lang['database.backup.wrong.site']      = 'Le fichier à importer n\'est pas la sauvegarde de ce site, restauration impossible.';
$lang['database.backup.wrong.version']   = 'Le fichier à importer ne contient pas la version actuelle de phpboost, restauration impossible.';
$lang['database.unlink.success']         = 'Le fichier a été supprimé avec succès !';
$lang['database.unlink.error']           = 'Le fichier n\'a pas pu être supprimé';
$lang['database.repair.tables.succes']   = 'La sélection de tables (<em>%s</em>) a été réparée avec succès';
$lang['database.optimize.tables.succes'] = 'La sélection de tables (<em>%s</em>) a été optimisée avec succès';
?>
