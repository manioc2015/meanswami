<?php
error_reporting(E_ALL);
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class AccessTableSeeder extends Seeder {

	public function run() {

		Model::unguard();
		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		$this->call(RoleTableSeeder::class);
		$this->call(UserRoleSeeder::class);
		$this->call(PermissionGroupTableSeeder::class);
		$this->call(PermissionTableSeeder::class);
		$this->call(PermissionDependencyTableSeeder::class);
		if(env('DB_DRIVER')=='mysql')
			DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		Model::guard();
	}
}
