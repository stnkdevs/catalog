# catalog
Простой книжный каталог (тестовое)
Страница hostname/project path/catalog  — пользователь может просматривать книги, фильтровать их по жанрам, авторам. 
А также оставить заявку которая прийдет на e-mail администратора.
Страница hostname/project path/catalog/admin — страница администратора. Apache аутентификация login: user, password: 1111.
Админ может редактировать книгу, а также добавлять новые книги.
Каталог выполнялся на чистом PHP без фреймворков.

1. Дамп базы данных catalog.sql (MySQL)

2. Папка catalog содержит проект. В файле config.php задаются параметры сервера и имя БД.
 
Переменная $config['admin_email'] задает почту куда отправляются e-mail. При отправке использовалась 

стандартная функция PHP mail(), настройки соответствующих директив в проекте нет, они должны быть в php.ini.

Если письмо не было доставлено оно записывается в файл admin/failed_mails.txt.

Переменная $config['root_uri'] определяет путь к содержимому относительно корневой директории хоста. 

Например, если страница каталога для пользователя доступна localhost/projects/catalog/index.php, то 

$config['root_uri']='/projects/catalog/' (первый слеш обязательно)

3. Страница каталога для пользователя доступна имя_хоста/.../index.php, а страница администратора как

 имя_хоста/.../admin/index.php

ПРИМЕЧАНИЕ. Для страницы администратора применяется аутентификация через .htaccess. 

Директива AuthUserFile  C:\server\data\php7\catalog\admin\.htpasswd будет отличаться полным путем.

Файл admin\.htpasswd хранит содержит учетную запись пользователя user с паролем 1111

4. Каталог также доступен глобально:

— страница для пользователя http://stankmess.000webhostapp.com/catalog/

— страница администратора http://stankmess.000webhostapp.com/catalog/admin/
