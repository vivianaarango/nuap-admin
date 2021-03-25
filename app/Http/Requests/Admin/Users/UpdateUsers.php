<?php
namespace App\Http\Requests\Admin\Users;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateUsers
 * @package App\Http\Requests\Admin\Users
 */
class UpdateUsers extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'email' => ['required', 'email', 'string'],
            'password' => ['nullable', 'confirmed', 'min:8', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/', 'string'],
            'identity_type' => ['required', 'string'],
            'identity_number' => ['required', 'string'],
            'role' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'commission' => ['numeric', 'min:0.0','max:100.00'],
            'discount' => ['numeric', 'min:0.0','max:100.00'],
        ];
    }
}
