<?php

namespace App\Http\Controllers;
use App\Models\Donateur;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;


class ResetPasswordController extends Controller
{
    /**
     * Show the forget password form.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showForgetPasswordForm()
    {
        return response()->json(['message' => 'Afficher le formulaire d\'oubli de mot de passe']);
    }

    /**
     * Submit the forget password form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function soumettreMotpassOublie(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:donateurs',
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        Mail::send('MailConfirmeReset', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return response()->json(['message' => 'Email de récupération envoyé avec succès!']);
    }

    /**
     * Show the reset password form.
     *
     * @param  string  $token
 
     */
    public function showResetPasswordForm($token)
    {
        return view('resetPassword', ['token' => $token]);
    }

    /**
     * Submit the reset password form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitResetPasswordForm(Request $request)
    {
  
   $request->validate([
    'password' => 'required|string|min:8', 
    'password_confirmation' => 'required|same:password',
]);

        $updatePassword = DB::table('password_reset_tokens')
            ->where([

                'token' => $request->token,
            ])
            ->first();

        if (!$updatePassword) {
           
            return response()->json(['error' => 'Ressouces introuvables'], 404);
        }
        $user = DB::table('password_reset_tokens')->where(['token' => $request->token])->first();
       $donateur= Donateur::where('email', $user->email)
            ->update(['password' => Hash::make($request->password)]);

            

        DB::table('password_reset_tokens')->where(['token' => $request->token])->delete();

        return response()->json(['message' => 'Votre mot de passe est mis a jour avec succès']);
    }
}
