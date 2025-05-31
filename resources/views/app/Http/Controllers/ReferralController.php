<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ReferralCode;
use App\Models\JobApplication;

class ReferralController extends Controller
{
    public function showForm()
    {
        return view('referral.submit');
    }

    public function submitCode(Request $request)
    {
        $request->validate([
            'referral_code' => 'required|string'
        ]);

        $code = $request->input('referral_code');

        // Find valid referral code
        $referral = ReferralCode::where('code', $code)->where('is_used', false)->first();

        if (!$referral) {
            return back()->with('error', 'Referral code is invalid or has already been used.');
        }

        $user = Auth::user();

        // Check if user already applied to this job
        $alreadyApplied = JobApplication::where('job_id', $referral->job_id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyApplied) {
            return back()->with('error', 'You have already applied to this job.');
        }

        // Mark code as used
        $referral->is_used = true;
        $referral->used_by = $user->id;
        $referral->save();

        // Create job application
        JobApplication::create([
            'job_id' => $referral->job_id,
            'user_id' => $user->id,
            'status' => 'Processed',
            'applied_via_referral' => true,
        ]);

        return redirect()->route('applicantdashboard')->with('success', 'You have successfully applied using a referral!');
    }
}