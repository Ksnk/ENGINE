#install - утилита для установки и системной поддерхки проекта.

Конструктивно представляет собой приложение-"мастер", с переходом от одного окна к другому. Каждое окно запрашивается с сервера. Каждое окно обрабатывается одним методом приложения. Все введенные данные сохраняются в специальном файле на сервере.

Различают 2 режима работы "мастера". Первая инсталляция и модификация системы.
Первая инсталляция заключается в начальной настройки всех веток сайта, заполнение сайта тестовыми данными и переход к админке. Модификация - позволяет устанавливать новые модули и настраивать систему.

Каждый модуль системы обязан наследоватся от класса engine_plugin. Модули могут иметь метод `get_install_info` - выдающий собственную порцию файла конфигурации. Метод получает логический параметр - админка. Если есть необходимость различать конфигурацию модуля для админки и сайта - его нужно анализировать.

  Модули могут методы install/uninstall для установки/удаления собственных данных.

Утилита служит для формирования конифгураций

  - конфигурация адмминки
  - конфигурация сайта
  - аккаунт базы данных

## стартовое окно - окно проверки системы

Автоматически, при старте системы производятся действия

- проверка хостинга на соответствие принятым нормам - php 5.3 и т.д.
- проверка, что система уже установлена, в этом случае, переход на окно входа в систему.
- проверка, что система не установлена, в это случае -

## окно параметров для входа в базу данных

Запрашивается параметры базы данных  - хост, порт, имя пользлвателя и т.д. В случае удачной проверки всех параметров - переход на окно установки модулей.

## окно настройки модулей админки

Сканируется системный каталог для поиска всех модулей системы. Строится дерево зависимостей классов друг от друга.
Список модулей системы выводится пользователю. Включенные в систему модули отмечены галочками и именами классов-представителей.
Можно поменять список модулей или  класс-представитель. В этом случае удаленные модули будут деинсталлированы, измененные - деинсталлированы-инсталлированы заново, добавленные - инсталлированы. Все данные этих модулей будут, соответственно, изменены.
По результатам работы окна формируется файл конфигурации админки.
Само действие по изменению данных будет производится в окне `изменение данных`.

## окно настройки модулей сайта

Модули, выбранные в предыдущем окне можно отключить/включить для работы на сайте. У некоторых из них можно выбрать других редставителей.

По результатам работы окна формируется файл конфигурации сайта.

## изменение данных

Выводится список изменений и запрашивается подтверждение пользователя на изменение данных. Действие необратимо

## окно входа в систему

Запрашивается логин-пароль для входа в систему. В случае удачи - открытие окон модификации системы.


