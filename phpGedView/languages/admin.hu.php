<?php
/**
 * Hungarian texts
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
 *
 * Modifications Copyright (c) 2010 Greg Roach
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 *
 * @author PGV Developers
 * @author Hrotkó Gábor <roti@al.pmmf.hu>
 * @package PhpGedView
 * @subpackage Languages
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$pgv_lang["user"]					= "Autentikus felhasználó";
$pgv_lang["thumbnail_deleted"]				= "Előnézet állomány sikeresen törölve.";
$pgv_lang["thumbnail_not_deleted"]			= "Előnézet állományt nem lehetett kitörölni.";
$pgv_lang["refresh"]					= "Frissítés";
$pgv_lang["move_file_success"]				= "Média és előnézet állomány sikeresen átrakva.";
$pgv_lang["media_folder_corrupt"]			= "A média mappa hibás.";
$pgv_lang["media_file_not_deleted"]			= "Média állományt nem lehetett kitörölni.";
$pgv_lang["gedcom_deleted"]				= "GEDCOM [#GED#] sikeresen törölve lett.";
$pgv_lang["gedadmin"]					= "GEDCOM Adminisztrátor";
$pgv_lang["full_name"]					= "Teljes név";
$pgv_lang["error_header"] 				= "A <b>#GEDCOM#</b> nevű GEDCOM-állomány a megadott helyen nem érhető el.";
$pgv_lang["confirm_delete_file"]			= "Biztosan törölni kívánja ezt az állományt?";
$pgv_lang["confirm_folder_delete"] 			= "Biztos benne hogy, ezt a mappát ki akarja törölni?";
$pgv_lang["confirm_remove_links"]			= "Biztosan kívánja ennek az elemnek az összes kapcsolatait törölni?";
$pgv_lang["created_remotelinks_fail"] 			= "Nem sikerült a <i>Külső hivatkozások</i> tábla létrehozása.";
$pgv_lang["created_remotelinks"]			= "Sikeresen létrehoztuk a <i>Külső hivatkozások</i> táblát.";
$pgv_lang["files_in_backup"]				= "A mentésben lévő fájlok";
$pgv_lang["keep_media"]					= "Média hivatkozások megtartása";
$pgv_lang["PRIV_PUBLIC"]			= "Bármelyik látogató";
$pgv_lang["PRIV_USER"]				= "Csak regisztrált felhasználók";
$pgv_lang["PRIV_NONE"]				= "Csak az adminisztrátorok";
$pgv_lang["PRIV_HIDE"]				= "Az adminisztrátorok sem";
$pgv_lang["manage_gedcoms"]				= "GEDCOM-kezelés és diszkréciós beállítások";
$pgv_lang["created_indis"]				= "A <i>Személyek</i> táblát sikeresen létrehoztuk.";
$pgv_lang["created_indis_fail"] 			= "A <i>Személyek</i> táblát nem sikerült létrehozni.";
$pgv_lang["created_fams"]				= "A <i>Családok</i> táblát sikeresen létrehoztuk.";
$pgv_lang["created_fams_fail"]  			= "A <i>Családok</i> táblát nem sikerült létrehozni.";
$pgv_lang["created_sources"]				= "A <i>Források</i> táblát sikeresen létrehoztuk.";
$pgv_lang["created_sources_fail"]       		= "A <i>Források</i> táblát nem sikerült létrehozni.";
$pgv_lang["created_other"]				= "Az <i>Egyebek</i> táblát sikeresen létrehoztuk.";
$pgv_lang["created_other_fail"] 			= "Az <i>Egyebek</i> táblát nem sikerült létrehozni.";
$pgv_lang["created_places"]				= "A <i>Helyszínek</i> táblát sikeresen létrehoztuk.";
$pgv_lang["created_places_fail"]        		= "A <i>Helyszínek</i> táblát nem sikerült létrehozni.";
$pgv_lang["created_placelinks"] 			= "A <i>Helyszínek Link</i> táblát sikeresen létrehoztuk.";
$pgv_lang["label_banned_servers"]  			= "Oldal kitiltása Ip cím alapján";
$pgv_lang["label_added_servers"]			= "Hozzáadtuk a távoli szervereket";
$pgv_lang["access"]			= "Hozzáfér";
$pgv_lang["about_user"]			= "Először az Adminisztrátort kell létrehoznia.  Ennek a felhasználónak van jogosultsága a beállítások módosítására, személyes adatok megtekintésére és további felhasználók létrehozására.";
$pgv_lang["add_user"]			= "Új felhasználó";
$pgv_lang["add_new_language"]		= "Új nyelvi állományok és beállítások hozzáadása";
$pgv_lang["admin_gedcoms"]				= "Kattintson ide a GEDCOM-ok adminisztrálásához";
$pgv_lang["upload_replacement"]				= "Feltöltési csere";
$pgv_lang["progress_bars_info"]				= "A lenti állapotjelzők mutatják, hogy az importálási folyamat hogy halad. Ha az időkorlát letelt, az importálás megáll, és <b>Folytatás</b> gombra kattintva lehet folytatni. Ha nem látja a <b>Folytatás</b> gombot, újra kell kezdenie az importálási folyamatot kisebb időkorláttal.";
$pgv_lang["admin_verification_waiting"] 		= "Felhasználók várnak az adminisztrátor aktiválására";
$pgv_lang["ALLOW_USER_THEMES"]				= "Engedjük a felhasználóknak hogy témát váltsanak";
$pgv_lang["ALLOW_CHANGE_GEDCOM"]			= "GEDCOM váltás engedélyezése";
$pgv_lang["back_useradmin"]				= "Vissza a felhasználók adminisztrálásához";
$pgv_lang["admin_user_warnings"]			= "Egy vagy több felhasználónak figyelmeztetései vannak";
$pgv_lang["can_edit"]					= "Hozzáférési szint";
$pgv_lang["can_admin"]					= "A felhasználó adminisztrálhat";
$pgv_lang["created_placelinks_fail"]			= "A <i>Helyszínek Link</i> táblát nem sikerült létrehozni.";
$pgv_lang["click_here_to_continue"]			= "Kattintson ide a folytatáshoz.";
$pgv_lang["cleanup_users"]				= "Felhasználók tisztítása";
$pgv_lang["created_media_fail"]				= "A <i>Média</i> táblát nem sikerült létrehozni.";
$pgv_lang["config_still_writable"]			= "A <i>config.php</b> fájl írható. Biztonsági okokból, érdemes visszaállítani csak olvashatóra, miután befejezte a konfigurálást.";
$pgv_lang["confirm_gedcom_delete"]	= "Biztosan törölni szeretné ezt a GEDCOM-állományt?";
$pgv_lang["configure"]			= "PhpGedView konfiguráció";
$pgv_lang["configure_head"]             = "PhpGedView Konfiguráció";
$pgv_lang["daily"]			= "Napi";
$pgv_lang["date_registered"]    	= "Regisztráció dátuma";
$pgv_lang["del_unveru"]					= "A felhasználó nem jelzett vissza 7 napja.";
$pgv_lang["download_here"]		= "A letöltéshez kattintson ide.";
$pgv_lang["del_unvera"]					= "A felhasználót nem ellenőrizte az adminisztrátor.";
$pgv_lang["del_proceed"]				= "Folytatás";
$pgv_lang["del_gedrights"]				= "A GEDCOM már nem aktív, felhasználói hivatkozások eltávolítása";
$pgv_lang["default_user"]		= "Hozza létre az Adminisztrátort!";
$pgv_lang["enable_disable_lang"]			= "Támogatott nyelvek beállítása";
$pgv_lang["error_delete_person"]   			= "Ki kell választani a személyt, akinek a távoli hivatkozásait ki szeretné törölni.";
$pgv_lang["error_view_info"]       			= "Válassza ki azt a személyt, akinek az adatait meg kívánja tekinteni.";
$pgv_lang["error_url_blank"]				= "Kérem töltse ki a távoli oldal címét, vagy URL-jét";
$pgv_lang["error_siteauth_failed"]			= "A távoli oldallal való azonosítás nem sikerült";
$pgv_lang["ged_download"]				= "Letöltés";
$pgv_lang["gedcom_config_write_error"]			= "H I B A !!!<br />Nem írható a következő fájl: <i>#GLOBALS[whichFile]#</i>. Kérem ellenőrizze a megfelelő írási jogokat.";
$pgv_lang["gedcom_adm_head"]				= "GEDCOM adminisztráció";
$pgv_lang["gedcom_not_imported"]	= "Ezt a GEDCOM-állományt még nem importálta.";
$pgv_lang["ged_check"] 					= "Ellenőrzés";
$pgv_lang["DEFAULT_GEDCOM"] 		= "Alap GEDCOM";
$pgv_lang["current_users"]		= "Felhasználó lista";
$pgv_lang["config_help"]				= "Konfigurációs segítség";
$pgv_lang["created_media_mapping_fail"]			= "A <i>Média címezés</i> táblát nem sikerült létrehozni.";
$pgv_lang["no_thumb_dir"]				= " az előnézet mappa nem létezik és nem lehetett létrehozni.";
$pgv_lang["folder_created"]				= "Könyvtár létrehozása";
$pgv_lang["INDEX_DIRECTORY"]				= "Index fájl könyvtár";
$pgv_lang["label_new_server"]     			= "Új oldal hozzáadása";
$pgv_lang["label_manual_search_engines"]		= "Keresőmotor megjelölése IP cím alapján";
$pgv_lang["label_server_info"]     			= "Az összes személy akire külső oldalak hivatkoznak ezen az oldalon keresztül:";
$pgv_lang["label_server_url"]       			= "Oldal URL-je/IP címe";
$pgv_lang["BOM_detected"] 				= "A fájl elején a (BOM) karaktert detektáltam. A tisztítás során, ezek törlésre kerülnek.";
$pgv_lang["folder_no_create"]				= "A mappát nem sikerült létrehozni";
$pgv_lang["security_no_create"]				= "Biztonsági Figyelmeztetés: Az állomány <b><i>index.php</i></b>, nem létezik itt";
$pgv_lang["security_not_exist"]				= "Biztonsági Figyelmeztetés: Nem lehetett az <b><i>index.php</i></b> állományt létrehozni itt ";
$pgv_lang["label_delete"]           			= "Töröl";
$pgv_lang["add_gedcom"]					= "Új GEDCOM-állomány hozzáadása";
$pgv_lang["add_new_gedcom"]				= "Új GEDCOM-állomány létrehozása";
$pgv_lang["admin_gedcom"]              			 = "GEDCOM-adminisztráció";
$pgv_lang["admin_geds"]					= "Adat és GEDCOM adminisztráció";
$pgv_lang["admin_info"]					= "Információs";
$pgv_lang["admin_site"]					= "Site adminisztráció";
$pgv_lang["administration"]				= "Adminisztráció";
$pgv_lang["ansi_encoding_detected"]			= "ANSI kódolást találtunk. A program akkor működik a legmegfelelőbben, ha az állományok kódolása UTF-8.";
$pgv_lang["ansi_to_utf8"]				= "Át szeretné konvertálni ezt a GEDCOM-ot ANSI-ból UTF-8-ba?";
$pgv_lang["apply_privacy"]				= "Alkalmazás a bizalmas beállításoknak?";
$pgv_lang["bytes_read"]					= "Byte beolvasva:";
$pgv_lang["change_id"]					= "A személyes ID megváltoztatása erre:";
$pgv_lang["choose_priv"]				= "Válasszon bizalmassági fokot:";
$pgv_lang["cleanup_places"]            			= "Helyszínek tisztítása";
$pgv_lang["click_here_to_go_to_pedigree_tree"] 		= "Kattintson ide, hogy megtekintse az ősfát.";
$pgv_lang["comment"]					= "Adminisztrátor megjegyzése a felhasználóról";
$pgv_lang["comment_exp"]				= "Adminisztrátor figyelmeztetés ezen a dátumon";
$pgv_lang["configuration"]				= "Beállítások";
$pgv_lang["confirm_user_delete"]			= "Valóban törölni kívánja ezt a felhasználót?";
$pgv_lang["create_user"]				= "Felhasználó létrehozása";
$pgv_lang["dataset_exists"]				= "Ezzel a névvel már van adatbázisba importált GEDCOM.";
$pgv_lang["day_before_month"]				= "Nap a hónap előtt (NN HH ÉÉÉÉ)";
$pgv_lang["do_not_change"]				= "Ne legyen változás";
$pgv_lang["download_gedcom"]				= "GEDCOM-állomány letöltése";
$pgv_lang["download_note"]             			 = "Megjegyzés: A nagyméretű GEDCOM-állományok letöltés előtti feldolgozása hosszú időt vehet igénybe. Ha PHP előre definiált futási ideje letelik a letöltés befejezése előtt, akkor Ön egy nem teljes állományt kaphat.<br/><br/>A helyes letöltést ellenőrizheti az állomány végén található értékkel: <b>0&nbsp;TRLR</b>. GEDCOM állományok sima írott szöveggel vannak írva így bármilyen szövegolvasó szoftverrel kitudja nyitni, de biztos legyen benne hogy <u>ne</u> spórolja meg a GEDCOM állományt az ellenőrzés után.<br/><br/>Általánosságban a letöltés kb. annyi ideig tart, mint az adott GEDCOM-állomány importálása.";
$pgv_lang["editaccount"]				= "A felhasználó szerkesztheti a saját felhasználói adatait";
$pgv_lang["empty_dataset"]				= "Ki szeretné törölni a régi adatokat és kicserélni ezzel az új adatokkal?";
$pgv_lang["empty_lines_detected"]       		= "Üres sorokat találtam a GEDCOM fájlban. Tisztításkor ezeket törlöm.";
$pgv_lang["error_ban_server"]       			= "Érvénytelen IP cím.";
$pgv_lang["error_header_write"] 			= "A <b>#GEDCOM#</b> nevű GEDCOM-állomány nem írható. Kérjük, ellenőrizze a tulajdonságait és jogosultságait.";
$pgv_lang["example_date"]				= "Hibás dátum a GEDCOM-állományból:";
$pgv_lang["example_place"]				= "Hibás helyszín a GEDCOM-állományból:";
$pgv_lang["found_record"]				= "Rekordot találtunk";
$pgv_lang["ged_import"]					= "Importálás";
$pgv_lang["gedcom_downloadable"]       			= "Ez a GEDCOM-állomány letölthető az interneten kereszetül.<br/>Kérjük, tekintse át a <a href=\"readme.txt\"><b>readme.txt</b></a> SECURITY (BIZTONSÁG) fejezetét a probléma megszüntetéséhez.";
$pgv_lang["gedcom_file"]				= "GEDCOM-állomány:";
$pgv_lang["img_admin_settings"]				= "A képszerkesztés beállításai";
$pgv_lang["import_complete"]				= "Az importálás elkészült";
$pgv_lang["import_options"]				= "Import Lehetőségek";
$pgv_lang["import_progress"]    			= "Az importálás folyamatban...";
$pgv_lang["import_statistics"]				= "Statisztika Importálás";
$pgv_lang["import_time_exceeded"]			= "A maximum futtatási idő lejárt. Kattintson a Folytatás gombra hogy, folytassa a GEDCOM állomány importálását.";
$pgv_lang["inc_languages"]				= "Nyelvi állományok";
$pgv_lang["invalid_dates"]				= "Hibás dátumformátumokat találtam, a tisztítás után ezek a következő formátumra cseréljük: Év Hónap Nap (pl. 2004. január 1.).";
$pgv_lang["invalid_header"]             		= "A GEDCOM-állomány fejléce <b>(0&nbsp;HEAD)</b> előtti sorokat találtunk.  Ezeket a tisztítás során el fogjuk távolítani.";
$pgv_lang["label_families"]         			= "Családok";
$pgv_lang["label_gedcom_id2"]       			= "Adatbázis azonosítószáma:";
$pgv_lang["label_individuals"]      			= "Személyek";
$pgv_lang["label_password_id"]				= "Jelszó";
$pgv_lang["label_families"]         			= "Családok";
$pgv_lang["label_gedcom_id2"]      		 	= "Adatbázis azonosítószáma:";
$pgv_lang["label_individuals"]      			= "Személyek";
$pgv_lang["label_password_id"]				= "Jelszó";
$pgv_lang["link_manage_servers"]   			= "Oldalak karbantartása";
$pgv_lang["leave_blank"]				= "Hagyja üresen a jelszó mezőt, ha meg szeretné tartani a régi jelszót";
$pgv_lang["mailto"]					= "Levélküldési hivatkozás";
$pgv_lang["messaging3"]					= "A PhpGedView az email-eket tárolás nélkül küldi el";
$pgv_lang["no_messaging"]		= "Nincs kapcsolattartás";
$pgv_lang["page_views"]					= "&nbsp;&nbsp;oldalmegtekintés ennyi másodperc alatt&nbsp;&nbsp;";
$pgv_lang["PGV_MEMORY_LIMIT"]				= "Memóriahatár";
$pgv_lang["PGV_STORE_MESSAGES"]		= "Az üzenetek online tárolhatók";
$pgv_lang["PGV_SIMPLE_MAIL"] 		= "Egyszerű levélfejléc a külső e-mailekben";
$pgv_lang["PGV_SESSION_TIME"] 		= "Munkamenet lejárata";
$pgv_lang["privileges"]					= "Jogosultságok";
$pgv_lang["PGV_SESSION_SAVE_PATH"] 		= "Munkamenet állományainak elérési útja";
$pgv_lang["review_readme"]				= "Ajánlott elolvasni a <a href=\"readme.txt\" target=\"_blank\">readme.txt</a> fájlt, mielőtt folytatja a PhpGedView beállítását.<br /><br />";
$pgv_lang["seconds"]					= "&nbsp;&nbsp;másodperc";
$pgv_lang["show_phpinfo"]				= "PHP információs oldal mutatása";
$pgv_lang["REQUIRE_ADMIN_AUTH_REGISTRATION"] 		= "Az adminisztrátornak el kell fogadnia az új regisztrációkat";
$pgv_lang["themecustomization"]				= "Téma testreszabása";
$pgv_lang["translator_tools"]				= "Fordító eszközei";
$pgv_lang["title_view_conns"]       			= "Kapcsolatok megtekintése";
$pgv_lang["title_manage_servers"]   			= "Oldalak kezelése";
$pgv_lang["USE_REGISTRATION_MODULE"]			= "A látogatók kérhetnek regisztrációt";
$pgv_lang["TBLPREFIX"]					= "Adatbázis tábla előtag";
$pgv_lang["user_time"]					= "Jelenlegi felhasználói idő:";
$pgv_lang["system_time"]				= "Jelenlegi szerver idő:";
$pgv_lang["usr_unset_rootid"]				= "Gyökér azonosító feloldása a következőnél";
$pgv_lang["usr_unset_rights"]				= "Gedcom jog feloldása a következőnél";
$pgv_lang["usr_unset_gedcomid"]				= "GEDCOM azonosító feloldása";
$pgv_lang["usr_no_cleanup"]				= "Nincs mit tisztítani";
$pgv_lang["usr_idle_toolong"]				= "A felhasználó túl sokáig volt inaktív:";
$pgv_lang["usr_idle"]					= "Ha ennyi hónapig nem jelentkezik be egy felhasználó, inaktívnak tekintjük:";
$pgv_lang["usr_deleted"]				= "Törölt felhasználó";
$pgv_lang["users_unver_admin"]				= "Adminisztrátor által nem ellenőrzött";
$pgv_lang["users_unver"]				= "Felhasználó által nem ellenőrzött";
$pgv_lang["users_total"]			= "Felhasználók száma";
$pgv_lang["users_gedadmin"]				= "GEDCOM adminisztrátorok";
$pgv_lang["weekly"]					= "Heti";
$pgv_lang["all_rec"]      				= "Összes bejegyzés";
$pgv_lang["tradition_paternal"]			= "Apai";
$pgv_lang["tradition_icelandic"]		= "Izlandi";
$pgv_lang["tradition_portuguese"]		= "Portugál";
$pgv_lang["tradition_spanish"]   		= "Spanyol";
$pgv_lang["tradition_none"]				= "Semmi";
$pgv_lang["SURNAME_TRADITION"] 			= "Vezetéknév hagyomány";
$pgv_lang["ADVANCED_PLAC_FACTS"] 			= "Haladó helynév tények";
$pgv_lang["ADVANCED_NAME_FACTS"] 			= "Haladó név tények";
$pgv_lang["spacing"]      				= "térköz";
$pgv_lang["tag"]          				= "címke";
$pgv_lang["noref"]        				= "Nincs hivatkozás erre a bejegyzésre";
$pgv_lang["see"]          				= "lát";
$pgv_lang["data"]         				= "adat";
$pgv_lang["no_link"]      				= "nem hivatkozik vissza";
$pgv_lang["too_few"]      				= "túl kevés";
$pgv_lang["too_many"]    				= "túl sok";
$pgv_lang["invalid"]      				= "érvénytelen";
$pgv_lang["multiple"]     				= "többszörös";
$pgv_lang["missing"]      				= "hiányzó";
$pgv_lang["err_rec"]      				= "Hibás bejegyzések";
$pgv_lang["context_lines"]				= "GEDCOM tartalom sorai";
$pgv_lang["new_win"]      				= "Új fül/ablak";
$pgv_lang["same_win"]     				= "Ugyanazon fül/ablak";
$pgv_lang["open_link"]    				= "Hivatkozások megnyitása itt";
$pgv_lang["info"]         				= "Információ";
$pgv_lang["warning"]      				= "Figyelmeztetés";
$pgv_lang["error"]        				= "Hiba";
$pgv_lang["critical"]     				= "Kritikus";
$pgv_lang["level"]        				= "Szint";                   // Szint";
$pgv_lang["gedcheck_text"]				= "Ez a modul a GEDCOM fájl formátumát ellenőrzi le, hogy megfelel-e a következő specifikációnak: <a href=\"http://phpgedview.sourceforge.net/ged551-5.pdf\">5.5.1 GEDCOM Specification</a>. További szokványos hibát is leellenőriz. Érdemes megjegyezni, hogy mivel sok variációjú verzió, kiterjesztés és variáció létezik, ezért ami nem \"Kritikus\" besorolású, azzal nem biztos hogy foglalkoznia kell. A specifikációban megtalálható a sorról sorra történő magyarázat, ezek tanulmányozása után kérjen csak segítséget.";
$pgv_lang["gedcheck"]     				= "Gedcom ellenőrző";
$pgv_lang["admin_OK_message"]				= "A #SERVER_NAME# PhpGedView oldal adminisztrátora elfogadta a felhasználói jelentkezését. A következő hivatkozásra kattintva jelentkezhet be:\r\n\r\n#SERVER_NAME#\r\n";
$pgv_lang["admin_OK_subject"]				= "Felhasználó elfogadása itt: #SERVER_NAME#";
$pgv_lang["yearly"]					= "Éves";
$pgv_lang["welcome_new"] 		= "Üdvözöljük az Ön új PhpGedView honlapján.";
$pgv_lang["warn_users"]					= "Felhasználók figyelmeztetéssel";
$pgv_lang["users_admin"]				= "Oldal adminisztrátorok";
$pgv_lang["user_relationship_priv"]			= "A hozzáférés korlátozása bizonyos személyekre";
$pgv_lang["user_path_length"]				= "Maximális titkos rokonsági útvonal hossza";
$pgv_lang["remove_ip"] 					= "IP cím eltávolítása";
$pgv_lang["pgv_config_write_error"] 			= "Hiba!!! Nem írható a PhpGedView beállítási fájlja. Kérem ellenőrizze a fájlok és könyvtárak jogosultságait, majd próbálja újra.";
$pgv_lang["no_logs"]					= "Loggolás tiltása";
$pgv_lang["never"]					= "Soha";
$pgv_lang["monthly"]					= "Havi";
$pgv_lang["messaging2"]			= "Belső üzenet email-el megerősítve";
$pgv_lang["messaging"]			= "PhpGedView belső üzenet";
$pgv_lang["message_to_all"]			= "Üzenetküldés minden felhasználónak";
$pgv_lang["lasttab"]					= "\"Utoljára látogatott\" fül egy személyhez";
$pgv_lang["last_login"]					= "Utoljára belépve";
$pgv_lang["LANGUAGE_DEFAULT"]				= "Nem állította be, hogy mely nyelveket használja az oldal.<br />A PhpGedView az alapértelmezettet fogja használni.";
$pgv_lang["LANG_SELECTION"] 				= "Támogatott nyelvek";
$pgv_lang["label_view_remote"]     			= "Távoli információ megtekintése a személyről";
$pgv_lang["label_view_local"]       			= "Helyi információ megtekintése a személyről";
$pgv_lang["label_view_local"]       			= "Helyi információ a személyről";
$pgv_lang["label_username_id"]				= "Felhasználónév";
$pgv_lang["logfile_content"]    			= "A napló-állomány tartalma";
$pgv_lang["macfile_detected"]   			= "Macintosh-állományt találtunk. A tisztítás során ezt DOS-állománnyá fogjuk konvertálni.";
$pgv_lang["merge_records"]              		= "Rekordok összefűzése";
$pgv_lang["month_before_day"]				= "Hónap a nap előtt (HH NN ÉÉÉÉ)";
$pgv_lang["performing_validation"]			= "GEDCOM ellenőrzés kezdődik, válassza ki a kívánt lehetőségeket, majd kattintson a 'Tisztítás' gombra.";
$pgv_lang["phpinfo"]					= "PHP információ";
$pgv_lang["place_cleanup_detected"]     		= "Érvénytelen helyszín-kódolást találtunk, melyeket javítani lenne szükséges. Az észlelt érvénytelen helyszínt a következő minta mutatja be: ";
$pgv_lang["please_be_patient"]				= "KÉRJÜK, LEGYEN TÜRELEMMEL";
$pgv_lang["reading_file"]				= "GEDCOM állomány beolvasása";
$pgv_lang["readme_documentation"]			= "OLVASSEL Dokumentáció";
$pgv_lang["select_an_option"]				= "Válasszon az alábbi lehetőségek közül:";
$pgv_lang["siteadmin"]					= "Site adminisztrátor";
$pgv_lang["time_limit"]					= "Időhatár:";
$pgv_lang["update_myaccount"]				= "A felhasználói adataim frissítése";
$pgv_lang["update_user"]				= "Felhasználói jogosultság frissítése";
$pgv_lang["upload_gedcom"]				= "GEDCOM-állomány feltöltése";
$pgv_lang["user_auto_accept"]				= "Ennek a használónak a változtatásai automatikusan elfogadható";
$pgv_lang["user_contact_method"]			= "Kapcsolattartási mód";
$pgv_lang["user_create_error"]				= "Nem sikerült a felhasználót hozzáadni. Kérjük lépjen vissza, és próbálja meg újra.";
$pgv_lang["user_created"]				= "A felhasználót sikeresen hozzáadtuk.";
$pgv_lang["user_default_tab"]				= "Mutassa ezt az oldalt mint alap oldal a Személyek Információs oldalán";
$pgv_lang["valid_gedcom"]				= "Érvényes GEDCOM-ot észleltem. Nincs szükség tisztításra.";
$pgv_lang["validate_gedcom"]				= "GEDCOM érvényességének ellenőrzése";
$pgv_lang["verified"]					= "A felhasználó megerősítette jelentkezését";
$pgv_lang["verified_by_admin"]				= "A felhasználót elfogadta az adminisztrátor";
$pgv_lang["verify_gedcom"]				= "GEDCOM ellenőrzése";
$pgv_lang["verify_upload_instructions"] 		= "Ha Ön a folytatás mellet dönt, a korábbi GEDCOM-állományt az Ön által feltöltöttre fogjuk lecserélni és az importálási folyamat újrakezdődik. Ha a megszakítást választja, a korábbi GEDCOM-állomány érintetlen marad.";
$pgv_lang["view_logs"]					= "Napló-állományok megtekintése";
$pgv_lang["view_readme"]				= "readme.txt állomány tekintése";
$pgv_lang["visibleonline"]              		= "Bejelentkezés után látható";
$pgv_lang["visitor"]					= "Látogató";
$pgv_lang["clear_cache_succes"]				= "Az átmeneti fájlok törlésre kerültek";
$pgv_lang["clear_cache"]				= "Átmeneti fájlok törlése";
$pgv_lang["sanity_err0"]				= "Hibák:";
$pgv_lang["sanity_err1"]				= "Szükséges PHP verzió: 4.3 vagy nagyobb.";
$pgv_lang["sanity_err2"]				= "A könyvtár vagy fájl <i>#GLOBALS[whichFile]#</i> nem létezik. Ellenőrizze, hogy valóban létezik-e, vagy csak elírta a nevet, és az olvasási jogosultságok megfelelőek-e.";
$pgv_lang["sanity_err3"]				= "A fájl <i>#GLOBALS[whichFile]#</i> hibásan töltődött fel. Kérem töltse fel újra.";
$pgv_lang["sanity_err4"]				= "A következő fájl korrupt: <i>config.php</i>";
$pgv_lang["sanity_err5"]				= "A <i>config.php</i> fájl nem írható.";
$pgv_lang["sanity_err6"]				= "A <i>#GLOBALS[INDEX_DIRECTORY]#</i> könyvtár nem írható.";
$pgv_lang["sanity_warn0"]				= "Figyelmeztetések:";
$pgv_lang["sanity_warn1"]				= "A <i>#GLOBALS[MEDIA_DIRECTORY]#</i> könyvtár nem írható. A média fájlok feltöltése, illetve a kiskép generálás nem fog működni.";
$pgv_lang["sanity_warn2"]				= "A <i>#GLOBALS[MEDIA_DIRECTORY]#thumbs</i> könyvtár nem írható. Nem fog tudni feltölteni, illetve generálni előnézeti képeket.";
$pgv_lang["sanity_warn3"]				= "A GD képfeldolgozó könyvtár nem elérhető. A PhpGedView funkcionálisan működni fog ugyan, de néhány lehetőség úgy mint az előnézeti képek generálása, vagy a kördiagram létrehozása nem fog működni e nélkül. További információért látogasson el ide: <a href=\'http://www.php.net/manual/en/ref.image.php\'>http://www.php.net/manual/en/ref.image.php</a>.";
$pgv_lang["sanity_warn4"]				= "Az XML Parser könyvtár nem elérhető. A PhpGedView továbbra is funkcionál, de néhány funkció úgy mint különböző riportok generálása nem fog működni e nélkül. További információért látogasson el ide: <a href='http://www.php.net/manual/en/ref.xml.php'>http://www.php.net/manual/en/ref.xml.php</a>.";
$pgv_lang["query"]					= "Lekérdezés";
$pgv_lang["searchtype"]					= "Keresés típusa";
$pgv_lang["log_message"]				= "Log üzenet";
$pgv_lang["date_time"]					= "Dátum és idő";
$pgv_lang["ip_address"]					= "IP cím";
$pgv_lang["sanity_warn6"]				= "A Calendar könyvtár nem elérhető. A PhpGedView továbbra is funkcionál, de néhány funkció úgy mint más naptárformátumokba konvertálás mint például Héber vagy Francia nem fog működni e nélkül. További információért látogasson el ide: <a href=\'http://www.php.net/manual/en/ref.calendar.php\'>http://www.php.net/manual/en/ref.calendar.php</a>.";
$pgv_lang["sanity_warn5"]				= "A DOM XML könyvtár nem elérhető. A PhpGedView továbbra is funkcionál, de néhány funkció úgy mint Gramps Export a metszési kosárban, letöltés, és web szerviz nem fog működni e nélkül. További információért látogasson el ide: <a href=\'http://www.php.net/manual/en/ref.domxml.php\'>http://www.php.net/manual/en/ref.domxml.php</a>.";
$pgv_lang["mysql"]								= "MySQL";
$pgv_lang["tradition_polish"]		= "Lengyel";
$pgv_lang["elapsedMinute1"]	=	"1 perc";
$pgv_lang["elapsedHour1"]	=	"1 óra";
$pgv_lang["elapsedDay1"]	=	"1 nap";
$pgv_lang["elapsedMonth1"]	=	"1 hónap";
$pgv_lang["elapsedYear1"]	=	"1 év";
$pgv_lang["SERVER_URL"]							= "A PhpGedView URL-je";
$pgv_lang["elapsedYear2"]	=	"#pgv_lang[global_num1]# év";	// used in Polish for 2,3,4 or 22,23,24 or 32,33,34 etc.
$pgv_lang["elapsedYears"]	=	"#pgv_lang[global_num1]# év";
$pgv_lang["elapsedMonth2"]	=	"#pgv_lang[global_num1]# hónap";	// used in Polish for 2,3,4 or 22,23,24 or 32,33,34 etc.
$pgv_lang["elapsedMonths"]	=	"#pgv_lang[global_num1]# hónap";
$pgv_lang["elapsedDay2"]	=	"#pgv_lang[global_num1]# nap";		// used in Polish for 2,3,4 or 22,23,24 or 32,33,34 etc.
$pgv_lang["elapsedDays"]	=	"#pgv_lang[global_num1]# nap";
$pgv_lang["elapsedHour2"]	=	"#pgv_lang[global_num1]# óra";	// used in Polish for 2,3,4 or 22,23,24 or 32,33,34 etc.
$pgv_lang["elapsedHours"]	=	"#pgv_lang[global_num1]# óra";
$pgv_lang["elapsedMinute2"]	=	"#pgv_lang[global_num1]# perc";	// used in Polish for 2,3,4 or 22,23,24 or 32,33,34 etc.
$pgv_lang["elapsedMinutes"]	=	"#pgv_lang[global_num1]# perc";
$pgv_lang["mod_admin_installed"]	= "Telepített modulok";
$pgv_lang["module_admin"]			= "Modul Adminisztráció";
$pgv_lang["mod_admin_menus"]		= "Menük kezelése";
$pgv_lang["mod_admin_name"]			= "Modul neve";
$pgv_lang["mod_admin_description"]	= "Leírás";
$pgv_lang["mod_admin_version"]		= "Változat / PGV";
$pgv_lang["mod_admin_hasmenu"]		= "Menü?";
$pgv_lang["mod_admin_access_level"]	= "Hozzáférési szint";
$pgv_lang["mod_admin_config"]		= "Mod Beállítások";
$pgv_lang["mod_admin_settings"]		= "Modul konfigurációs beállítások";
$pgv_lang["ret_module_admin"]		= "Vissza a modul felügyelet lapra";
$pgv_lang["ret_admin"]				= "Vissza a Felügyelet lapra";
$pgv_lang["start_entering"] 		= "Kezdje az adatbevitelt";
$pgv_lang["install_step_1"] = "Környezeti ellenörzés";
$pgv_lang["install_step_2"] = "Adatbázis-kapcsolat";
$pgv_lang["install_step_6"] = "A beállítások mentése";
$pgv_lang["basic_site_config"] = "Alap beállítások";
$pgv_lang["adv_site_config"] = "Speciális beállítások";
$pgv_lang["download_config"] = "Letöltés config.php";
$pgv_lang["site_unavailable"] = "A honlap jelenleg nem érhető el";
$pgv_lang["install_step_4"] = "Hónlap beállítása";
$pgv_lang["db_tables_created"] = "Adatbázis táblák sikeresen létrejöttek";
$pgv_lang["using_php_version"]		= "Használt PHP verzió";
$pgv_lang["checking_php_version"]		= "Szükséges PHP verzió ellenőrzése:";
$pgv_lang["config_saved"] = "Beállitások mentése sikeresen megtörtént";
$pgv_lang["checking_errors"]		= "Hibák ellenõrzése...";
$pgv_lang["checking_db_support"]		= "Minimális adatbázis-támogatás ellenörzése:";
$pgv_lang["config.php_missing"]		= "config.php fájl nem található.";
$pgv_lang["checking_config.php"]		= "config.php ellenörzése:";
$pgv_lang["install_step_5"] = "Nyelvek";
$pgv_lang["config.php_writable"]		= "config.php jelen van, és írható.";
$pgv_lang["PGV_SMTP_AUTH_PASS"]					= "Jelszó";
$pgv_lang["PGV_SMTP_FROM_NAME"] 				= "Küldő neve";
$pgv_lang["PGV_SMTP_SSL"]						= "Biztonságos kapcsolat";
$pgv_lang["PGV_SMTP_AUTH_USER"]					= "Felhasználói név";
$pgv_lang["PGV_SMTP_AUTH"]						= "Felhasználónév és jelszó";
$pgv_lang["autoContinue"]						= "Automatikusan nyomja a <<Tovább>> gombót";
$pgv_lang["cannot_change_memlimit"]		= "Nem sikerült megváltoztatni a memória korlátozást.";
$pgv_lang["associated_files"]		= "Kapcsolódó fájlok:";
$pgv_lang["deleted_files"]          = "Törölt fájlok:";
$pgv_lang["gedcom_admins"]			= "GEDCOM rendszergazdák";
$pgv_lang["site_admins"]			= "Hónlap rendszergazdák";
$pgv_lang["enter_name"]				= "Adja meg a nevét";
$pgv_lang["PGV_SMTP_HELO"]						= "Küldő domain név";
$pgv_lang["gedcheck_nothing"] = "Nincs hiba találat ezen a szinten.";
$pgv_lang["config.php_not_writable"]		= "config.php nem írható.";
$pgv_lang["upload_a_gedcom"] 		= "GEDCOM fájl feltöltése";
$pgv_lang["mod_admin_active"]		= "";
?>
