<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'mood',
        'weather',
        'gps_location',
        'note',
    ];

    /**
     * Get the decrypted value of the note attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getNoteAttribute($value)
    {
        return decrypt($value);
    }

    /**
     * Set the encrypted value of the note attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setNoteAttribute($value)
    {
        $this->attributes['note'] = encrypt($value);
    }
}
