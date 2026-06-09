<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 06 06
 * @since       PHPBoost 6.1 - 2026 03 21
*/

####################################################
#                      French                      #
####################################################

$lang['lobby.edito.description'] = '
    Vous êtes sur le module <strong>Page d\'accueil</strong> du site. N\'hésitez pas à le modifier via sa <div class="pinned bgc link-color"><a class="offload" href="' . LobbyUrlBuilder::configuration()->relative() . '">configuration</a></div>.
    <br />
    <br />
    Votre site propulsé par PHPBoost est bien installé et fonctionnel. Afin de vous aider à le prendre en main, voici quelques recommandations que nous vous proposons de lire avec attention :
    <br />
    <h2 class="formatter-title">N\'oubliez pas de supprimer le répertoire /install</h2>
    <a class="offload" href="' . AdminConfigUrlBuilder::general_config()->relative() . '">Supprimez le répertoire /install</a> à la racine de votre site pour des raisons de sécurité afin que personne ne puisse recommencer l\'installation.
    <br />
    <br />
    <h2 class="formatter-title">Administrez votre site</h2>
    Des modules, un menu et du contenu ont été installés par défaut, vous pouvez les modifier :
    <br />
    Accédez au <div class="pinned link-color"><a class="offload" href="' . UserUrlBuilder::administration()->relative() . '">panneau d\'administration</a></div> de votre site afin de le paramétrer comme vous le souhaitez !
    <br />
    <br />
    <br />
    <div class="cell-flex formatter-columns cell-columns-4 cell-tile">
        <div class="cell">
            <div class="cell-header"><h4 class="formatter-title">1. Maintenance</h4></div>
            <div class="cell-body">
                <div class="cell-content">
                    <p style="text-align: center;"><a class="offload" href="' . AdminMaintainUrlBuilder::maintain()->relative() . '"><i class="fa fa-person-digging fa-2x" aria-hidden="true"></i></a></p>
                    <a class="offload" href="' . AdminMaintainUrlBuilder::maintain()->relative() . '">Mettez votre site en maintenance</a> en attendant que vous le configuriez à votre guise.
                </div>
            </div>
        </div>
        <div class="cell">
            <div class="cell-header"><h4 class="formatter-title">2. Configuration</h4></div>
            <div class="cell-body">
                <div class="cell-content">
                    <p style="text-align: center;"><a class="offload" href="' . AdminConfigUrlBuilder::general_config()->relative() . '"><i class="fa fa-gears fa-2x" aria-hidden="true"></i></a></p>
                    Rendez vous dans la <a class="offload" href="' . AdminConfigUrlBuilder::general_config()->relative() . '">configuration générale</a> du site pour définir vos options par défaut.
                </div>
            </div>
        </div>
        <div class="cell">
            <div class="cell-header"><h4 class="formatter-title">3. Membres</h4></div>
            <div class="cell-body">
                <div class="cell-content">
                    <p style="text-align: center;"><a class="offload" href="' . AdminMembersUrlBuilder::configuration()->relative() . '"><i class="fa fa-users fa-2x" aria-hidden="true"></i></a></p>
                    <a class="offload" href="' . AdminMembersUrlBuilder::configuration()->relative() . '">Configurez l\'inscription des membres</a>.
                </div>
            </div>
        </div>
        <div class="cell">
            <div class="cell-header"><h4 class="formatter-title">4. Éditeur</h4></div>
            <div class="cell-body">
                <div class="cell-content">
                    <p style="text-align: center;"><a class="offload" href="' . AdminContentUrlBuilder::content_configuration()->relative() . '"><i class="far fa-pen-to-square fa-2x" aria-hidden="true"></i></a></p>
                    <a class="offload" href="' . AdminContentUrlBuilder::content_configuration()->relative() . '">Choisissez un éditeur de texte</a> outil indispensable pour ajouter du contenu.
                </div>
            </div>
        </div>
        <div class="cell">
            <div class="cell-header"><h4 class="formatter-title">5. Menus</h4></div>
            <div class="cell-body">
                <div class="cell-content">
                    <p style="text-align: center;"><a class="offload" href="/admin/menus/menus.php"><i class="fa fa-bars fa-2x" aria-hidden="true"></i></a></p>
                    <a class="offload" href="/admin/menus/menus.php">Modifiez le menu de lien ou créez d\'autres menus</a> puis configurez leur droits d\'accès.
                </div>
            </div>
        </div>
        <div class="cell">
            <div class="cell-header"><h4 class="formatter-title">6. Modules</h4></div>
            <div class="cell-body">
                <div class="cell-content">
                    <p style="text-align: center;"><a class="offload" href="' . AdminModulesUrlBuilder::add_module()->relative() . '"><i class="fa fa-puzzle-piece fa-2x" aria-hidden="true"></i></a></p>
                    <a class="offload" href="' . AdminModulesUrlBuilder::add_module()->relative() . '">Ajoutez des modules</a> puis configurez leur droits d\'accès.
                </div>
            </div>
        </div>
        <div class="cell">
            <div class="cell-header"><h4 class="formatter-title">7. Thèmes</h4></div>
            <div class="cell-body">
                <div class="cell-content">
                    <p style="text-align: center;"><a class="offload" href="' . AdminThemeUrlBuilder::add_theme()->relative() . '"><i class="far fa-images fa-2x" aria-hidden="true"></i></a></p>
                    <a class="offload" href="' . AdminThemeUrlBuilder::add_theme()->relative() . '">Ajoutez des thèmes</a> (templates) puis configurez leur droits d\'accès.
                </div>
            </div>
        </div>
        <div class="cell">
            <div class="cell-header"><h4 class="formatter-title">8. Contenu</h4></div>
            <div class="cell-body">
                <div class="cell-content">
                    <p style="text-align: center;"><i class="fa fa-file-signature fa-2x" aria-hidden="true"></i></p>
                    Avant de donner l\'accès de votre site à vos visiteurs, prenez un peu de temps pour y mettre du contenu, puis désactivez la <a class="offload" href="/phpboost/dev/pbtnext/admin/maintain/">maintenance</a>
                </div>
            </div>
        </div>
    </div>
    <br />
    <br />
        <h2 class="formatter-title">Que faire si vous rencontrez un problème ?</h2><br />
        N\'hésitez pas à consulter <div class="pinned bgc-full moderator"><a class="offload" href="https://www.phpboost.com/wiki/" target="_blank" rel="noopener">la documentation de PHPBoost</a></div> ou à poser vos questions sur le <div class="pinned bgc-full link-color"><a class="offload" href="https://www.phpboost.com/forum/" target="_blank" rel="noopener">forum d\'entraide</a></div>.
    <br />
    <br />
    <p class="float-right">Toute l\'équipe de PHPBoost vous remercie d\'utiliser son logiciel pour créer votre site web !</p>
';
?>
