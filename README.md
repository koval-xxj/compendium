# compendium

## Структура

* scheme.sql - дамп БД без рабочих данных
* parser.php — скрипт для парсинга
* var/ - папка для временных лог-файлов. Папка и файл var/error.log создаються при возникновении ошибки
* includes/config/config.php - конфиг файл в который необходимо будет внести параметры доступа mysql

* index.html - файл интерфейс
* public/ - папка с css и js ресурсами.
* router.php - файл для приёма api запросов

* **[includes](includes/):**
   * **[api](includes/api/):** - контроллеры для обработки api запросов
   * **[classes](includes/classes/):** - общие классы
   * **[functions](includes/functions/):** - общие функции
   * **[libs](includes/libs/):** библиотеки
   * **[app.php](includes/app.php):** - подключение к БД, подключение функций
   * **[loader.php](includes/loader.php):** - фунция autoload классов
