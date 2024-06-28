<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Repository\CategoryRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class UploadRequest extends FormRequest
{
    public function __construct(
        private CategoryRepository $categoryRepository,
    ){
        parent::__construct();
    }

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category' => [
                'required',
            ],
            'image' => [
                'required',
                File::image()->max('2mb')
                    ->extensions(['jpg', 'jpeg', 'png'])
            ],
        ];
    }
}
