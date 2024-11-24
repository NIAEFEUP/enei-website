<?php

namespace App\Actions\Fortify;

use App\Models\Participant;
use App\Models\StudentAssociation;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $referral_code = Cookie::get("referral");

        if (! is_null($referral_code)) {

            /*
            $participant = Participant::firstWhere('code', $promoter);
            if (!is_null($participant)) {
                $participant->points = $participant->points + 10;
                $promoter = $participant->code;
            }
            */

            /*
            $student_association = StudentAssociation::firstWhere('code', $promoter);
            if (!is_null($student_association)) {
                $student_association->points = $student_association->points + 20;

                $promoter_code = $student_association->code;

                // save
                if (!is_null($promoter_code)) {
                    // $participant->save();
                    $student_association->save();
                }
            }
            */
        }

        $data = [
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'usertype_id' => '0',
            'usertype_type' => Participant::class,
        ];

        $user = User::create($data);
        // $participant = Participant::create(['user_id' => $user->id, 'code', $promoter_code]);
        $participant = Participant::create(['user_id' => $user->id]);
        $user->usertype()->associate($participant);
        $user->save();

        Log::info('Created user with input: {input}', ['input' => $user->toArray()]);

        return $user;
    }
}
