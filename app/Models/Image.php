<?php namespace App\Models;

use Matriphe\Imageupload\ImageuploadModel;
use Intervention\Image\Image as InterventionImage;

class Image extends ImageuploadModel {

    const PATH = '/uploads/images/';

    protected $table = 'images';

    public function imageable()
    {
        return $this->morphTo();
    }

    public function getPubpathAttribute()
    {
        return self::PATH . $this->filename;
    }

//    public function getThumbDirAttribute()
//    {
//        return $targetDir = '/' . $width . '/' . $height . '/' . (int)$squared;
//    }

    public function crop($width, $height, $squared = false)
    {
        $targetDir = '/' . $width . '/' . $height . '/' . (int)$squared;
        $fullDir = config('imageupload.path') . $targetDir;
        $fullpath = config('imageupload.path') . $targetDir . '/' . $this->filename;

        if (!file_exists($fullpath)) {//dd($fullDir);
            if (!is_dir($fullDir)) {
                mkdir($fullDir, 0777, true);
            }
            $squared = (isset($squared) ? $squared : false);
            $image = \Image::make($this->path . '/' . $this->filename);

            if ($squared) {
                $width = ($height < $width ? $height : $width);
                $height = $width;

                $image->fit($width, $height, function ($image) {
                    $image->upsize();
                });
            } else {
                $image->resize($width, $height, function ($image) {
                    $image->aspectRatio();
                });
            }

            $image->save(config('imageupload.path') . $targetDir . '/' . $this->filename, $this->quality);
        }

        return self::PATH . $targetDir . '/' . $this->filename;
    }
}
