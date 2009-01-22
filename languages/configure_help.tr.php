<?php
/**
 * Turkish Language file for PhpGedView.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
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
 * @package PhpGedView
 * @author Kurt Norgaz
 * @author Adem GENÇ uzayuydu@gmail.com http://www.muttafi.com
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$pgv_lang["SHOW_SOURCES"]		= "Kaynakları göster";

//-- CONFIGURE FILE MESSAGES
$pgv_lang["gedcom_conf"]		= "Genel GEDCOM ayarları";
$pgv_lang["media_conf"]			= "Multimedya";
$pgv_lang["media_general_conf"]	= "Genel";
$pgv_lang["media_firewall_conf"]	= "Medya Güvenlik Duvarı (Firewall)";
$pgv_lang["accpriv_conf"]		= "Erişim ve mahremiyet";
$pgv_lang["displ_conf"]			= "Gösterim ve Plan Düzenlemeleri";
$pgv_lang["displ_names_conf"]		= "İsimler";
$pgv_lang["displ_comsurn_conf"]		= "Yaygın soy isimleri";
$pgv_lang["displ_layout_conf"]		= "Yerleşim";
$pgv_lang["displ_hide_conf"]		= "Sakla & Göster";
$pgv_lang["editopt_conf"]		= "Düzenleme Seçenekleri";
$pgv_lang["useropt_conf"]		= "Üye seçenekleri";
$pgv_lang["contact_conf"]		= "İlişki ayarları";
$pgv_lang["meta_conf"]			= "META TAG ayarlama seçenekleri";
$pgv_lang["gedconf_head"]		= "GEDCOM ayarları";
$pgv_lang["other_theme"]		= "Başka - Lütfen işleyin";
$pgv_lang["performing_update"]		= "Güncelleme yapılmaktadır.";
$pgv_lang["config_file_read"]		= "Yapılandırma dosyası okundu.";
$pgv_lang["does_not_exist"]		= "mevcut değil";
$pgv_lang["media_drive_letter"]		= "Medya yolunun içinde sürücü harfi bulunmamalıdır. Aksi halde medya belki gösterilemez.";
$pgv_lang["db_setup_bad"]		= "Güncel veritabanı konfigürasyonunuz hatalıdır. Lütfen veritabanı parametreleriniz kontrol edip tekrar deneyin.";
$pgv_lang["db"]				= "Veritabanı";

$pgv_lang["current_gedcoms"]		= "Geçerli GEDCOM veritabanları";
$pgv_lang["ged_gedcom"]			= "GEDCOM dosyası";
$pgv_lang["ged_title"]			= "GEDCOM veritabanın başlığı";
$pgv_lang["ged_config"]			= "Yapılandırma dosyası";
$pgv_lang["ged_search"]			= "Arama-Günlük dosyalar";
$pgv_lang["ged_privacy"]		= "Mahremiyet dosyası";
$pgv_lang["disabled"]			= "Kullanım dışı";
$pgv_lang["mouseover"]			= "Fare üzerindeyken (mouse over)";
$pgv_lang["mousedown"]			= "Fare tıklandıktan sonra (mouse down)";
$pgv_lang["click"]			= "Fare tıklamasında (mouse click)";
$pgv_lang["enter_db_pass"]		= "#pgv_lang[DBUSER]# ve #pgv_lang[DBPASS]# her konfigürasyon değerleri değiştirdiğinizde girilmesi gerekiyor.";
$pgv_lang["server_url_note"]		= "Buraya PhpGedView yazılımının yerleştiridiği dizinin URL adresi yazılmalıdır. Bu veriyi ancak ne yaptığınızı gerçekten biliyorsanız değiştirin.<br />PhpGedView sunucunun URL adresini yandaki şekilde belirlemiştir: #GUESS_URL#";
$pgv_lang["DBTYPE"]			= "Varitabanı türü";
$pgv_lang["DBHOST"]			= "Veritabanı Hostu";
$pgv_lang["DBUSER"]			= "Veritabanı Kullanıcı adı";
$pgv_lang["ged_change"]			= "DeğişenLog dosyalar";

$pgv_lang["DBPASS"]			= "MySQL Veritabanı şifresi";
$pgv_lang["DBNAME"]			= "Veritabanın Adı";
$pgv_lang["upload_path"]			= "Yükleme yolu";
$pgv_lang["gedcom_path"]			= "GEDCOM yolu ve ismi";
$pgv_lang["CHARACTER_SET"]		= "Harflerin kodlanma sistemi";
$pgv_lang["LANGUAGE"]			= "Dil";
$pgv_lang["ENABLE_MULTI_LANGUAGE"]	= "Üye dili değiştirmesine izin ver";
$pgv_lang["CALENDAR_FORMAT"]		= "Takvim türü";
$pgv_lang["DISPLAY_JEWISH_THOUSANDS"]	= "İbranice binliklerini göster";
$pgv_lang["DISPLAY_JEWISH_GERESHAYIM"]		= "İbranicre \"Gershayim\" göster";
$pgv_lang["JEWISH_ASHKENAZ_PRONUNCIATION"]	= "Musevilere ait ashkenaz telaffuz";
$pgv_lang["USE_RTL_FUNCTIONS"]			= "Sağdan sola (RTL) işlemini kullan";
$pgv_lang["DEFAULT_PEDIGREE_GENERATIONS"]	= "Soyağacı Çizelgesinde varsayılan nesil sayısı";
$pgv_lang["DEFAULT_PEDIGREE_GENERATIONS_help"]	= "~#pgv_lang[DEFAULT_PEDIGREE_GENERATIONS]#~<br /><br />Seçere Çizelgesinde göstermek için nesillerin varsayılan sayıyı girin.<br />";
$pgv_lang["MAX_PEDIGREE_GENERATIONS"]		= "Soyağacı Çizelgesinde maksimum nesil sayısı";
$pgv_lang["MAX_PEDIGREE_GENERATIONS_help"]	= "~#pgv_lang[MAX_PEDIGREE_GENERATIONS]#~<br /><br />Seçere Çizelgesinde göstermek için nesillerin en fazla sayıyı girin.<br />";
$pgv_lang["MAX_DESCENDANCY_GENERATIONS"]	= "Şahsı izleyen nesiller Çizelgesinde maksimum nesil sayısı";
$pgv_lang["MAX_DESCENDANCY_GENERATIONS_help"]	= "~#pgv_lang[MAX_DESCENDANCY_GENERATIONS]#~<br /><br />Şahsı izleyen nesiller Çizelgesinde göstermek için nesillerin en fazla sayıyı girin.<br />";
$pgv_lang["USE_RIN"]			= "GEDCOM - Kişisel numaralarının yerine RIN# kullan";
$pgv_lang["GENERATE_GUID"]		= "Otomatikman global olarak tek ID oluştur";
$pgv_lang["PEDIGREE_ROOT_ID"]		= "Soyağacı ya da şahsı izleyen nesiller Çizelgesinde kullanılacak ilk şahıs";
$pgv_lang["PEDIGREE_ROOT_ID_help"]	= "~#pgv_lang[PEDIGREE_ROOT_ID]#~<br /><br />Soyağacı ve şahsı izleyen nesiller Çizgelerinde göstermek için vaysayılan kişinin ID bilgisini girin.<br />";
$pgv_lang["GEDCOM_ID_PREFIX"]		= "GEDCOM - Kişisel ID öneki";
$pgv_lang["SOURCE_ID_PREFIX"]		= "Kaynak ID öneki";
$pgv_lang["REPO_ID_PREFIX"]		= "Havuz ID öneki";
$pgv_lang["PEDIGREE_FULL_DETAILS"]	= "Soyağacı ya da şahsı izleyen nesiller Çizelgesinde doğum ve ölüm detaylarını göster";
$pgv_lang["PEDIGREE_LAYOUT"]		= "Genel soyağacı Çizelgesinin düzeni";
$pgv_lang["SHOW_EMPTY_BOXES"]		= "Soyağacı Çizgelerinde boş kutuları göster";
$pgv_lang["ZOOM_BOXES"]			= "Çizgelerdeki kutuların büyültüp küçültmesine izin ver";
$pgv_lang["LINK_ICONS"]			= "Çizgelerdeki bağlantı kutularının otomatik açılmasına izin ver";
$pgv_lang["ABBREVIATE_CHART_LABELS"]			= "Çizgelerdeki hadise başlıklarını kısalt";
$pgv_lang["SHOW_AGE_DIFF"]			= "Yaş Farklarını Göster";
$pgv_lang["SHOW_PARENTS_AGE"]			= "Ebeveyn yaşlarını çocukların doğum tarihinin yanında göster";
$pgv_lang["SHOW_RELATIVES_EVENTS"]      = "Kişisel sayfada akrabaların hadiseleri göstermek veya göstermemek";
$pgv_lang["EXPAND_RELATIVES_EVENTS"]      = "Otomatik olarak olayların listesi genişlet";
$pgv_lang["EXPAND_SOURCES"]      = "Otomatik olarak kaynakları genişlet";
$pgv_lang["EXPAND_NOTES"]      = "Otomatikman notları genişlet";
$pgv_lang["HIDE_LIVE_PEOPLE"]		= "Mahremiyet kurallarını kullan";
$pgv_lang["REQUIRE_AUTHENTICATION"]	= "Ziyaretçi denetlemesini kullan";
$pgv_lang["WELCOME_TEXT_AUTH_MODE"]	= "Ziyaretçi denetlemesi yapılacaksa giriş sayfasında kullanılacak karşılama mesajı";
$pgv_lang["WELCOME_TEXT_AUTH_MODE_OPT0"]	= "Metin öceden tanımlanmadı";
$pgv_lang["WELCOME_TEXT_AUTH_MODE_OPT1"]	= "Her ziyaretçinin üye isteminde bulunabileceğini söyleyen önceden tanımlanmış mesaj";
$pgv_lang["WELCOME_TEXT_AUTH_MODE_OPT2"]	= "Her üyelik istemine yöneticinin karar vereceğini söyleyen önceden tanımlanmış mesaj";
$pgv_lang["WELCOME_TEXT_AUTH_MODE_OPT3"]	= "Ancak akrabaların üye olabileceğini söyleyen önceden tanımlanmış mesaj";
$pgv_lang["WELCOME_TEXT_AUTH_MODE_OPT4"]	= "Aşağıdaki ziyaretçi karşılama mesajını seç";
$pgv_lang["WELCOME_TEXT_AUTH_MODE_CUST"]	= "Ziyaretçi denetlemesi yapılacaksa kullanılacak özel karşılama mesajı";
$pgv_lang["WELCOME_TEXT_AUTH_MODE_CUST_HEAD"]	= "Özel karşılama mesajı için standart başlık";
$pgv_lang["SHOW_REGISTER_CAUTION"]			= "«Üye olmak istiyorum» sayfasında üyelik şartları göster";
$pgv_lang["CHECK_CHILD_DATES"]		= "Çocuklarin yaşlarını denetle";
$pgv_lang["MAX_ALIVE_AGE"]		= "Bir şahsın öldüğünü farzetmek için yaş sınırı";
$pgv_lang["SHOW_GEDCOM_RECORD"]		= "Ziyaretçiler sadece GEDCOM kayıtlarını görebilir";
$pgv_lang["ALLOW_EDIT_GEDCOM"]		= "Çevrimiçi düzenlemeye izin ver";
$pgv_lang["EDIT_AUTOCLOSE"]		= "Otomatik çıkış penceresini düzenle ";
$pgv_lang["POSTAL_CODE"]  = "Posta Kodu Durumu";
$pgv_lang["SUBLIST_TRIGGER_I"]		= "Soyadların en yüksek sayısı";
$pgv_lang["SUBLIST_TRIGGER_F"]		= "Aile adların en yüksek sayısı";
$pgv_lang["SURNAME_LIST_STYLE"]		= "Soyadı listeme biçimi";

$pgv_lang["SHOW_LAST_CHANGE"]		= "Listelerde geçen değişklik tarihini GEDCOM kayıtlarını göster";
$pgv_lang["SHOW_PEDIGREE_PLACES"]	= "Şahsi kutularda gösterilecek yer isimlerinin derinliği";
$pgv_lang["MULTI_MEDIA"]		= "Multimedya kullanıma ve gösterime izin ver";
$pgv_lang["MEDIA_EXTERNAL"]		= "Bağlantıları muhafaza et";
$pgv_lang["MEDIA_DIRECTORY"]		= "Multimedya dizini";
$pgv_lang["MEDIA_DIRECTORY_LEVELS"]	= "Multimedya dizin derinliği";
$pgv_lang["USE_THUMBS_MAIN"]		= "Şahıs sayfasında tırnak resim yerine ana resim kullan";
$pgv_lang["SHOW_MEDIA_FILENAME"]		= "Medya izleyiciye dosya adını göster";
$pgv_lang["SHOW_MEDIA_DOWNLOAD"]		= "Medya izleyiciye dosya linkini göster";
$pgv_lang["ENABLE_CLIPPINGS_CART"]	= "Parça toplama mahfazasını kullan";
$pgv_lang["HIDE_GEDCOM_ERRORS"]		= "GEDCOM hatalarını sakla";
$pgv_lang["WORD_WRAPPED_NOTES"]		= "Notların kesildiği yerde boşluk ekle";
$pgv_lang["SHOW_CONTEXT_HELP"]		= "Yardım almak için link <b>?</b> ikonu göster";
$pgv_lang["DAYS_TO_SHOW_LIMIT"]		= "Gelecek hadiselerin blokta duracağı gün";
$pgv_lang["COMMON_NAMES_THRESHOLD"]	= "\"Yaygın soy isim\" için minimum miktar";
$pgv_lang["COMMON_NAMES_ADD"]		= "\"Yaygın soy isim\" listesine katılacak soy isimler (virgül ile ayrılacak)";
$pgv_lang["COMMON_NAMES_REMOVE"]	= "\"Yaygın soy isim\" listesinden silinecek soy isimleri (virgül ile ayrılacak)";
$pgv_lang["HOME_SITE_URL"]		= "Sitenin ana URL adresi";
$pgv_lang["HOME_SITE_TEXT"]		= "Sitenin ana genel mesajı";
$pgv_lang["CONTACT_EMAIL"]		= "Nesep tetkiki için temas edilecek kişi";
$pgv_lang["CONTACT_METHOD"]		= "İletişim türü";
$pgv_lang["PHPGEDVIEW_EMAIL"]		= "PhpGedViewde cevap adresi";
$pgv_lang["WEBMASTER_EMAIL"]		= "Teknik yardım için temas edilecek üye";
$pgv_lang["SUPPORT_METHOD"]		= "Destek türü";
$pgv_lang["SHOW_FACT_ICONS"] 		= "Gerçek İkonları Göster";
$pgv_lang["FAVICON"]			= "\"Sık Kullanılanlar\" (favorites) simgesi";
$pgv_lang["THEME_DIR"]			= "Tema dizini";
$pgv_lang["TIME_LIMIT"]			= "PHP zaman aşım sınırlaması";
$pgv_lang["LOGIN_URL"]			= "Giriş URL adresi";
$pgv_lang["SHOW_STATS"]			= "İşletim istatistiklerini göster";
$pgv_lang["SHOW_COUNTER"]		= "Sayaçları göster";
$pgv_lang["LOGFILE_CREATE"]		= "Sistemin günlüklerini arşivle";
$pgv_lang["ALLOW_THEME_DROPDOWN"]	= "Tema değiştirmek için açılırliste göster";
$pgv_lang["MAX_VIEW_RATE"]		= "En fazla sayfa görüntüleme oranı";
$pgv_lang["META_AUTHOR"]		= "Yazar META tag";
$pgv_lang["META_PUBLISHER"]		= "Yayıncı META tag";
$pgv_lang["META_COPYRIGHT"]		= "Telif hakkı META tag";
$pgv_lang["META_DESCRIPTION"]		= "Açıklama META tag";
$pgv_lang["META_DESCRIPTION_descr"]	= "Şu anki aktif veritabanının başlığını kullanmak için burayı boş geçin.";
$pgv_lang["META_PAGE_TOPIC"]		= "Sayfa içerik META tag";
$pgv_lang["META_PAGE_TOPIC_descr"]	= "Şu anki aktif veritabanının başlığını kullanmak için burayı boş geçin.";
$pgv_lang["META_AUDIENCE"]		= "Audience META tag";
$pgv_lang["META_PAGE_TYPE"]		= "Sayfa tipi META tag";
$pgv_lang["META_ROBOTS"]		= "Robotlar META tag";
$pgv_lang["META_KEYWORDS"]		= "Anahtar kelimeler META tag";
$pgv_lang["META_PAGE_TOPIC_descr"]	= "Şu anki aktif veritabanının başlığını kullanmak için burayı boş geçin.";
$pgv_lang["META_TITLE"]		= "Başlık etiketini isimlendirme ekle";
$pgv_lang["META_SURNAME_KEYWORDS"]		= "\"Yaygın soy isimleri\" Keywords META alanına ekle";
$pgv_lang["ENABLE_RSS"]				= "RSS Etkinleştir";
$pgv_lang["RSS_FORMAT"]				= "RSS Biçimi";
$pgv_lang["SECURITY_CHECK_GEDCOM_DOWNLOADABLE"] = "GEDCOM dosyaların indirebilirmidir kontrol et";
$pgv_lang["gedcom_download_secure"]	= "#GEDCOM# indirilemez.";
$pgv_lang["return_editconfig"]		= "Bu yapılandırma sayfasına her zaman tarayıcınızda <b>editconfig.php</b> dosyasını açarak ya da <b>Yönetim</b> sayfasındaki <b>Genel ayarlar</b> bağlantısını tıklayarak geri dönebilirsiniz.<br />";
$pgv_lang["return_editconfig_gedcom"]	= "Bu yapılandırma sayfasına her zaman tarayıcınızda <b>editconfig_gedcom.php</b> dosyasını açarak ya da <b>Yönetim</b> sayfasındaki <b>GEDCOM - Veritabanı ayarları</b> bağlantısını tıklayarak açılan <b>Aktüel GEDCOM veritabanları</b> sayfasında ayarlamak istediğiniz GEDCOM Veritabanı yanındaki <b>Düzenle</b> bağlantısına tıklayarak geri dönebilirsiniz.<br />";
$pgv_lang["save_config"]		= "Ayarları Kaydet";
$pgv_lang["download_gedconf"]		= "GEDCOM ayarları dosyasını indir.";
$pgv_lang["not_writable"]		= "Konfigürasyon (confing.php) dosyanız PHP ile yazılabilir değildir. Konfigürasyon dosyanızı <b>#pgv_lang[download_file]#</b> duğmesini tıklayarak dosyayı indirin FTP ile serverinize yükleyiniz. Yada confing.php dosyanın CHMOD izni 777 yapın.";
$pgv_lang["upload_to_index"]		= "Dosyayı indeks dizinine yolla:";

//-- edit privacy messages
$pgv_lang["edit_privacy"]		= "Mahremiyet ayarlarını işle";
$pgv_lang["edit_privacy_title"]		= "GEDCOM mahremiyet ayarlarını işle";
$pgv_lang["save_changed_settings"]	= "Değişiklikleri Kaydet";
$pgv_lang["add_new_pp_setting"]		= "Şahıs mahremiyetine yeni ekleme yap";
$pgv_lang["add_new_up_setting"]		= "Üye mahremiyetine yeni ekleme yap";
$pgv_lang["add_new_gf_setting"]		= "Genel hadise mahremiyetine yeni ekleme yap";
$pgv_lang["add_new_pf_setting"]		= "Şahsi hadise mahremiyetine yeni ekleme yap";
$pgv_lang["file_read_error"]		= "H A T A !!! Mahremiyet dosyasını okuyamadım!";
$pgv_lang["edit_exist_person_privacy_settings"]	= "Varolan şahıs mahremiyeti ayarını işle";
$pgv_lang["edit_exist_user_privacy_settings"]	= "Varolan üye mahremiyeti ayarını işle";
$pgv_lang["edit_exist_global_facts_settings"]	= "Varolan genel hadise mahremiyeti ayarını işle";
$pgv_lang["edit_exist_person_facts_settings"]	= "Varolan şahsi hadise mahremiyeti ayarını işle";
$pgv_lang["general_privacy"]			= "Genel mahremiyet ayarları";
$pgv_lang["person_privacy"]				= "Şahıs mahremiyeti ayarları";
$pgv_lang["user_privacy"]				= "Üye mahremiyeti ayarları";
$pgv_lang["global_facts"]				= "Genel hadise mahremiyeti ayarları";
$pgv_lang["person_facts"]				= "Şahsi hadise mahremiyeti ayarları";
$pgv_lang["accessible_by"]		= "Kime gösterilsin?";
$pgv_lang["hide"]			= "Sakla";
$pgv_lang["show_question"]		= "Göster?";
$pgv_lang["user_name"]			= "Kullanıcı adı";
$pgv_lang["name_of_fact"]		= "Hadisenin ismi";
$pgv_lang["choice"]			= "Seçenek";
$pgv_lang["fact_show"]			= "Hadiseyi göster";
$pgv_lang["fact_details"]		= "Hadiselerin ayrıntılarını göster";
$pgv_lang["privacy_header"]		= "Mahremiyet ayarlarını düzenleme aracı";
$pgv_lang["unable_to_find_privacy_indi"]	= "Seçilen numaraya bağlı hiç bir şahıs bulunamadı";
$pgv_lang["SHOW_LIVING_NAMES"]		= "Yaşayan şahısların ismini göster";
$pgv_lang["SHOW_RESEARCH_ASSISTANT"]			= "Asistan Aramayı Göster";
$pgv_lang["USE_RELATIONSHIP_PRIVACY"]	= "Akrabalık mahremiyetini kullan";
$pgv_lang["MAX_RELATION_PATH_LENGTH"]	= "Maksimum akrabalık derecesi";
$pgv_lang["CHECK_MARRIAGE_RELATIONS"]	= "Akrabalık derecesini denetle";
$pgv_lang["SHOW_DEAD_PEOPLE"]		= "Hayatta olmayan şahısları göster";
$pgv_lang["SHOW_DEAD_PEOPLE_help"]		= "~#pgv_lang[SHOW_DEAD_PEOPLE]#~<br /><br />Tüm ölü kişiler için gizlilik erişim düzeyi kur.<br />";
$pgv_lang["help_info"]			= "Her nesne üzerine kırmızı &quot;?&quot; (soru işaretlerine) tıklayıp yardım elde edebilirsiniz.<br />";
$pgv_lang["select_privacyfile_button"]	= "Mahremiyet dosyasını seç";
$pgv_lang["PRIVACY_BY_YEAR"]		= "Mahremiyeti hadisenin yalşı ile sınırla";

//-- language edit utility
$pgv_lang["edit_langdiff"]		= "Dil dosyalarının içeriğini ve dil ayarlarını işle";
$pgv_lang["bom_check"]			= "Dil dosyası içinde BOM denetimi";
$pgv_lang["lang_debug"]			= "Yardım metni hata giderme seçeneği";
$pgv_lang["lang_debug_use"]		= "Yardım metni hata giderme seçeneği kullan";
$pgv_lang["bom_not_found"]		= "Hiçbir BOM bulunamadı.";
$pgv_lang["bom_found"]			= "İçinde BOM bulundu ";
$pgv_lang["edit_lang_utility"]		= "Dil dosyalarının içeriğini değiştirme aracı";
$pgv_lang["language_to_edit"]		= "İçeriği değiştirilecek dil";
$pgv_lang["file_to_edit"]		= "İçeriği değiştirilecek dosya tipi";
$pgv_lang["check"]			= "Denetle";
$pgv_lang["lang_save"]			= "Kaydet";
$pgv_lang["contents"]			= "İçerik";
$pgv_lang["listing"]			= "Gösterilen dosya";
$pgv_lang["no_content"]			= "İçerik yok";
$pgv_lang["editlang"]			= "Düzenle";
$pgv_lang["editlang_help"]		= "Dil dosyasındaki mesajın içeriğinini değiştir";
$pgv_lang["savelang"]			= "Kaydet";
$pgv_lang["savelang_help"]		= "İçeriği değiştirilen mesajı Kaydet";
$pgv_lang["original_message"]		= "Orjinal mesaj";
$pgv_lang["message_to_edit"]		= "Değiştirilecek mesaj içeriği";
$pgv_lang["changed_message"]		= "Değiştirmiş içerik";
$pgv_lang["message_empty_warning"]	= "-&lt; Dikkat!!! Bu mesajın içeriği [#LANGUAGE_FILE#] dosyasında boştur &gt;-";
$pgv_lang["language_to_export"]		= "İhraç edilecek dil";
$pgv_lang["export_lang_utility"]	= "Dil dosyasını ihraç etme aracı";
$pgv_lang["export"]			= "İhraç et";
$pgv_lang["export_ok"]			= "Yardım mesajları ihraç edilmiştir";
$pgv_lang["compare_lang_utility"]	= "Dil dosyalarını karşılaştırma aracı";
$pgv_lang["new_language"]		= "Kaynak dil";
$pgv_lang["old_language"]		= "İkinci dil";
$pgv_lang["compare"]			= "Karşılaştır";
$pgv_lang["comparing"]			= "Karşılaştırılan diller";
$pgv_lang["additions"]			= "Eklenenler";
$pgv_lang["no_additions"]		= "Ekleme yapılmamış";
$pgv_lang["subtractions"]		= "Silinenler";
$pgv_lang["no_subtractions"]		= "Silenecekler yok";
$pgv_lang["config_lang_utility"]	= "Desteklenen dillerin ayarı";
$pgv_lang["active"]			= "Kullanımda";
$pgv_lang["edit_settings"]		= "Ayarları değiştir";
$pgv_lang["lang_edit"]			= "Düzenle";
$pgv_lang["lang_language"]		= "Dil";
$pgv_lang["export_filename"]		= "Çıktı dosya ismi:";
$pgv_lang["lang_back"]			= "Dil dosyalarının içeriğini ve dil ayarlarını işlemek için ana sayfaya geri dön";
$pgv_lang["lang_back_admin"]		= "Yönetim sayfasına geri dön";
$pgv_lang["lang_back_manage_gedcoms"]	= "GEDCOM ayarlandırma sayfasına geri dön";
$pgv_lang["lang_name_czech"]		= "Çekçe";
$pgv_lang["lang_name_chinese"]		= "Çince";
$pgv_lang["lang_name_danish"]		= "Danca";
$pgv_lang["lang_name_dutch"]		= "Hollandaca";
$pgv_lang["lang_name_english"]		= "İngilizce";
$pgv_lang["lang_name_finnish"]		= "Fince";
$pgv_lang["lang_name_french"]		= "Fransızca";
$pgv_lang["lang_name_german"]		= "Almanca";
$pgv_lang["lang_name_hebrew"]		= "İbranice";
$pgv_lang["lang_name_hungarian"]	= "Macarca";
$pgv_lang["lang_name_italian"]		= "İtalyanca";
$pgv_lang["lang_name_norwegian"]	= "Norveççe";
$pgv_lang["lang_name_polish"]		= "Lehçe";
$pgv_lang["lang_name_portuguese"]	= "Portekizce";

$pgv_lang["lang_name_russian"]		= "Rusça";
$pgv_lang["lang_name_spanish"]		= "İspanyolca";
$pgv_lang["lang_name_spanish-ar"]	= "Latin Amerika İspanyolcası";
$pgv_lang["add_new_lang_button"]	= "Yeni dil ekle";
$pgv_lang["hide_translated"]		= "Tercümesi tamamlanmış olanları sakla";
$pgv_lang["lang_file_write_error"]	= "Hata!!!<br /><br />Secilen dil dosyasina degisiklikleri yazamadım!<br />Lütfen [#lang_filename#] adlı dosyaya yazma izinini denetleyin ve ondan sonra bu adımı tekrar deneyin.";
$pgv_lang["no_open"]			= "H A T A !!!<br /><br />#lang_filename# isimli dosyayı okuyamadım";
$pgv_lang["users_langs"]			= "Kullanıcıların Lisanları";
$pgv_lang["configured_languages"]	= "Lisanları kullan";
$pgv_lang["lang_name_swedish"]		= "İsveççe";
$pgv_lang["lang_name_turkish"]		= "Türkçe";
$pgv_lang["lang_name_greek"]		= "Yunanca";
$pgv_lang["lang_name_arabic"]		= "Arapça";
$pgv_lang["lang_name_vietnamese"]	= "Vietnamlı";
$pgv_lang["lang_name_slovak"]		= "Slovak";
$pgv_lang["lang_name_estonian"]		= "Estonya";
$pgv_lang["lang_new_language"]		= "Yeni Dil";
$pgv_lang["original_lang_name"]		= "Bu dilin #D_LANGNAME# dilindeki gerçek ismi";
$pgv_lang["lang_shortcut"]		= "Dil dosyaları için kısaltma";
$pgv_lang["lang_langcode"]		= "Dil belirleme kodları";
$pgv_lang["lang_filenames"]		= "Dil dosyaları";
$pgv_lang["flagsfile"]			= "Bayrak dosyasının ismi";
$pgv_lang["flagsfile_help"]		= "Seçilen dilin ulusal bayrağını içeren resim dosyasının isimi ve yolu.";
$pgv_lang["text_direction"]		= "Yazım yönü";
$pgv_lang["date_format"]		= "Tarih biçimi";
$pgv_lang["time_format"]		= "Saat biçimi";
$pgv_lang["week_start"]			= "Haftanın ilk günü";
$pgv_lang["name_reverse"]		= "Önce soy isim";
$pgv_lang["ltr"]			= "Soldan sağa";
$pgv_lang["rtl"]			= "Sağdan sola";
$pgv_lang["file_does_not_exist"]	= "HATA! Bu dosya yoktur...";
$pgv_lang["optional_file_not_exist"]	= "Bu isteğe bağlı dosya mevcut değil.";
$pgv_lang["alphabet_upper"]		= "Alfabe büyük harf";
$pgv_lang["alphabet_lower"]		= "Alfabe küçük harf";
$pgv_lang["multi_letter_alphabet"]		= "Çoklu-harf alfabesi";
$pgv_lang["dictionary_sort"]		= "Düzenlerken sözlük kurallarını kullan";
$pgv_lang["lang_config_write_error"]	= "Hata!!! [language_settings.php] adlı dosyaya yazamadım. Lütfen dosya ve dizin izinlerini denetleyin ve ondan sonra bu adımı tekrar deneyin.";
$pgv_lang["translation_forum"]		= "SourceForge sitesindeki PhpGedView tercüme forumuna bağlantı";
$pgv_lang["lang_set_file_read_error"]	= "H A T A !!! <b>lang_settings.php</b> yı okuyamadım !";

//-- User Migration Tool messages
$pgv_lang["um_header"]			= "Üye verilerini kaydırma aracı";
$pgv_lang["um_proceed"] = "Seçeneği seç veya yönetime dönmek için aşağıdaki Yönetim butonu tıklayın<br />";
$pgv_lang["um_creating"] = "Oluşturuluyor";
$pgv_lang["um_sql_index"] = "Bu araç <i>authenticate.php</i> oluşturacak ve çeşitli <i>.dat</i> dosyalarla beraber sunucunuzdaki index klasörüne dosyalayacaktır.<br /><br /><br /><br />Tüm geçerli kullanıcılar, mesajlar, favoriler, haberler ve Benim Soyağacı Görüntüleme yerleşimi başarılı biçimde oluşturulduktan sonra buradan çalışacaktır.<br /><br /><br /><br />Not: İndex moduna geçtikten sonra tekrar veritabanına geçmek için GEDCOM dosyları içeri aktarmalısınız.<br /><br />";
$pgv_lang["um_file_create_fail1"] = "Oluşturmaya çalıştığınız dosya adında zaten dosya mevcut olduğu için başarısız oldu:";
$pgv_lang["um_file_create_fail2"] = "Oluşturamaz";
$pgv_lang["um_file_create_fail3"] = "Bu klasörde erişim izinlerini kontrol edin. ";
$pgv_lang["um_file_create_succ1"] = "Yeni dosya başarılı biçimde oluşturuldu: ";
$pgv_lang["um_file_not_created"]	= "Dosya oluşturulamadı.";
$pgv_lang["um_nomsg"] = "Sistemde şu anda görülecek hiç bir mesaj yok.";
$pgv_lang["um_nofav"] = "Sistemde şu anda görülecek hiç bir favoriler yok.";
$pgv_lang["um_nonews"] = "Sistemde şu anda görülecek hiç bir haberler yok.";
$pgv_lang["um_noblocks"] = "Sistemde şu anda görülecek hiç bir bloklar yok.";
$pgv_lang["um_index_sql"] = "Bu araç index klasörünüzdeki <i>authenticate.php</i> ve diğer <i>.dat</i> dosyaları veritabanınıza aktaracaktır.<br />";
$pgv_lang["um_import"] = "İçeri Al";
$pgv_lang["um_export"] = "Dışarı Ver";
$pgv_lang["um_explain"] = "Bu araç dışarı aktarım için SQL den index klasörüne kaydıracak veya içeri aktarım için index klasöründen SQL tablolarına kaydıracaktır.<br /><br /> Kullanıcı bilgileri, Favoriler, Blok belirlemeleri, Mesajlar ve haberleri kaydırıldıktan sonra tekrar mevcut olacaklar.<br /><br /><b>UYARI</b><br />Bu araç farklı PhpGedView versiyonları arasında kullanıcı bilgilerini kaydırmak için kullanamazsınız.  Verinin orijinal olduğundan emin olun yada PhpGedViewnin aynı versiyonu olduğundan emin olun.<br /><br /><b>İÇERİ ALMAK</b><br />Kullanıcı bilgileri içeri aktarma seçerseniz index içindeki kullanıcı bilgileri içeren dosyadan tümü veritabanındaki mevcut <u>kullanıcı bilgilerinin üzerine yazılacaktır</u>. Bu araç <u>bilgileri birleştirmez</u>.  İçeri aktarımı yaptıktan sonra PhpGedViewde olan eski bilgileri geri almak için hiç bir yol yok ve <u>geri almak mümkün değildir</u>.<br /><br /><b>DIŞARI VERMEK</b><br />Kullanıcı bilgilerini Dışarı Aktar seçeneğini seçerseniz SQL veritabanınızdaki tüm kullanıcı bilgileri index klasörüne <i>authenticate.php</i> ve <i>.dat</i> dosyalar oluşturularak aktarılacaktır. Eğer index klasöründe aynı dosyalar mevcut ise bir yada birkaç dosya mevcutur onların üstüne yazmak istiyormusunuz diye sizi uyaracaktır. Dışarı Aktar butonuna bastıktan sonra tüm dosyalar index klasöründe mevcut olacaklardır.<br /><br /><b>Not:</b> İndex modu kapattıktan sonra GEDCOM dosyanızı tekrar içeri aktarma ihtiyaç duyacaksınız.( After switching to Index mode, you will need to import your GEDCOM files again.)<br />";
$pgv_lang["um_imp_users"] = "İçeri aktarılan kullanıcılar";
$pgv_lang["um_imp_blocks"] = "İçeri aktarılan engeller";
$pgv_lang["um_imp_favorites"] = "İçeri aktarılan favoriler";
$pgv_lang["um_imp_messages"] = "İçeri aktarılan mesajlar";
$pgv_lang["um_imp_news"] = "İçeri aktarılan haber";
$pgv_lang["um_nousers"] = "<i>authenticate.php</i> dosya index klasörünüzde bulunamadı. Kaydırma iptal edildi.";
$pgv_lang["um_imp_succ"] = "İçeri aktarım başarılı";
$pgv_lang["um_imp_fail"] = "İçeri atarım başarısız oldu";
$pgv_lang["um_backup"]			= "Yedekle";
$pgv_lang["um_zip_succ"] = "ZIP dosyası başarılı biçimde oluşturuldu.";
$pgv_lang["um_zip_dl"] = "Yedekleme ZIP lendi. İndirebilirsiniz tıkla yada FTP ile indir ";
$pgv_lang["um_bu_explain"] = "Bu araç PhpGedViewdeki bir çok veri çeşitlerini yedekleyebilir bu yedek indirmenize izin verir.<br /><br />Bu verileri yedekleyip ZIP dosya olarak indirmek için yedeklemek istediğiniz bilgileri aşağıdaki seçim kutularında seçmelisiniz seçimlerinizi yaptıktan sonra aşağıdaki Yedeklemeyi yap düğmesine basınız.<br /><br />Bu oluşturulacak ZIP dosyası index dızınde siz elle silene kadar kalacaktır.<br />";
$pgv_lang["um_bu_config"]		= "PhpGedView yapılandırma dosyası";
$pgv_lang["um_bu_gedcoms"]		= "GEDCOM dosyaları";
$pgv_lang["um_bu_gedsets"]		= "GEDCOM ayarları, yapılandırma ve mahremiyet dosyaları";
$pgv_lang["um_bu_logs"]			= "GEDCOM sayacları, Arama- ve PhpGedView-Günlük dosyaları";
$pgv_lang["um_bu_usinfo"] = "Kullanıcı tanımları, Blok ayarlar, Favoriler, Yeni Mesajlar";
$pgv_lang["um_bu_media"]	= "Media dosyalar";
$pgv_lang["um_mk_bu"]			= "Yedeklemeyi yap";
$pgv_lang["um_nofiles"]			= "Yedekleme için hiç bir dosya bulunamadı.";
$pgv_lang["um_files_exist"]		= "Bir ya da birkaç dosya mevcuttur. Onların üstüne yazmak istiyormusunuz?";
$pgv_lang["um_results"]		= "Sonuçlar";
$pgv_lang["preview_faq_item"] = "Tüm SSS bölümleri önizleme";
$pgv_lang["restore_faq_edits"] = "SSS işlevselliği için yeniden düzenle";
$pgv_lang["add_faq_item"] = "SSS Bölüm Ekle";
$pgv_lang["edit_faq_item"] = "SSS Bölümü Düzenle";
$pgv_lang["delete_faq_item"] = "SSS parçası sil";
$pgv_lang["moveup_faq_item"] = "SSS bölümü yukarı taşı";
$pgv_lang["movedown_faq_item"] = "SSS bölümü aşağı taşı";
$pgv_lang["ged_filter_results"] = "Bulunan sonuçlar";
$pgv_lang["ged_filter_reset"] = "Aramayı Temizle";
$pgv_lang["ged_filter_description"] = "Arama seçenek metni";
$pgv_lang["SHOW_SOURCES"]		= "Kaynakları göster";
$pgv_lang["SPLIT_PLACES"]		= "Düzenleme kipinde yerleri böl";
$pgv_lang["UNDERLINE_NAME_QUOTES"]	= "Tırnak işaretleri arasındaki isimlerin altını çiz";
$pgv_lang["PRIVACY_BY_RESN"]		= "GEDCOM (RESN) Gizlilik kısıtlamasını kullan";
$pgv_lang["SHOW_LDS_AT_GLANCE"]		= "Çizge kutularında LDS kurallarının kodlarını göster";
$pgv_lang["GEDCOM_DEFAULT_TAB"]		= "Şahısların bilgileri sayfasında gösterilecek ilk sekme";
$pgv_lang["SHOW_MARRIED_NAMES"]		= "Şahıs listesinde evlilik isimlerini göster";
$pgv_lang["SHOW_QUICK_RESN"]		= "#pgv_lang[quick_update_title]# formunda gizli alanları göster";
$pgv_lang["USE_QUICK_UPDATE"]		= "#pgv_lang[quick_update_title]# formu kullan";
$pgv_lang["SEARCHLOG_CREATE"]		= "Günlük-Aramaların kütüğü";
$pgv_lang["CHANGELOG_CREATE"]		= "Değişen-Dosyaların kütüğü";
$pgv_lang["CHART_BOX_TAGS"]		= "Çizgelerde gösterilecek diğer hadiseler";

$pgv_lang["FAM_ID_PREFIX"]		= "Aile ID öneki";
$pgv_lang["QUICK_REQUIRED_FAMFACTS"]			= "Ailelerden her zaman gerçekler hızlı güncelleştirmede gösterirler";
$pgv_lang["QUICK_ADD_FAMFACTS"]			= "Hızlı güncelleştirmede göstermek için aileler için gerçekler";
$pgv_lang["QUICK_REQUIRED_FACTS"]			= "Hızlı güncellemede gerçekler her zaman göster";
$pgv_lang["QUICK_ADD_FACTS"]			= "Hızlı güncelleştirmede göstermek için gerçekler";
$pgv_lang["AUTO_GENERATE_THUMBS"]			= "Otomatikman tırnak önizleme üret";
$pgv_lang["more_config_hjaelp"]			= "<br /><b>Daha çok Yardım</b><br />Daha çok yardım almak için sayfadaki <b>?</b> ikonu tıklayın.<br />";
$pgv_lang["THUMBNAIL_WIDTH"]			= "Üretilen tırnak önizleme genişliği";
$pgv_lang["MEDIA_ID_PREFIX"]		= "Medya ID öneki";
$pgv_lang["INDI_FACTS_ADD"] 			= "Kişisel Gerçekleri Ekle";
$pgv_lang["INDI_FACTS_UNIQUE"] 			= "Tek Kişisel Gerçekler";
$pgv_lang["INDI_FACTS_QUICK"] 			= "Hızlı Kişisel Gerçekler";
$pgv_lang["FAM_FACTS_ADD"] 			= "Aile Gerçekleri Ekle";
$pgv_lang["FAM_FACTS_UNIQUE"] 			= "Tek Aile Gerçekleri";
$pgv_lang["FAM_FACTS_QUICK"] 			= "Hızlı Aile Gerçekler";
$pgv_lang["SOUR_FACTS_ADD"] 			= "Kaynak Gerçekleri Ekle";
$pgv_lang["SOUR_FACTS_UNIQUE"] 			= "Tek Kaynak Gerçekleri";
$pgv_lang["SOUR_FACTS_QUICK"] 			= "Hızlı Kaynak Gerçekleri";
$pgv_lang["REPO_FACTS_ADD"] 			= "Özel Bilgi veren Kişi Gerçekleri Ekle";
$pgv_lang["REPO_FACTS_UNIQUE"] 			= "Tek Özel Bilgi veren Kişi Gerçekler";
$pgv_lang["REPO_FACTS_QUICK"] 			= "Hızlı Gerçek Saklama Yeri";
$pgv_lang["COMMIT_COMMAND"] 			= "Versiyon Kontrol Komut İşlemi";
$pgv_lang["SHOW_MULTISITE_SEARCH"]		= "Çoklu site aramayı göster";
$pgv_lang["SHOW_NO_WATERMARK"]			= "Filigranlı olmayan resimleri kim görüntüleyebilir?";
$pgv_lang["WATERMARK_THUMB"]			= "Tırnak önizlemelerede filigran ekle?";
$pgv_lang["SAVE_WATERMARK_THUMB"]		= "Sunucuya filigranlı tırnak önizlemeleri depolan?";
$pgv_lang["SAVE_WATERMARK_IMAGE"]		= "Sunucuya filigranlı tam boyutlu resimleri depola?";
$pgv_lang["DBPERSIST"]					= "Veritabanına inatçı bağlantıyı kullan";
$pgv_lang["USE_MEDIA_VIEWER"]			= "Medya İzleyici Kullan";
$pgv_lang["USE_MEDIA_FIREWALL"]			= "Medya Firewall Kullan";
$pgv_lang["MEDIA_FIREWALL_ROOTDIR"]			= "Medya Firewall Kök Klasörü";
$pgv_lang["MEDIA_FIREWALL_ROOTDIR_note"]	= "Bu alan boş iken <b>#GLOBALS[INDEX_DIRECTORY]#</b> klasörü kullanılacak.";
$pgv_lang["MEDIA_FIREWALL_THUMBS"]			= "Korunmuş resimlerin tırnak önizlemelerini koru";
$pgv_lang["SHOW_SPIDER_TAGLINE"]		= "Etiket içinde Örümcek Göster";
$pgv_lang["SHOW_PRIVATE_RELATIONSHIPS"]	= "Özel ilişkileri göster";
$pgv_lang["SYNC_GEDCOM_FILE"]			= "Düzenlemeleri GEDCOM dosya içinde senkronize et";
$pgv_lang["new_gedcom_title"]		= "[#GEDCOMFILE#] dosyasından alınan seçere ile ilgili veri";
$pgv_lang["SHOW_LIST_PLACES"]	= "Listelerde göstermek için yer düzeyleri";
$pgv_lang["DBPORT"]			= "Veritabanı Portu";
$pgv_lang["PEDIGREE_SHOW_GENDER"]	= "Çizelgede cinsiyet ikonu göster";
$pgv_lang["USE_MEDIA_FIREWALL_help"]	= "~#pgv_lang[USE_MEDIA_FIREWALL]#~<br /><br />Media Firewall kullanmak hakkında açıklama için Wiki web sitesini görün. <a href=\"#PGV_PHPGEDVIEW_WIKI#/en/index.php?title=Media_Firewall\" target=\"_blank\">#PGV_PHPGEDVIEW_WIKI#</a><br />";

$pgv_lang["FULL_SOURCES"]		= "Tam kaynak alıntıları kullan";
$pgv_lang["PREFER_LEVEL2_SOURCES"]		= "Gerçek kaynakları tercih et";
$pgv_lang["MEDIA_FIREWALL_ROOTDIR_help"]	= "~#pgv_lang[MEDIA_FIREWALL_ROOTDIR]#~<br /><br />Korunmuş medya rehberin oluşturulduğu rehber. #pgv_lang[MEDIA_FIREWALL_ROOTDIR_note]#<br />";
$pgv_lang["MEDIA_FIREWALL_THUMBS_help"]	= "~#pgv_lang[MEDIA_FIREWALL_THUMBS]#~<br /><br />Resim korunmuş medya rehberde olduğu zaman resim tırnak önizlemeside korunmalımıdır?<br />";
$pgv_lang["SHOW_NO_WATERMARK_help"]		= "~#pgv_lang[SHOW_NO_WATERMARK]#~<br /><br />Eğer Medya Firewall etkinleştirilirse kullanıcılar filigranları görecekler, eğer burada ayrıcalıklı bir seviyeye sahip olmazlar ise.<br />";
$pgv_lang["SHOW_LEVEL2_NOTES"]      = "Notlar ve Kaynak sekmesinde tüm notları ve kaynağı göster";
$pgv_lang["SHOW_EST_LIST_DATES"]		= "Doğum ve ölüm için hesaplanan tarihleri göster";
?>
