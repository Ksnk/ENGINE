# CMS

## ядро

Ядро системы представляется скелетным классом ENGINE. ENGINE - статический класс. Весь необходимый приложению функционал интегрируется в скелет с помощью расширений системы. Вообще говоря, скелет - комплект функций работы с системными объектами и сущьностями. Он обязан обеспечить связнось системы без использования каких-либо дополнительных связей.

Объект представляет из себя глобально видимый конструкт, доступный в любом месте приложения. Следует различать плагины и расширения системного объекта. Расширения имеют потенциальную возможность "влиться" в исходный код скелетного класса, методы расширения необходимы нескольким (более одного) приложениям системы. Для плагинов такой надобности нет.

### расширение ядра.

Различают статические и динамические расширения ядра. Статические - элементы ядра интегрируются в исходный код класса на этапе сборки, что позволяет сконструировать заточенный под конкретную задачу и окружение инструмент. Динамическое расширение доступно при добавлении расширения `interface`. Оно добавляет несколько методов, которые позволяют динамически, в процессе работы приложения, добавлять-удалять методы скелетного объекта. "Донорами" методов могут быть как классы , так и отдельные функции, в том числе и функции php. Вообще говоря, любое расширение системы, кроме `interface` можно подключить динамически или статически, для этого в коде расширений сделана специальная разметка.
Статическое внедрение незначительно эффективнее, с точки зрения скорости работы, и совместимо с php версии 5.2. Динамическое внедрение более гибко, однако и более громоздко (несколько файлов, вместо одного)

### стандартный интерфейс скелетного класса

Исторически определился комплект методов, необходимых скелетному классу.

* системные методы
    *  _shutdown
* методы отладки
    *  debug($par,...) - вывод отладочной информации
    *  error($msg,$par,...) - вывод сообщения об ошибке приложения.
    *  backtrace
    *  _t - простенькое форматирование сообщения об ошибке.
* работа с параметрами
    *  set_option- установить параметры
    *  option - получить значение параметра с указанием дефолтного значения.
* интеграция плагинов
    *  exec - выполнить метод класса с нужными параметрами.
    *  _autoload
    *  getObj - получить по имени объекта его представителя.
* шаблонизация
    *  template
* db - метод работы с базой данных
* cache - служба кэширования

Существуют разные подходы к проектированию и применению ядра.
Сейчас используется внедрение движка в существующие web-приложения с постепенным переписыванием ключевых моментов на новый движок. Так сложилось, что класс чисто статичесий, без возможности расширяться с помошью register_interface

### Интерфейс объекта ENGINE

Объект полностью статический и не имеет динамических методов. Это позволяет исключить его инициализацию и излишнее оформлениее singleton'а.

Методы объекта ENGINE

* register_interface - регистрация нового интерфейса (функции) объекта ENGINE или чистка, если второй параметр пуст. Результатом будет предыдущее значение обработчика, для возможности его "горячей" подмены при необходимости
* exec (callable,args) - выполнить функцию, с предложенными параметрами.
* error - стандарное сообщение об ошибке. Не смотря на то, что метод статический - его можно переопределить стандартным (register_interface) образом

## расширения ENGINE

Некоторые расширения ENGINE признаны необходимыми в любом случае (базовыми) и они непосредственно внедрены в текст скрипта на этапе сборки проекта, однако, для лучшего понимания, можно рассматривать их как отдельные приложения. Тем более, что каждому такому механизму соответствует отдельный файл исходников.

### расширение autoload (базовое)

Расширение предоставляет возможность приложению подключать классы по имени класса. Никаких дополнительных интерфейсных методов оно не предоставляет. Поиск нового класса ведется в каталоге `plugins`

### расширение shutdown (базовое)

Оппределяет метод _shutdown скелетного объекта и включает в себя shutdown-код для всех статически интегрированных расширений. Регистрирует shutdown как стандартный shutdown-callback в php

### расширение events

Расширение предоставляет интерфейс для реализации event-based архитектуры приложения.

Добавлены методы

* register_event_handler - регистрация обработчика событий. Обработчики событий - стандартноустроенные функции, вызывающиеся в порядке регистрации.
* unregister_event_hanler - убрать обработчик события
* trigger_event - вызвать все зарегистрированные обработчики.

Вызов "событий" предполагается явно, методом `ENGINE::trigger`.

Поддерживается установка обработчика событий в 3 позициях - в начало, как обычно, и после всех обработчиков - в конец очереди обработчиков этого события. Фактически, за одним событием закрепляется 3 очереди событий.

### расширение options (базовое)

Расширение предлагает дисциплину работы с параметрами. Всем незаинтресованным приложениям предоставляется интерфейс `ENGINE::option` для получения и модификации значения переменных.

