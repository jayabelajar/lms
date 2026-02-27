<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $course = $this->route('course');

        return $course && $this->user()?->can('update', $course);
    }

    public function rules(): array
    {
        $courseId = $this->route('course')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:courses,slug,' . $courseId],
            'description' => ['nullable', 'string'],
            'instructor_id' => ['required', 'integer', 'exists:users,id'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ];
    }
}
