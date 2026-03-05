# Деплой проекта на сервер `46.19.65.188`

Этот проект является Symfony-приложением. Рабочий код приложения лежит в папке `releases/21`, поэтому для запуска на сервере нужно работать именно с ней.

## 1. Подключиться к серверу

```bash
ssh root@46.19.65.188
```

Если у вас не `root`, замените пользователя на своего.

## 2. Установить системные пакеты

Пример для Ubuntu/Debian:

```bash
apt update
apt install -y nginx git unzip curl software-properties-common
add-apt-repository ppa:ondrej/php -y
apt update
apt install -y php8.1 php8.1-cli php8.1-fpm php8.1-common php8.1-mbstring php8.1-xml php8.1-curl php8.1-intl php8.1-zip php8.1-pgsql php8.1-mysql
```

Проект требует PHP `8.1+`.

## 3. Установить Composer

```bash
cd /tmp
curl -sS https://getcomposer.org/installer -o composer-setup.php
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
composer --version
```

## 4. Создать папку проекта

```bash
mkdir -p /var/www/landshaft_site
cd /var/www/landshaft_site
```

## 5. Скачать проект с GitHub

```bash
git clone https://github.com/Vindma2903/landshaft_site.git .
```

## 6. Перейти в папку с реальным приложением

```bash
cd releases/21
```

Именно здесь находятся `composer.json`, `src`, `public`, `config`.

## 7. Установить PHP-зависимости

```bash
composer install --no-dev --optimize-autoloader
```

## 8. Настроить `.env.local`

Откройте файл:

```bash
nano .env.local
```

Проверьте и заполните:

```env
APP_ENV=prod
APP_SECRET=случайная_длинная_секретная_строка
DATABASE_URL="postgresql://dbuser:dbpass@127.0.0.1:5432/landshaft?serverVersion=15&charset=utf8"
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0
```

Если используете MySQL, строка будет другой. Главное: указать реальные логин, пароль, имя базы и правильный драйвер.

## 9. Создать базу данных

### Вариант для PostgreSQL

```bash
sudo -u postgres psql
```

Внутри `psql`:

```sql
CREATE DATABASE landshaft;
CREATE USER dbuser WITH PASSWORD 'dbpass';
GRANT ALL PRIVILEGES ON DATABASE landshaft TO dbuser;
\q
```

## 10. Создать таблицы в базе

В проекте папка `migrations` пустая, поэтому можно создать схему напрямую:

```bash
php bin/console doctrine:schema:update --force --env=prod
```

Если у вас есть готовый дамп базы со старого сервера, лучше импортировать его вместо этой команды.

## 11. Очистить кэш Symfony

```bash
php bin/console cache:clear --env=prod
```

## 12. Собрать фронтенд

Если папки `public/build` нет или она неактуальна, установите Node.js и соберите ассеты:

```bash
apt install -y nodejs npm
npm ci
npm run build
```

## 13. Выдать права на папки

```bash
chown -R www-data:www-data /var/www/landshaft_site
chmod -R 775 var
```

Если команда выполняется не из `releases/21`, всё равно права нужно выдать для проекта целиком.

## 14. Настроить Nginx

Создайте конфиг:

```bash
nano /etc/nginx/sites-available/landshaft_site
```

Вставьте:

```nginx
server {
    listen 80;
    server_name 46.19.65.188;

    root /var/www/landshaft_site/releases/21/public;
    index index.php;

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        internal;
    }

    location ~ \.php$ {
        return 404;
    }

    access_log /var/log/nginx/landshaft_access.log;
    error_log /var/log/nginx/landshaft_error.log;
}
```

Активируйте сайт:

```bash
ln -s /etc/nginx/sites-available/landshaft_site /etc/nginx/sites-enabled/landshaft_site
nginx -t
systemctl restart nginx
systemctl restart php8.1-fpm
```

## 15. Проверить запуск

Откройте:

```text
http://46.19.65.188
```

Если всё настроено правильно, сайт должен открыться.

## 16. Как обновлять проект после изменений

После `git push` в `main`:

```bash
ssh root@46.19.65.188
cd /var/www/landshaft_site
git pull origin main
cd releases/21
composer install --no-dev --optimize-autoloader
php bin/console cache:clear --env=prod
npm run build
chown -R www-data:www-data var public/build
systemctl restart php8.1-fpm
systemctl restart nginx
```

## 17. Что важно проверить заранее

- SSH-доступ на сервер открыт.
- Порт `80` не занят другим сайтом.
- База данных создана и доступна.
- В `.env.local` указаны реальные данные, а не заглушки.
- GitHub-репозиторий `https://github.com/Vindma2903/landshaft_site.git` содержит актуальный код.

## 18. Особенность этого репозитория

Сейчас в корне репозитория лежит не чистый исходный код, а структура деплоя: `current`, `releases`, `shared`, `.dep`.

Для дальнейшей поддержки удобнее вынести содержимое `releases/21` в корень отдельного чистого репозитория. Тогда деплой и обновления будут проще и понятнее.
