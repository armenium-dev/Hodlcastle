<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\Page;
use Carbon\Carbon;
use Exception;
use Flash;

/**
 * Class CourseRepository
 * @package App\Repositories
 *
 * @method Course findWithoutFail($id, $columns = ['*'])
 * @method Course find($id, $columns = ['*'])
 * @method Course first($columns = ['*'])
*/
class CourseRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'module_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Course::class;
    }

    public function createRequest($request)
    {
        try {

            $input = $request->all();

            // Get pages request data.
            if (isset($input['pages'])) {
                $pages = $input['pages'];
                unset($input['pages']);
            } else {
                $pages = [];
            }

            if(!isset($input['public'])) {
                $input['public'] = 0;
            }

            // Create course.
            $course = parent::create($input);

            $course_id = $course->id;
            $language_id = $course->language_id;

            // Create pages.
            if ($pages != []) {
                foreach ($pages as $position_id => $page) {

                    // Create page content.
                    $model_name = 'App\Models\Page' . ucfirst($page['entity_type']);
                    $entity_model = new $model_name;
                    $entity = $entity_model::create();
                    $entity_id = $entity->id;

                    // Create page.
                    $page['course_id'] = $course_id;
                    $page['language_id'] = $language_id;
                    $page['position_id'] = $position_id + 1;
                    $page['entity_id'] = $entity_id ;

                    Page::create($page);

                }
            }

        } catch (Exception $e) {
            Flash::error($e->getMessage());
        }
    }

    public function updateRequest($request, $id)
    {

        try {

            $input = $request->all();

            // Update course.
            if (isset($input['pages'])) {
                $pages = $input['pages'];
                unset($input['pages']);
            } else {
                $pages = [];
            }

            if(!isset($input['public'])) {
                $input['public'] = 0;
            }

            $course = parent::update($input, $id);

            $language_id = $course->language_id;

            // All pages id in course.
            $pages_id = $course->pages->pluck('id')->toArray();

            if ($pages != []) {

                foreach ($pages as $position_id => $page) {

                    $page['course_id'] = $id;
                    $page['language_id'] = $language_id;
                    $page['position_id'] = $position_id + 1;

                    if (isset($page['id'])) {

                        $page_id_state = array_search($page['id'], $pages_id);
                        if ($page_id_state !== false) {
                            unset($pages_id[$page_id_state]);
                        }

                        // Update page.
                        $p = Page::where('id', $page['id'])->first();

                        if ($p) {
                            $p->fill($page);
                            $p->save();
                        }

                    } else {

                        // Create page content.
                        $model_name = 'App\Models\Page' . ucfirst($page['entity_type']);
                        $entity_model = new $model_name;
                        $entity = $entity_model::create();
                        $entity_id = $entity->id;

                        $page['entity_id'] = $entity_id;

                        Page::create($page);

                    }

                }

            }

            // Delete pages.
            foreach($pages_id as $page_id) {
                $p = Page::where('id', $page_id)->first();

                $p->entity()->delete();
                $p->delete();
            }

        } catch (Exception $e) {
            Flash::error($e->getMessage());
        }
    }

}