* `ENGINE::option(name,default='')` - выдать значение параметра. Если параметра нет - использовать значение по умолчанию.
* `ENGINE::set_option(name,value,$transport)` - установить значения параметра
* `ENGINE::set_option(array('param'=>'value','param2'=>'value2'))`  - установка списка параметров
* `ENGINE::set_option(array('param','param2'=>'value2'),$transport)`  - установка списка параметров для выделенного транспорта (способа хранения параметров). Установленные таким образом параметры помечаются как обрабатываемые этим транспортом. Если параметра нет - он только помечается, без реального создания.
* `$value = ENGINE::option('param')` - получение значения параметра
* `ENGINE::options()`  - сохранить кэшированные данные, если надо. Опрерация не нужна в обычной жизни, так как операция сохранения вызывается в деструкторе тех объектов, которые в этом нуждаются.

Предполагаются следующие варианты транспорта

* явное указание параметра в PHP   (пустой транспорт)
* хранение значения в базе   - `db` (не реализовано)
* хранения в INI файле  - `ini|NAME` (не реализовано) NAME - имя файла
* хранение в VAR_EXPORT изображении массива  `varexport|NAME`
* хранение в сессии   `session`
* и так далее

Реализация этих и дополнительных способов работы с параметрами находится в отдельных файлах в каталоге plugins готовой сборки.

###Список предопределенных параметров

`action` - действие системы. Первоначально устанавливается роутером, может корректироваться системой авторизации, ets.

`engine.classes` - массив переименования классов. Ключ - имя класса, значение - путь до файла с описанием класса.

    ENGINE::set_option('engine.classes',array(
        'tournament'=>'engine/tournaments.php',
        'plugin'=>'engine/engine.php',
        'engine_Main'=>'engine/syspar.php',
        'darts_Main'=>'engine/darts.php',
        'template_compiler'=>'engine/compiler.class.php',
        'ml_plugin'=>'engine/news.php',
        'form'=>'engine/html.class.php',
        'Auth'=>'engine/rights.php',
        'template_compiler'=>'compiler.class.php',
    ));

`engine.sessionname` - имя сессии для переопределения.

## идеология CMS

система представлена в виде классов, сгруппированных в несколько каталогов

* каталог системы - каталог, в котором находится актуальный на данный момент релиз CMS какой то версии.
* каталог сайта - доработанные и/или унаследованные файлы, расширяющие систему в интересах текущей реализации.
* каталог шаблонов - результат трансляции шаблонов сайта.
* web-каталог - хранилище web-содержимого, Javascript, css, картинки другие web-рессурсы.

Первые 2 каталога поддерживают одинаковую внутреннюю структуру, так как поиск классов идет по очереди сначала во втором, затем в первом каталоге. Каталог шаблонов находится в каталоге сайта. Сами шаблоны располагаются в удобных, с точки зрения структуры, местах.

Разбивка на каталоги определяется в расширении `autoload`. Все имена классов даны относительно "базовых" каталогов. Поиск классов происходит сначала в каталоге сайта, затем в каталоге системы.

Все достаточные для работы системы определения содержатся в этом коде

    define('INDEX_DIR',dirname(__FILE__));
    define('SYSTEM_PATH',realpath(INDEX_DIR.'/../system'));
    define('SITE_PATH',realpath(INDEX_DIR.'/../site'));
    define('TEMPLATE_PATH',realpath(SITE_PATH.'/template'));

## установка расширений системы

Связь ядра с расширениями описана в массиве параметров.

Каждое расширение, любой степени интеграции, обязано предоставить список параметров, связывающих его с системой. Этот список может быть в виде xml файла или в виде массива конфигурации. При установке/удалении/отключении расширения, вместе с ним удаляются и соответсвующие ему параметры.

Различают несколько уровней расширений системы
### компонент CMS

Комплект файлов,

## Ядро

Основным связующим элементом CMS является объект ENGINE. Объект считается самодостаточным и не подлежит модификации или наследованию от проекта к проекту, только от версии к версии. Вся уникальность каждого проекта основывается на наборе расширений, внедряемых в ENGINE.
Объект ядра связывает между собой приложения, обеспечивает вызов нужных участков приложения в нужные моменты, инкапсулирует в себе методы работы с параметрами приложения. Хорошим тоном, при программировании приложений, будет использование только этого объекта для связи с остальными приложениями.

###Средства отладки

функция debug выводит список переданных параметров в виде html-комментариев. Комментарий снабжается информацией о месте вызова функции debug.

функции hasFlag и initFlag - функции отладки.

встретив параметр `testing=xxx,yyy,zzz` в строке GET, функция initFlag формирует куку `testing` и записывет в нее получившийся набор флагов. Возможно указание `testing=+xxx` - в этом случае в набор флагов будет добавлен новый, `testing=-xxx` - в этом случае флаг будет исключен из списка флагов.

Проверяется наличие флагов в функции `hasFlag('xxx')`.

Флаг `norelock` используется при автоматической преадресации при обработке форм. Рекомендуется использовать его и в других случаях автоматической переадресации. Вместо `header('location...` будет выведена ссылка на следующую страницу. Удобно для просмотра отладочной информации, выведенной обработчиком формы.

Флаг `database` используется для вывода в отладочные сообщения всех запросов к базе данных.

Флаг `getfromhome` используется для отработки 404 иссключения. Для локальной отладки при отработке 404 события, осуществляется подгрузка отсутствующего изображения-рессурса с исходного сайта. пример lapsi.com/system/Main.php|do_404