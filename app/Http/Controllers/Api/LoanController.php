<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Repayment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoanController extends Controller
{
    // ─── List loans ─────────────────────────────────────────────
    public function index(Request $request)
    {
        $loans = Loan::where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'loans'   => $loans
        ]);
    }

    // ─── Create loan ─────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->merge([
            'description' => ucwords(strtolower($request->description))
        ]);

        $request->validate([
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

            'next_of_kin_name'    => 'required|string|max:255',
            'next_of_kin_contact' => 'required|string|max:20',

            'amount'              => 'required|numeric|min:1',
            'description'         => 'required|in:School Fees Loan,Business Loan,Personal Loan,Land Title Loan',

            // ✅ Updated collateral
            'collateral'          => 'required|in:Land,Vehicle Logbook,Business Assets',

            'status'              => 'nullable|string|in:pending,approved,rejected',

            // ✅ New images
            'id_image_front'      => 'required|string',
            'id_image_back'       => 'required|string',
        ]);

        try {
            $frontImage = $this->saveBase64Image($request->id_image_front, 'front');
            $backImage  = $this->saveBase64Image($request->id_image_back, 'back');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process ID images.',
            ], 422);
        }

        $loan = Loan::create([
            'user_id'             => $request->user_id,
            'name'                => $request->name,
            'email'               => $request->email,
            'contact'             => $request->contact,
            'other_contact'       => $request->other_contact,
            'gender'              => $request->gender,
            'location'            => $request->location,
            'current_address'     => $request->current_address,
            'occupation'          => $request->occupation,
            'monthly_income'      => $request->monthly_income,
            'next_of_kin_name'    => $request->next_of_kin_name,
            'next_of_kin_contact' => $request->next_of_kin_contact,
            'amount'              => $request->amount,
            'description'         => $request->description,
            'collateral'          => $request->collateral,
            'status'              => $request->status ?? 'pending',

            // ✅ Save both images
            'id_image_front'      => $frontImage,
            'id_image_back'       => $backImage,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Loan application submitted successfully',
            'loan'    => $loan
        ], 201);
    }

    // ─── Show single loan ───────────────────────────────────────
    public function show($id)
    {
        $loan = Loan::with('user')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

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

    // ─── Update loan ────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $loan = Loan::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$loan) {
            return response()->json([
                'success' => false,
                'message' => 'Loan not found'
            ], 404);
        }

        if ($loan->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending loans can be edited.',
            ], 403);
        }

        if ($request->has('description')) {
            $request->merge([
                'description' => ucwords(strtolower($request->description))
            ]);
        }

        $request->validate([
            'amount'      => 'sometimes|numeric|min:1',
            'description' => 'sometimes|in:School Fees Loan,Business Loan,Personal Loan,Land Title Loan',
            'collateral'  => 'sometimes|in:Land,Vehicle Logbook,Business Assets',
        ]);

        $loan->update([
            'amount'      => $request->amount      ?? $loan->amount,
            'description' => $request->description ?? $loan->description,
            'collateral'  => $request->collateral  ?? $loan->collateral,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Loan updated successfully',
            'loan'    => $loan->fresh()
        ]);
    }

    // ─── Delete loan ────────────────────────────────────────────
    public function destroy($id)
    {
        $loan = Loan::find($id);

        if (!$loan) {
            return response()->json([
                'success' => false,
                'message' => 'Loan not found'
            ], 404);
        }

        // ✅ Delete both images
        if ($loan->id_image_front) {
            Storage::disk('public')->delete($loan->id_image_front);
        }

        if ($loan->id_image_back) {
            Storage::disk('public')->delete($loan->id_image_back);
        }

        $loan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Loan deleted successfully'
        ]);
    }

    // ─── Repayment ──────────────────────────────────────────────
    public function repay(Request $request, $id)
    {
        $loan = Loan::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$loan) {
            return response()->json([
                'success' => false,
                'message' => 'Loan not found'
            ], 404);
        }

        if (!in_array($loan->status, ['approved', 'disbursed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Loan must be approved or disbursed.',
            ], 403);
        }

        $request->validate([
            'amount'         => 'required|numeric|min:1',
            'payment_method' => 'required|in:Mobile Money,Bank Transfer,Cash,Cheque',
        ]);

        $repayment = Repayment::create([
            'loan_id'        => $loan->id,
            'user_id'        => Auth::id(),
            'amount'         => $request->amount,
            'payment_method' => $request->payment_method,
        ]);

        $totalRepaid = Repayment::where('loan_id', $loan->id)->sum('amount');

        if ($totalRepaid >= $loan->amount) {
            $loan->update(['status' => 'completed']);
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Repayment recorded successfully',
            'repayment' => $repayment,
            'loan'      => $loan->fresh(),
        ], 201);
    }

    // ─── Repayment history ──────────────────────────────────────
    public function repayments($id)
    {
        $loan = Loan::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$loan) {
            return response()->json([
                'success' => false,
                'message' => 'Loan not found'
            ], 404);
        }

        $repayments = Repayment::where('loan_id', $loan->id)
            ->latest()
            ->get();

        return response()->json([
            'success'    => true,
            'repayments' => $repayments
        ]);
    }

    // ─── Helper: Save Base64 Image ───────────────────────────────
    private function saveBase64Image($base64Image, $type)
    {
        if (str_contains($base64Image, ',')) {
            $base64Image = explode(',', $base64Image)[1];
        }

        $imageData = base64_decode($base64Image);

        $fileName = 'id_images/' . Str::uuid() . '_' . $type . '.jpg';

        Storage::disk('public')->put($fileName, $imageData);

        return $fileName;
    }
}




// namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Loan;
// use App\Models\Repayment;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Str;

// class LoanController extends Controller
// {
//     // ─── List loans ───────────────────────────────────────────────────────────
//     // Returns only the logged-in user's own loans
//     public function index(Request $request)
//     {
//         $loans = Loan::where('user_id', Auth::id())
//             ->latest()
//             ->get();

//         return response()->json([
//             'success' => true,
//             'loans'   => $loans
//         ]);
//     }

//     // ─── Create a new loan ────────────────────────────────────────────────────
//     public function store(Request $request)
// {
//     $request->merge([
//         'description' => ucwords(strtolower($request->description))
//     ]);

//     $request->validate([
//         'user_id'             => 'required|exists:users,id',
//         'name'                => 'required|string|max:255',
//         'email'               => 'required|email|max:255',
//         'contact'             => 'required|string|max:20',
//         'other_contact'       => 'nullable|string|max:20',
//         'gender'              => 'required|in:Male,Female,Other',
//         'location'            => 'required|string|max:255',
//         'current_address'     => 'required|string',
//         'occupation'          => 'required|string|max:255',
//         'monthly_income'      => 'required|numeric|min:0',

//         'next_of_kin_name'    => 'required|string|max:255',
//         'next_of_kin_contact' => 'required|string|max:20',

//         'amount'              => 'required|numeric|min:1',
//         'description'         => 'required|in:School Fees Loan,Business Loan,Personal Loan,Land Title Loan',

//         // ✅ UPDATED COLLATERAL
//         'collateral'          => 'required|in:Land,Vehicle Logbook,Business Assets',

//         'status'              => 'nullable|string|in:pending,approved,rejected',

//         // ✅ NEW IMAGES
//         'id_image_front'      => 'required|string',
//         'id_image_back'       => 'required|string',
//     ]);

//     try {
//         // Save both images
//         $frontImage = $this->saveBase64Image($request->id_image_front, 'front');
//         $backImage  = $this->saveBase64Image($request->id_image_back, 'back');
//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Failed to process ID images.',
//         ], 422);
//     }

//     $loan = Loan::create([
//         'user_id'             => $request->user_id,
//         'name'                => $request->name,
//         'email'               => $request->email,
//         'contact'             => $request->contact,
//         'other_contact'       => $request->other_contact,
//         'gender'              => $request->gender,
//         'location'            => $request->location,
//         'current_address'     => $request->current_address,
//         'occupation'          => $request->occupation,
//         'monthly_income'      => $request->monthly_income,
//         'next_of_kin_name'    => $request->next_of_kin_name,
//         'next_of_kin_contact' => $request->next_of_kin_contact,
//         'amount'              => $request->amount,
//         'description'         => $request->description,
//         'collateral'          => $request->collateral,
//         'status'              => $request->status ?? 'pending',

