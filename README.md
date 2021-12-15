## Laravel Starter (PHP 8.1 + Laravel 8 + Octane + Swoole + Supervisor + Docker Compose)

Bu çalışmayı kendi projelerimde sıklıkla oluşturduğum kalıbı kolayca tekrar oluşturabilmek için yaptım.

### Nasıl Çalışır
3 farklı yöntemle çalıştırılabilir.

#### Artisan İle
    php artisan serve

### Build
    docker build -t laravel-octane .

### Docker
    docker run -p 9000:9000 --rm laravel-octane:latest

### Docker Compose
    docker-compose up -d --build
    docker-compose up -d
    docker-compose down

### Docker Remove (volumes, images, etc.)
    docker-compose down --rmi all -v --remove-orphans

#### Octane İle
    yarn install
    php artisan octane:start --watch

#### Laravel Artisan İşlemleri

##### Migrate
    docker-compose exec App php artisan migrate

### Yapılacaklar

- [ ] JWT Auth
- [ ] Docker
- [x] Octane
- [x] Swoole
- [ ] Redis Session
- [ ] Redis Cache
- [ ] Redis Queue
- [ ] Redis Default
- [ ] WebSocket
- [ ] Pusher
- [ ] Modüler Yapı
- [ ] Gelişmiş Yetkilendirme
- [x] Vue Arayüz
- [ ] NGinX Reverse Proxy
- [x] IDE Helper
- [x] Swoole Helper
- [ ] Development ve Production php.ini
- [ ] Log dışarı yazılmalı
- [ ] MySQL
- [ ] MariaDB
- [ ] PostgreSQL
- [ ] Swoole + Redis
- [ ] Xdebug
- [ ] Memcached
