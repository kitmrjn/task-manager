<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\WelcomeSetPasswordNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AdminUserService
{
    /**
     * Create a new user, assign permissions, and send the setup email.
     */
    public function createUser(array $data): User
    {
        // Generate a random 16-character secure placeholder password
        $placeholderPassword = Str::random(16);

        $user = User::create([
            'name'           => $data['name'],
            'email'          => $data['email'],
            'password'       => Hash::make($placeholderPassword),
            'role'           => $data['role'],
            'campaign_id'    => $data['campaign_id'] ?? null,
            'team_leader_id' => $data['team_leader_id'] ?? null,
        ]);

        // Initialize default permissions for the new user
        $user->getPermissions();

        // Generate the standard Laravel password reset token
        $token = Password::broker()->createToken($user);

        // Dispatch our custom welcome notification
        $user->notify(new WelcomeSetPasswordNotification($token));

        return $user;
    }
}