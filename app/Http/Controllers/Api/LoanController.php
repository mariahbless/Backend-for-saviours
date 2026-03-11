<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;

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
            'user_id'     => 'required|exists:users,id',
            'amount'      => 'required|numeric|min:1',
            'description' => 'required|in:School Fees Loan,Business Loan,Personal Loan,Land Title Loan',
            'status'      => 'nullable|string|in:pending,approved,rejected',
        ]);

        $loan = Loan::create([
            'user_id'     => $request->user_id,
            'amount'      => $request->amount,
            'description' => $request->description,
            'status'      => $request->status ?? 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Loan created successfully',
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

        $loan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Loan deleted successfully'
        ]);
    }
}