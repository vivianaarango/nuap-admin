<?php
namespace App\Http\Requests\Admin\Client;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

/**
 * Class CreateClient
 * @package App\Http\Requests\Admin\Commerce
 */
class CreateClient extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'string', 'unique:users'],
            'country_code' => ['required', 'string'],
            'phone' => ['required', 'string', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/', 'string'],
            'name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'identity_number' => ['required', 'string'],
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

        $data['role'] = User::USER_ROLE;
        $data['status'] = User::STATUS_ACTIVE;
        $data['last_logged_in'] = now();
        $data['password'] = md5($data['password']);
        $data['phone_validated'] = User::PHONE_NOT_VALIDATED;
        $data['api_token'] = Str::random(60);

        return $data;
    }
}
