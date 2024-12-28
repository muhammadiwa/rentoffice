<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\OfficeSpace;
use Illuminate\Http\Request;
use App\Models\BookingTransaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ViewBookingResource;
use App\Services\WhatsAppService;

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
            // Load office space with city relationship
            $officeSpace = OfficeSpace::findOrFail($validatedData['office_space_id']);
            $validatedData['is_paid'] = false;
            $validatedData['booking_trx_id'] = BookingTransaction::generateUniqueTrxId();
            $validatedData['duration'] = $officeSpace->duration;
            $validatedData['ended_at'] = Carbon::parse($validatedData['started_at'])->addDays($officeSpace->duration);

            $bookingTransaction = BookingTransaction::create($validatedData);

            // Send WhatsApp notification
            $messageBody = "✅ Booking Confirmation\n\n" .
                      "📋 Booking ID: {$bookingTransaction->booking_trx_id}\n" .
                      "👤 Name: {$bookingTransaction->name}\n" .
                      "📅 Start Date: {$bookingTransaction->started_at}\n" .
                      "⏱️ Duration: {$bookingTransaction->duration} days\n\n";
            
            // $messageBody .= "Hi {$bookingTransaction->name}, Terimakasih telah booking kantor di Rent Office pada {$bookingTransaction->started_at} dengan durasi {$bookingTransaction->duration} hari.\n\n" ;
            $messageBody .= "Pesanan kantor {$bookingTransaction->officeSpace->name} anda sedang kami proses.\n\n";
            $messageBody .= "Kami akan menginformasikan kembali status pesanan anda melalui WhatsApp secepat mungkin.\n\n";
            $messageBody .= "Rent Office\n\n";

            // Send WhatsApp message using try-catch
            try {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://wablaz.com/api/create-message',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array(
                        'appkey' => 'bf4d7e1d-b58d-4aa2-9438-72a0f0507dfa',
                        'authkey' => '4M7ywzSiNcVJ1JFyiePkcI2czQI5VqPPQYyfspBd8wLdFFc3eO',
                        'to' => $bookingTransaction->phone_number,
                        'message' => $messageBody,
                        'sandbox' => 'false'
                    ),
                ));

                $response = curl_exec($curl);
                $error = curl_error($curl);
                curl_close($curl);

                if ($error) {
                    \Log::error('WhatsApp API Error: ' . $error);
                }
            } catch (\Exception $e) {
                \Log::error('WhatsApp Sending Error: ' . $e->getMessage());
            }


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
