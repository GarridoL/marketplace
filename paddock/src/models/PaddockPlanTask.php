<?php

namespace Increment\Imarket\Paddock\Models;
use Illuminate\Database\Eloquent\Model;
use App\APIModel;
class PaddockPlanTask extends APIModel
{
    protected $table = 'paddock_plan_tasks';
    protected $fillable = ['paddock_plan_id','paddock_id','category','due_date','nickname', 'spray_mix_id', 'status'];
}
