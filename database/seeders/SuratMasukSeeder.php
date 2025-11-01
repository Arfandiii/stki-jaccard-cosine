<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SuratMasuk;

class SuratMasukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nomor_surat'=>'470/001/SH/2024','tanggal_surat'=>'2024-01-01','tanggal_terima'=>'2024-01-06','asal_surat'=>'Kelurahan Siantan Hilir - Kecamatan Pontianak Utara','perihal'=>'Permintaan Bantuan Modal Usaha - Lampiran Dokumen (Karang Taruna) - No.358','jenis_surat'=>'Surat Laporan'],
            ['nomor_surat'=>'470/002/RAW/2024','tanggal_surat'=>'2024-01-02','tanggal_terima'=>'2024-01-11','asal_surat'=>'Kelurahan Rawa - Kecamatan Pontianak Timur','perihal'=>'Permohonan Pengaspalan Jalan - Koordinasi Lintas Sektoral (RW 03) - No.365','jenis_surat'=>'Surat Keputusan'],
            ['nomor_surat'=>'470/003/BL/2024','tanggal_surat'=>'2024-01-03','tanggal_terima'=>'2024-01-03','asal_surat'=>'Kelurahan Bansir Laut - Kecamatan Pontianak Tenggara','perihal'=>'Permintaan Informasi Statistik - Permohonan Informasi (Kelompok PKK) - No.457','jenis_surat'=>'Surat Pemberitahuan'],
            ['nomor_surat'=>'470/004/SB/2024','tanggal_surat'=>'2024-01-05','tanggal_terima'=>'2024-01-12','asal_surat'=>'Kelurahan Sungai Beliung - Kecamatan Pontianak Barat','perihal'=>'Permohonan Fasilitasi Pelatihan - Koordinasi Lintas Sektoral (RT 02) - No.437','jenis_surat'=>'Surat Laporan'],
            ['nomor_surat'=>'470/005/BML/2024','tanggal_surat'=>'2024-01-06','tanggal_terima'=>'2024-01-07','asal_surat'=>'Kelurahan Benua Melayu Laut - Kecamatan Pontianak Selatan','perihal'=>'Permohonan Bantuan Pendidikan - Tindak Lanjut (Kelompok PKK) - No.34','jenis_surat'=>'Surat Undangan'],
            ['nomor_surat'=>'470/006/SB/2024','tanggal_surat'=>'2024-01-06','tanggal_terima'=>'2024-01-06','asal_surat'=>'Kelurahan Siantan Barat - Kecamatan Pontianak Utara','perihal'=>'Permintaan Surat Rekomendasi - Agenda Bulanan (RT 01) - No.585','jenis_surat'=>'Surat Pemberitahuan'],
            ['nomor_surat'=>'470/007/SH/2024','tanggal_surat'=>'2024-01-07','tanggal_terima'=>'2024-01-11','asal_surat'=>'Kelurahan Siantan Hulu - Kecamatan Pontianak Timur','perihal'=>'Permohonan Renovasi Balai RW - Pengajuan Tahun Anggaran (RT 01) - No.135','jenis_surat'=>'Surat Pengajuan'],
            ['nomor_surat'=>'470/008/SUN/2024','tanggal_surat'=>'2024-01-07','tanggal_terima'=>'2024-01-15','asal_surat'=>'Kelurahan Sungaijawi - Kecamatan Pontianak Kota','perihal'=>'Surat Rekomendasi Pendirian Posyandu - Survey Lapangan (Puskesmas Pontianak Selatan) - No.564','jenis_surat'=>'Surat Laporan'],
            ['nomor_surat'=>'470/009/BD/2024','tanggal_surat'=>'2024-01-09','tanggal_terima'=>'2024-01-18','asal_surat'=>'Kelurahan Bansir Darat - Kecamatan Pontianak Tenggara','perihal'=>'Permohonan Bantuan Perbaikan Rumah - Agenda Bulanan (RT 01) - No.288','jenis_surat'=>'Surat Pengajuan'],
            ['nomor_surat'=>'470/010/BD/2024','tanggal_surat'=>'2024-01-09','tanggal_terima'=>'2024-01-09','asal_surat'=>'Kelurahan Bansir Darat - Kecamatan Pontianak Tenggara','perihal'=>'Permohonan Pembuatan KTP Elektronik - Untuk Verifikasi (Kelompok PKK) - No.623','jenis_surat'=>'Surat Permintaan Data'],
            ['nomor_surat'=>'470/011/SB/2024','tanggal_surat'=>'2024-01-10','tanggal_terima'=>'2024-01-14','asal_surat'=>'Kelurahan Siantan Barat - Kecamatan Pontianak Utara','perihal'=>'Permohonan Pengaspalan Jalan - Usulan Masyarakat (RW 03) - No.128','jenis_surat'=>'Surat Pemberitahuan'],
            ['nomor_surat'=>'470/012/MAR/2024','tanggal_surat'=>'2024-01-10','tanggal_terima'=>'2024-01-17','asal_surat'=>'Kelurahan Mariana - Kecamatan Pontianak Kota','perihal'=>'Permintaan Fasilitas Air Bersih - Permohonan Informasi (Puskesmas Pontianak Selatan) - No.255','jenis_surat'=>'Surat Permintaan Data'],
            ['nomor_surat'=>'470/013/KB/2024','tanggal_surat'=>'2024-01-10','tanggal_terima'=>'2024-01-15','asal_surat'=>'Kelurahan Kota Baru - Kecamatan Pontianak Selatan','perihal'=>'Laporan Kegiatan Olahraga - Notifikasi (Sekolah Dasar Negeri 5) - No.322','jenis_surat'=>'Surat Pengaduan'],
            ['nomor_surat'=>'470/014/MAR/2024','tanggal_surat'=>'2024-01-10','tanggal_terima'=>'2024-01-17','asal_surat'=>'Kelurahan Mariana - Kecamatan Pontianak Kota','perihal'=>'Permohonan Pengadaan Buku Perpustakaan - Permintaan Data Tambahan (RT 02) - No.416','jenis_surat'=>'Surat Keputusan'],
            ['nomor_surat'=>'470/015/SRL/2024','tanggal_surat'=>'2024-01-13','tanggal_terima'=>'2024-01-17','asal_surat'=>'Kelurahan Sungai Raya Luar - Kecamatan Pontianak Timur','perihal'=>'Permintaan Data Pendidikan - Usulan Masyarakat (RW 03) - No.214','jenis_surat'=>'Surat Pemberitahuan'],
            ['nomor_surat'=>'470/016/SB/2024','tanggal_surat'=>'2024-01-15','tanggal_terima'=>'2024-01-20','asal_surat'=>'Kelurahan Sungai Beliung - Kecamatan Pontianak Barat','perihal'=>'Laporan Pelanggaran Tata Ruang - Tindak Lanjut (Sekolah Dasar Negeri 5) - No.132','jenis_surat'=>'Surat Laporan'],
            ['nomor_surat'=>'470/017/DAR/2024','tanggal_surat'=>'2024-01-17','tanggal_terima'=>'2024-01-20','asal_surat'=>'Kelurahan Daratsekip - Kecamatan Pontianak Kota','perihal'=>'Laporan Keamanan Lingkungan - Tindak Lanjut (RT 01) - No.158','jenis_surat'=>'Surat Pemberitahuan'],
            ['nomor_surat'=>'470/018/SB/2024','tanggal_surat'=>'2024-01-17','tanggal_terima'=>'2024-01-27','asal_surat'=>'Kelurahan Siantan Barat - Kecamatan Pontianak Utara','perihal'=>'Laporan Kegiatan Olahraga - Usulan Masyarakat (Kelompok PKK) - No.38','jenis_surat'=>'Surat Undangan'],
            ['nomor_surat'=>'470/019/BML/2024','tanggal_surat'=>'2024-01-17','tanggal_terima'=>'2024-01-22','asal_surat'=>'Kelurahan Benua Melayu Laut - Kecamatan Pontianak Selatan','perihal'=>'Permintaan Bantuan Medis - Notifikasi (Kelompok PKK) - No.43','jenis_surat'=>'Surat Undangan'],
            ['nomor_surat'=>'470/020/BBD/2024','tanggal_surat'=>'2024-01-18','tanggal_terima'=>'2024-01-26','asal_surat'=>'Kelurahan Bangka Belitung Darat - Kecamatan Pontianak Tenggara','perihal'=>'Laporan Kegiatan Sosial - Permohonan Informasi (Puskesmas Pontianak Selatan) - No.190','jenis_surat'=>'Surat Laporan'],
            ['nomor_surat'=>'470/021/PAL/2024','tanggal_surat'=>'2024-01-18','tanggal_terima'=>'2024-01-23','asal_surat'=>'Kelurahan Pallima - Kecamatan Pontianak Barat','perihal'=>'Surat Permohonan Kerjasama - Pengajuan Tahun Anggaran (Puskesmas Pontianak Selatan) - No.104','jenis_surat'=>'Surat Permintaan Data'],
            ['nomor_surat'=>'470/022/BBL/2024','tanggal_surat'=>'2024-01-18','tanggal_terima'=>'2024-01-26','asal_surat'=>'Kelurahan Bangka Belitung Laut - Kecamatan Pontianak Tenggara','perihal'=>'Surat Keberatan - Tindak Lanjut (Puskesmas Pontianak Selatan) - No.189','jenis_surat'=>'Surat Tugas'],
            ['nomor_surat'=>'470/023/BL/2024','tanggal_surat'=>'2024-01-18','tanggal_terima'=>'2024-01-24','asal_surat'=>'Kelurahan Bansir Laut - Kecamatan Pontianak Tenggara','perihal'=>'Laporan Kegiatan Keagamaan - Agenda Bulanan (RT 01) - No.528','jenis_surat'=>'Surat Tugas'],
            ['nomor_surat'=>'470/024/SD/2024','tanggal_surat'=>'2024-01-21','tanggal_terima'=>'2024-01-26','asal_surat'=>'Kelurahan Sungaijawi Dalam - Kecamatan Pontianak Barat','perihal'=>'Permintaan Data Ketenagakerjaan - Usulan Masyarakat (Sekolah Dasar Negeri 5) - No.403','jenis_surat'=>'Surat Pengaduan'],
            ['nomor_surat'=>'470/025/SH/2024','tanggal_surat'=>'2024-01-23','tanggal_terima'=>'2024-02-01','asal_surat'=>'Kelurahan Siantan Hilir - Kecamatan Pontianak Utara','perihal'=>'Permohonan Bantuan Kesehatan Ibu dan Anak - Koordinasi Lintas Sektoral (Karang Taruna) - No.447','jenis_surat'=>'Surat Pemberitahuan'],
            ['nomor_surat'=>'470/026/HM/2024','tanggal_surat'=>'2024-01-24','tanggal_terima'=>'2024-01-28','asal_surat'=>'Kelurahan Haji Mangku - Kecamatan Pontianak Timur','perihal'=>'Permintaan Informasi Peraturan Daerah - Untuk Verifikasi (Karang Taruna) - No.155','jenis_surat'=>'Surat Tugas'],
            ['nomor_surat'=>'470/027/SRD/2024','tanggal_surat'=>'2024-01-27','tanggal_terima'=>'2024-02-06','asal_surat'=>'Kelurahan Sungai Raya Dalam - Kecamatan Pontianak Timur','perihal'=>'Permohonan Pemasangan SPAL - Surat Resmi (Puskesmas Pontianak Selatan) - No.424','jenis_surat'=>'Surat Pengaduan'],
            ['nomor_surat'=>'470/028/SJ/2024','tanggal_surat'=>'2024-01-27','tanggal_terima'=>'2024-02-02','asal_surat'=>'Kelurahan Sungai Jawi - Kecamatan Pontianak Utara','perihal'=>'Permintaan Sertifikat Tanah - Tindak Lanjut (RT 02) - No.646','jenis_surat'=>'Surat Pemberitahuan'],
            ['nomor_surat'=>'470/029/BML/2024','tanggal_surat'=>'2024-01-29','tanggal_terima'=>'2024-02-05','asal_surat'=>'Kelurahan Benua Melayu Laut - Kecamatan Pontianak Selatan','perihal'=>'Surat Keberatan - Tindak Lanjut (Sekolah Dasar Negeri 5) - No.609','jenis_surat'=>'Surat Pemberitahuan'],
            ['nomor_surat'=>'470/030/BML/2024','tanggal_surat'=>'2024-01-29','tanggal_terima'=>'2024-02-03','asal_surat'=>'Kelurahan Benua Melayu Laut - Kecamatan Pontianak Selatan','perihal'=>'Permintaan Bantuan Medis - Pelaporan Periodik (RW 03) - No.32','jenis_surat'=>'Surat Laporan'],
            ['nomor_surat'=>'470/031/PT/2024','tanggal_surat'=>'2024-01-30','tanggal_terima'=>'2024-01-31','asal_surat'=>'Kelurahan Parit Tokaya - Kecamatan Pontianak Selatan','perihal'=>'Permohonan Pengadaan Buku Perpustakaan - Koordinasi Lintas Sektoral (Sekolah Dasar Negeri 5) - No.80','jenis_surat'=>'Surat Pengaduan'],
            ['nomor_surat'=>'470/032/SUN/2024','tanggal_surat'=>'2024-02-03','tanggal_terima'=>'2024-02-12','asal_surat'=>'Kelurahan Sungaibangkong - Kecamatan Pontianak Kota','perihal'=>'Permintaan Pemindahan Lokasi Pasar - Notifikasi (Kelompok PKK) - No.61','jenis_surat'=>'Surat Rekomendasi'],
            ['nomor_surat'=>'470/033/SD/2024','tanggal_surat'=>'2024-02-03','tanggal_terima'=>'2024-02-10','asal_surat'=>'Kelurahan Sungaijawi Dalam - Kecamatan Pontianak Barat','perihal'=>'Permintaan Audit Administrasi - Notifikasi (Kelompok PKK) - No.263','jenis_surat'=>'Surat Pemberitahuan'],
            ['nomor_surat'=>'470/034/SB/2024','tanggal_surat'=>'2024-02-05','tanggal_terima'=>'2024-02-13','asal_surat'=>'Kelurahan Siantan Barat - Kecamatan Pontianak Utara','perihal'=>'Permohonan Data Kependudukan - Pengajuan Tahun Anggaran (Puskesmas Pontianak Selatan) - No.390','jenis_surat'=>'Surat Keputusan'],
            ['nomor_surat'=>'470/035/DAR/2024','tanggal_surat'=>'2024-02-06','tanggal_terima'=>'2024-02-11','asal_surat'=>'Kelurahan Daratsekip - Kecamatan Pontianak Kota','perihal'=>'Surat Pemberitahuan Kegiatan - Permohonan Mendesak (Karang Taruna) - No.600','jenis_surat'=>'Surat Laporan'],
            ['nomor_surat'=>'470/036/SH/2024','tanggal_surat'=>'2024-02-07','tanggal_terima'=>'2024-02-13','asal_surat'=>'Kelurahan Siantan Hulu - Kecamatan Pontianak Timur','perihal'=>'Surat Pemberitahuan Perubahan Kontak - Usulan Masyarakat (Kelompok PKK) - No.127','jenis_surat'=>'Surat Pengaduan'],
            ['nomor_surat'=>'470/037/SUN/2024','tanggal_surat'=>'2024-02-07','tanggal_terima'=>'2024-02-17','asal_surat'=>'Kelurahan Sungaibangkong - Kecamatan Pontianak Kota','perihal'=>'Laporan Keamanan Lingkungan - Usulan Masyarakat (Kelompok PKK) - No.49','jenis_surat'=>'Surat Rekomendasi'],
            ['nomor_surat'=>'470/038/RAW/2024','tanggal_surat'=>'2024-02-08','tanggal_terima'=>'2024-02-10','asal_surat'=>'Kelurahan Rawa - Kecamatan Pontianak Timur','perihal'=>'Permohonan Bantuan Kesehatan Ibu dan Anak - Permintaan Prioritas (RT 01) - No.126','jenis_surat'=>'Surat Laporan'],
            ['nomor_surat'=>'470/039/BBL/2024','tanggal_surat'=>'2024-02-08','tanggal_terima'=>'2024-02-14','asal_surat'=>'Kelurahan Bangka Belitung Laut - Kecamatan Pontianak Tenggara','perihal'=>'Permohonan Fasilitasi Pelatihan - Pengajuan Tahun Anggaran (Sekolah Dasar Negeri 5) - No.448','jenis_surat'=>'Surat Tugas'],
        ];

        foreach ($data as $row) {
            SuratMasuk::create($row);   // otomatis generate document_terms
        }
    }
}
