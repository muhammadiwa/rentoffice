<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\OfficeSpace;
use Illuminate\Http\Request;
use App\Models\BookingTransaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingTransactionRequest;

class BookingTransactionController extends Controller
{
    // public function store(StoreBookingTransactionRequest $request)
    // {
    //     $validatedData = $request->validated();

    //     $officeSpace = OfficeSpace::find($validatedData['office_space_id']);

    //     $validatedData['is_paid'] = false;
    //     $validatedData['booking_trx_id'] = BookingTransaction::generateUniqueTrxId();
    //     $validatedData['duration'] = $officeSpace->duration;

    //     $validatedData['ended_at'] = Carbon::parse($validatedData['started_at'])->addDays($officeSpace->duration);

    //     $bookingTransaction = BookingTransaction::create($validatedData);


    // }

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
}
