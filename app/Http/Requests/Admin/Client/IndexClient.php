<?php
namespace App\Http\Requests\Admin\Client;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class IndexClient
 * @package App\Http\Requests\Admin\Commerce
 */
class IndexClient extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'orderBy' => 'in:id,name,last_name,identity_number|nullable',
            'orderDirection' => 'in:asc,desc|nullable',
            'search' => 'string|nullable',
            'page' => 'integer|nullable',
            'per_page' => 'integer|nullable',
        ];
    }
}
