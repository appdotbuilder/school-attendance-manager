<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Student
 *
 * @property int $id
 * @property string $student_id
 * @property string $first_name
 * @property string $last_name
 * @property \Illuminate\Support\Carbon $date_of_birth
 * @property string $gender
 * @property string|null $parent_name
 * @property string|null $parent_phone
 * @property string|null $parent_email
 * @property string|null $address
 * @property int|null $class_id
 * @property \Illuminate\Support\Carbon $enrollment_date
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SchoolClass|null $schoolClass
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AttendanceRecord> $attendanceRecords
 * @property-read string $full_name
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Student newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Student newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Student query()
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereParentName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereParentPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereParentEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereEnrollmentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student active()
 * @method static \Database\Factories\StudentFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'parent_name',
        'parent_phone',
        'parent_email',
        'address',
        'class_id',
        'enrollment_date',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'enrollment_date' => 'date',
    ];

    /**
     * Get the class that the student belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Get the attendance records for the student.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    /**
     * Get the student's full name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Scope a query to only include active students.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}