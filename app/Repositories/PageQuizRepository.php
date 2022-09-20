<?php

namespace App\Repositories;

use App\Models\PageQuiz;
use App\Models\PageQuizQuestion;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class PageQuizRepository
 * @package App\Repositories
 *
 * @method Page findWithoutFail($id, $columns = ['*'])
 * @method Page find($id, $columns = ['*'])
 * @method Page first($columns = ['*'])
*/
class PageQuizRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = ['name', 'type', 'text'];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PageQuiz::class;
    }

    public function updateRequest($request, $id)
    {
        try {

            $input = $request->all();

            if (isset($input['quizs'])) {

                foreach ($input['quizs'] as $quiz) {

                    if (isset($quiz['answer'])) {
                        if (isset($quiz['id'])) {
                            $question_model = PageQuizQuestion::find($quiz['id']);
                            $question_model->answer = $quiz['answer'];
                            if (isset($quiz['correct'])) {
                                $question_model->correct = 1;
                            }
                            $question_model->save();
                        } else {
                            $temp = [];
                            $temp['answer'] = $quiz['answer'];
                            if (isset($quiz['correct'])) {
                                $temp['correct'] = 1;
                            }
                            $temp['page_quiz_id'] = $id;

                            PageQuizQuestion::create($temp);
                        }
                    }

                }

                unset($input['quizs']);

            }

            $model = parent::update($input, $id);

            return $model;

        } catch (Exception $e) {
            Flash::error($e->getMessage());
        }
    }
}
