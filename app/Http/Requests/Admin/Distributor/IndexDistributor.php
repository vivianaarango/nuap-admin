<?php
namespace App\Http\Requests\Admin\Distributor;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class IndexDistributor
 * @package App\Http\Requests\Admin\Distributor
 */
class IndexDistributor extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'orderBy' => 'in:id,name,lastname,email,phone,commission,discount,status,last_logged_in|nullable',
            'orderDirection' => 'in:asc,desc|nullable',
            'search' => 'string|nullable',
            'page' => 'integer|nullable',
            'per_page' => 'integer|nullable',
        ];
    }
}
