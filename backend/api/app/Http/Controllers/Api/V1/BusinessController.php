<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        $businesses = $request->user()
            ->businesses()
            ->wherePivot('status', 'active')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $businesses,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'business_type' => [
                'required',
                Rule::in([
                    'grocery',
                    'garments',
                    'electronics',
                    'hardware',
                    'wholesale',
                    'pharmacy',
                    'services',
                    'other',
                ]),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
            'gstin' => ['nullable', 'string', 'size:15'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'pincode' => ['nullable', 'string', 'max:10'],
        ]);

        $business = Business::create([
            ...$validated,
            'slug' => Str::slug($validated['name'])
                . '-' . Str::lower(Str::random(8)),
        ]);

        $business->users()->attach($request->user()->id, [
            'role' => 'owner',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Business created successfully',
            'data' => $business,
        ], 201);
    }

    public function show(Request $request, int $id)
    {
        $business = $request->user()
            ->businesses()
            ->wherePivot('status', 'active')
            ->where('businesses.id', $id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $business,
        ]);
    }
}
