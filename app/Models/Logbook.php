<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Logbook extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'planet_id',
        'mood',
        'weather',
        'gps_location',
        'note',
    ];

    /**
     * Get the decrypted value of the mood attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getMoodAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    /**
     * Get the decrypted value of the weather attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getWeatherAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    /**
     * Get the decrypted value of the gps location attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getGpsLocationAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    /**
     * Get the decrypted value of the note attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getNoteAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    /**
     * Set the encrypted value of the mood attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setMoodAttribute($value)
    {
        $this->attributes['mood'] = Crypt::encryptString($value);
    }

    /**
     * Set the encrypted value of the weather attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setWeatherAttribute($value)
    {
        $this->attributes['weather'] = Crypt::encryptString($value);
    }

    /**
     * Set the encrypted value of the gps location attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setGpsLocationAttribute($value)
    {
        $this->attributes['gps_location'] = Crypt::encryptString($value);
    }

    /**
     * Set the encrypted value of the note attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setNoteAttribute($value)
    {
        $this->attributes['note'] = Crypt::encryptString($value);
    }

    /**
     * Get the planet associated with the logbook entry.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function planet()
    {
        return $this->belongsTo(Planet::class);
    }
}
