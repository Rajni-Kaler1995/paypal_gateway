<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dochdr extends Model
{
    protected $table = 'dochdr';
    public $timestamps = false;
    protected $primaryKey = 'HDRAutoID';
    use HasFactory;
}
