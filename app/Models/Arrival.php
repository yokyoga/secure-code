<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arrival extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'full_name',
        'passport_number',
        'nationality',
        'gender',
        'birth_date',
        'photo_path',
        'phone_number',
        'email',
        'stay_address',
        'flight_number',
        'arrival_date',
        'origin_city',
        'destination_city',
        'health_history',
        'emergency_contact_name',
        'emergency_contact_phone',
        'vaccine_certificate_path',
        'status',
        'approved_by_user_id',
        'rejected_by_user_id',
        'reject_reason'
    ];

    protected $hidden = [
        'passport_number',
        'phone_number',
        'email',
        'stay_address',
        'health_history',
        'emergency_contact_name',
        'emergency_contact_phone',
        'photo_path',
        'vaccine_certificate_path',
        'reject_reason',
        'approved_by_user_id',
        'rejected_by_user_id',
    ];

    protected $casts = [
        'birth_date'               => 'date:Y-m-d',
        'arrival_date'             => 'datetime',
        'passport_number'          => 'encrypted',
        'phone_number'             => 'encrypted',
        'email'                    => 'encrypted',
        'stay_address'             => 'encrypted',
        'health_history'           => 'encrypted',
        'emergency_contact_name'   => 'encrypted',
        'emergency_contact_phone'  => 'encrypted',
        'photo_path'               => 'encrypted',
        'vaccine_certificate_path' => 'encrypted',
        'reject_reason'            => 'encrypted',
    ];
}
