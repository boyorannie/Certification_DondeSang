<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Requests\StoreRegisterRequestApiController;

class CampagneCollecteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "date" => ['required', 'regex:/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/'],
            'lieu' => ['required'],
            "statut" => ['required', 'in:ouverte,complete'],
            
                 
        ];
    }
    public function failedValidation(validator $validator ){
        throw new HttpResponseException(response()->json([
            'success'=>false,
            'status_code'=>422,
            'error'=>true,
            'message'=>'erreur de validation',
            'errorList'=>$validator->errors()
        ]));
    }
    public function messages(): array
    {
        return [
            'date.required'=> 'Le champs date est obligatoire',
            'date.regex'=> 'Le format de la date doit etre de type annee-mois-jour espace hh:mm:ss',
            'adresse.required'=> 'Le champs adresse est obligatoire',
            'statut.required'=> 'Le champs statut est obligatoire',
            'lieu.required'=> 'Le champs lieu est obligatoire',
            'statut.in' => 'Le statut doit être ouverte ou complete',
            
            
        ];
    }
}
