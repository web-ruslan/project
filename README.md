# project

Проект разрабатывался на виртуальном сервере с такими параметрами (.env)

APP_NAME=Project

APP_ENV=local

APP_KEY=base64:F88AOQCc8pp0miuc/t9mTRivyeVMEkW3gJE8wXIlS2w=

APP_DEBUG=true

APP_LOG_LEVEL=debug

APP_URL=http://project.dev/


DB_CONNECTION=mysql

DB_HOST=127.0.0.1

DB_PORT=3306

DB_DATABASE=project

DB_USERNAME=root

DB_PASSWORD=123


BROADCAST_DRIVER=log

CACHE_DRIVER=array

SESSION_DRIVER=file

QUEUE_DRIVER=sync


REDIS_HOST=127.0.0.1

REDIS_PASSWORD=null

REDIS_PORT=6379


FACEBOOK_CLIENT_ID=1732920243673867

FACEBOOK_CLIENT_SECRET=564464b145a21284dfa0ade392a3f550

CALLBACK_URL=http://project.dev/auth/facebook/callback


VKONTAKTE_KEY=6154265

VKONTAKTE_SECRET=iHrrb9me591u2rzQpBUW

VKONTAKTE_REDIRECT_URI=http://project.dev/auth/vkontakte/callback


Реализованы пункты 1-4.

"СТРАНИЦА 1" называется в меню "Login" (авторизация через логин+пароль, facebook, vkontakte, социальные аккаунты пишутся в БД в таблицу user_social_accounts). Авторизация реализована используя 5dmatweblogin/ajaxlogin, через фейсбук используя laravel/socialite, через vkontakte используя socialiteproviders/vkontakte

"СТРАНИЦА 2" называется в меню "Login whith GeoIP" (авторизация через логин+пароль, запись страны и даты успешного логина в БД в таблицу user_geoip_logs). Определение страны осуществляется при помощи torann/geoip

Пока не удалось победить п.5 т.к. пробовал написать простой сервер используя Ratchet, ZMQ, autobahn.min.js, но, как потом понял, текущая версия autobahn.js не работает с Ratchet. Ищу возможные варианты реализации...
