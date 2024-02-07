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
            'name' => ['required', 'regex:/^[a-zA-Z\s]+$/'],
            'prenom' => ['required', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'email', 'unique:donateurs,email'],
            'password' => ['required', 'min:8', 'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}$/'],
            'adresse' => ['required'],
            'sexe' => ['required', 'in:Homme,Femme'],
            'image' => ['required','image','mimes:jpeg,png,jpg'],
            'cni' => ['required','min:13'],
            'groupe_sanguin' => ['required', 'in:O+,O-,A+,A-,B+,B-,AB+,AB-'],
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
            'name.regex'=> 'Le nom est composé uniquement de lettres majuscules ou minuscules et d\'espaces',
            'prenom.required'=> 'Le champs prenom est obligatoire',
            'prenom.regex'=> 'Le prenom est composé uniquement de lettres majuscules ou minuscules et d\'espaces',
            'email.required'=> 'Le champs email est obligatoire',
            'sexe.required'=> 'Le champs sexe est obligatoire',
            'sexe.in'=> 'Le champs sexe prend Homme ou Femme',
            'image.required'=> 'Le champs image est obligatoire',
            'image.mines'=> 'L\'image doit etre de type jpeg ou png ou jpg',
            'groupe_sanguin.required'=> 'Le champs groupe_sanguin est obligatoire',
            'groupe_sanguin.in'=> 'Le champs groupe_sanguin contient l\'un des éléments: O-,O+, A+, A-, B+, B-, AB+, AB-',
            'cni.required'=> 'Le champs cni est obligatoire',
            'cni.min'=> 'La CNI doit contenir 13 caractère',
            'adresse.required'=> 'Le champs adresse est obligatoire',
            'telephone.required'=> 'Le champs telephone est obligatoire',
            'telephone.regex'=> 'Le telephone doit commencer par 78 ou 77 ou 76 ou 70',
            'password.required'=> 'Le champs mot de passe est obligatoire ',
            'password.regex'=> 'Le mot de passe doit contenir au moins une lettre mininuscule, au moins une lettre majuscule et au moins un caractère spécial',
            'password.min'=> 'Le mot de passe doit contenir au minimum 8 caracteres',
            
        ];
    }
}
