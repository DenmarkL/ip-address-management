<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIPAddressRequest;
use App\Http\Requests\UpdateIPAddressRequest;
use App\Http\Resources\IPAddressResource;
use App\Models\IPAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class IPAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = IPAddress::query();

        // Check if there's a search query
        if ($request->has('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%$search%")
                    ->orWhere('label', 'like', "%$search%")
                    ->orWhere('ip_type', 'like', "%$search%");
            });
        }

        // Fetch results and hide unnecessary fields
        return IPAddressResource::collection(
            $query->get()->makeHidden(['user', 'created_at'])
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIPAddressRequest $request)
    {
        $validated = $request->validated();

        $validated['ip_type'] = filter_var($validated['ip_address'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? 'IPv6' : 'IPv4';
        $validated['user_id'] = auth()->id();

        $ipAddress = IPAddress::create($validated);

        return new IPAddressResource($ipAddress);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIPAddressRequest $request, string $id)
    {
        $ipAddress = IPAddress::findOrFail($id);
        $validated = $request->validated();

        if ($request->has('ip_address')) {
            $validated['ip_type'] = filter_var($validated['ip_address'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? 'IPv6' : 'IPv4';
        }

        $ipAddress->update($validated);

        return new IPAddressResource($ipAddress);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ipAddress = IPAddress::findOrFail($id);

        if (!Auth::user()->isSuperAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $ipAddress->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
