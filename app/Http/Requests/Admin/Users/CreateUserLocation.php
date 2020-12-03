<?php
namespace App\Http\Requests\Admin\Users;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateUserLocation
 * @package App\Http\Requests\Admin\Users
 */
class CreateUserLocation extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'role' => ['required', 'string'],
            'user_id' => ['required', 'string'],
            'city' => ['required', 'string'],
            'location' => ['required', 'string'],
            'neighborhood' => ['required', 'string'],
            'address' => ['required', 'string'],
            'latitude' => ['required', 'string'],
            'longitude' => ['required', 'string']
        ];
    }

    /**
     * Modify input data
     *
     * @return array
     */
    public function getModifiedData(): array
    {
        return $this->only(collect($this->rules())->keys()->all());
    }
}
