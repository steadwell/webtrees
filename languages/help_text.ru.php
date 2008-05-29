<?php
/**
 * Russian Language file for PhpGedView.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team.  All rights reserved.
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
 * @author Eugene Fedorov
 * @author Natalia Anikeeva
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access a language file directly.";
	exit;
}


//-- GENERAL
$pgv_lang["help_header"]			= "Дополнительная информация о:";

//-- Pedigree-page
$pgv_lang["help_pedigree.php"]			= "~ВОСХОДЯЩЕЕ ДЕРЕВО~<br /><br />Страница восходящего дерева - это первая страница нашей программы.<br /><br />*ПОМОЩНИК<br />С правой стороны некоторых полей и соединений стоят вопросительные знаки. Если щелкнуть по ним, то появится окно с информацией о затрагиваемой теме.<br /><br />*ВХОД В ПРОГРАММУ<br />Хотя можно зайти в программу (путем ввода имя пользователя и пароля) на любой странице, обычно все-таки это делается на первой странице, т.к. после это Вам будет открыта вся доступная Вам информация.<br />Вход в программу находится в правом верхнем углу первой страницы.<br />Посетители, кто хочет запросить имя пользователя и пароль, должны кликнуть по линку \"Вход в программу\"\"<br /><br />* ВОСХОДЯЩЕЕ ДЕРЕВО<br />Большенство посетителей и пользователей сайта знают что такое \"восходящее дерево\", но тем не менее это - небольшое объяснение:<br />Восходящее дерево это перечисление всех предков стартовой персоны, \"пробант\". Он отображается первым, затем его родители, бабушка, дедушка, пробабушка, прадедушка и т.д. Пробант - это поколение I, его родители - поколение II и т.д.<br /><br />*ЛИЧНЫЕ КОНТЕЙНЕРЫ В ВОСХОДЯЩЕМ ДЕРЕВЕ<br />В контейнере даны некоторые данные о персоне, такие как имя, дата рождения, дата смерти и места. Если кликнуть по имени, то появится окно с подробными данными. Кроме этого существует специальная пиктограмма (опция \"Показать подробности\"):<br /><br />* КНОПКА ВОСХОДЯЩЕЕ ДЕРЕВО В КОНТЕЙНЕРЕ<br />В зависимости от настройки сайта, Вы можете эту кнопку активировать кликнув по ней или щелкнув по ней мышкой. Появляется экранчик, где вы можете кликнув по: восходящее дерево персоны, его/ее потомки, его/ее семьи и персональное окошечко с детьми.<br /><br />* ЛУПА В КОНТЕЙНЕРЕ<br />В зависимости от настройки сайта, Вы можете эту кнопку активировать кликнув по ней или щелкнув по ней мышкой. Появляется экранчик с экстра информаций о персоне. Это касается (в числе прочего) таких фактов как профессия, переезд и т.д.<br /><br />* СОЕДИНЯЮЩИЕ ЛИНИИ СПРАВА И СЛЕВА ОТ КОНТЕЙНЕРА<br />Кликнув по линиям (слева от самого левого контейнера и справа от самого правого) , можно передвинуть окно, слева на детей пробанта и справа на поколение назад.<br /><br />";
$pgv_lang["show_full_help"]			= "~ПОКАЗАТЬ ПОДРОБНОСТИ ИЛИ СКРЫТЬ ПОДРОБНОСТИ~<br /><br />В этой опции Вы можете указать в названиях разделах  хотите Вы показать или скрыть подробности. Если все подробности скрыты, то на экране можно показать большее название разделов.";
$pgv_lang["talloffset_help"]			= "~ОРИЕНТАЦИЯ ЭКРАНА~<br /><br />В этой опции можно определить ориентацию экрана: вертикальная или горизонтальная. Эти изменения полезно устанавливать при некоторых типах мониторов или в некоторых случаях можно это изменить если это улучшает изображение.";
$pgv_lang["rootid_help"]			= "~СТАРТОВАЯ ПЕРСОНА ВОСХОДЯЩЕГО ДЕРЕВА~<br /><br /Если Вы хотите стартовой персоной восходящего дерева сделать другое лицо, то введите его ID номер.<br /> Если Вы не знаете ID номер этого лица, то использйте опцию \"Искать ID\". Для поиска и выбора, введя вручную имя (или часть имени) интересующей персоны.";
$pgv_lang["PEDIGREE_GENERATIONS_help"]		= "~ЧИСЛО ПОКОЛЕНИЙ НА ЭКРАНЕ~<br /><br />Здесь Вы можете задать сколько поколений будет показано на экране.<br />Это зависит от размера Вашего экрана и от того, хотите ли Вы видеть также подробности названий разделов.";

//-- LOGIN-page
$pgv_lang["login_page_help"]			= "~ВХОД В ПРОГРАММУ~<br /><br />На этой странице Вы можете войти в программу PhpGedView, запросить новый пароль, объявить себя как новый пользователь.<br /><br />Кликнув по \"?\" слева за полем, можно получить больше информации.";
$pgv_lang["username_help"]			= "~ИМЯ ПОЛЬЗОВАТЕЛЯ~<br /><br />В это поле вводится имя пользователя. Система различает заглавные и строчные буквы.<br /><br />Если Вы еще не имеете имя пользователя, то можете его запросить кликнув по нижестоящему линку \"Еще не являетесь пользователем? Запрос имя пользователя здесь.\"";
$pgv_lang["password_help"]			= "~ПАРОЛЬ~<br /><br />В это поле вводится пароль.<br />Система различает в пароле заглавные и строчные буквы.<br /><br />Если Вы забыли пароль, то можете кликнуть по линку \"Забыли пароль? Запрос нового пароля здесь\".";
$pgv_lang["login_buttons_help"]			= "~КНОПКИ ДОСТУПА~<br /><br />Вы видете здесь три кнопки доступа к системе.<br />В зависимости от Вашего выбора, вы идете на:<br /><br />* кнопка<b> #pgv_lang[login]# </b> <br />Если Вы по ней кликните, то Вы возвратитесь на первоначальную страницу, откуда пришли<br /Таким образом, если Вы кликните по \"#pgv_lang[login]#\" начиная со страницы восходящего дерева, то вы возвратитесь туда назад.<br /><br />* <b>Кнопка#pgv_lang[mygedview]#</b> <br />Если Вы кликните здесь, то Вы войдете в программу сразу на личный портал. Здесь Вы можете сделать Ваши личные установки и применить фавориты, послать и получить эл.письма (e-mail) и т.д.<br /><br />* <b>#pgv_lang[admin]#</b> кнопка<br />Если Вы имеете право на администрирование, то Вы можете использовать эту кнопку для прямого входа на страничку администрирования.";
$pgv_lang["login_buttons_aut_help"]		= "~КНОПКИ ДОСТУПА~<br /><br />Вы видете здесь три кнопки доступа к системе. до того как Вы ввели имя пользователя и пароль.<br />В зависимости от Вашего выбора, вы идете на:<br /><br />* <b> #pgv_lang[login_aut]# </b> кнопка<br />Если Вы по ней кликните, то Вы получите возможность изменить данные пользователя. Это может быть Ваш пароль, Ваша стандартная стартовая персона в восходящем дереве, язык PhpGedView и Ваша отделка сайта.<br /><br />* <b>#pgv_lang[mygedview]#</b> кнопка <br />Если Вы кликните здесь, то Вы войдете в программу сразу на личный портал. Здесь Вы можете сделать Ваши личные установки и применить фавориты, послать и получить эл.письма (e-mail) и т.д.<br /><br />* <b>#pgv_lang[admin]#</b> кнопка<br />Если Вы имеете право на администрирование, то Вы можете использовать эту кнопку для прямого входа на страничку администрирования.|";
$pgv_lang["new_password_help"]			= "~ЗАПРОС ПАРОЛЯ ~<br /><br />Если Вы забыли пароль, то Вы можете сделать запрос нового пароля, кликнув здесь.<br />Внимание: это действительно только для пользователей, которые распологают уже именем пользователя на нашем сайте.";
$pgv_lang["new_user_help"]			= "~ЗАПРОС НОВОГО ИМЕНИ ПОЛЬЗОВАТЕЛЯ~<br /><br />Щелкните здесь если Вы хотите запросить имя пользователя на нашем сайте.<br />Внимание: это действительно для пользователей, кто еще никогда не имел имя пользования на нашем сайте или не подтвердил свое имя пользователя в течение 7 дней после запроса.";

//-- Descendancy-page
$pgv_lang["help_descendancy.php"]		= "~ПОТОМКИ~<br /><br />На этой странице показываются потомки выбранной персоны. Максимальное число поколений Вы можете установить в поле \"поколения\". В случае если Вы хотите выбрать другую персону чем стартовая персона, Вы можете сразу ввести его ID номер в поле \"Стартовая персона\". Если Вы не знаете его ID номер, Вы можете воспользоваться поиском. Для этого Вам надо кликнуть по \"Искать персону\" и ввести его/ее имя (или часть имени).<br /><br />*ЛИЧНЫЕ КОНТЕЙНЕРЫ В ВОСХОДЯЩЕМ ДЕРЕВЕ<br />В контейнере даны некоторые данные о персона, такие как имя, дата рождения, дата смерти и места. Если кликнуть по имени, то появится окно с подробными данными. Кроме этого существует специальная пиктограмма (опция \"Показать подробности\"):<br /><br />* КНОПКА ВОСХОДЯЩЕЕ ДЕРЕВО В КОНТЕЙНЕРЕ<br />В зависимости от настройки сайта, Вы можете эту кнопку активировать кликнув по ней или щелкнув по ней мышкой. Появляется экранчик, где вы можете кликнув по: восходящее дерево персоны, его/ее потомки, его/ее семьи и персональное окошечко с детьми.<br /><br />* ЛУПА В КОНТЕЙНЕРЕ<br />В зависимости от настройки сайта, Вы можете эту кнопку активировать кликнув по ней или щелкнув по ней мышкой. Появляется экранчик с экстра информаций о персоне. Это касается (в числе прочего) таких фактов как профессия, переезд и т.д.<br /><br />* СОЕДИНЯЮЩИЕ ЛИНИИ СПРАВА И СЛЕВА ОТ КОНТЕЙНЕРА<br />Кликнув по линиям (слева от самого левого контейнера и справа от самого правого) , можно передвинуть окно, слева на детей пробанта и справа на поколение назад.<br /><br />";
$pgv_lang["desc_rootid_help"]			= "СТАРТОВАЯ ПЕРСОНА ПОТОМКОВ~<br /><br />Если просмотр потомков Вы хотите начать с другой персоны чем стартовая, введите в поле его ID номер.<br />Если Вы не знаете его/ее ID номер, Вы можете воспользоваться поиском, используя линк \"Искать ID\". Для поиска введите имя (или часть имени) интересующей персоны.";
$pgv_lang["desc_generations_help"]		= "~ЧИСЛО ПОКОЛЕНИЙ~<br /><br />Установить число поколений, которое будет показано на экране. Воспроизведение на Вашем экране зависит от величины модитора и показаны ли детальные сведения.<br />";

//-- Time line-page
$pgv_lang["help_time_line.php"]			= "~ВРЕМЕННАЯ ОСЬ~<br /><br />На этой странице Вы можете посмотреть события и факты, имеющие место с одним или более лицом, и произошедшие в определенное время.<br />Например Вы можете посмотреть статут лиц в определенный момент.<br /><br />Если Вы начиная с этой страницы кликните по линку \"Временная ось\", то информация о выбранном лице будет показана на временной оси.<br />Если Вы выбрали \"временную ось\" начиная с основного меню, то указать интересующее лицо можно по номеру ID. Также можно искать лицо поисковиком \"Искать лицо\".";
$pgv_lang["add_person_help"]			= "~ДОБАВИТЬ ПЕРСОНУ НА ВРЕМЕННУЮ ОСЬ~<br /><br />На временной оси отображаются события лиц.<br />Используйте поле в контейнере для добавления персон. Вы можете сразу ввести ID номер персоны.<br />Если Вы не знаете его/ее ID номер, Вы можете воспользоваться поиском, используя линк \"Искать ID\". Введите для поиска имя (или часть имени) интересующей Вас персоны.";
$pgv_lang["remove_person_help"]			= "~УДАЛЕНИЕ ПЕРСОНЫ С ВРЕМЕННОЙ ОСИ~<br /><br />Кликните по этому линку для удаления персоны и событий, с ним/с ней связанных.|";
$pgv_lang["show_age_marker_help"]		= "~ПОКАЗАТЬ УКАЗАТЕЛЬ ВОЗРАСТА~<br /><br />Если Вы отметите эту опцию, то указатель возраста появится на временной оси.<br />С помощью левой кнопки мышки, вы можете опустить или поднять его на временной оси. Этот указатель дает возможность увидеть сколько лет было персоне в момент совершения какого-либо события в его жизни.<br />Для каждой персоны Вы можете включить или выключить этот индикатор.";

//-- Relationship-page
$pgv_lang["help_relationship.php"]		= "~РОДСТВО~<br /><br />На этой странице Вы можете указать родство между двумя персонами. Это не обязательно чтобы лица имели кровное родство. Родственные отношения могут быть также по линии брака. Последние можно найти если опция \"Проверь родство по браку\" отмечена галочкой. Это возможно что два лица могут иметь не одну линию родства. Программа дает возможность увидеть все существующиеся линии родства. Используйте также кнопку \"Искать следующую ветвь\".";
$pgv_lang["relationship_id_help"]		= "~РОДСТВО~<br /><br />Если Вы с какой-либо страницы назад на эту страницу перейдете (например кликнув по линку \"мои родственные связи\"), то здесь будут показаны родственные отношения между двумя персонами.<br />В другом случае, сначала Вы заполняете поля с ID, или пользуетесь средством поиска \"Искть ID\".<br />Две персоны не обязательно должны иметь кровное родство, это могут быть также супружеские родственные связи. Эти родственные связи могут быть найдены только в случае если опция \"Контролируй супружеские связи\" отмечена. Программа дает возможность найти как кровное, так и не кровное родство. Используйте для этого кнопку \"Искать следующую ветвь\".";
$pgv_lang["next_path_help"]			= "~СЛЕДУЮЩАЯ ВЕТВЬ~<br /><br />Две персоны могут иметь родство с друг с другом через различных лиц в генеологическом дереве. Мы можете использовать эту кнопку для поиска следующей ветки родства. Найденные ветви родства можно посмотреть линком \"Показать ветвь...\".";
$pgv_lang["follow_spouse_help"]			= "~ПРОВЕРКА РОДСТВА ПО БРАКУ~<br /><br />Без этой опции поиск идет только среди кровных родственников.<br />Если пометить эту опцию, то поиск идет также среди родства по браку.";

//-- Indilist-page
$pgv_lang["help_indilist.php"]			= "~ЛИЦА~<br /><br />ИСКАТЬ ЛИЦО<br />На этой странице Вы можете задать поиск подробных данных о персоне. Для этого выберите в предлагаемом алфавите первую букву фамилии. Вам будет показан список фамилий, в котором Вы можете продолжать поиск. В итоге Вам будет показан список лиц с выбранной Вам фамилией. Если кликнуть на персону, то будут показаны подробные сведения о ней/о нем.<br />Лист с фамилиям можно пропустить кликнув по линку \"Пропустить лист списка фамилий\".<br />Для получения экстра информации, кликните по воспросительному знаку, находящемуся за полем ввода.<br /><br />ДОБАВИТЬ ЛИЦО<br />Вы можете также добавить новую персону в базу данных если у Вас есть не это право. Это делается в помощью линка \"Добавить новую неприсоединенную персону\".<br />|";
$pgv_lang["alpha_help"]				= "~АЛФАВИТ~<br /><br />Этот индекс работает просто: <br />Кликните по букве алфавита и появится лист с фамилиями, начинающимися на эту букву.<br /><br />Слева от буквы \"А\" видите Вы \"открытую скобку\". Если Вы по ней кликните, то будут выбраны персоны без фамилии или с неизвестной фамилией.<br /><br />Справа от всех букв алфавита стоит \"ВСЕ\". Кликнув здесь, Вы получите весь список лиц базы данных.<br /><br />Неизвестные буквы?<br /Чтобы это предотвратить, в цепочке букв находятся несколько пропусков.<br />Это не ошибка, а результат того, что не встретились еще лица, фамилия которых начинается с неизвестной буквы алфавита.|";
$pgv_lang["name_list_help"]			= "~СПИСОК ФАМИЛИЙ~<br /><br />Если Вы выбрали опцию \"Показать список фамилий\" (или это опция была выбрана ранее и Вы не изменили выбор), то будет показано:<br />1. Если Вы кликните по \"(\", то Вам будут показаны все персоны без фамилии и с неизвестной фамилией.<br />2. Если Вы кликните по \"ВСЕ\", то будет показан список фамилий всей базы данных<br />3. Лист с фамилиями, начинающимися на опеределенную букву.<br />При этом выборе Вам будут показаны все фамилии на выбранную букву. Кликнув на персону, можно посмотреть подробные сведения о ней/о нем.<br /><br />При выборе опции \"Пропустить страницу списка фамилий\", Вы напрямую попадаете с выбора букву алфавита на список персон, фамилии которых удовлетворяют условию выбора. Промежуточный шаг, где Вы сначала лист с фамилиями должны выбрать, в этом случае пропускается.|";
$pgv_lang["skip_sublist_help"]			= "~ПЕРЕЛИСТНУТЬ ЛИСТ СПИСКА ФАМИЛИЙ~<br /><br />Если Вы эту опцию не изменили, то после выбора буквы, Вы получите лист со списком фамилий, начинающихся с выбранной буквы. После этого показывается список персон, имеющих выбранную фамилию.<br /><br />Если Вы кликните по этой опции, то лист списка фамилий будет пропущен и Вы напрямую попадете на лист со всеми персонами, первая буква фамилии которых начинается на выбранную букву.|";

//-- Families-page
$pgv_lang["help_famlist.php"]			= "~СЕМЬИ~<br /><br />На этой странице Вы можете найти подробные данные о выбранной семье. Выберите в алфавите первую буквы фамилии. Вам будет показан список фамилий на эту букву. Выбрав конкретную персону, вы получите список партнеров (по браку). Далее Вам будут показаны члены этой семьи.";

//-- Source list-page
$pgv_lang["help_sourcelist.php"]		= "~СПИСОК ИСТОЧНИКОВ~<br /><br />На этой странице Вы попадаете на обзор всех источников.<br />Источники ЛИЦ и семей не проиндексированы в алфавитном порядке. Это не сделано по той причине, что источник персоны может быть как у имени и фамилии, так и у организации, и даже у web-сайта.<br />Поэтому нет возможности однозначно определить порядок сортировки, и в алфавитном порядке предоставлены только названия источников.<br /><br />ИСТОЧНИКИ<br />Без источника данных генеологически файл не может быть построен с достаточной степенью ответственности. Вся информация должна быть подходящая для преобразования, воспроизведени и контроля. Поэтому все персоны, семьи и факты должны быть присоединены к источникам. Источником может быть персона, предоставившая информацию, определенный документ (свидетельство и тому подобное) или другой файл (например Genlias). Если информация получена из нескольких источников, то называются они все. Вместе с тем, источник может дать информацию о нескольких событиях, поэтому привязываются несколько фактов (свадьба, рождение, профессия и т.д.<br /><br />|";
$pgv_lang["sourcelist_listbox_help"]		= "~ОКНО СПИСКА ИСТОЧНИКОВ~<br /><br />В этом окне Вы видете названия всех источником, которые есть в файле.<br />Список отсортирован в алфавитном порядке.<br /><br />Кликнув по названию источника, Вы идете на просмотр детальных данных. Там Вы видете (в случае применения) примечания, место хранения и соединение с изображением источника (например отсканированный документ).<br />Кроме этого Вы можете увидеть на какой источник указывает какая информация о персоне и семье. Кликнув по персоне или семье, Вы попадете на соответствующию информацию.|";

//-- Sources-page
$pgv_lang["help_source.php"]			= "~ИСТОЧНИКИ~<br /><br />На этой странице Вы видете подробные данные источника.<br />Если имеют место, напряду с примечания, показываются также места хранения и указание на изображение источника. Изображением может быть к примеру отсканированный документ.<br /><br />Также показывается лист с персонами и семьями, факты и события которых указывают на источник.<br /><br />Если администратор сайта предоставил такую возможность, то с правой стороны экрана есть два линка: <br />\"Смотреть запись GEDCOM\" en <br />\"Вырезать под-дерево\":<br /><br />Для получения большей информации по помощи, кликните по воспросительному знаку \"?\"находящемуся рядом с линком или полем ввода.|";
$pgv_lang["sources_listbox_help"]		= "~СПИСОК ИСТОЧНИКОВ~<br /><br />В этом кадре Вы видете имена персон, и/или семей, факты или события которых указаны с источником фактов или событий.<br />Если Вы кликните по персоне или семье, то будут показано подробные данные о них.";
$pgv_lang["show_source_gedcom_help"]		= "~ПОКАЗАТЬ ЗАПИСЬ GEDCOM~<br /><br />Если Вы кликните по этому линку, появится новое окошечко с данными откуда был взят GEDCOM файл.|";
$pgv_lang["add_source_clip_help"]		= "~ВЫРЕЗАТЬ ПОД-ДЕРЕВО~<br /><br />Кликнув по этому линку, Вы можете данные источника добавить в вырезанное под-дерево, также как это происходил в GEDCOM-файле.|";

//-- Persons per Place-page
$pgv_lang["help_placelist.php"]			= "~ГЕОГРАФИЯ ЛИЦ~<br /><br />В этом экране Вы можете искать лиц и семьи, имеющие между собой географическое единение.<br /><br />Если в факте или в событии указано географическое место, то здесь оно будет найдено.<br />Таким образом Вы можете например искать кто имел что-либо, связанное с определенной страной, городом или даже с определенной улицей.<br /><br />Результаты показаны в двойном окне:<br />Одно для персон и одно для семей.<br /><br />Для более специфической информации помощника, Вы должны кликнуть по вопросительному знаку, относящимуся к линку или контейнеру.|";
$pgv_lang["ppp_default_form_help"]		= "~УПОРЯДОЧЕНИЕ ГЕОГРАФИЧЕСКИХ МЕСТ - СТАНДАРТНОЕ~<br /><br />Внутри одного географического места существуют различные уровки для описания.<br /> Так может быть указано не только страна, но также название места (город, село и т.д.) и даже улица. В файле построение географического места идет как указано выше. Это построение является стандартным, так как в файле GEDCOM, который был импортирован нет указания на отклонения построения географического месторасположения.|";
$pgv_lang["ppp_match_one_help"]			= "~УПОРЯДОЧЕНИЕ ГЕОГРАФИЧЕСКИХ МЕСТ - СОГЛАСНО GEDCOM~<br /><br />Внутри одного географического места существуют различные уровки для описания.<br /> Так может быть указано не только страна, но также название места (город, село и т.д.) и даже улица. В файле построение географического места идет как указано выше. Это построение является определено в файле GEDCOM, который был импортирован.|";
$pgv_lang["ppp_numfound_help"]			= "~ГЕОГРАФИЧЕСКАЯ СВЯЗЬ НАЙДЕНА~<br /><br />В контейнере показаны найденные географические связи.<br />Вы можете посмотреть затронутые лица и семьи, кликнув по этому линку. Кликнув по географическому месторасположению, Вы можете также задать более уточненный поиск географического месторасположения.|";
$pgv_lang["ppp_levels_help"]			= "~УРОВЕНЬ ГЕОГРАФИЧЕСКОГО МЕСТОРАСПОЛОЖЕНИЯ~<br /><br />Это позволяет увидеть число уровней внутри показанного географического месторасположения.<br />Лист, показанный в контейнере является в действительности верхнем уровнем.<br /><br />НАПРИМЕР:<br />Стандартный порядок: #pgv_lang[default_form]#<br />Если настоящий уровень является верхним, то в контейнете будут показаны все страны, находящиеся в файле.<br />Если настоящий уровень: \"Лайден, Верхний уровень\", то будут показаны все улицы города Лайден, находящиеся в файле.<br />etc.<br /><br />Вы можете также кликнуть по уровню для того, чтобы возвратиться на один или более шагов.|";
$pgv_lang["ppp_placelist_help"]			= "~ГЕОГРАФИЧЕСКИЕ МЕСТА~<br /><br />На этом листе показываются найденные географические места.<br />Вы можете кликнуть по названиям географических мест.<br />Клик по географическому месту работает как фильтр, и Вы перейдете на более глубокий уровень.<br />Если Вы достигли нижнего уровня и кликните по названию, тогда Вы получите список лиц и семей, факты или события которых имели место в этом географическом месте.|";
$pgv_lang["ppp_name_list_help"]			= "~ГЕОГРАФИЧЕСКИЕ МЕСТА - СПИСОК ЛИЦ~<br /><br />Это полный список всех лиц и семей, у которых в фактах и событиях, происшедших с ними есть ссылки на географию.<br /><br />Если Вы кликните по имени, то здесь будет показана подробная информация о лице или семье.<br /><br />Вы можете начать поиск заново, перейдя на вышележащий уровень (например кликнув о линку \"верхний уровень. В этом случае будет показан список географических мест.|";

//-- Multimedia-page
$pgv_lang["help_medialist.php"]			= "~СПИСОК МУЛЬТИМЕДИА (ФОТО/АУДИО/ВИДЕО)~<br /><br />На этом листе сообщаются все пункты-мультимедиа (фото/аудио/видео), которые находятся в файле. Список отсортирован по названию.<br />Кликнув по названию файла Вы можете непосредственно посмотреть мультимедиа-артикль.<br />Посредством линка \"Посмотреть источник\", Вы можете посмотреть все данные о мультимедиа-пункте.|";

//-- Anniversaries-page
$pgv_lang["help_calendar.php"]			= "~КАЛЕНДАРЬ СОБЫТИЙ~<br /><br />Эта функция показывает всех лиц и события, которые \"привязаны\" к определенному дню и определенному месяцу. У Вас есть следующие возможности:<br /><br />СОБЫТИЯ ДНЯ<br />Если Вы ввели дату, кликните по \"Показать день\". Здесь будут показаны все события связвнные с определенными лицами и семьями, которые происходили в этот определенный день.<br /><br />СОБЫТИЯ МЕСЯЦА<br />Эта функция аналогична вышеуказанной. Только показываются события лиц и семьи в определенный месяц.<br /><br />ПОКАЗАТЬ: ВСЕ ПЕРСОНЫ<br />Эта функция не несет в себе определения событий, которые будут показаны.<br /><br />ПОКАЗАТЬ:ЖИВУЩИЕ НЫНЫ ПЕРСОНЫ<br />Если Вы выберите эту опцию, то здесь будут показаны события, связанные с ныне живущими (согласно PhpGedView) лицами.<br /><br />ПОКАЗАТЬ: НЕДАВНИЕ СОБЫТИЯ ( < 100 ЛЕТ)<br />В этой опции показываются события, которые произошли меньше чем 100 лет назад.<br /><br />Во всех опциях Вы можете из данных по персонам и семьям перепрыгнуть на детальные сведения о лицах и семьях, кликнув по интересующей Вас персоне/семье.<br /><br /><br /><br /><br /><br /><br /><br /><br />";
$pgv_lang["annivers_date_select_help"]		= "~ВЫБОР ДАТЫ</b><br /<br />Вы можете выбрать дату путем выбора значения дня и месяца в соответствующих полях и ввести желаемый год и поле года.<br />По умолчанию введена дата - текущий день.<br /><br />Если кликнуть по кнопке \"Показать день\" то будут показаны все лица, события, происшедшие с которыми попадают на выбранный день и месяц.<br /><br />Кликнув по кнопке \"Показать месяц\", Вы увидете месячный календарь выбранного месяца, где будут показаны день за днем события, происшедшие в выбранный месяц.<br />";
$pgv_lang["annivers_tip_help"]			= "~TIP~<br />Например, Вы имеете члена семьи, кто был рожден 25 января 1875 года.<br />Если Вы кликните по кнопке \"Показать\", Вы увидите лист с персонами или календарь с этой датой.<br />Все дни рождения, возраст и т.д.подсчитанные и вычисленные назад начитая с даты, которая была Вами определена.<br />В действительности увидите Вы календарь дней рождений, которые видели давно члены семьи.";
$pgv_lang["annivers_show_help"]			= "~ЧТО ПОКАЗЫВАТЬ</b><br /<br />Вы можете отметить что Вы хотите увидеть:<br /><br />Все Лица<br />Без ограничений<br /><br />Живущие ныне<br />Показать только живущих ныне, чьи события выпадают на указанный день<br /><br />Последние события(< 100 лет)<br />Показать только лиц, чьи события выпадают на указанный день и которые произошли менее 100 лет назад.";
$pgv_lang["day_month_help"]			= "~ПОКАЗАТЬ ДЕНЬ/ПОКАЗАТЬ МЕСЯЦ~<br /><br />Также как и в других окнах помощника кнопка<b>\"ПОКАЗАТЬ ДЕНЬ\"</b>используется для отображения событий, произошедших в определенный день. Кнопка <b>\"ПОКАЗАТЬ МЕСЯЦ\"</b> показывает календарь событий день за днем.";

//-- Upgrade utility
$pgv_lang["how_upgrade_help"]			= "~МОДУЛЬ ПЕРЕХОДА НА СЛЕДУЮЩУЮ ВЕРСИЮ~<br /><br />Этот модуль заботится о подгонке существующей версии PhpGedView и программ, загруженных из новой версии.<br /><br /><b>Новая версия загружена</b><br />Новая версия может быть напрямую получена на http://sourceforge.net/projects/phpgedview/. Также программа перехода на слудующую версию контролирует имеется ли новая версия. Если она имеется в наличии то показываются доступные версии. Это zip- или gz-файлы, или оба.<br /><br /><b>Переход на другую (более новую) версию</b><br /><br />* PhpGedView<br />Заменяются все базовые программы на PhpGedView, включая  config.php, privacy.php en authenticate.php. В случае необходимости это применяется также к images-папкам.<br /><br />* Индексные файлы<br />Это применяется к /index-папкам. Внимание: существующие файлы перезаписываются!<br /><br />* Языки<br />\"Это применяется к /languages папке.<br /><br />* Темы<br />Это применяется к /themes папке. Внимание: все применяемые темы перезаписываются.<br /><br />* Файл конфигурации<br />Контролирует существующий файл конфигурации и подгоняет его в соответствии с изменениями, найденными в новом файле конфигурации. Заботится чтобы файл конфигурации был изменен в соответствии с последней версией, но чтобы сохранились Ваши личные установки.<br /><br />* Личный файл<br />Контролирует существующий личный файл(ы) и приспосабливает его к изменениям, найденным в новом личном файле. Заботится чтобы личный файл был изменен в соответствии с последней версией, но чтобы сохранились Ваши личные установки.<br /><br />* Руководство<br />Это применяется к /doc папке.<br /><br />* Архив log-файлов<br />Применяется к модулю архива log-файлов.<br /><br />* Резерв<br />Сохраняет старые файлы как резервные в папке /backup.<br /><br /><b>Как обновлять версию</b><br /><br />1. Выбрать части, которые Вы хотите обновить.<br /><br />2. Кликнуть по \"Сохранить\"<br /><br />Продолжать далее по пути, появляющемуся на экране.";

?>
