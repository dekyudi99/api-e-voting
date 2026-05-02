<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Models\Candidates;

#[Table('votes')]
#[Fillable(['candidate_id'])]
class Votes extends Model
{
    public function candidate()
    {
        return $this->belongsTo(Candidates::class, 'candidate_id');
    }
}
