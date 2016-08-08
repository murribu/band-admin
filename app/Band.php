<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Band extends Model {
    
    use HasSlugTrait;
    
	protected $table = 'bands';
}
