<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Инструкция развёртывния проекта


- Создать пустую папку для клонирования проекта
- Прописать в терминале в той же папке `git clone https://github.com/Zuev-Yaroslav/test-work-catalog.git` (убедитесь, что на вашем ПК установлен GIT)
- Создать дубликат файла .env.example и переименовать на .env
- В .env указать:
  * пароль {DB_PASSWORD}
  * базу данных {DB_DATABASE}
  * порт {DB_PORT}
  * {USER_NAME} - имя пользователя для создания
  * {USER_EMAIL} - email пользователя для создания
  * {USER_PASSWORD} - пароль пользователя для создания
  * {NGINX_PORT} - порт nginx для docker-compose

- Запускаем докер:
```` bash
docker-compose up -d
````
- Заходим в wb_api_app контейнер
```` bash
docker exec -it catalog_app bash
````
```` bash
composer update
php artisan key:generate
php artisan migrate --seed
npm i
npm run build
````

- Можно потом запустить npm run dev

Можно пользоваться
