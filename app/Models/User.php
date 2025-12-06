<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class User extends Authenticatable implements JWTSubject
{
    use Uuid, HasRoles;

    protected $fillable = [
        'username',
        'teacher_id',
        'student_id',
        'name',
        'email',
        'password',
        'status',
        'photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['permission', 'role'];

    /**
     * Helper: cek apakah user adalah admin
     */
    public function isAdmin()
    {
        return $this->roles()->where('id', 1)->exists();
    }

    /**
     * Boot model
     */
    protected static function booted()
    {
        // CREATE
        static::creating(function ($user) {

            // kalau password diisi
            if (!empty($user->password)) {

                // cek role_id dari request (ketika create user)
                if (request()->has('role_id') && request()->role_id == 1) {
                    // hash hanya admin
                    $user->password = Hash::make($user->password);
                }
            }
        });

        // CREATED
        static::created(function ($user) {

            // Assign role
            if (request()->has('role_id')) {

                $role = Role::find(request()->role_id);

                if ($role) {
                    $user->syncRoles([$role->name]);
                }

            } else {
                // default role id 3
                $roleDefault = Role::find(3);
                if ($roleDefault) {
                    $user->assignRole($roleDefault->name);
                }
            }

            // Jika admin ternyata password plaintext → hash
            if ($user->isAdmin()) {

                if (!password_get_info($user->password)['algo']) {
                    $user->password = Hash::make($user->password);
                    $user->saveQuietly();
                }
            }
        });

        // UPDATE
        static::updating(function ($user) {

            // jika password berubah & tidak kosong
            if ($user->isDirty('password') && !empty($user->password)) {

                // Admin → hash
                if ($user->isAdmin()) {
                    // hash hanya jika belum di-hash
                    if (!password_get_info($user->password)['algo']) {
                        $user->password = Hash::make($user->password);
                    }
                }

                // Non-admin → biarkan plaintext
            }
        });

        // DELETE → hapus foto
        static::deleted(function ($user) {
            if (!empty($user->photo)) {
                $old = str_replace('/storage/', '', $user->photo);
                Storage::disk('public')->delete($old);
            }
        });
    }

    /** JWT */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /** Attributes */
    public function getRoleAttribute()
    {
        return $this->roles()->first();
    }

    public function getPermissionAttribute()
    {
        return $this->getAllPermissions()->pluck('name');
    }

    /** Relations */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function siswa()
    {
        return $this->hasOne(Student::class, 'user_id');
    }
}
