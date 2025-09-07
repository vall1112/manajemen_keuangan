export interface Bill {
    id: number;
    student_id: number;              // relasi ke students
    payment_type_id: number;   // relasi ke payment_types
    school_year_id: number;        // relasi ke school_years
    total_tagihan: number;
    tanggal_tagih: string;         // format date (YYYY-MM-DD)
    status: "Belum Dibayar" | "Dibayar Sebagian" | "Lunas";
    dibayar: number;
    sisa: number;
    keterangan?: string;
    created_at?: string;
    updated_at?: string;
}
