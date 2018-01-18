# compendium

## Структура

* **[scheme.sql](scheme.sql)** - дамп БД без рабочих данных
* **[parser.php](parser.php)** — скрипт для парсинга
* var/ - папка для временных лог-файлов. Папка и файл var/error.log создаються при возникновении ошибки
* **[config.php](includes/config/config.php)** - конфиг файл в который необходимо будет внести параметры доступа mysql

* **[index.html](index.html)** - файл интерфейс
* **[public/](public/)** - папка с css и js ресурсами.
* **[router.php](router.php)** - файл для приёма api запросов

* **[includes](includes/):**
   * **[api](includes/api/):** - контроллеры для обработки api запросов
   * **[classes](includes/classes/):** - общие классы
   * **[functions](includes/functions/):** - общие функции
   * **[libs](includes/libs/):** библиотеки
   * **[app.php](includes/app.php):** - подключение к БД, подключение функций
   * **[loader.php](includes/loader.php):** - autoload классов


## Последовательность

* Клонирование файлов на репозиторий
* Импорт scheme.sql в базу
* Запуск скрипта parcer.php
* Отображение результатов на index.html
