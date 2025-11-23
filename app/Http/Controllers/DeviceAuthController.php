<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DeviceAuthController extends Controller
{
    /**
     * Handle device registration for new users
     */
    public function register(Request $request)
    {
        // Get device UUID from session
        $deviceUuid = $request->session()->get('device_uuid');
        
        if (!$deviceUuid) {
            return back()->with('error', 'Device UUID not found. Please try again.');
        }
        
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Registration failed. Please check your input.');
        }
        
        try {
            // Create new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            
            // Create device record
            Device::create([
                'user_id' => $user->id,
                'device_uuid' => $deviceUuid,
                'last_used_at' => now(),
            ]);
            
            // Login the user
            Auth::login($user);
            
            return redirect()->route('dashboard');
            
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'An error occurred during registration. Please try again.');
        }
    }
    
    /**
     * Handle device login for existing users (adding new device)
     */
    public function login(Request $request)
    {
        // Get device UUID from session
        $deviceUuid = $request->session()->get('device_uuid');
        
        if (!$deviceUuid) {
            return back()->with('error', 'Device UUID not found. Please try again.');
        }
        
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);
        
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Login failed. Please check your input.');
        }
        
        // Attempt to authenticate
        $user = User::where('email', $request->email)->first();
        
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withInput(['email' => $request->email])
                ->with('error', 'Invalid credentials. Please try again.');
        }
        
        try {
            // Check if this device is already registered to this user
            $existingDevice = Device::where('device_uuid', $deviceUuid)
                ->where('user_id', $user->id)
                ->first();
            
            if (!$existingDevice) {
                // Add new device to user's account
                Device::create([
                    'user_id' => $user->id,
                    'device_uuid' => $deviceUuid,
                    'last_used_at' => now(),
                ]);
            } else {
                // Update last used timestamp
                $existingDevice->update(['last_used_at' => now()]);
            }
            
            // Login the user
            Auth::login($user);
            
            return redirect()->route('dashboard');
            
        } catch (\Exception $e) {
            return back()
                ->withInput(['email' => $request->email])
                ->with('error', 'An error occurred during login. Please try again.');
        }
    }
}
