<?php
namespace App\Http\Requests\Admin\Distributor;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class IndexCommission
 * @package App\Http\Requests\Admin\Distributor
 */
class IndexCommission extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'orderBy' => 'in:id,business_name,commission,name_legal_representative|nullable',
            'orderDirection' => 'in:asc,desc|nullable',
            'search' => 'string|nullable',
            'page' => 'integer|nullable',
            'per_page' => 'integer|nullable',
        ];
    }
}
