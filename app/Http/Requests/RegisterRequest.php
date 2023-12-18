<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'name' => ['required'],
            'email' => ['required', 'string', 'email'],
            'country' => ['required'],
            'state' => ['required'],
            'city' => ['required'],
            'trade_experience' => ['required'],
            'source_of_fund' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return [
            'title' => 'Title',
            'name' => 'Name',
            'email' => 'Email',
            'country' => 'Country',
            'state' => 'State',
            'city' => 'City',
            'trade_experience' => 'Trade experience',
            'source_of_fund' => 'Source of funds',
        ];
    }
}
