<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateLeaderboardView extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		$_sql = file_get_contents(database_path('/sql/view_leaderboard.sql'));
		DB::statement('DROP VIEW IF EXISTS leaderboard');
		DB::statement($_sql);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		DB::statement('DROP VIEW IF EXISTS leaderboard');
	}
}
