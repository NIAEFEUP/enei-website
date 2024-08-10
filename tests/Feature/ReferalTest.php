<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Participant;
use App\Models\StudentAssociation;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;

class ReferalTest extends TestCase
{
    use RefreshDatabase;

    public function test_referal_page_can_be_rendered(): void
    {
        $response = $this->get('/register/');

        $response->assertStatus(200);
    }

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
                'name'    => $association_data['name'],
                'code'    => $promoter_code
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
}
