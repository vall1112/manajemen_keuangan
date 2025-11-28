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

    // hashed DIHAPUS
    protected $casts = [];

    protected $appends = ['permission', 'role'];

    protected static function booted()
    {
        // Hash password saat creating, JIKA role admin dikirim di request
        static::creating(function ($user) {
            if (!empty($user->password)) {

                // Jika request mengirim role_id admin
                if (request()->has('role_id')) {
                    $role = Role::find(request()->role_id);

                    if ($role && $role->id == 1) {
                        $user->password = Hash::make($user->password);
                    }
                }
            }
        });

        // Setelah user dibuat → assign role default & hashing final jika perlu
        static::created(function ($user) {

            // AUTO role default siswa ID=3
            $roleDefault = Role::find(3);
            if ($roleDefault && !$user->hasRole($roleDefault->name)) {
                $user->assignRole($roleDefault->name);
            }

            // Jika user DISIMPAN dulu, lalu role admin dipasang setelah created
            if ($user->hasRole(Role::find(1)->name)) {

                // Jika password belum hashed → hash disini
                if (!password_get_info($user->password)['algo']) {
                    $user->password = Hash::make($user->password);
                    $user->saveQuietly();
                }
            }
        });

        // Saat update → hash password jika user sudah punya role admin
        static::updating(function ($user) {
            if ($user->isDirty('password') && !empty($user->password)) {
                if ($user->hasRole(Role::find(1)->name)) {
                    $user->password = Hash::make($user->password);
                }
            }
        });

        // Hapus foto saat user dihapus
        static::deleted(function ($user) {
            if (!empty($user->photo)) {
                $old = str_replace('/storage/', '', $user->photo);
                Storage::disk('public')->delete($old);
            }
        });
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getRoleAttribute()
    {
        return $this->roles()->first();
    }

    public function getPermissionAttribute()
    {
        return $this->getAllPermissions()->pluck('name');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
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
