<?php

namespace App\Http\Middleware;

use App\Models\Device;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DetectDeviceUuid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if device UUID is sent via POST (first time from iOS app)
        if ($request->isMethod('post') && $request->has('device_uuid')) {
            $deviceUuid = $request->input('device_uuid');
            
            // Validate device UUID
            if (!empty($deviceUuid) && is_string($deviceUuid)) {
                // Store device UUID in session
                $request->session()->put('device_uuid', $deviceUuid);
                
                // Check if this device is already registered
                $device = Device::where('device_uuid', $deviceUuid)->first();
                
                if ($device) {
                    // Device found - auto-login the user
                    Auth::login($device->user);
                    
                    // Update last used timestamp
                    $device->update(['last_used_at' => now()]);
                    
                    // Redirect to dashboard
                    return redirect()->route('dashboard');
                }
            }
        }
        
        // Check if device UUID exists in session (subsequent requests)
        if ($request->session()->has('device_uuid') && !Auth::check()) {
            $deviceUuid = $request->session()->get('device_uuid');
            
            // Try to auto-login from session device UUID
            $device = Device::where('device_uuid', $deviceUuid)->first();
            
            if ($device) {
                Auth::login($device->user);
                $device->update(['last_used_at' => now()]);
            }
        }
        
        return $next($request);
    }
}
