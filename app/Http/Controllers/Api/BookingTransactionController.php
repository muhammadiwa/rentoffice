<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\OfficeSpace;
use Illuminate\Http\Request;
use App\Models\BookingTransaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ViewBookingResource;

class BookingTransactionController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:255',
                'started_at' => 'required|date',
                'office_space_id' => 'required',
                'total_amount' => 'required|numeric',
            ]);
            $officeSpace = OfficeSpace::find($validatedData['office_space_id']);
            $validatedData['is_paid'] = false;
            $validatedData['booking_trx_id'] = BookingTransaction::generateUniqueTrxId();
            $validatedData['duration'] = $officeSpace->duration;
            $validatedData['ended_at'] = Carbon::parse($validatedData['started_at'])->addDays($officeSpace->duration);

            $bookingTransaction = BookingTransaction::create($validatedData);

            DB::commit();
            return response()->json([
                'message' => 'Booking transaction created successfully',
                'data' => $bookingTransaction,
            ], 201);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Failed to create booking transaction',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function booking_details(Request $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validate([
                'booking_trx_id' => 'required|string|max:255',
                'phone_number' => 'required|string|max:255',
            ]);
            $bookingTransaction = BookingTransaction::where('booking_trx_id', $validatedData['booking_trx_id'])
                ->where('phone_number', $validatedData['phone_number'])
                ->with(['officeSpace', 'officeSpace.city'])
                ->first();

            if (!$bookingTransaction) {
                return response()->json([
                    'message' => 'Booking transaction not found',
                ], 404);
            }

            DB::commit();
            return new ViewBookingResource($bookingTransaction);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Failed to get booking transaction',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
