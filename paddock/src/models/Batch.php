<?php

namespace Increment\Imarket\Paddock\Models;
use Illuminate\Database\Eloquent\Model;
use App\APIModel;
class Batch extends APIModel
{
    protected $table = 'batch';
    protected $fillable = ['spray_mix_id', 'machine_id', 'merchant_id', 'account_id', 'notes', 'water'];
}
