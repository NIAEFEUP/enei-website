<?php

namespace Tests\Feature;

use App\Http\Controllers\ReferralPointsController;
use App\Models\Participant;
use App\Models\StudentAssociation;
use App\Models\User;
use App\Services\HashIdService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ReferralTest extends TestCase
{
    use RefreshDatabase;


    public function test_referral_code_points_to_referral(): void
    {
        $user = User::create([
            'name' => "John Doe",
            'email' => "john@doe.com",
            'password' => Hash::make('testpassword'),
            'usertype_id' => '0',
            'usertype_type' => Participant::class,
        ]);
        $participant = Participant::create(['user_id' => $user->id]);
        $user->usertype()->associate($participant);
        $user->save();

        $participant = $participant->refresh();

        $ref_code = $participant->getReferralCode();
        $this->assertEquals(
            (new HashIdService())->decode($ref_code),
            $user->id
        );
    }

    public function test_add_referral_cookie(): void
    {
        $user = User::create([
            'name' => "John Doe",
            'email' => "john@doe.com",
            'password' => Hash::make('testpassword'),
            'usertype_id' => '0',
            'usertype_type' => Participant::class,
        ]);
        $participant = Participant::create(['user_id' => $user->id]);
        $user->usertype()->associate($participant);
        $user->save();
        $participant = $participant->refresh();

        $ref_code = $participant->getReferralCode();

        $response = $this->get("/register?ref=" . $ref_code);

        $response->assertStatus(200);
        $response->assertCookie("referral");
        $this->assertEquals($response->getCookie("referral")->getValue(), $ref_code);
        $this->assertEquals(
            (new HashIdService())->decode($ref_code),
            $user->id
        );
    }


    public function test_referral_user_referred_by_user(): void
    {
        $referral_user = User::create([
            'name' => "John Doe",
            'email' => "john@doe.com",
            'password' => Hash::make('testpassword'),
            'usertype_id' => '0',
            'usertype_type' => Participant::class,
        ]);

        $referral_participant = Participant::create(['user_id' => $referral_user->id]);
        $referral_user->usertype()->associate($referral_participant);
        $referral_user->save();
        $referral_participant = $referral_participant->refresh();


        $referred_user = User::create([
            'name' => "Jane Doe",
            'email' => "jane@doe.com",
            'password' => Hash::make('testpassword2'),
            'usertype_id' => '0',
            'usertype_type' => Participant::class,
        ]);
        $referred_participant = Participant::create(['user_id' => $referred_user->id, 'promoter' => null]);
        $referred_user->usertype()->associate($referred_participant);
        $referred_user->save();
        $referred_participant = $referred_participant->refresh();

        $referral_user_ref_code = $referral_participant->getReferralCode();
        ReferralPointsController::AttributePoints($referred_participant, $referral_user_ref_code);

        $this->assertEquals(
            (new HashIdService())->decode($referral_user_ref_code),
            $referral_user->id
        );
        $this->assertNull($referral_participant->promoter);
        $this->assertNull($referred_participant->promoter);
        // TODO: check points
    }


    public function test_referral_user_referred_by_association(): void
    {
        $referral_user = User::create([
            'name' => "Test Association",
            'email' => 'test@association.com',
            'password' => Hash::make('testpassword'),
            'usertype_id' => '0',
            'usertype_type' => StudentAssociation::class,
        ]);
        $referral_association = StudentAssociation::create(
            [
                'user_id' => $referral_user->id,
                'name' => $referral_user->name,
            ]
        );
        $referral_user->usertype()->associate($referral_association);
        $referral_user->save();
        $referral_association = $referral_association->refresh();


        $referred_user = User::create([
            'name' => "Jane Doe",
            'email' => "jane@doe.com",
            'password' => Hash::make('testpassword2'),
            'usertype_id' => '0',
            'usertype_type' => Participant::class,
        ]);
        $referred_participant = Participant::create(['user_id' => $referred_user->id, 'promoter' => null]);
        $referred_user->usertype()->associate($referred_participant);
        $referred_user->save();
        $referred_participant = $referred_participant->refresh();


        $referral_code = $referral_association->getReferralCode();


        ReferralPointsController::AttributePoints($referred_participant, $referral_code);

        $this->assertEquals(
            (new HashIdService())->decode($referral_code),
            $referral_user->id
        );
        $this->assertNull($referred_participant->promoter);
        // TODO: check points
    }




    /**

    public function test_new_users_can_register_without_promoter_code(): void
    {
        if (! Features::enabled(Features::registration())) {
            $this->markTestSkipped('Registration support is not enabled.');

            return;
        }

        $username = 'Test User';
        $password = 'password';

        $response = $this->post('/register/', [
            'name' => $username,
            'email' => 'test@example.com',
            'password' => $password,
            'password_confirmation' => $password,
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
            'promoter' => null,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);

        $participant = User::firstWhere('name', $username);
        $this->assertNull($participant->code);
    }

    public function test_new_users_can_register_with_promoter_code(): void
    {
        if (! Features::enabled(Features::registration())) {
            $this->markTestSkipped('Registration support is not enabled.');

            return;
        }

        $username = 'Test User';
        $password = 'password';
        $promoter_code = 'NUC-1';
        $association_name = 'Test Association';

        $association_data = [
            'name' => $association_name,
            'email' => 'test@association.com',
            'password' => Hash::make($password),
            'usertype_id' => '0',
            'usertype_type' => StudentAssociation::class,
        ];

        $association_user = User::create($association_data);
        $test_association = StudentAssociation::create(
            [
                'user_id' => $association_user->id,
                'name' => $association_data['name'],
                'code' => $promoter_code,
            ]
        );
        $association_user->usertype()->associate($test_association);
        $association_user->save();

        $response = $this->post('/register/', [
            'name' => $username,
            'email' => 'test@example.com',
            'password' => $password,
            'password_confirmation' => $password,
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
            'promoter' => $promoter_code,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);

        // Check if StudentAssociation got the points
        $student_association = StudentAssociation::firstWhere('code', $promoter_code);
        $this->assertEquals($association_name, $student_association->name);
        $this->assertNotNull($student_association->code);
        $this->assertEquals(20, $student_association->points);
    }

     */
}
