<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        Teacher::create([
            'nip' => '872929449269789443',
            'nama' => 'Drs. Nuripan, M.Pd.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '081111111111',
            'email' => 'nuripan@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Kepala Sekolah',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '325572716927802599',
            'nama' => 'Nur Qomari, M.Pd.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '082222222222',
            'email' => 'qomari@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Wakil Kepala Sekolah',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '820617609836683862',
            'nama' => 'Sutrisno, M.Pd.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '083333333333',
            'email' => 'guru3@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '241227592367987930',
            'nama' => 'Drs. Sudirdjo',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '084444444444',
            'email' => 'sudirjo@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '461280981678929321',
            'nama' => 'Drs. Dwi Jatmiko',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '085555555555',
            'email' => 'jatmiko@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '368218944117813332',
            'nama' => 'M. Hasin, S. Ag.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '086666666666',
            'email' => 'hasin@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '672726166445488151',
            'nama' => 'Wasis Supeno, S. Pd, MT.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '087777777777',
            'email' => 'wasis@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '383744041710533320',
            'nama' => 'Kokoh Indranto, ST.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '088888888888',
            'email' => 'kokoh@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '459081869174544916',
            'nama' => 'Adi Swandana, S. Pd.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089999999999',
            'email' => 'adi@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '302481679811565149',
            'nama' => 'Drs. Sokip',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '081010101010',
            'email' => 'sokip@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '994127308415276381',
            'nama' => 'Akhmad Ikhsan, M. Fil. I.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'ikhsan@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '112738920567834902',
            'nama' => 'Wandik Mashudi, S. Pd.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'wandik@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '887621459230158732',
            'nama' => 'Herlina Septiyorini, S. Si.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'setiyorini@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '563098127459002837',
            'nama' => 'Ghofur, S. Pd. I.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'gofur@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '745823910265873491',
            'nama' => 'Mufarohah, S. Pd. I.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'mufarohah@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '268392157483920145',
            'nama' => 'Pasiadi, S. Pd. I.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'pasiadi@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '917382645019283746',
            'nama' => 'Ervin Dwi Priyanto, S. Pd.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'ervin@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '385920176459283746',
            'nama' => 'Muchlis Warsito, S. Pd.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'muchlis@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '509283746592837465',
            'nama' => 'Yusfita Prawitasari, S. Pd.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'yusfita@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '673920581746592837',
            'nama' => 'Tulus Budi Hartoyo, M. Pd. 1',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'tulus@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '938471029384756192',
            'nama' => 'Nurul Huda S, Pd. I.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'huda@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '192837465092837465',
            'nama' => 'Rizky Aditya, S. E.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'rizky@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '746592837465092837',
            'nama' => 'Abdul Rochman, S. Pd.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'rochman@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '593847102938475610',
            'nama' => 'Abdul Rochim, S. Pd.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'rochim@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '849201938475610293',
            'nama' => 'M. Reza Pahlevi, S. Pd.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'reza@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '304958102938475610',
            'nama' => 'Nor Oktaviyanti, S. Pd.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'oktaviyanti@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '657483920174659283',
            'nama' => 'Putri Suhandari, S. Pd.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'putri@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '120394857610293847',
            'nama' => 'Miftakhul Hijriyah, S. Kom.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru28@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '574839201938475610',
            'nama' => 'Lailatul Miftachurriyah, S. Pd.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru29@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '987654321098765432',
            'nama' => 'Nur Izatul Khumairoh, S. E.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru30@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '109283746592837465',
            'nama' => 'Masdar Hilmi, S. E.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru31@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '837465920183746592',
            'nama' => 'Naning Yuliani, M. Psi.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru32@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '647382910283746592',
            'nama' => 'M. Syahrun Nizam, S. H.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru33@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '283746591028374659',
            'nama' => 'Eka Agustina Maulida, S. Mat.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru34@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '485920183746592837',
            'nama' => 'Laili Rohmah, S. Pd.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru35@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '918273645091827364',
            'nama' => 'Jakfad Sodik, S. Pd.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru36@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '384756192837465092',
            'nama' => 'Cindi Nuriana, S. S.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru37@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '506192837465092837',
            'nama' => 'Ayu Diah Arianti, S. Pd.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru38@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '719283746591028374',
            'nama' => 'Mufidatun Nuriftahiah, S. Pd.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru39@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '928374659102837465',
            'nama' => 'Nur Ainiyatun Nabilah, S. Psi.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru40@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '384750192837465092',
            'nama' => 'Faris Irfanto S. E.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru41@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '560192837465092837',
            'nama' => 'Eka Laila Juli Anita S. Pd.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru42@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '748291037465910283',
            'nama' => 'Fatimatul Khusna S. Pd.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru43@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '294857610928374659',
            'nama' => 'Dinda Darma Wanti S. Pd.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru44@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '918374650192837465',
            'nama' => 'Rofif Nursofi S. Pd.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru45@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '304958712938475610',
            'nama' => 'M. Sokib S. Pd.',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru46@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '657483920193847561',
            'nama' => 'Siti Nur Aisyah S. Pd.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru47@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '294857610283746591',
            'nama' => 'Novita S. Pd.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru48@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '918374659203847561',
            'nama' => 'Widya Ayu Lestari S. Pd.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru49@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '374650192837465092',
            'nama' => 'Luthfiyah Anggraini S. Pd.',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru50@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '561928374659102837',
            'nama' => 'Ustadzah Syamsiyah',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'guru51@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '748291037465092837',
            'nama' => 'Ustad Hibbin',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'habibin@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '293847561092837465',
            'nama' => 'Ustadzah Umi',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'umi@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);

        Teacher::create([
            'nip' => '918374650192837462    ',
            'nama' => 'Ustad Rega',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Gresik',
            'tanggal_lahir' => '2000-01-01',
            'no_telepon' => '089765435643',
            'email' => 'rega@gmail.com',
            'alamat' => 'Gresik',
            'jabatan' => 'Guru Mapel',
            'mata_pelajaran' => null,
            'status' => 'Aktif',
            'foto' => null,
        ]);
    }
}
