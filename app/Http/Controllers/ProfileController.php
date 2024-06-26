<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

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
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }


    /**
     * Set the email notification for weather and clothing suggestions.
     */

    public function setEmailNotification(Request $request)
    {
        $city = $request->input('city');
        $preferred_email_time = $request->input('preferred_email_time');
        $user = $request->user();
        if (!$city || !$preferred_email_time) {
            return Redirect::route('profile.edit')->with('status', 'email-notification-failed');
        }
        $user->city = $city;
        $user->preferred_email_time = $preferred_email_time;
        $user->save();
        return Redirect::route('profile.edit')->with('status', 'email-notification-set');
    }

    /**
     * Remove the email notification for weather and clothing suggestions.
     */

    public function deleteEmailNotification(Request $request)
    {
        $user = $request->user();
        $user->city = null;
        $user->preferred_email_time = null;
        $user->save();
        return Redirect::route('profile.edit')->with('status', 'email-notification-removed');
    }
}
