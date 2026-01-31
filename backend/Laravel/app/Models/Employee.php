<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'company_id',
        'manager_id',
        'country',
        'state',
        'city',
        'address',
        'hire_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'hire_date' => 'date',
    ];

    /**
     * Get the company that the employee belongs to.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the manager of the employee.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    /**
     * Get the subordinates of the employee.
     */
    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }

    /**
     * Get the employee's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
