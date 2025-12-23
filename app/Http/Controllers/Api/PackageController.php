<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $limit = request()->query('limit', 10);
        $page = request()->query('page', 1);
        $search = request()->query('search', '');
        $orderBy = request()->query('orderBy', 'id');
        $sortBy = request()->query('sortBy', 'asc');

        $query = Package::query();

        if ($search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('provider', 'like', "%$search%");
        }

        $packages = $query->orderBy($orderBy, $sortBy)
                     ->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'message' => 'Success get all packages',
            'data' => $packages->items(),
            'pagination' => [
                'total' => $packages->total(),
                'per_page' => $packages->perPage(),
                'current_page' => $packages->currentPage(),
                'last_page' => $packages->lastPage(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'provider' => 'required|in:Telkomsel,Indosat,XL,Tri,Smartfren,Axis',
                'quota' => 'required|string',
                'price' => 'required|numeric|min:0',
                'validity_days' => 'required|integer|min:1',
                'description' => 'nullable|string',
                'is_active' => 'nullable|in:true,false,1,0,on,off',
                'image' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120'
            ]);

            if (array_key_exists('is_active', $validated)) {
                $validated['is_active'] = in_array($validated['is_active'], ['true', '1', 'on'], true);
            }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('packages', $filename, 'public');
                $validated['image_path'] = $path;
            }

            $package = Package::create($validated);

            return response()->json([
                'message' => 'Package created successfully',
                'data' => $package
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //
    public function show($slug)
    {
        try {
            // Cari by slug, jika tidak ketemu cari by id
            $package = Package::where('slug', $slug)
                ->orWhere('id', $slug)
                ->first();

            if (!$package) {
                return response()->json([
                    'success' => false,
                    'message' => 'Package not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Package retrieved successfully',
                'data' => $package
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve package',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $package = Package::find($id);

            if (!$package) {
                return response()->json([
                    'message' => 'Package not found'
                ], 404);
            }

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:100',
                'provider' => 'sometimes|required|in:Telkomsel,Indosat,XL,Tri,Smartfren,Axis',
                'quota' => 'sometimes|required|string',
                'price' => 'sometimes|required|numeric|min:0',
                'validity_days' => 'sometimes|required|integer|min:1',
                'description' => 'nullable|string',
                'is_active' => 'nullable|in:true,false,1,0,on,off',
                'image' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120' // max 5MB
            ]);

            // Normalize is_active to boolean if provided
            if (array_key_exists('is_active', $validated)) {
                $validated['is_active'] = in_array($validated['is_active'], ['true', '1', 'on'], true);
            }

            // Handle file upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($package->image_path && Storage::disk('public')->exists($package->image_path)) {
                    Storage::disk('public')->delete($package->image_path);
                }

                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('packages', $filename, 'public');
                $validated['image_path'] = $path;
            }

            $package->update($validated);

            return response()->json([
                'message' => 'Package updated successfully',
                'data' => $package
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $package = Package::find($id);

        if (!$package) {
            return response()->json([
                'message' => 'Package not found'
            ], 404);
        }

        // Delete file if exists
        if ($package->image_path && Storage::disk('public')->exists($package->image_path)) {
            Storage::disk('public')->delete($package->image_path);
        }

        $package->delete();

        return response()->json([
            'message' => 'Package deleted successfully'
        ]);
    }
}
