<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Setting;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $userName;
    public $userEmail;
    public $schoolName;
    public $supportEmail;
    public $supportUrl;
    public $logo;
    public $description;

    /**
     * Buat instance baru dari email OTP.
     *
     * @param string $otp
     * @param string|null $userName
     * @param string|null $userEmail
     */
    public function __construct($otp, $userName = null, $userEmail = null)
    {
        $this->otp = $otp;
        $this->userName = $userName;
        $this->userEmail = $userEmail;

        // Ambil data setting pertama (logo, nama aplikasi, deskripsi, dsb)
        $setting = Setting::first();

        $this->schoolName = $setting->app ?? 'Manajemen Keuangan Sekolah';
        $this->description = $setting->description ?? 'Sistem Manajemen Keuangan & Administrasi Sekolah';
        $this->logo = $setting && $setting->logo
            ? asset($setting->logo)
            : asset('images/logo.png');

        // Info support (ambil dari setting jika ada)
        $this->supportEmail = $setting->support_email ?? 'support@manajemenkeuangan.com';
        $this->supportUrl = $setting->support_url 
            ?? 'https://www.instagram.com/smk_alazhar_mgt?utm_source=ig_web_button_share_sheet&igsh=d3F2YTk3YXlja2lk';
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Kode OTP Pendaftaran')
                    ->view('emails.otp')
                    ->with([
                        'otp' => $this->otp,
                        'userName' => $this->userName,
                        'userEmail' => $this->userEmail,
                        'schoolName' => $this->schoolName,
                        'description' => $this->description, // ✅ Tambahkan ini
                        'supportEmail' => $this->supportEmail,
                        'supportUrl' => $this->supportUrl,
                        'logo' => $this->logo,
                    ]);
    }
}
