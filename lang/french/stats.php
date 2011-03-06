<?php
/*##################################################
 *                                stats.php
 *                            -------------------
 *   begin                : September 05, 2007
 *   copyright          : (C) 2007 Viarre Régis
 *   email                : mickaelhemri@gmail.com
 *
 *  
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/


####################################################
#                                                           French                                                                               #
####################################################

//Titre
$LANG['title_stats'] = 'Statistiques';

//Error
$LANG['error_gd'] = 'Librairie GD non chargée!';

//Statistiques
$LANG['stats'] = 'Statistiques';
$LANG['more_stats'] = 'Plus de stats';
$LANG['site'] = 'Site';
$LANG['referer_s'] = 'Sites référents';
$LANG['page_s'] = 'Pages vues';
$LANG['browser_s'] = 'Navigateurs';
$LANG['keyword_s'] = 'Mots clés';
$LANG['fai'] = 'Fournisseurs d\'accès Internet';
$LANG['all_fai'] = 'Voir la liste complète des fournisseurs d\'accès Internet';
$LANG['10_fai'] = 'Voir les 10 principaux fournisseurs d\'accès Internet';
$LANG['os'] = 'Systèmes d\'exploitation';
$LANG['other'] = 'Autres';
$LANG['number'] = 'Nombre ';
$LANG['start'] = 'Création du site';
$LANG['stat_lang'] = 'Pays des visiteurs';
$LANG['all_langs'] = 'Voir la liste complète des pays des visiteurs';
$LANG['10_langs'] = 'Voir les 10 principaux pays des visiteurs';
$LANG['visits_year'] = 'Voir les statistiques de l\'année';
$LANG['unknown'] = 'Inconnu';
$LANG['last_member'] = 'Dernier membre';
$LANG['top_10_posters'] = 'Top 10 : posteurs';
$LANG['version'] = 'Version';
$LANG['colors'] = 'Couleurs';

//Calendrier
$LANG['calendar'] = 'Calendrier';
$LANG['events'] = 'Evènements';
$LANG['january'] = 'Janvier';
$LANG['february'] = 'Février';
$LANG['march'] = 'Mars';
$LANG['april'] = 'Avril';
$LANG['may'] = 'Mai';
$LANG['june'] = 'Juin';
$LANG['july'] = 'Juillet';
$LANG['august'] = 'Août';
$LANG['september'] = 'Septembre';
$LANG['october'] = 'Octobre';
$LANG['november'] = 'Novembre';
$LANG['december'] = 'Décembre';
$LANG['monday'] = 'Lun';
$LANG['tuesday'] = 'Mar';
$LANG['wenesday'] = 'Mer';
$LANG['thursday'] = 'Jeu';
$LANG['friday'] = 'Ven';
$LANG['saturday'] = 'Sam';
$LANG['sunday']	= 'Dim';

//Sites référents.
$LANG['total_visit'] = 'Total des visites';
$LANG['average_visit'] = 'Visites moyennes';
$LANG['trend'] = 'Tendance';
$LANG['last_update'] = 'Dernière visite';

//Navigateurs.
$stats_array_browsers = array(
	'internetexplorer' => array('Internet explorer', 'internetexplorer.png'),
	'opera' => array('Opera', 'opera.png'),
	'firefox' => array('Firefox', 'firefox.png'),
	'safari' => array('Safari', 'safari.png'),
	'chrome' => array('Chrome', 'chrome.png'),
	'konqueror' => array('Konqueror', 'konqueror.png'),
	'netscape' => array('Netscape', 'netscape.png'),
	'mozilla firebird' => array('Mozilla firebird', 'mozilla.png'),
	'mozilla' => array('Mozilla', 'mozilla.png'),
	'aol' => array('Aol', ''),
	'lynx' => array('lynx', ''),
	'seamonkey' => array('SeaMonkey', 'seamonkey.png'),
	'camino' => array('Camino', ''),
	'links' => array('Links', ''),
	'galeon' => array('Galeaon', ''),
	'phoenix' => array('Phoenix', ''),
	'chimera' => array('Chimera', ''),
	'k-meleon' => array('K-meleon', ''),
	'icab' => array('Icab', ''),
	'ncsa mosaic'=> array('Ncsa mosaic', ''),
	'amaya'	=> array('Amaya', ''),
	'omniweb' => array('Omniweb', ''),
	'hotjava' => array('Hotjava', ''),
	'browsex' => array('Browsex', ''),
	'amigavoyager'=> array('Amigavoyager', ''),
	'amiga-aweb'=> array('Amiga-aweb', ''),
	'ibrowse' => array('Ibrowse', ''),
	'phone' => array('Mobile', '../phone.png'),
	'other' => array('Autres', '../other.png')
);

//Système d'exploitation.
$stats_array_os = array(
	'linux' => array('Linux', 'linux.png'),
	'windowsseven' => array('Windows Seven', 'windowsseven.png'),
	'windowsvista' => array('Windows Vista', 'windowsvista.png'),
	'windowsserver2003' => array('Windows Serveur 2003', 'windowsxp.png'),
	'windowsxp' => array('Windows XP', 'windowsxp.png'),
	'windows2000' => array('Windows 2000', 'windowsold.png'),
	'windowsnt' => array('Windows NT', 'windowsold.png'),
	'windowsme' => array('Windows Me', 'windowsold.png'),
	'windows98' => array('Windows 98', 'windowsold.png'),
	'windows95' => array('Windows 95', 'windowsold.png'),
	'windowsce' => array('Windows CE', 'windowsold.png'),
	'macintosh' => array('Mac OS', 'mac.png'),
	'sunos' => array('SunOS', 'sun.png'),
	'os2' => array('OS2', 'os2.png'),
	'freebsd' => array('FreeBSD', 'freebsd.png'),
	'netbsd' => array('NetBSD', 'freebsd.png'),
	'aix' => array('AIX', 'aix.png'),
	'irix' => array('Irix', 'irix.png'),
	'hp-ux' => array('HP-UX', 'hpux.png'),
	'wii' => array('Wii', 'wii.png'),
	'psp' => array('Psp', 'psp.png'),
	'playstation3' => array('Playstation 3', 'ps3.png'),
	'phone' => array('Mobile', '../phone.png'),
	'other' => array('Autres', '../other.png')
);

//Pays.
global $stats_array_lang;
$stats_array_lang = array(
	'ac' => array('Ascension (île)', 'ac.png'),
	'ad' => array('Andorre', 'ad.png'),
	'ae' => array('Emirats Arabes Unis', 'ae.png'),
	'af' => array('Afghanistan', 'af.png'),
	'ag' => array('Antigua et Barbuda', 'ag.png'),
	'ai' => array('Anguilla', 'ai.png'),
	'al' => array('Albanie', 'al.png'),
	'am' => array('Arménie', 'am.png'),
	'an' => array('Antilles Neerlandaises', 'an.png'),
	'ao' => array('Angola', 'ao.png'),
	'aq' => array('Antarctique', 'aq.png'),
	'ar' => array('Argentine', 'ar.png'),
	'as' => array('American Samoa', 'as.png'),
	'at' => array('Autriche', 'at.png'),
	'au' => array('Australie', 'au.png'),
	'aw' => array('Aruba', 'aw.png'),
	'az' => array('Azerbaidjan', 'az.png'),
	'ba' => array('Bosnie Herzégovine', 'ba.png'),
	'bb' => array('Barbade', 'bb.png'),
	'bd' => array('Bangladesh', 'bd.png'),
	'be' => array('Belgique', 'be.png'),
	'bf' => array('Burkina Faso', 'bf.png'),
	'bg' => array('Bulgarie', 'bg.png'),
	'bh' => array('Bahrein', 'bh.png'),
	'bi' => array('Burundi', 'bi.png'),
	'bj' => array('Bénin', 'bj.png'),
	'bm' => array('Bermudes', 'bm.png'),
	'bn' => array('Brunei', 'bn.png'),
	'bo' => array('Bolivie', 'bo.png'),
	'br' => array('Brésil', 'br.png'),
	'bs' => array('Bahamas', 'bs.png'),
	'bt' => array('Bhoutan', 'bt.png'),
	'bv' => array('Bouvet (île)', 'bv.png'),
	'bw' => array('Botswana', 'bw.png'),
	'by' => array('Biélorussie', 'by.png'),
	'bz' => array('Bélize', 'bz.png'),
	'ca' => array('Canada', 'ca.png'),
	'cc' => array('Cocos (Keeling) îles', 'cc.png'),
	'cd' => array('Rép. dém. du Congo', 'cd.png'),
	'cf' => array('Rép Centrafricaine', 'cf.png'),
	'cg' => array('Congo', 'cg.png'),
	'ch' => array('Suisse', 'ch.png'),
	'ci' => array('Côte d\'Ivoire', 'ci.png'),
	'ck' => array('Cook (îles)', 'ck.png'),
	'cl' => array('Chili', 'cl.png'),
	'cm' => array('Cameroun', 'cm.png'),
	'cn' => array('Chine', 'cn.png'),
	'co' => array('Colombie', 'co.png'),
	'cr' => array('Costa Rica', 'cr.png'),
	'cs' => array('Serbie Montenegro', 'cs.png'), 
	'cu' => array('Cuba', 'cu.png'),
	'cv' => array('Cap Vert', 'cv.png'),
	'cx' => array('Christmas (île)', 'cx.png'),
	'cy' => array('Chypre', 'cy.png'),
	'cz' => array('Tchéquie', 'cz.png'),
	'de' => array('Allemagne', 'de.png'),
	'dj' => array('Djibouti', 'dj.png'),
	'dk' => array('Danemark', 'dk.png'),
	'dm' => array('Dominique', 'dm.png'),
	'do' => array('Rép Dominicaine', 'do.png'),
	'dz' => array('Algérie', 'dz.png'),
	'ec' => array('Equateur', 'ec.png'),
	'ee' => array('Estonie', 'ee.png'),
	'eg' => array('Egypte', 'eg.png'),
	'eh' => array('Sahara Occidental', 'eh.png'),
	'er' => array('Erythrée', 'er.png'),
	'es' => array('Espagne', 'es.png'),
	'et' => array('Ethiopie', 'et.png'),
	'fi' => array('Finlande', 'fi.png'),
	'fj' => array('Fidji', 'fj.png'),
	'fk' => array('Falkland (Malouines) îles', 'fk.png'),
	'fm' => array('Micronésie', 'fm.png'),
	'fo' => array('Faroe (îles)', 'fo.png'),
	'fr' => array('France', 'fr.png'),
	'ga' => array('Gabon', 'ga.png'),
	'gd' => array('Grenade', 'gd.png'),
	'ge' => array('Géorgie', 'ge.png'),
	'gf' => array('Guyane Française', 'gf.png'),
	'gg' => array('Guernsey', 'gg.png'),
	'gh' => array('Ghana', 'gh.png'),
	'gi' => array('Gibraltar', 'gi.png'),
	'gl' => array('Groenland', 'gl.png'),
	'gm' => array('Gambie', 'gm.png'),
	'gn' => array('Guinée', 'gn.png'),
	'gp' => array('Guadeloupe', 'gp.png'),
	'gq' => array('Guinée Equatoriale', 'gq.png'),
	'gr' => array('Grèce', 'gr.png'),
	'gs' => array('Géorgie du sud', 'gs.png'),
	'gt' => array('Guatemala', 'gt.png'),
	'gu' => array('Guam', 'gu.png'),
	'gw' => array('Guinée-Bissau', 'gw.png'),
	'gy' => array('Guyana', 'gy.png'),
	'hk' => array('Hong Kong', 'hk.png'),
	'hm' => array('Heard et McDonald (îles)', 'hm.png'),
	'hn' => array('Honduras', 'hn.png'),
	'hr' => array('Croatie', 'hr.png'),
	'ht' => array('Haiti', 'ht.png'),
	'hu' => array('Hongrie', 'hu.png'),
	'id' => array('Indonésie', 'id.png'),
	'ie' => array('Irlande', 'ie.png'),
	'il' => array('Israël', 'il.png'),
	'im' => array('Ile de Man', 'im.png'),
	'in' => array('Inde', 'in.png'),
	'io' => array('Ter. Brit. Océan Indien', 'io.png'),
	'iq' => array('Iraq', 'iq.png'),
	'ir' => array('Iran', 'ir.png'),
	'is' => array('Islande', 'is.png'),
	'it' => array('Italie', 'it.png'),
	'je' => array('Jersey', 'je.png'),
	'jm' => array('Jamaïque', 'jm.png'),
	'jo' => array('Jordanie', 'jo.png'),
	'jp' => array('Japon', 'jp.png'),
	'ke' => array('Kenya', 'ke.png'),
	'kg' => array('Kirghizistan', 'kg.png'),
	'kh' => array('Cambodge', 'kh.png'),
	'ki' => array('Kiribati', 'ki.png'),
	'km' => array('Comores', 'km.png'),
	'kn' => array('Saint Kitts et Nevis', 'kn.png'),
	'kp' => array('Corée du nord', 'kp.png'),
	'kr' => array('Corée du sud', 'kr.png'),
	'kw' => array('Koweït', 'kw.png'),
	'ky' => array('Caïmanes (îles)', 'ky.png'),
	'kz' => array('Kazakhstan', 'kz.png'),
	'la' => array('Laos', 'la.png'),
	'lb' => array('Liban', 'lb.png'),
	'lc' => array('Sainte Lucie', 'lc.png'),
	'li' => array('Liechtenstein', 'li.png'),
	'lk' => array('Sri Lanka', 'lk.png'),
	'lr' => array('Liberia', 'lr.png'),
	'ls' => array('Lesotho', 'ls.png'),
	'lt' => array('Lituanie', 'lt.png'),
	'lu' => array('Luxembourg', 'lu.png'),
	'lv' => array('Lettonie', 'lv.png'),
	'ly' => array('Libye', 'ly.png'),
	'ma' => array('Maroc', 'ma.png'),
	'mc' => array('Monaco', 'mc.png'),
	'md' => array('Moldavie', 'md.png'),
	'mg' => array('Madagascar', 'mg.png'),
	'mh' => array('Marshall (îles)', 'mh.png'),
	'mk' => array('Macédoine', 'mk.png'),
	'ml' => array('Mali', 'ml.png'),
	'mm' => array('Myanmar', 'mm.png'),
	'mn' => array('Mongolie', 'mn.png'),
	'mo' => array('Macao', 'mo.png'),
	'mp' => array('Mariannes du nord (îles)', 'mp.png'),
	'mq' => array('Martinique', 'mq.png'),
	'mr' => array('Mauritanie', 'mr.png'),
	'ms' => array('Montserrat', 'ms.png'),
	'mt' => array('Malte', 'mt.png'),
	'mu' => array('Maurice (île)', 'mu.png'),
	'mv' => array('Maldives', 'mv.png'),
	'mw' => array('Malawi', 'mw.png'),
	'mx' => array('Mexique', 'mx.png'),
	'my' => array('Malaisie', 'my.png'),
	'mz' => array('Mozambique', 'mz.png'),
	'na' => array('Namibie', 'na.png'),
	'nc' => array('Nouvelle Calédonie', 'nc.png'),
	'ne' => array('Niger', 'ne.png'),
	'nf' => array('Norfolk (île)', 'nf.png'),
	'ng' => array('Nigéria', 'ng.png'),
	'ni' => array('Nicaragua', 'ni.png'),
	'nl' => array('Pays Bas', 'nl.png'),
	'no' => array('Norvège', 'no.png'),
	'np' => array('Népal', 'np.png'),
	'nr' => array('Nauru', 'nr.png'),
	'nu' => array('Niue', 'nu.png'),
	'nz' => array('Nouvelle Zélande', 'nz.png'),
	'om' => array('Oman', 'om.png'),
	'pa' => array('Panama', 'pa.png'),
	'pe' => array('Pérou', 'pe.png'),
	'pf' => array('Polynésie Française', 'pf.png'),
	'pg' => array('Papouasie Nvelle Guinée', 'pg.png'),
	'ph' => array('Philippines', 'ph.png'),
	'pk' => array('Pakistan', 'pk.png'),
	'pl' => array('Pologne', 'pl.png'),
	'pm' => array('St. Pierre et Miquelon', 'pm.png'),
	'pn' => array('Pitcairn (île)', 'pn.png'),
	'pr' => array('Porto Rico', 'pr.png'),
	'ps' => array('Palestine', 'ps.png'),
	'pt' => array('Portugal', 'pt.png'),
	'pw' => array('Palau', 'pw.png'),
	'py' => array('Paraguay', 'py.png'),
	'qa' => array('Qatar', 'qa.png'),
	're' => array('Réunion (île de la)', 're.png'),
	'ro' => array('Roumanie', 'ro.png'),
	'ru' => array('Russie', 'ru.png'),
	'rs' => array('Russie', 'rs.png'),
	'rw' => array('Rwanda', 'rw.png'),
	'sa' => array('Arabie Saoudite', 'sa.png'),
	'sb' => array('Salomon (îles)', 'sb.png'),
	'sc' => array('Seychelles', 'sc.png'),
	'sd' => array('Soudan', 'sd.png'),
	'se' => array('Suède', 'se.png'),
	'sg' => array('Singapour', 'sg.png'),
	'sh' => array('St. Hélène', 'sh.png'),
	'si' => array('Slovénie', 'si.png'),
	'sj' => array('Svalbard/Jan Mayen (îles)', 'sj.png'),
	'sk' => array('Slovaquie', 'sk.png'),
	'sl' => array('Sierra Leone', 'sl.png'),
	'sm' => array('Saint-Marin', 'sm.png'),
	'sn' => array('Sénégal', 'sn.png'),
	'so' => array('Somalie', 'so.png'),
	'sr' => array('Suriname', 'sr.png'),
	'st' => array('Sao Tome et Principe', 'st.png'),
	'su' => array('Ex U.R.S.S.', 'su.png'),
	'sv' => array('Salvador', 'sv.png'),
	'sy' => array('Syrie', 'sy.png'),
	'sz' => array('Swaziland', 'sz.png'),
	'tc' => array('Turks et Caïques (îles)', 'tc.png'),
	'td' => array('Tchad', 'td.png'),
	'tf' => array('Territoires Fr du sud', 'tf.png'),
	'tg' => array('Togo', 'tg.png'),
	'th' => array('Thailande', 'th.png'),
	'tj' => array('Tadjikistan', 'tj.png'),
	'tk' => array('Tokelau', 'tk.png'),
	'tm' => array('Turkménistan', 'tm.png'),
	'tn' => array('Tunisie', 'tn.png'),
	'to' => array('Tonga', 'to.png'),
	'tp' => array('Timor Oriental', 'tp.png'),
	'tr' => array('Turquie', 'tr.png'),
	'tt' => array('Trinité et Tobago', 'tt.png'),
	'tv' => array('Tuvalu', 'tv.png'),
	'tw' => array('Taiwan', 'tw.png'),
	'tz' => array('Tanzanie', 'tz.png'),
	'ua' => array('Ukraine', 'ua.png'),
	'ug' => array('Ouganda', 'ug.png'),
	'uk' => array('Royaume Uni', 'uk.png'),
	'gb' => array('Grande Bretagne', 'gb.png'),
	'um' => array('US Minor Outlying (îles)', 'um.png'),
	'us' => array('États-Unis', 'us.png'),
	'uy' => array('Uruguay', 'uy.png'),
	'uz' => array('Ouzbékistan', 'uz.png'),
	'va' => array('Vatican', 'va.png'),
	'vc' => array('St Vincent et les Grenadines', 'vc.png'),
	've' => array('Venezuela', 've.png'),
	'vg' => array('Vierges Brit. (îles)', 'vg.png'),
	'vi' => array('Vierges USA (îles)', 'vi.png'),
	'vn' => array('Viêt Nam', 'vn.png'),
	'vu' => array('Vanuatu', 'vu.png'),
	'wf' => array('Wallis et Futuna (îles)', 'wf.png'),
	'ws' => array('Western Samoa', 'ws.png'),
	'ye' => array('Yemen', 'ye.png'),
	'yt' => array('Mayotte', 'yt.png'),
	'yu' => array('Yugoslavie', 'yu.png'),
	'za' => array('Afrique du Sud', 'za.png'),
	'zm' => array('Zambie', 'zm.png'),
	'zr' => array('Rép. Dém. du Congo (ex Zaïre)', 'zr.png'),
	'zw' => array('Zimbabwe', 'zw.png'),
	'tv' => array('Tuvalu', 'tv.png'),
	'ws' => array('Western Samoa', 'ws.png'),
	'other' => array('Autres', '../other.png')
);

?>