<?php

namespace App\Http\Controllers;

use App\Models\OTP;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OTPController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    /**
     * إرسال OTP إلى الرقم المحدد
     */
    public function sendOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // توليد OTP عشوائي
        $otp = rand(100000, 999999);

        // تخزين OTP في قاعدة البيانات
        OTP::create([
            'phone' => $request->phone,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(5), // صلاحية الكود لمدة 5 دقائق
        ]);

        // إرسال OTP باستخدام خدمة Twilio
        $this->twilioService->sendOTP($request->phone, $otp);

        return response()->json(['message' => 'OTP sent successfully']);
    }

    /**
     * التحقق من OTP
     */
    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric',
            'otp' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $otpRecord = OTP::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->first();

        if (!$otpRecord) {
            return response()->json(['error' => 'Invalid OTP'], 400);
        }

        if ($otpRecord->isExpired()) {
            return response()->json(['error' => 'OTP expired'], 400);
        }

        return response()->json(['message' => 'OTP verified successfully']);
    }
}
