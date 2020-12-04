<?php
namespace App\Http\Requests\Admin\Users;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class IndexUserLocation
 * @package App\Http\Requests\Admin\Client
 */
class IndexUserLocation extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'orderBy' => 'in:id,city,location,neighborhood,address|nullable',
            'orderDirection' => 'in:asc,desc|nullable',
            'search' => 'string|nullable',
            'page' => 'integer|nullable',
            'per_page' => 'integer|nullable',
        ];
    }
}
