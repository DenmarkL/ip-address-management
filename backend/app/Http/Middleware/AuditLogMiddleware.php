<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use App\Models\IPAddress;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuditLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $deletedRecord = null;

        if ($request->isMethod('delete')) {
            $id = $request->route('id');
            $deletedRecord = IPAddress::find($id);
        }

        $response = $next($request);
        try {
            if ($user = JWTAuth::parseToken()->authenticate()) {
                $action = match (true) {
                    $request->isMethod('post') => 'created',
                    $request->isMethod('put') => 'updated',
                    $request->isMethod('delete') => 'deleted',
                    default => null
                };

                // Extract ID from the response (if available)
                $responseData = json_decode($response->getContent(), true);
                $recordId = $responseData['data']['id'] ?? null;

                if ($action) {
                    AuditLog::create([
                        'user_id' => $user->id,
                        'action' => $action,
                        'ip_id' => $recordId, // Captures the created IP address ID
                        'ip_address' => $request->input('ip_address'),
                        'changes' => json_encode($request->all()),
                    ]);
                }
            }
        } catch (Exception $e) {
            // Token invalid or missing, no action needed
        }

        return $response;
    }
}
