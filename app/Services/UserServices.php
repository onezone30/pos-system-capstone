<?php

namespace App\Services;

use App\Models\User;
use App\Models\InventoryLogs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UserServices {

    private function handleImage(?TemporaryUploadedFile $file, ?string $oldPath = null) 
    {
        if(!$file) return $oldPath;

        $imageName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $imagePath = $file->storeAs('images/profiles', $imageName, 'public');

        if($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        return $imagePath;
    }

    public function create(array $data) 
    {
        return DB::transaction(function () use ($data) {
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'profile_image' => $this->handleImage($data['profile_image'] ?? null),
                'role' => $data['role'],
                'password' => bcrypt($data['password'])
            ];

            $user = User::create($userData);

            // Log user creation
            InventoryLogs::create([
                'product_id' => null,
                'user_id'    => auth()->id(),
                'type'       => 'adjustment',
                'quantity'   => 0,
                'note'       => "User System: Created new user '{$user->name}' with role '{$user->role}'"
            ]);

            return $user;
        });
    }
    
    public function update(User $user, array $data) 
    {
        return DB::transaction(function () use ($user, $data) {
            $oldRole = $user->role;
            $oldName = $user->name;

            if(isset($data['profile_image']) && $data['profile_image'] instanceof TemporaryUploadedFile) {
                $profileImage = $this->handleImage($data['profile_image'], $user->profile_image);
            } else {
                $profileImage = $user->profile_image;
            }

            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
                'profile_image' => $profileImage,
            ];

            if(!empty($data['password'])) {
                $userData['password'] = bcrypt($data['password']);
            }

            $updated = $user->update($userData);

            if ($updated) {
                // Log significant changes (Name or Role)
                $changes = [];
                if ($oldName !== $data['name']) $changes[] = "name changed from '{$oldName}' to '{$data['name']}'";
                if ($oldRole !== $data['role']) $changes[] = "role changed from '{$oldRole}' to '{$data['role']}'";
                if (!empty($data['password'])) $changes[] = "password reset";

                InventoryLogs::create([
                    'product_id' => null,
                    'user_id'    => auth()->id(),
                    'type'       => 'adjustment',
                    'quantity'   => 0,
                    'note'       => "User System: Updated '{$user->name}' - " . implode(', ', $changes)
                ]);
            }

            return $updated;
        });
    }

    public function delete(User $user)
    {
        return DB::transaction(function () use ($user) {
            $userName = $user->name;

            InventoryLogs::create([
                'product_id' => null,
                'user_id'    => auth()->id(),
                'type'       => 'adjustment',
                'quantity'   => 0,
                'note'       => "User System: Deleted user '{$userName}'"
            ]);

            return $user->delete();
        });
    }
}