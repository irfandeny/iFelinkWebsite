<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Display user's transactions (USER VIEW)
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $perPage = $request->get('per_page', 15);

            $transactions = $user->transactions()
                ->with('package')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Transactions retrieved successfully',
                'data' => $transactions
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve transactions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a specific transaction
     */
    public function show(Request $request, $id)
    {
        try {
            $transaction = Transaction::with('package')->find($id);

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaction retrieved successfully',
                'data' => $transaction
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new transaction (USER MELAKUKAN PEMBELIAN)
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'package_id' => 'required|exists:packages,id',
                'phone_number' => 'required|string|max:15',
                'customer_name' => 'nullable|string|max:255',
                'quantity' => 'required|integer|min:1',
                'payment_method' => 'nullable|string|max:50',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $package = Package::find($request->package_id);

            if (!$package || !$package->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Package not available',
                ], 404);
            }

            $totalAmount = $package->price * $request->quantity;

            $transaction = Transaction::create([
                'user_id' => $request->user()->id,
                'package_id' => $package->id,
                'phone_number' => $request->phone_number,
                'customer_name' => $request->customer_name ?? $request->user()->name,
                'quantity' => $request->quantity,
                'price' => $package->price,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            $transaction->load('package');

            return response()->json([
                'success' => true,
                'message' => 'Transaction created successfully',
                'data' => $transaction
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update transaction status (ADMIN PROSES)
     */
    public function update(Request $request, $id)
    {
        try {
            $transaction = Transaction::find($id);

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found',
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,processing,success,failed,cancelled',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $transaction->update([
                'status' => $request->status,
                'notes' => $request->notes ?? $transaction->notes,
                'processed_at' => $request->status === 'processing' ? now() : $transaction->processed_at,
                'completed_at' => $request->status === 'success' ? now() : $transaction->completed_at,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaction updated successfully',
                'data' => $transaction
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete/Cancel transaction
     */
    public function destroy(Request $request, $id)
    {
        try {
            $transaction = Transaction::find($id);

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found',
                ], 404);
            }

            // Cek status, hanya bisa delete jika pending atau failed
            if (!in_array($transaction->status, ['pending', 'failed'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete transaction with status: ' . $transaction->status,
                ], 400);
            }

            $transaction->delete(); // Soft delete

            return response()->json([
                'success' => true,
                'message' => 'Transaction deleted successfully',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
