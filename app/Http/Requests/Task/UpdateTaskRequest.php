<?php

namespace App\Http\Requests\Task;

use App\Http\Requests\BaseRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTaskRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:50',
            'description' => 'sometimes|nullable|string',
            'status' => 'sometimes|in:pending,in_progress,done',
            'priority' => 'sometimes|in:low,medium,high',
            'due_date' => 'sometimes|nullable|date|required_if:priority,high|after_or_equal:today',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        return throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors(),
        ], 422));
    }
}
