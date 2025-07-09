<?php
namespace App\Http\Requests;

use App\Models\File;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class FileRequest extends FormRequest
{
    /* Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /*
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => 'required|mimes:csv,pdf|max:2048',
        ];
    }

    /*
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if ($this->fileExists()) {
                $validator->errors()->add('file', 'This name has already exist.');
            }
        });
    }

    /*
     * Check if the file name already exists in the database.
     *
     * @return bool
     */
    protected function fileExists(): bool
    {
        return File::where('name', $this->file->getClientOriginalName())->exists();
    }

    public function messages()
    {
        return [
            'file.required' => 'Please select a file.',
            'file.mimes' => 'The file must be a file of type: pdf, csv.',
            'file.max' => 'The file may not be greater than 2048 kilobytes.',
            'file.unique' => 'This name has already exist.',
        ];
    }
}
