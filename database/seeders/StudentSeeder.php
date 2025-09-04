<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Alfiana Nur Sadevi
        Student::create([
            'nama'          => 'Alfiana Nur Sadevi',
            'nis'           => '00719366542',
            'jenis_kelamin' => 'P',
            'tempat_lahir'  => 'Lamongan',
            'tanggal_lahir' => '2007-01-02',
            'alamat'        => 'Desa Bringkang, Kec. Menganti, Kab. Gresik',
            'classroom_id'  => 1,
            'email'         => 'alfiana@gmail.com',
            'telepon'       => '6289531234567',
            'status'        => 'Aktif',
            'nama_ayah'     => 'Sutrisno Wijaya',
            'telepon_ayah'  => '6285212345678',
            'nama_ibu'      => 'Sulastri Dewi',
            'telepon_ibu'   => '6287812345678',
            'foto'          => null,
        ]);

        // 2. Bagas Fawaz Wahyudi
        Student::create([
            'nama'          => 'Bagas Fawaz Wahyudi',
            'nis'           => '0089092060',
            'jenis_kelamin' => 'L',
            'tempat_lahir'  => 'Surabaya',
            'tanggal_lahir' => '2008-02-29',
            'alamat'        => 'Perumahan Green Menganti blok A2/08 RT16 RW 8 Kelurahan Drancang, Menganti, Gresik',
            'classroom_id'  => 1,
            'email'         => 'bagas@gmail.com',
            'telepon'       => '628566485189',
            'status'        => 'Aktif',
            'nama_ayah'     => 'Hendro Santoso',
            'telepon_ayah'  => '6285212345678',
            'nama_ibu'      => 'Rini Kartika',
            'telepon_ibu'   => '6287712345678',
            'foto'          => null,
        ]);

        // 3. Bunga Auliya Agustina
        Student::create([
            'nama'          => 'Bunga Auliya Agustina',
            'nis'           => '0083085020',
            'jenis_kelamin' => 'P',
            'tempat_lahir'  => 'Gresik',
            'tanggal_lahir' => '2008-08-29',
            'alamat'        => 'Dusun Gempol RT.03 RW.04 Desa Lampah, Kedamean, Gresik',
            'classroom_id'  => 1,
            'email'         => 'bunga@gmail.com',
            'telepon'       => '6285608669658',
            'status'        => 'Aktif',
            'nama_ayah'     => 'Slamet Widodo',
            'telepon_ayah'  => '6285312345678',
            'nama_ibu'      => 'Dewi Anggraini',
            'telepon_ibu'   => '6287812345678',
            'foto'          => null,
        ]);

        // 4. Elsya Syahrani Aulia Putri
        Student::create([
            'nama'          => 'Elsya Syahrani Aulia Putri',
            'nis'           => '0077456300',
            'jenis_kelamin' => 'P',
            'tempat_lahir'  => 'Gresik',
            'tanggal_lahir' => '2007-08-04',
            'alamat'        => 'Dusun Pranti RT 01 RW 04 Desa Pranti, Menganti, Gresik',
            'classroom_id'  => 1,
            'email'         => 'elsya@gmail.com',
            'telepon'       => '6285101695107',
            'status'        => 'Aktif',
            'nama_ayah'     => 'Rahmat Hidayat',
            'telepon_ayah'  => '6285712345678',
            'nama_ibu'      => 'Lestari Wulandari',
            'telepon_ibu'   => '6289112345678',
            'foto'          => null,
        ]);

        // 5. Indriani
        Student::create([
            'nama'          => 'Indriani',
            'nis'           => '0083244230',
            'jenis_kelamin' => 'P',
            'tempat_lahir'  => 'Gresik',
            'tanggal_lahir' => '2008-06-06',
            'alamat'        => 'Dusun Sidowareg RT 16 RW 05 Desa Sidojangkung, Menganti, Gresik',
            'classroom_id'  => 1,
            'email'         => 'indriani@gmail.com',
            'telepon'       => '6288235683425',
            'status'        => 'Aktif',
            'nama_ayah'     => 'Sugeng Prabowo',
            'telepon_ayah'  => '6285219876543',
            'nama_ibu'      => 'Yuliana Sari',
            'telepon_ibu'   => '6287719876543',
            'foto'          => null,
        ]);

        // 6. Moch Zulfa Al Audy
        Student::create([
            'nama'          => 'Moch Zulfa Al Audy',
            'nis'           => '0078830364',
            'jenis_kelamin' => 'L',
            'tempat_lahir'  => 'Surabaya',
            'tanggal_lahir' => '2007-03-23',
            'alamat'        => 'Setro Wetan RT.01 RW.01 Desa Setro, Menganti, Gresik',
            'classroom_id'  => 1,
            'email'         => 'audy@gmail.com',
            'telepon'       => '6283117584955',
            'status'        => 'Aktif',
            'nama_ayah'     => 'Agus Suryanto',
            'telepon_ayah'  => '6285212348888',
            'nama_ibu'      => 'Murni Astuti',
            'telepon_ibu'   => '6287812348888',
            'foto'          => null,
        ]);


        // 7. Muhammad Mihros Qolby Al-amin
        Student::create([
            'nama'          => 'Muhammad Mihros Qolby Al-amin',
            'nis'           => '0074524972',
            'jenis_kelamin' => 'L',
            'tempat_lahir'  => 'Surabaya',
            'tanggal_lahir' => '2007-08-09',
            'alamat'        => 'Perumahan Graha Puncak Anomsari A3/22 RT.15 RW.05 Wedoroanom, Driyorejo, Gresik',
            'classroom_id'  => 1,
            'email'         => 'mihros@gmail.com',
            'telepon'       => '6288102631575',
            'status'        => 'Aktif',
            'nama_ayah'     => 'Ahmad Syafii',
            'telepon_ayah'  => '6285212221111',
            'nama_ibu'      => 'Wati',
            'telepon_ibu'   => '6287888881111',
            'foto'          => null,
        ]);

        // 8. Muhammad Raffi Ramadhan
        Student::create([
            'nama'          => 'Muhammad Raffi Ramadhan',
            'nis'           => '3078708654',
            'jenis_kelamin' => 'L',
            'tempat_lahir'  => 'Surabaya',
            'tanggal_lahir' => '2007-10-02',
            'alamat'        => 'Dusun Kutil RT.01 RW.01 Desa Gempol Kurung, Menganti, Gresik',
            'classroom_id'  => 1,
            'email'         => 'raffi@gmail.com',
            'telepon'       => '6281243175531',
            'status'        => 'Aktif',
            'nama_ayah'     => 'Yuda Prasetyo',
            'telepon_ayah'  => '6285217774444',
            'nama_ibu'      => 'Tutik Puspitasari',
            'telepon_ibu'   => '6287815552222',
            'foto'          => null,
        ]);

        // 9. Nabilah Galuh Candra Kirana
        Student::create([
            'nama'          => 'Nabilah Galuh Candra Kirana',
            'nis'           => '0071531442',
            'jenis_kelamin' => 'P',
            'tempat_lahir'  => 'Gresik',
            'tanggal_lahir' => '2007-07-07',
            'alamat'        => 'Gang Masjid RT.02 RW.02 Desa Bringkang, Menganti, Gresik',
            'classroom_id'  => 1,
            'email'         => 'nabilah@gmail.com',
            'telepon'       => '6285815140275',
            'status'        => 'Aktif',
            'nama_ayah'     => 'Rudi Santoso',
            'telepon_ayah'  => '6285222221111',
            'nama_ibu'      => 'Tijah Kurniasih',
            'telepon_ibu'   => '6287812347777',
            'foto'          => null,
        ]);

        // 10. Nita Nur Fadilah
        Student::create([
            'nama'          => 'Nita Nur Fadilah',
            'nis'           => '0085935160',
            'jenis_kelamin' => 'P',
            'tempat_lahir'  => 'Gresik',
            'tanggal_lahir' => '2008-07-06',
            'alamat'        => 'Dusun Kendayaan RT.03 RW.07 Desa Lampah, Kedamean, Gresik',
            'classroom_id'  => 1,
            'email'         => 'nita@gmail.com',
            'telepon'       => '6281288458890',
            'status'        => 'Aktif',
            'nama_ayah'     => 'Pramudito',
            'telepon_ayah'  => '6285233339999',
            'nama_ibu'      => 'Dasih (Alm)',
            'telepon_ibu'   => null,
            'foto'          => null,
        ]);

        // 11. Novida Zahra Aulia
        Student::create([
            'nama'          => 'Novida Zahra Aulia',
            'nis'           => '0086791364',
            'jenis_kelamin' => 'P',
            'tempat_lahir'  => 'Gresik',
            'tanggal_lahir' => '2008-02-25',
            'alamat'        => 'Dusun Pranti RT.05 RW.05 Desa Pranti, Menganti, Gresik',
            'classroom_id'  => 1,
            'email'         => 'novida@gmail.com',
            'telepon'       => '628577592468',
            'status'        => 'Aktif',
            'nama_ayah'     => 'Rejo Pranoto',
            'telepon_ayah'  => '6285666663333',
            'nama_ibu'      => 'Sutianah',
            'telepon_ibu'   => '6287774442222',
            'foto'          => null,
        ]);

        // 12. Nur Habib Ramadhan
        Student::create([
            'nama'          => 'Nur Habib Ramadhan',
            'nis'           => '3309131509070002',
            'jenis_kelamin' => 'L',
            'tempat_lahir'  => 'Boyolali',
            'tanggal_lahir' => '2007-09-15',
            'alamat'        => 'Perumahan Golden Berry blok DD 10',
            'classroom_id'  => 1,
            'email'         => 'habib@example.com',
            'telepon'       => '081325133671',
            'status'        => 'Aktif',
            'nama_ayah'     => 'Ismail Pratama',
            'telepon_ayah'  => '6285211112233',
            'nama_ibu'      => 'Mariana Putri',
            'telepon_ibu'   => '6287811112233',
            'foto'          => null,
        ]);

        // 13. Nur Laily Rahma Dhini
        Student::create([
            'nama'          => 'Nur Laily Rahma Dhini',
            'nis'           => '3525135609070003',
            'jenis_kelamin' => 'P',
            'tempat_lahir'  => 'Gresik',
            'tanggal_lahir' => '2007-09-26',
            'alamat'        => 'Dusun Bongso Wetan RT.014 RW.008 Desa Pengalangan Kec. Menganti Kab. Gresik',
            'classroom_id'  => 1,
            'email'         => 'dhini@example.com',
            'telepon'       => '082122983652',
            'status'        => 'Aktif',
            'nama_ayah'     => 'Sugeng Raharjo',
            'telepon_ayah'  => '6285211113344',
            'nama_ibu'      => 'Wanti Kusuma',
            'telepon_ibu'   => '6287811113344',
            'foto'          => null,
        ]);

        // 14. Nurul Izzah Lailatul Maghfiroh
        Student::create([
            'nama'          => 'Nurul Izzah Lailatul Maghfiroh',
            'nis'           => '3517036906070004',
            'jenis_kelamin' => 'P',
            'tempat_lahir'  => 'Jombang',
            'tanggal_lahir' => '2007-06-29',
            'alamat'        => 'Desa Boteng RT. 12 RW. 04 Kec. Menganti Kab. Gresik',
            'classroom_id'  => 1,
            'email'         => 'izzah@example.com',
            'telepon'       => '083129752807',
            'status'        => 'Aktif',
            'nama_ayah'     => 'Ahmad Rochim',
            'telepon_ayah'  => '6285211114455',
            'nama_ibu'      => 'Siti Munawaroh',
            'telepon_ibu'   => '6287811114455',
            'foto'          => null,
        ]);

        // 15. Putri Amelia
        Student::create([
            'nama'          => 'Putri Amelia',
            'nis'           => '3525136604070001',
            'jenis_kelamin' => 'P',
            'tempat_lahir'  => 'Gresik',
            'tanggal_lahir' => '2007-04-15',
            'alamat'        => 'Dusun Sidowungu RT.13 RW.04 Desa Mboro Kec. Menganti Kab. Gresik',
            'classroom_id'  => 1,
            'email'         => 'amelia@example.com',
            'telepon'       => '087863094791',
            'status'        => 'Aktif',
            'nama_ayah'     => 'Rowidin Saputra',
            'telepon_ayah'  => '6285211115566',
            'nama_ibu'      => 'Sumainah Dewi',
            'telepon_ibu'   => '6287811115566',
            'foto'          => null,
        ]);

        // 16. Raditya Surya Lesmana
        Student::create([
            'nama'          => 'Raditya Surya Lesmana',
            'nis'           => '0074319293',
            'jenis_kelamin' => 'L',
            'tempat_lahir'  => 'Surabaya',
            'tanggal_lahir' => '2007-04-26',
            'alamat'        => 'Sidowungu RT.16 RW.4 Menganti Kab. Gresik',
            'classroom_id'  => 1,
            'email'         => 'radit@example.com',
            'telepon'       => '085859302536',
            'status'        => 'Aktif',
            'nama_ayah'     => 'Agus Prasetyo',
            'telepon_ayah'  => '6285212223344',
            'nama_ibu'      => 'Sri Lestari',
            'telepon_ibu'   => '6287812223344',
            'foto'          => null,
        ]);

        // 17. Retika Putri Megasari
        Student::create([
            'nama'          => 'Retika Putri Megasari',
            'nis'           => '0083198153',
            'jenis_kelamin' => 'P',
            'tempat_lahir'  => 'Kediri',
            'tanggal_lahir' => '2008-03-01',
            'alamat'        => 'Dusun Bongso Wetan RT.22 RW.08 Desa Pengalangan Kec. Menganti Kab. Gresik',
            'classroom_id'  => 1,
            'email'         => 'tika@example.com',
            'telepon'       => '08989734532',
            'status'        => 'Aktif',
            'nama_ayah'     => 'Heri Santoso',
            'telepon_ayah'  => '6285213334455',
            'nama_ibu'      => 'Ruhmawati Dewi',
            'telepon_ibu'   => '6287813334455',
            'foto'          => null,
        ]);

        // 18. Rival Nanda Diansyah
        Student::create([
            'nama'          => 'Rival Nanda Diansyah',
            'nis'           => '0072873627',
            'jenis_kelamin' => 'L',
            'tempat_lahir'  => 'Surabaya',
            'tanggal_lahir' => '2007-12-11',
            'alamat'        => 'JL. Made Selatan RT.01 RW.03',
            'classroom_id'  => 1,
            'email'         => 'rival@example.com',
            'telepon'       => '088217809899',
            'status'        => 'Aktif',
            'nama_ayah'     => 'Masdari Hidayat',
            'telepon_ayah'  => '6285214445566',
            'nama_ibu'      => 'Erna Susanti',
            'telepon_ibu'   => '6287814445566',
            'foto'          => null,
        ]);

        // 19. Zahrakirana Budi Febriyanti
        Student::create([
            'nama'          => 'Zahrakirana Budi Febriyanti',
            'nis'           => '0086229888',
            'jenis_kelamin' => 'P',
            'tempat_lahir'  => 'Tuban',
            'tanggal_lahir' => '2008-02-04',
            'alamat'        => 'Perum Puri Menganti Indah RT.38 RW.12 Desa Menganti, Kab. Gresik',
            'classroom_id'  => 1,
            'email'         => 'zahra@example.com',
            'telepon'       => '088989498707',
            'status'        => 'Aktif',
            'nama_ayah'     => 'Budi Utomo',
            'telepon_ayah'  => '6285215556677',
            'nama_ibu'      => 'Arsi Kurniawati',
            'telepon_ibu'   => '6287815556677',
            'foto'          => null,
        ]);
    }
}
