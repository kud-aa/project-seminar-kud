# Задание 6.1 Шаблонизатор
Запуск:
```
docker-compose up
```

Импортирование дампа
```
cat dump.sql | docker exec -i mariadb1 /usr/bin/mysql -u arch --password=arch template
```
login: root
pass: arch
