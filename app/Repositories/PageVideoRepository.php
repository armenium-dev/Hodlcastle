<?php

namespace App\Repositories;

use App\Models\PageVideo;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class PageVideoRepository
 * @package App\Repositories
 *
 * @method Page findWithoutFail($id, $columns = ['*'])
 * @method Page find($id, $columns = ['*'])
 * @method Page first($columns = ['*'])
*/
class PageVideoRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = ['url'];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PageVideo::class;
    }

    public function createRequest($request)
    {
        try {

            $input = $request->all();
            $model = parent::create($input);

            return $model;

        } catch (Exception $e) {
            Flash::error($e->getMessage());
        }
    }

    public function updateRequest($request, $id)
    {
        try {

            $input = $request->all();

            /*$video_code = $input['url'];

            preg_match('#(\.be/|/embed/|/v/|/watch\?v=)([A-Za-z0-9_-]{5,11})#', $video_code, $matches);
            if(isset($matches[2]) && $matches[2] != ''){
                $input['url'] = $matches[2];
			}*/

            $model = parent::update($input, $id);

            return $model;

        } catch (Exception $e) {
            Flash::error($e->getMessage());
        }
    }
}
