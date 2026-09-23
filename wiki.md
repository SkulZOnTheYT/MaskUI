# MaskUI — Wiki Survival

Versi tetap **1.0.0**. Semua mask mendapat **Night Vision I**. Level berikut adalah level yang terlihat di game, bukan amplifier internal.

| Tier | Mask | Harga default | Peran | Efek tambahan |
| --- | --- | ---: | --- | --- |
| 1 | Skeleton | 5,000 | Eksplorasi darat, gua dan air | Speed II, Haste I, Water Breathing I |
| 2 | Zombie | 10,000 | Ketahanan dan pemulihan awal | Strength II, Resistance I, Regeneration I |
| 3 | Creeper | 15,000 | Mobilitas cepat dan pertahanan | Speed III, Jump Boost I, Resistance II |
| 4 | Piglin | 20,000 | Eksplorasi Nether | Speed II, Strength II, Resistance II, Fire Resistance I |
| 5 | Steve | 25,000 | Menambang dan membangun | Speed II, Haste III, Resistance II, Water Breathing I, Fire Resistance I |
| 6 | Wither Skeleton | 30,000 | Combat tingkat tinggi | Speed II, Strength III, Resistance II, Regeneration I, Absorption II, Fire Resistance I |
| 7 | Dragon | 35,000 | All-rounder survival terkuat | Speed III, Strength III, Haste III, Resistance III, Regeneration II, Absorption III, Fire Resistance I, Water Breathing I |

## Alasan pembagian efek

- **Skeleton:** Speed II, Haste I dan Water Breathing membantu eksplorasi tanpa harus menjadi petarung kuat.
- **Zombie:** Strength II, Resistance I dan Regeneration I memberi ketahanan serta pemulihan; tanpa penalti Slowness.
- **Creeper:** Speed III, Jump Boost I dan Resistance II untuk mobilitas. Lompatan dibuat rendah agar lebih mudah dikontrol.
- **Piglin:** Fire Resistance, Strength II dan Resistance II untuk menghadapi lingkungan Nether. Mask ini tidak memberikan kekebalan terhadap semua bahaya Nether atau membuat mob netral.
- **Steve:** Haste III, Water Breathing, Fire Resistance dan Resistance II untuk kegiatan menambang serta membangun. Tidak dibebani buff combat berlebihan.
- **Wither:** Strength III, Resistance II, Regeneration I dan Absorption II menjadi pilihan combat dengan ketahanan tinggi.
- **Dragon:** Speed III, Strength III, Haste III, Resistance III, Regeneration II dan Absorption III, ditambah Fire Resistance serta Water Breathing. Tetap kuat dan serbaguna, tetapi tidak mendapat Jump Boost tinggi atau Slow Falling permanen yang mengganggu pergerakan.

Tier menunjukkan peningkatan kemampuan keseluruhan dan cakupan utilitas, bukan berarti setiap efek mask murah selalu ada di mask mahal. Harga default tetap sama; bisa diatur di config.

## Memakai mask

Pegang mask lalu klik kanan di udara atau pada blok, dari tangan utama maupun off-hand. Slot helm harus kosong. Satu mask dipindahkan dari tangan tanpa menggandakan item, termasuk di Creative. Lepas melalui slot armor di inventory. Mask bertag tidak diletakkan menjadi blok saat klik kanan; kepala mob biasa tetap berperilaku normal.

Efek diperiksa setiap detik, berdurasi 30 detik dan diperbarui saat tersisa 20 detik untuk menghindari kedipan night vision. Efek milik mask dibersihkan sekitar satu detik setelah dilepas/diganti. Efek eksternal yang sedang aktif dibiarkan sampai berakhir. Absorption diperbarui bersama efek lain sehingga hati tambahan dapat terisi kembali pada refresh.

`/mask wiki`, shop, status, dan lore mask baru menggunakan definisi efek yang sama. Mask lama otomatis mendapat efek baru lewat tag item; teks lore lama belum otomatis ditulis ulang.

## Saldo dan jual kembali

Menu utama `/mask` menampilkan saldo pada ikon emerald di bagian atas. Klik saldo atau tombol Refresh untuk memperbarui tampilannya. Saldo dan kelayakan transaksi diperiksa lagi saat konfirmasi.

Buka tombol **Sell masks**, `/mask sell`, atau `/mask sell <mask>`. Pilih mask lalu konfirmasi nominal refund. Satu transaksi menjual satu item.

Syarat penjualan:

1. Mask dibeli melalui shop MaskUI setelah sistem pencatatan ini dipasang.
2. Siapa pun yang memegang item hasil pembelian boleh menjualnya; tidak harus pembeli asli.
3. Item dengan ID pembelian tersebut ada di inventory utama/hotbar atau off-hand. Lepas dari helm dahulu; item di cursor, chest, atau ender chest tidak dihitung.
4. Catatan pembelian masih aktif dan belum pernah dijual. Salinan item dengan ID sama tidak mendapat hak refund tambahan.

Default refund adalah **50% harga yang benar-benar dibayar**, dibulatkan turun ke dua desimal. Contoh: Skeleton dibeli 5.000, dijual 2.500 walaupun harga shop kemudian naik. Persentase diatur melalui `selling.refund-percent` (lebih dari 0 sampai 100); nilai tidak valid menonaktifkan refund. `selling.enabled: false` menonaktifkan jual kembali.

Mask gratis, hasil `/mask give`, kepala biasa, dan mask lama tanpa catatan pembelian tidak bisa dijual. Mask tersebut tetap bisa dipakai jika tag mask valid. Ini bukan sistem jual semua item berdasarkan material atau nama.

Jika pembayaran ditolak secara normal oleh economy provider, item dan hak jual dikembalikan. Jika server terputus atau hasil transaksi tidak dapat dipastikan, catatan pending dikunci untuk pemeriksaan admin dan tidak dibayar ulang otomatis. Lihat bagian pemulihan pada README.

Setiap perubahan efek berikutnya harus disertai pembaruan file ini.

Tombol Effect Guide di menu utama dihapus. Daftar efek tetap terlihat pada item shop dan melalui `/mask wiki`.

## Kompatibilitas tampilan

Target server 1.16.x–1.21.x. Pada versi tanpa Piglin Head bawaan, mask Piglin menggunakan Player Head dengan nama/tag Piglin. Efek tidak berubah; mask fallback tetap dikenali setelah upgrade server.
