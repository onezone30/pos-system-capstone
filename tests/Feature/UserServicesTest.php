<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserServicesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_user_with_profile_image()
    {
        Storage::fake('public'); // prevent real storage writes

        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password123',
            'role' => 'admin',
            'password_confirmation' => 'Password123',
            'profile_image' => UploadedFile::fake()->create('avatar.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);

        $user = User::where('email', 'john@example.com')->first();

        Storage::disk('public')->assertExists($user->profile_image);
    }
}
