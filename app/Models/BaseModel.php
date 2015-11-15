<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model {

	use SoftDeletes;
    
	public static function table()
	{
		$instance = new static;
		return $instance->getTable();
	}
    
}