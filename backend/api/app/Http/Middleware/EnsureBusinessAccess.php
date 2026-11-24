<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Business;

class EnsureBusinessAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $businessId = $request->header('X-Business-ID');

        if (!$businessId || !ctype_digit((string) $businessId)) {
            return response()->json([
                'success' => false,
                'message' => 'Valid X-Business-ID header is required.',
            ], 400);
        }

        $business = Business::query()
            ->where('businesses.id', $businessId)
            ->where('businesses.status', 'active')
            ->whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id)
                    ->where('business_users.status', 'active');
            })
            ->first();

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this business.',
            ], 403);
        }

        $request->attributes->set('currentBusiness', $business);

        return $next($request);
    }
}
