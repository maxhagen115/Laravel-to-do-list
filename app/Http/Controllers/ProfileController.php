<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();

        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        $request->user()->save();

        return response()->json(['message' => 'Profile updated successfully']);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);
    
        $user = $request->user();
    
        // Begin a transaction to ensure all deletes are handled atomically
        DB::beginTransaction();
    
        try {
            // Delete related projects and tasks
            $user->projects()->each(function ($project) {
                // Delete related tasks first
                $project->tasks()->delete();
                // Now delete the project
                $project->delete();
            });
    
            // Delete the user account
            $user->delete();
    
            // Log the user out
            Auth::logout();
    
            // Invalidate the session and regenerate the CSRF token
            $request->session()->invalidate();
            $request->session()->regenerateToken();
    
            // Commit the transaction
            DB::commit();
    
            return Redirect::to('/')->with('status', 'Account deleted successfully');
        } catch (\Exception $e) {
            // Rollback the transaction in case of any error
            DB::rollBack();
    
            // Return a response with an error message
            return back()->withErrors(['error' => 'There was an issue deleting your account. Please try again later.']);
        }
    }
}
