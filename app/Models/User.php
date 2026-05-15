<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email', 
        'password',
        'role',
        'assigned_books',
        'is_active', 
        'photo',
        'permissions'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'assigned_books' => 'array',
            'permissions' => 'array',  // Tambahkan ini
            'is_active' => 'boolean',
        ];
    }

    /**
     * Cek apakah user adalah Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Cek apakah user adalah Admin biasa
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user memiliki permission tertentu
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        // Super admin memiliki semua akses
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions);
    }

    /**
     * Cek apakah user memiliki multiple permissions
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Cek apakah user memiliki semua permissions
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Relasi ke Buku yang dibuat oleh user
     */
    public function books()
    {
        return $this->hasMany(Book::class, 'created_by');
    }

    /**
     * Relasi ke Party yang dibuat oleh user
     */
    public function parties()
    {
        return $this->hasMany(Party::class, 'created_by');
    }

    /**
     * Relasi ke Transaksi yang dibuat oleh user
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'created_by');
    }

    /**
     * Scope untuk filter user aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk filter berdasarkan role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Mendapatkan foto profil URL
     *
     * @return string
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->photo && file_exists(storage_path('app/public/photos/' . $this->photo))) {
            return asset('storage/photos/' . $this->photo);
        }
        
        // Generate avatar dari nama
        return 'https://ui-avatars.com/api/?background=3b82f6&color=fff&name=' . urlencode($this->name);
    }

    /**
     * Mendapatkan status badge HTML
     *
     * @return string
     */
    public function getStatusBadgeAttribute()
    {
        if ($this->is_active) {
            return '<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Aktif</span>';
        }
        return '<span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Nonaktif</span>';
    }

    /**
     * Mendapatkan role badge HTML
     *
     * @return string
     */
    public function getRoleBadgeAttribute()
    {
        if ($this->isSuperAdmin()) {
            return '<span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">Super Admin</span>';
        }
        return '<span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Admin</span>';
    }
}