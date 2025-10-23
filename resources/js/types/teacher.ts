export interface Teacher {
    id: BigInteger;
    teacher_id?: BigInteger;
    user_id?: BigInteger;
    nip?: string;
    nama: string;
    jenis_kelamin: "L" | "P";
    tempat_lahir: string;
    tanggal_lahir: string;
    no_telepon: string;
    email: string;
    alamat: string;
    jabatan: string;
    mata_pelajaran?: string;
    status: "Aktif" | "Tidak Aktif" | "Cuti";
    foto?: string;
    created_at?: string;
    updated_at?: string;
}