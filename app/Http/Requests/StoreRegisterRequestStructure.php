<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Requests\StoreRegisterRequestStructure;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRegisterRequestStructure extends FormRequest
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
            'name' => ['required', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'email', 'unique:structures,email'],
            'password' => ['required', 'min:8', 'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}$/'],
            'adresse' => ['required'],  
            'image' => ['required','image','mimes:jpeg,png,jpg'],
            'telephone' => ['required', 'regex:/^(33|77|78|70|79|75)[0-9]{7}$/'],

           
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
            'name.regex'=> 'Le champs doit être composé uniquement de lettres majuscules ou minuscules (a à z ou A à Z) et d\'espaces',
            'email.required'=> 'Le champs email est obligatoire',
            'image.required'=> 'L\'image est obligatoire',
            'image.mimes'=> 'L\'image doit avoir le format jpeg,png,jpg ',
            'adresse.required'=> 'Le champs adresse est obligatoire',
            'telephone.required'=> 'Le champs telephone est obligatoire ',
            'telephone.regex'=> 'Le telephone doit commencer par 78 ou 77 ou 76 ou 70, ou 33',
            'password.required'=> 'Le champs mot de passe est obligatoire ',
            'password.regex'=> 'Le mot de passe doit contenir au moins une lettre mininuscule, au moins une lettre majuscule et au moins un caractère spécial',
            'password.min'=> 'Le mot de passe doit contenir au minimum 8 caracteres',
        ];
    }
}
