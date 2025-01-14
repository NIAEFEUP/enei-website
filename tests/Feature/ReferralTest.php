<?php

namespace Tests\Feature;

use App\Http\Controllers\ReferralPointsController;
use App\Models\Participant;
use App\Models\StudentAssociation;
use App\Models\User;
use App\Services\HashIdService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
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

        $referral_participant = $referral_participant->refresh();
        $referred_participant = $referred_participant->refresh();

        $this->assertEquals(
            (new HashIdService())->decode($referral_user_ref_code),
            $referral_user->id
        );
        $this->assertNull($referral_participant->promoter);
        $this->assertNull($referred_participant->promoter);
        $this->assertEquals(ReferralPointsController::$ParticipantPoints, $referral_participant->points);
        $this->assertEquals(0, $referred_participant->points);
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


        $referred_user = User::create([
            'name' => "Jane Doe",
            'email' => "jane@doe.com",
            'password' => Hash::make('testpassword2'),
            'usertype_id' => '0',
            'usertype_type' => Participant::class,
        ]);
        $referred_participant = Participant::create(['user_id' => $referred_user->id]);
        $referred_user->usertype()->associate($referred_participant);
        $referred_user->save();

        $referral_code = $referral_association->getReferralCode();

        ReferralPointsController::AttributePoints($referred_participant, $referral_code);

        $referral_association = $referral_association->refresh();
        $referred_participant = $referred_participant->refresh();

        $this->assertEquals(
            (new HashIdService())->decode($referral_code),
            $referral_user->id
        );
        $this->assertNull($referred_participant->promoter);
        $this->assertEquals(ReferralPointsController::$StudentAssociationPoints, $referral_association->points);
        $this->assertEquals(0, $referred_participant->points);
    }


    public function test_referral_user_referred_by_user_by_association(): void
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


        $first_referred_user = User::create([
            'name' => "Jane Doe",
            'email' => "jane@doe.com",
            'password' => Hash::make('testpassword2'),
            'usertype_id' => '0',
            'usertype_type' => Participant::class,
        ]);
        $first_referred_participant = Participant::create(['user_id' => $first_referred_user->id]);
        $first_referred_user->usertype()->associate($first_referred_participant);
        $first_referred_user->save();

        $first_referral_code = $referral_association->getReferralCode();

        ReferralPointsController::AttributePoints($first_referred_participant, $first_referral_code);

        $referral_association = $referral_association->refresh();
        $first_referred_participant = $first_referred_participant->refresh();

        $second_referred_user = User::create([
            'name' => "John Doe",
            'email' => "john@doe.com",
            'password' => Hash::make('testpassword'),
            'usertype_id' => '0',
            'usertype_type' => Participant::class,
        ]);
        $second_reffered_participant = Participant::create(['user_id' => $second_referred_user->id]);
        $second_referred_user->usertype()->associate($second_reffered_participant);
        $second_referred_user->save();

        $second_referral_code = $first_referred_participant->getReferralCode();

        ReferralPointsController::AttributePoints($first_referred_participant, $second_referral_code);

        $referral_association = $referral_association->refresh();
        $first_referred_participant = $first_referred_participant->refresh();
        $second_reffered_participant = $second_reffered_participant->refresh();

        $this->assertEquals(
            (new HashIdService())->decode($second_referral_code),
            $first_referred_user->id
        );
        $this->assertNull($first_referred_participant->promoter);
        $this->assertEquals(ReferralPointsController::$StudentAssociationPoints + ReferralPointsController::$ParticipantPoints, $referral_association->points);
        $this->assertEquals(ReferralPointsController::$ParticipantPoints, $first_referred_participant->points);
        $this->assertEquals(0, $second_reffered_participant->points);
    }
}
