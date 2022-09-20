<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDimensionFieldsToImageUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('image_uploads', function (Blueprint $table) {
	        foreach (Config::get('imageupload.dimensions') as $key => $dimension) {
	        	if($key == 'square100'){
			        $table->string($key.'_s3_url')->nullable()->after('square135_s3_url');
			        $table->boolean($key.'_is_squared')->unsigned()->nullable()->after('square135_s3_url');
			        $table->smallInteger($key.'_height')->unsigned()->nullable()->after('square135_s3_url');
			        $table->smallInteger($key.'_width')->unsigned()->nullable()->after('square135_s3_url');
			        $table->integer($key.'_filesize')->unsigned()->nullable()->after('square135_s3_url');
			        $table->string($key.'_filedir')->nullable()->after('square135_s3_url');
			        $table->string($key.'_filepath')->nullable()->after('square135_s3_url');
			        $table->string($key.'_filename')->nullable()->after('square135_s3_url');
			        $table->string($key.'_dir')->nullable()->after('square135_s3_url');
			        $table->string($key.'_path')->nullable()->after('square135_s3_url');
		        }
	        }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('image_uploads', function (Blueprint $table) {
	        foreach (Config::get('imageupload.dimensions') as $key => $dimension) {
		        if($key == 'square100'){
			        $table->dropColumn($key.'_path');
			        $table->dropColumn($key.'_dir');
			        $table->dropColumn($key.'_filename');
			        $table->dropColumn($key.'_filepath');
			        $table->dropColumn($key.'_filedir');
			        $table->dropColumn($key.'_filesize');
			        $table->dropColumn($key.'_width');
			        $table->dropColumn($key.'_height');
			        $table->dropColumn($key.'_is_squared');
			        $table->dropColumn($key.'_s3_url');
		        }
	        }
        });
    }
}
