<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 23
 * @since       PHPBoost 6.0 - 2021 04 20
*/

####################################################
#                     French                       #
####################################################

$lang['contribution.panel']          = 'Panneau de contributions';
$lang['contribution.my.items']       = 'Mes contributions';
$lang['contribution.contribution']   = 'Contribution';
$lang['contribution.contributions']  = 'Contributions';
$lang['contribution.details']        = 'Détails de la contribution';
$lang['contribution.process']        = 'Traiter la contribution';
$lang['contribution.not.processed']  = 'Non traitée';
$lang['contribution.in.progress']    = 'En cours';
$lang['contribution.processed']      = 'Traitée';
$lang['contribution.edition']        = 'Edition d\'une contribution';
$lang['contribution.contributor']    = 'Contributeur';
$lang['contribution.closing.date']   = 'Date de clôture';
$lang['contribution.change.status']  = 'Modifier le statut de la contribution';
$lang['contribution.delete']         = 'Supprimer la contribution';
$lang['contribution.list']           = 'Liste des contributions';
$lang['contribution.contribute']     = 'Contribuer';
$lang['contribution.member.edition'] = 'Modification de contribution par l\'auteur';

$lang['contribution.contribute.in.modules']     = 'Les modules suivants permettent aux utilisateurs de contribuer. Cliquez sur un module pour vous rendre dans son interface de contribution.';
$lang['contribution.contribute.in.module.name'] = 'Contribuer dans le module %s';
$lang['contribution.no.module.to.contribute']   = 'Aucun module supportant la contribution n\'est installé.';

$lang['contribution.warning'] = '
    Votre contribution sera traitée dans le panneau de contribution.
    <span class="error text-strong">La modification est possible tant que la contribution n\'a pas été approuvée.</span>
    Vous pouvez, dans le champ suivant, justifier votre contribution de façon à expliquer votre démarche à un modérateur.
';
$lang['contribution.extended.warning'] = '
    Votre contribution sera traitée dans le panneau de contribution.
    <span class="error text-strong">La modification est possible avant son approbation ainsi qu\'après.</span>
    Vous pouvez, dans le champ suivant, justifier votre contribution de façon à expliquer votre démarche à un modérateur.
    Si vous modifiez votre contribution <span class="text-strong">après approbation</span>, elle sera retraitée dans le panneau de contribution, en attente d\'une nouvelle approbation.
';
$lang['contribution.edition.warning'] = '
    Vous êtes sur le point de modifier votre contribution. Elle va être déplacée dans les éléments en attente afin d\'être traitée
    et une nouvelle alerte sera envoyée aux administrateurs.
';

$lang['contribution.description']              = 'Complément de contribution';
$lang['contribution.description.clue']         = 'Expliquez les raisons de votre contribution. Ce champ est facultatif mais il peut aider un approbateur à prendre sa décision.';
$lang['contribution.edition.description']      = 'Complément de modification';
$lang['contribution.edition.description.clue'] = 'Expliquez ce que vous avez modifié pour un meilleur traitement d\'approbation.';
$lang['contribution.confirmed']                = 'Votre contribution a bien été enregistrée.';
$lang['contribution.confirmed.messages'] = '
    <p>
        Vous pourrez la suivre dans le <a class="offload" href="' . UserUrlBuilder::contribution_panel()->rel() . '">panneau de contribution</a>
        et éventuellement discuter avec les validateurs si leur choix n\'est pas franc.</p><p>Merci d\'avoir participé à la vie du site !
    </p>
';
$lang['contribution.pm.title']    = 'La contribution <strong>:title</strong> a été commentée';
$lang['contribution.pm.content'] = '
    :author a ajouté un commentaire à la contribution <strong>:title</strong>.
    <p>
        <h6>Commentaire :</h6>
        :comment
    </p>
    <a class="offload" href=":contribution_url">Accéder à la contribution</a>
';

// Dead link
$lang['contribution.report.dead.link']       = 'Signaler un lien mort';
$lang['contribution.dead.link.confirmation'] = 'Êtes-vous sûr de vouloir signaler ce lien comme étant mort ?';
$lang['contribution.dead.link.name']         = 'Lien mort : :link_name';
$lang['contribution.dead.link.clue']         = 'Un membre a signalé ce lien comme étant mort. Veuillez vérifier le lien et modifiez-le si nécessaire.';
?>
