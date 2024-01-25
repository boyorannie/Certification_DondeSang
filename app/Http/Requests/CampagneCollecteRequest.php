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
            "jour" => ['required', 'in:Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi,Dimanche'],
            "heure" => ['required','date_format:H:i:s'],
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
            'jour.required'=> 'Le champs jour est obligatoire',
            'heure.required'=> 'Le champs heure est obligatoire',
            'adresse.required'=> 'Le champs adresse est obligatoire',
            'statut.required'=> 'Le champs email est obligatoire',
            
            
            
        ];
    }
}
