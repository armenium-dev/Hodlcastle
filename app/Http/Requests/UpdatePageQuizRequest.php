<?php

namespace App\Http\Requests;

use App\Models\PageQuiz;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Course;

class UpdatePageQuizRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return PageQuiz::$rules;
    }
}
