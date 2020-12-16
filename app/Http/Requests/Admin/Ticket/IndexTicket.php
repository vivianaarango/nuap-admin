<?php
namespace App\Http\Requests\Admin\Ticket;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class IndexTicket
 * @package App\Http\Requests\Admin\Ticket
 */
class IndexTicket extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'orderBy' => 'in:id,issues,description,is_closed,init_date|nullable',
            'orderDirection' => 'in:asc,desc|nullable',
            'search' => 'string|nullable',
            'page' => 'integer|nullable',
            'per_page' => 'integer|nullable',
        ];
    }
}
