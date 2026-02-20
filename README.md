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
