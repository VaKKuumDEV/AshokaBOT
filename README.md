# AshokaBOT
Бот для обработки сообщений в социальной сети ВКонтакте.
С его помощью можно создавать различные механизмы работы, основанные на методах ВКонтакте API.

## Установка:
- Бот работает на версии PHP 7.3 и выше (если уже вышла)
- Для корректной работы требуется установка модулей php-curl, php-gd, php-yaml, php-json
- Необходимо перенести файлы "*bot-core.phar*" (или исходную директорию) и "*bot.php*" в рабочую папку
- Перейдите в рабочую папку, используя "*cd*"
- Выполните команду "*php bot.php*" в консоли
- Вам выведет, что бот выключился. Теперь вам нужно заполнить конфиг по пути "*configs/config.yml*", изменив поле "*access_token*" на ваш ключ от страницы
- Выполните команду "*php bot.php*" ещё раз

## Где взять токен?
Токен необходим для работы бота. Получить его можно различными методами. Важно отметить, что этот бот - страничный. Вы, конечно, можете использовать ключ доступа сообщества, но я не знаю, к чему это может привести. Токены получаются открытыми методами VK API. Лучше всего использовать токен, полученный методом прямой авторизации (DirectAuth). Получить токен для работы можно на сайте [vkhost.github.io](http://vkhost.github.io).

## Тестирование:
Протестировать бота можно на странице [Асоки Тано](http://vk.com/ashoka_zv) - напишите ей что-нибудь. Бот настроен так, что он будет обрабатывать только доступные ему команды, поэтому отправляйте команду `/статистика` для проверки работы.
