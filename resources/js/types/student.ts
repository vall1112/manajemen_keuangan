export interface Student {
    id: number;
    nis: string;
    nama: string;
    jenis_kelamin: 'L' | 'P';
    tempat_lahir: string;
    tanggal_lahir: string; // pakai string karena biasanya format ISO (YYYY-MM-DD)
    alamat: string;
    classroom_id: number;
    email?: string;
    telepon?: string;
    status: 'aktif' | 'prakerin' | 'alumni' | 'keluar';
    nama_ayah?: string;
    telepon_ayah?: string;
    nama_ibu?: string;
    telepon_ibu?: string;
    foto?: string; // biasanya url/path
    created_at?: string;
    updated_at?: string;
}
