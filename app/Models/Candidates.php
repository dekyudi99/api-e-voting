<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Table('candidates')]
#[Fillable(['no_undi', 'path_photo', 'calon_ketua', 'calon_wakil', 'visi', 'misi', 'proker_unggulan', 'on_deleted'])]
class Candidates extends Model
{
    //
}
