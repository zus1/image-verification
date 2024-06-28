<?php

namespace App\Http\Requests;

use App\Repository\CategoryRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ImageRequest extends FormRequest
{
    public function __construct(
        private CategoryRepository $categoryRepository,
    ) {
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
        $categories = array_map(function (array $category) {
            return $category['name'];
        }, $this->categoryRepository->findAll()->toArray());

        if($this->getMethod() === FormRequest::METHOD_GET) {
            return $this->getRules($categories);
        }
        if($this->getMethod() === FormRequest::METHOD_POST) {
            return $this->postRules($categories);
        }

        throw new HttpException(500, 'Unsupported request method '.$this->getMethod());
    }

    private function getRules(array $categories): array
    {
        return [
            'category' => [
                Rule::in($categories),
            ],
        ];
    }

    private function postRules(array $categories): array
    {
        return [
            'category' => [
                'required',
                Rule::in($categories),
            ],
            'images' => 'required|array|size:'.config('app.custom.correct_image_num'),
            'images.*' => 'exists:images,id'
        ];
    }
}
