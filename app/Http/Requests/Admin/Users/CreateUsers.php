<?php
namespace App\Http\Requests\Admin\Users;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateUsers
 * @package App\Http\Requests\Admin\Users
 */
class CreateUsers extends FormRequest
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
            'password' => ['required', 'confirmed', 'min:8', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/', 'string'],
            'identity_type' => ['required', 'string'],
            'identity_number' => ['required', 'string'],
            'role' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'commission' => ['numeric', 'min:0.0','max:100.00'],
            'discount' => ['numeric', 'min:0.0','max:100.00'],
        ];
    }

    /**
     * Modify input data
     *
     * @return array
     */
    public function getModifiedData(): array
    {
        $data = $this->only(collect($this->rules())->keys()->all());

        $data['status'] = true;
        $data['last_logged_in'] = now();
        $data['password'] = md5($data['password']);

        return $data;
    }
}
