<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

####################################################
#                      French                      #
####################################################

$lang['lobby.title']               = 'Bienvenue';
$lang['lobby.config.module.title'] = 'Configuration du module Page d\'accueil';
$lang['lobby.modules.position']    = 'Position des éléments';

// Module detection
$lang['lobby.add.modules']             = 'Ajouter des éléments';
$lang['lobby.add.modules.warning']     = '
    <p class="text-strong">:modules_list</p>
    <p>Cliquer sur <strong>Valider</strong> pour ajouter les nouveaux modules.</p>
';
$lang['lobby.incomplete.module.warning'] = '
    <p>Le module <strong>:module_name</strong> déclare la feature <code>lobby</code> mais il manque les fichiers suivants : <code>:missing_files</code>.</p>
    <p>Ce module ne peut pas être ajouté au module Page d\'accueil tant que ces fichiers ne sont pas présents.</p>
';
$lang['lobby.new.modules']             = 'Nouveaux modules détectés';
$lang['lobby.no.new.module']           = '<p>Les nouveaux modules ont bien été ajoutés au <strong>Page d\'accueil</strong>.</p>';
$lang['lobby.back.to.configuration']   = '<p>La <a href="' . LobbyUrlBuilder::configuration()->rel() . '">configuration</a> des nouveaux modules est maintenant disponible.</p>';
$lang['lobby.new.modules.description'] = '
    <p>Des nouveaux modules éligibles au module <strong>Page d\'accueil</strong> ont été installés et activés sur le site :</p>
    <p class="text-strong">:modules_list</p>
';

// Messages
$lang['lobby.posted.in.topic']  = 'Posté dans le sujet :';
$lang['lobby.posted.in.module'] = 'Posté dans le module :';

// Modules labels
$lang['lobby.see.module']          = 'Voir le module';
$lang['lobby.display.module']      = 'Afficher le module';
$lang['lobby.module.carousel']     = 'Carrousel';
$lang['lobby.module.anchors.menu'] = 'Menu d\'accueil';
$lang['lobby.module.edito']        = 'Edito';
$lang['lobby.module.lastcoms']     = 'Commentaires';

// Anchors menu
$lang['lobby.anchors.title'] = 'Menu d\'accueil';

// Carousel
$lang['lobby.carousel.no.alt'] = 'Élément du carrousel';

// Contact
$lang['lobby.link.to.contact']      = 'Voir la page contact';

// Configuration
$lang['lobby.label.module.title']      = 'Titre du module';
$lang['lobby.label.module.title.clue'] = 'Affiche le titre du module sur la page, le fil d\'Ariane et l\'onglet';

$lang['lobby.menus.display']            = 'Affichage des emplacements de menus';
$lang['lobby.show.menus']               = 'L\'affichage des menus ne concerne que la page d\'accueil';
$lang['lobby.show.menu.left']           = 'Afficher le menu gauche';
$lang['lobby.show.menu.right']          = 'Afficher le menu droite';
$lang['lobby.show.menu.top.central']    = 'Afficher le menu central haut';
$lang['lobby.show.menu.bottom.central'] = 'Afficher le menu central bas';
$lang['lobby.show.menu.top.footer']     = 'Afficher le menu sur-pied de page';

$lang['lobby.items.number']                    = 'Nombre d\'éléments à afficher';
$lang['lobby.chars.number']                    = 'Limiter le nombre de caractères';
$lang['lobby.category']                        = 'Catégorie';
$lang['lobby.subcategories.content.displayed'] = 'Afficher le contenu des sous-catégories';

// Configuration Anchors Menu
$lang['lobby.config.anchors']       = 'Affichage du menu d\'accueil';
$lang['lobby.display.anchors']      = 'Afficher le menu d\'accueil';
$lang['lobby.display.anchors.clue'] = 'Ce menu permet de naviguer rapidement au sein de la page d\'accueil';

// Configuration Edito
$lang['lobby.config.edito']  = 'Affichage de l\'édito';
$lang['lobby.display.edito'] = 'Afficher l\'édito';
$lang['lobby.edito.content'] = 'Contenu de l\'édito';

// Configuration Lastcoms
$lang['lobby.config.lastcoms']  = 'Affichage des commentaires';
$lang['lobby.display.lastcoms'] = 'Afficher les commentaires récents';

// Configuration Carousel
$lang['lobby.config.carousel']      = 'Affichage du carrousel';
$lang['lobby.display.carousel']     = 'Afficher le carrousel';
$lang['lobby.carousel.content']     = 'Contenu du carrousel';
$lang['lobby.carousel.speed']       = 'Vitesse de changement d\'image (ms)';
$lang['lobby.carousel.time']        = 'Durée d\'affichage d\'une image (ms)';
$lang['lobby.carousel.number']      = 'Nombre d\'images affichées';
$lang['lobby.carousel.number.clue'] = '0px < 1 image < 768px < 2 images < 1024px < choix';
$lang['lobby.carousel.auto']        = 'Défilement automatique';
$lang['lobby.carousel.hover']       = 'Pause au survol';
$lang['lobby.carousel.enabled']     = 'Activé';
$lang['lobby.carousel.disabled']    = 'Désactivé';
// Carousel content
$lang['lobby.carousel.description'] = 'Description du slide';
$lang['lobby.carousel.link.url']    = 'Adresse du lien';
$lang['lobby.carousel.picture.url'] = 'Adresse de l\'image';
$lang['lobby.carousel.upload']      = 'Ouvrir le gestionnaire de fichiers';
$lang['lobby.carousel.add']         = 'Ajouter une image';
$lang['lobby.carousel.del']         = 'Supprimer le slide';

// Module-specific config hints
$lang['lobby.calendar.clue'] = 'Affiche uniquement les événements à venir';
$lang['lobby.flux.clue']     = 'Affiche les éléments de flux les plus récents parmi tous les flux';
$lang['lobby.web.clue']      = 'Affiche seulement les liens partenaires';
?>
