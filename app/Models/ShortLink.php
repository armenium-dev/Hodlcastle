<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortLink extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'link'
    ];

    public static function generate($link)
    {
        $last_row = self::orderby('id', 'desc')->first();
        $last_id = $last_row ? $last_row->id + 1 : 1;

        $input['link'] = $link;
        $input['code'] = base_convert(time(),10 , 36) . base_convert(rand(0, 9999),10 , 36);

        return self::create($input);
    }
}