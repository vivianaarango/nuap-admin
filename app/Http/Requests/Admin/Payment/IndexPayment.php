<?php
namespace App\Http\Requests\Admin\Payment;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class IndexPayment
 * @package App\Http\Requests\Admin\Ticket
 */
class IndexPayment extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'orderBy' => 'in:id,value,request_date,payment_date,status|nullable',
            'orderDirection' => 'in:asc,desc|nullable',
            'search' => 'string|nullable',
            'page' => 'integer|nullable',
            'per_page' => 'integer|nullable',
        ];
    }
}
