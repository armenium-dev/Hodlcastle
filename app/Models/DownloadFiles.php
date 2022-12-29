<?php

namespace App\Models;

use Eloquent as Model;
use Event;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CompanyProfiles
 * @package App\Models
 * @property string name
 */
class DownloadFiles extends Model{
	use SoftDeletes;

	public $table = 'download_files';

	public $fillable = [
		'file',
	];
	

}
