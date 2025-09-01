export interface Classroom {
    id: number;
    nama_kelas: string;
    jurusan: string;
    wali_kelas_id: number; // relasi ke teacher
    jumlah_anak: number;
    created_at?: string;
    updated_at?: string;
}
