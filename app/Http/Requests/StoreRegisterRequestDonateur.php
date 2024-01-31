<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Requests\StoreRegisterRequestApiController;

class StoreRegisterRequestDonateur extends FormRequest
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
            'name' => ['required', 'min:5', 'regex:/^[a-zA-Z\s]+$/'],
            'prenom' => ['required', 'min:4', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required','min:8'],
            'adresse' => ['required'],
            'sexe' => ['required'],

             'image' => ['required','image','mimes:jpeg,png,jpg'],
            'cni' => ['required','min:13'],
            'groupe_sanguin' => ['required'],
            'telephone' => ['required','regex:/^(70|75|76|77|78)[0-9]{7}$/'],
           
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
            'name.required'=> 'Le champs nom est obligatoire',
            'prenom.required'=> 'Le champs prenom est obligatoire',
            'email.required'=> 'Le champs email est obligatoire',
            'sexe.required'=> 'Le champs sexe est obligatoire',
            'image.required'=> 'Le champs image est obligatoire',
            'groupe_sanguin.required'=> 'Le champs email est obligatoire',
            'cni.required'=> 'Le champs email est obligatoire',
            'adresse.required'=> 'Le champs adresse est obligatoire',
            'telephone.required'=> 'Le champs telephone est obligatoire et doit commencer par 78 ou 77 ou 76 ou 70',
            'password.required'=> 'Le champs mot de passe est obligatoire et doit contenir au   minimum 8 caracteres',
            
        ];
    }
}
