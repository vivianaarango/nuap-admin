<?php
namespace App\Http\Requests\Admin\Distributor;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateDistributor
 * @package App\Http\Requests\Admin\Distributor
 */
class CreateDistributor extends FormRequest
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
            'phone' => ['required', 'string', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/', 'string'],
            'business_name' => ['required', 'string'],
            'nit' => ['required', 'string'],
            'second_phone' => ['required', 'string'],
            'commission' => ['numeric', 'min:0.0','max:100.00'],
            'name_legal_representative' => ['required', 'string'],
            'cc_legal_representative' => ['required', 'string'],
            'contact_legal_representative' => ['required', 'string']
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

        $data['role'] = User::DISTRIBUTOR_ROLE;
        $data['status'] = User::STATUS_INACTIVE;
        $data['last_logged_in'] = now();
        $data['password'] = md5($data['password']);
        $data['phone_validated'] = User::PHONE_NOT_VALIDATED;

        return $data;
    }
}
