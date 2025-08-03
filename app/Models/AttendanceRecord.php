<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\AttendanceRecord
 *
 * @property int $id
 * @property int $student_id
 * @property int $class_id
 * @property int $marked_by
 * @property \Illuminate\Support\Carbon $attendance_date
 * @property string $status
 * @property string|null $notes
 * @property string|null $marked_at_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Student $student
 * @property-read \App\Models\SchoolClass $schoolClass
 * @property-read \App\Models\User $markedBy
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceRecord whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceRecord whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceRecord whereMarkedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceRecord whereAttendanceDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceRecord whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceRecord whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceRecord whereMarkedAtTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceRecord forDate($date)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceRecord present()
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceRecord absent()
 * @method static \Database\Factories\AttendanceRecordFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class AttendanceRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'class_id',
        'marked_by',
        'attendance_date',
        'status',
        'notes',
        'marked_at_time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attendance_date' => 'date',
        'marked_at_time' => 'datetime:H:i',
    ];

    /**
     * Get the student that the attendance record belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the class that the attendance record belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Get the user who marked the attendance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function markedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    /**
     * Scope a query to filter by attendance date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('attendance_date', $date);
    }

    /**
     * Scope a query to only include present records.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    /**
     * Scope a query to only include absent records.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAbsent($query)
    {
        return $query->whereIn('status', ['absent', 'late', 'excused']);
    }
}