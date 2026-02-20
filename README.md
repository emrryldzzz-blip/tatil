# Mini PHP CMS (MySQL)

Bu proje, istenen özelliklere göre hazırlanmış mini bir CMS örneğidir:
- MySQL veritabanı
- Admin paneli üzerinden kategori + yazı yönetimi
- Yazılarda öne çıkan görsel yükleme
- Anasayfada grid yazı listesi

## Kurulum

1. Veritabanını oluşturun:
```bash
mysql -u root -p < database.sql
```

2. `config.php` dosyasını (veya environment değişkenlerini) kendi MySQL bilgilerinize göre düzenleyin.

3. Projeyi çalıştırın:
```bash
php -S 0.0.0.0:8000
```

4. Tarayıcıda:
- Site: `http://localhost:8000`
- Admin: `http://localhost:8000/admin/login.php`

Varsayılan admin:
- kullanıcı adı: `admin`
- şifre: `admin123`

## Dosya Yapısı
- `index.php`: Grid blog anasayfası
- `post.php`: Yazı detay sayfası
- `admin/`: Tüm yönetim ekranları
- `includes/`: DB bağlantısı + yardımcı fonksiyonlar
- `uploads/`: Öne çıkan görsellerin saklandığı klasör


## Sık Karşılaşılan MySQL Hatası

Eğer kurulumda `#1071 - Specified key was too long; max key length is 1000 bytes` hatasını alırsanız, bu genelde eski MySQL/MariaDB sürümlerinde `utf8mb4 + UNIQUE VARCHAR(255)` kombinasyonundan kaynaklanır.

Bu repo içinde `posts.slug` alanı bu nedenle `VARCHAR(191)` olarak ayarlanmıştır ve hata çözülmüştür.
