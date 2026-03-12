<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LoanController extends Controller
{
    // List all loans
    public function index()
    {
        $loans = Loan::with('user')->latest()->get();

        return response()->json([
            'success' => true,
            'loans'   => $loans
        ]);
    }

    // Create a new loan
    public function store(Request $request)
    {
        $request->merge([
            'description' => ucwords(strtolower($request->description))
        ]);

        $request->validate([
            // Personal Information
            'user_id'             => 'required|exists:users,id',
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|max:255',
            'contact'             => 'required|string|max:20',
            'other_contact'       => 'nullable|string|max:20',
            'gender'              => 'required|in:Male,Female,Other',
            'location'            => 'required|string|max:255',
            'current_address'     => 'required|string',
            'occupation'          => 'required|string|max:255',
            'monthly_income'      => 'required|numeric|min:0',

            // Next of Kin
            'next_of_kin_name'    => 'required|string|max:255',
            'next_of_kin_contact' => 'required|string|max:20',

            // Loan Details
            'amount'              => 'required|numeric|min:1',
            'description'         => 'required|in:School Fees Loan,Business Loan,Personal Loan,Land Title Loan',
            'collateral'          => 'nullable|string',
            'status'              => 'nullable|string|in:pending,approved,rejected',

            // ID Image (Base64)
            'id_image'            => 'required|string',
        ]);

        // Decode and save the Base64 ID image as a file
        try {
            $base64Image = $request->id_image;

            // Strip data URI prefix if present e.g. "data:image/jpeg;base64,"
            if (str_contains($base64Image, ',')) {
                $base64Image = explode(',', $base64Image)[1];
            }

            $imageData = base64_decode($base64Image);
            $fileName  = 'id_images/' . Str::uuid() . '.jpg';

            Storage::disk('public')->put($fileName, $imageData);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process ID image. Please try again.',
            ], 422);
        }

        $loan = Loan::create([
            'user_id'             => $request->user_id,

            // Personal Information
            'name'                => $request->name,
            'email'               => $request->email,
            'contact'             => $request->contact,
            'other_contact'       => $request->other_contact,
            'gender'              => $request->gender,
            'location'            => $request->location,
            'current_address'     => $request->current_address,
            'occupation'          => $request->occupation,
            'monthly_income'      => $request->monthly_income,

            // Next of Kin
            'next_of_kin_name'    => $request->next_of_kin_name,
            'next_of_kin_contact' => $request->next_of_kin_contact,

            // Loan Details
            'amount'              => $request->amount,
            'description'         => $request->description,
            'collateral'          => $request->collateral,
            'status'              => $request->status ?? 'pending',

            // ID Image
            'id_image'            => $fileName,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Loan application submitted successfully',
            'loan'    => $loan
        ], 201);
    }

    // Show single loan
    public function show($id)
    {
        $loan = Loan::with('user')->find($id);

        if (!$loan) {
            return response()->json([
                'success' => false,
                'message' => 'Loan not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'loan'    => $loan
        ]);
    }

    // Update a loan
    public function update(Request $request, $id)
    {
        $loan = Loan::find($id);

        if (!$loan) {
            return response()->json([
                'success' => false,
                'message' => 'Loan not found'
            ], 404);
        }

        if ($request->has('description')) {
            $request->merge([
                'description' => ucwords(strtolower($request->description))
            ]);
        }

        $request->validate([
            'amount'      => 'sometimes|numeric|min:1',
            'description' => 'sometimes|in:School Fees Loan,Business Loan,Personal Loan,Land Title Loan',
            'status'      => 'sometimes|string|in:pending,approved,rejected',
        ]);

        $loan->update([
            'amount'      => $request->amount      ?? $loan->amount,
            'description' => $request->description ?? $loan->description,
            'status'      => $request->status      ?? $loan->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Loan updated successfully',
            'loan'    => $loan
        ]);
    }

    // Delete a loan
    public function destroy($id)
    {
        $loan = Loan::find($id);

        if (!$loan) {
            return response()->json([
                'success' => false,
                'message' => 'Loan not found'
            ], 404);
        }

        // Delete the ID image file if it exists
        if ($loan->id_image) {
            Storage::disk('public')->delete($loan->id_image);
        }

        $loan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Loan deleted successfully'
        ]);
    }
}