<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use HasinHayder\Tyro\Concerns\HasTyroRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasTyroRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'team_leader_id',
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
        ];
    }

    /**
     * Get the team leader of the user.
     */
    public function teamLeader(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'team_leader_id');
    }

    /**
     * Get the team members managed by this user.
     */
    public function teamMembers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class, 'team_leader_id');
    }

    /**
     * Get the sales for the user.
     */
    public function sales(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get the leads assigned to the user.
     */
    public function leads(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Lead::class, 'assigned_user');
    }

    /**
     * Role check helpers
     */
    public function isManager(): bool
    {
        return in_array('manager', $this->tyroRoleSlugs());
    }

    public function isSuperAdmin(): bool
    {
        return in_array('super_admin', $this->tyroRoleSlugs()) || 
               in_array('admin', $this->tyroRoleSlugs()) || 
               in_array('super-admin', $this->tyroRoleSlugs());
    }

    /**
     * Get the role attribute for backward compatibility.
     */
    public function getRoleAttribute(): string
    {
        return $this->tyroRoleSlugs()[0] ?? '';
    }
}
