<?php
namespace App\Http\Requests\Admin\Commerce;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class IndexCommerce
 * @package App\Http\Requests\Admin\Commerce
 */
class IndexCommerce extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'orderBy' => 'in:id,email,phone,business_name,type,commission,name_legal_representative,last_logged_in|nullable',
            'orderDirection' => 'in:asc,desc|nullable',
            'search' => 'string|nullable',
            'page' => 'integer|nullable',
            'per_page' => 'integer|nullable',
        ];
    }
}
