<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\TradeExperience;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'referral_code' => ['nullable']
        ]);

        $trade_experience = TradeExperience::find($request->trade_experience);

        $user = User::create([
            'title'=> $request->title,
            'name'=> $request->name,
            'email'=> $request->email,
            'phone'=> $request->phone,
            'country'=> $request->country,
            'state'=> $request->state,
            'city'=> $request->city,
            'trade_min_experience' => $trade_experience->min_trade_year,
            'trade_max_experience' => $trade_experience->max_trade_year,
            'source_of_fund'=> $request->source_of_fund,
            'gender'=> $request->gender,
            'dob'=> $request->dob,
            'nationality'=> $request->nationality,
            'us_citizen'=> !$request->us_citizen ? 1 : 0,
            'identification_type'=> $request->identification_type,
            'identification_number'=> $request->identification_number,
            'password'=> Hash::make($request->password),
            'referral_code'=> $request->referral_code,
        ]);

        event(new Registered($user));

//        Auth::login($user);

        return response()->noContent();
    }

    public function validateRegister(Request $request)
    {
        $rules = [
            'title' => 'required',
            'name' => 'required',
            'email' => 'required|string|email',
            'phone' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'trade_experience' => 'required',
            'source_of_fund' => 'required',
        ];

        $attributes = [
            'title' => 'Title',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'country' => 'Country',
            'state' => 'State',
            'city' => 'City',
            'trade_experience' => 'Trade experience',
            'source_of_fund' => 'Source of funds',
        ];

        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames($attributes);

        switch ($request->form_step) {
            case 1:
                $validator->validate();
                break;

            case 2:
                $additionalRules = [
                    'gender' => ['required'],
                    'dob' => ['required'],
                    'nationality' => ['required'],
                    'us_citizen' => ['required'],
                    'identification_type' => ['required'],
                    'identification_number' => ['required'],
                    'identity_proof' => ['required'],
                    'address_proof' => ['required'],
                ];
                $rules = array_merge($rules, $additionalRules);

                $additionalAttributes = [
                    'gender' => 'Gender',
                    'dob' => 'Date of birth',
                    'nationality' => 'Nationality',
                    'us_citizen' => 'US citizen',
                    'identification_type' => 'Identification type',
                    'identification_number' => 'Identification number',
                    'identity_proof' => 'IC/Passport photo',
                    'address_proof' => 'Address photo',
                ];
                $attributes = array_merge($attributes, $additionalAttributes);

                $validator = Validator::make($request->all(), $rules);
                $validator->setAttributeNames($attributes);
                $validator->validate();
                break;

            case 3:
                $additionalRules = [
                    'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
                ];
                $rules = array_merge($rules, $additionalRules);

                $additionalAttributes = [
                    'password' => 'Password',
                ];
                $attributes = array_merge($attributes, $additionalAttributes);

                $validator = Validator::make($request->all(), $rules);
                $validator->setAttributeNames($attributes);
                $validator->validate();
                break;

            default:
                // Handle unknown form_step
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Invalid form step',
                ], 422);
        }

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

// Your success logic here

// If validation passes, continue with your logic and return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Validation passed successfully',
        ]);

    }

    public function getRegisterInputs()
    {
        $countries = Country::all();
        $formattedCountries = $countries->map(function ($country) {
            return [
                'value' => $country->id,
                'label' => $country->name,
            ];
        });

        $formattedNationalities = $countries->map(function ($country) {
            return [
                'value' => $country->nationality,
                'label' => $country->nationality,
            ];
        });

        $experiences = TradeExperience::all();

        $formattedExperience = $experiences->map(function ($experience) {
            $label = $experience->min_trade_year;

            if ($experience->max_trade_year) {
                $label .= ' - ' . $experience->max_trade_year . ' years';
            } else {
                $label .= ' years and above';
            }

            return [
                'value' => $experience->id,
                'label' => $label,
            ];
        });

        return response()->json([
            'countries' => $formattedCountries,
            'tradeExperiences' => $formattedExperience,
            'nationalities' => $formattedNationalities
        ]);
    }

    public function getStates(Request $request)
    {
        $states = State::where('country_id', $request->country_id)->get();
        $formattedStates = $states->map(function ($state) {
            return [
                'value' => $state->id,
                'label' => $state->name,
            ];
        });

        return response()->json($formattedStates);
    }

    public function getCities(Request $request)
    {
        $cities = City::where('state_id', $request->state_id)->get();
        $formattedCities = $cities->map(function ($city) {
            return [
                'value' => $city->id,
                'label' => $city->name,
            ];
        });

        return response()->json($formattedCities);
    }
}
