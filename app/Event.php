<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {
    use HasSlugTrait;
    
	protected $table = 'events';
    protected $fillable = ['band_id', 'start_time_local', 'timezone', 'venue', 'address1', 'address2', 'city', 'state', 'zip', 'contact', 'description', 'active'];
    public function band(){
        return $this->belongsTo('App\Band');
    }
    
    public static function create_from_input($input){
        $event = Event::create($input);
        $slug = $event->band->name."-".$event->city;
        if ($event->start_time_local){
            $slug .= "-".date("Y-m", strtotime($event->start_time_local));
        }
        $event->slug = self::findSlug($slug);
        $event->save();
        return $event;
    }
}