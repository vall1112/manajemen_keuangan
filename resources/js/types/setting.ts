export interface Setting {
    id: BigInteger,
    uuid: string,
    app: string;
    telepon: string;
    description: string;
    alamat: string;
    school: string;
    pemerintah: string;
    email: string;
    logo: Array<File | string> | string;
    bg_auth: Array<File | string> | string;
}