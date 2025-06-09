<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PersonController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'date_of_birth' => 'nullable|date',
                'picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'biography' => 'nullable|string',
                'father_id' => 'nullable|exists:people,id',
                'mother_id' => 'nullable|exists:people,id',
                'phone_number' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string|max:255',
                'is_deceased' => 'nullable|boolean',
                'date_of_death' => 'nullable|date|after:date_of_birth',
            ]);

            if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
                Log::info('Picture upload attempt: ' . $request->file('picture')->getClientOriginalName());
                $path = $request->file('picture')->store('pictures', 'public');
                if (Storage::disk('public')->exists($path)) {
                    Log::info('Picture stored at: ' . $path);
                    $validated['picture'] = $path;
                } else {
                    Log::error('Failed to store picture at: ' . $path);
                    return response()->json(['error' => 'Failed to store image'], 500);
                }
            } else {
                Log::info('No picture uploaded or invalid file');
            }

            $person = Person::create($validated);

            return new PersonResource($person);
        } catch (\Exception $e) {
            Log::error('Error storing person: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create person'], 500);
        }
    }

    public function index()
    {
        $people = Person::all();
        return PersonResource::collection($people);
    }

    public function show($id)
    {
        $person = Person::findOrFail($id);
        return new PersonResource($person);
    }

    public function update(Request $request, $id)
    {
        try {
            // Find person
            $person = Person::findOrFail($id);

            // Validate request
            $validated = $request->validate([
                'full_name' => 'sometimes|string|max:255',
                'date_of_birth' => 'nullable|date',
                'picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'biography' => 'nullable|string',
                'father_id' => 'nullable|exists:people,id',
                'mother_id' => 'nullable|exists:people,id',
                'phone_number' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string|max:255',
                'is_deceased' => 'sometimes|boolean',
                'date_of_death' => 'nullable|date|after:date_of_birth',
            ]);

            // Handle picture update
            if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
                Log::info('Picture update attempt for person ID ' . $id . ': ' . $request->file('picture')->getClientOriginalName());

                // Delete old picture if exists
                if ($person->picture) {
                    Storage::disk('public')->delete($person->picture);
                    Log::info('Deleted old picture: ' . $person->picture);
                }

                // Store new picture
                $path = $request->file('picture')->store('pictures', 'public');
                if (Storage::disk('public')->exists($path)) {
                    Log::info('Picture stored at: ' . $path);
                    $validated['picture'] = $path;
                } else {
                    Log::error('Failed to store picture at: ' . $path);
                    return response()->json(['error' => 'Failed to store image'], 500);
                }
            }

            // Update person
            $person->update($validated);
            Log::info('Person updated: ID ' . $id);

            return response()->json(new PersonResource($person), 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Person not found: ID ' . $id);
            return response()->json(['error' => 'Person not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error updating person ID ' . $id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update person'], 500);
        }
    }
}
