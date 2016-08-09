<?php
namespace App;

trait HasPermissionsTrait{
    public function permissions($band){
        return Permission::whereRaw('id in (select permission_id from '.self::$permission_pivot_table.' where '.self::$permission_pivot_key.' = ?)', array($this->id))->get();
    }
}