//         // ✅ SAVE NEW IMAGES
//         'id_image_front'      => $frontImage,
//         'id_image_back'       => $backImage,
//     ]);

//     return response()->json([
//         'success' => true,
//         'message' => 'Loan application submitted successfully',
//         'loan'    => $loan
//     ], 201);
// }


//     public function show($id)
//     {
//         $loan = Loan::with('user')
//             ->where('id', $id)
//             ->where('user_id', Auth::id())
//             ->first();

//         if (!$loan) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Loan not found'
//             ], 404);
//         }

//         return response()->json([
//             'success' => true,
//             'loan'    => $loan
//         ]);
//     }

//     // ─── Update a loan ────────────────────────────────────────────────────────
//     // Client can only edit their own loan while status is still PENDING
//     public function update(Request $request, $id)
//     {
//         $loan = Loan::where('id', $id)
//             ->where('user_id', Auth::id())
//             ->first();

//         if (!$loan) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Loan not found'
//             ], 404);
//         }

//         // Block editing if loan is no longer pending
//         if ($loan->status !== 'pending') {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'This loan can no longer be edited. Only pending loans can be modified.',
//             ], 403);
//         }

//         if ($request->has('description')) {
//             $request->merge([
//                 'description' => ucwords(strtolower($request->description))
//             ]);
//         }

//         $request->validate([
//             'amount'      => 'sometimes|numeric|min:1',
//             'description' => 'sometimes|in:School Fees Loan,Business Loan,Personal Loan,Land Title Loan',
//             'collateral'  => 'sometimes|nullable|string',
//         ]);

//         $loan->update([
//             'amount'      => $request->amount      ?? $loan->amount,
//             'description' => $request->description ?? $loan->description,
//             'collateral'  => $request->collateral  ?? $loan->collateral,
//         ]);

//         return response()->json([
//             'success' => true,
//             'message' => 'Loan updated successfully',
//             'loan'    => $loan->fresh()
//         ]);
//     }

//     // ─── Delete a loan ────────────────────────────────────────────────────────
//     public function destroy($id)
//     {
//         $loan = Loan::find($id);

//         if (!$loan) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Loan not found'
//             ], 404);
//         }

//         if ($loan->id_image) {
//             Storage::disk('public')->delete($loan->id_image);
//         }

//         $loan->delete();

//         return response()->json([
//             'success' => true,
//             'message' => 'Loan deleted successfully'
//         ]);
//     }

//     // ─── Make a repayment ─────────────────────────────────────────────────────
//     public function repay(Request $request, $id)
//     {
//         $loan = Loan::where('id', $id)
//             ->where('user_id', Auth::id())
//             ->first();

//         if (!$loan) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Loan not found'
//             ], 404);
//         }

//         // Only allow repayments on approved or disbursed loans
//         if (!in_array($loan->status, ['approved', 'disbursed'])) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Repayments can only be made on approved or disbursed loans.',
//             ], 403);
//         }

//         $request->validate([
//             'amount'         => 'required|numeric|min:1',
//             'payment_method' => 'required|string|in:Mobile Money,Bank Transfer,Cash,Cheque',
//         ]);

//         $repayment = Repayment::create([
//             'loan_id'        => $loan->id,
//             'user_id'        => Auth::id(),
//             'amount'         => $request->amount,
//             'payment_method' => $request->payment_method,
//         ]);

//         // Auto-complete the loan if fully repaid
//         $totalRepaid = Repayment::where('loan_id', $loan->id)->sum('amount');
//         if ($totalRepaid >= $loan->amount) {
//             $loan->update(['status' => 'completed']);
//         }

//         return response()->json([
//             'success'   => true,
//             'message'   => 'Repayment recorded successfully',
//             'repayment' => $repayment,
//             'loan'      => $loan->fresh(),
//         ], 201);
//     }

//     // ─── Get repayment history for a loan ─────────────────────────────────────
//     public function repayments($id)
//     {
//         $loan = Loan::where('id', $id)
//             ->where('user_id', Auth::id())
//             ->first();

//         if (!$loan) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Loan not found'
//             ], 404);
//         }

//         $repayments = Repayment::where('loan_id', $loan->id)
//             ->orderBy('created_at', 'desc')
//             ->get();

//         return response()->json([
//             'success'    => true,
//             'repayments' => $repayments
//         ]);
//     }
// }

