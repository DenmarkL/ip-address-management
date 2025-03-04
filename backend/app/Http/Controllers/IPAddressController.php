<?php

namespace App\Http\Controllers;

use App\Http\Resources\IPAddressResource;
use App\Models\IPAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IPAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return IPAddressResource::collection(IPAddress::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ip_address' => 'required|ip',
            'label' => 'required|string|max:255',
            'comment' => 'nullable|string',
        ]);

        $validated['ip_type'] = filter_var($validated['ip_address'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? 'IPv6' : 'IPv4';

        $validated['user_id'] = Auth::id();

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
    public function update(Request $request, string $id)
    {
        $ipAddress = IPAddress::findOrFail($id);

        if (!Auth::user()->isSuperAdmin() && $ipAddress->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'label' => 'required|string|max:255',
        ]);

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
