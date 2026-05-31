<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Graduate;
use App\Models\AcademicRecord;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    /**
     * Security Dashboard
     */
    public function dashboard()
    {
        $totalUsers = User::count();
        $suspiciousUsers = User::where('is_suspicious', true)->count();
        $vpnDetected = User::where('vpn_detected', true)->count();
        $highBotScore = User::where('bot_score', '>=', 0.65)->count();
        $datacenters = User::where('security_flags->0', 'datacenter_detected')->count();
        
        // Recent suspicious activities
        $recentSuspicious = User::where('is_suspicious', true)
            ->orWhere('bot_score', '>=', 0.65)
            ->orderBy('last_suspicious_activity_at', 'desc')
            ->take(10)
            ->get();
        
        // VPN users
        $vpnUsers = User::where('vpn_detected', true)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Document verification stats
        $documentsVerified = Graduate::where('document_verification_status', 'verified')->count();
        $documentsPending = Graduate::where('document_verification_status', 'pending')->count();
        $documentsRejected = Graduate::where('document_verification_status', 'rejected')->count();
        $documentsManualReview = Graduate::where('document_verification_status', 'manual_review')->count();
        
        $locationMismatchCount = Graduate::whereNotNull('detected_region')
            ->whereRaw('region != detected_region')
            ->count();
        
        // Academic records stats (from AcademicRecord model)
        $academicVerified = AcademicRecord::where('status', 'verified')->count();
        $academicPending = AcademicRecord::where('status', 'pending')->count();
        $academicRejected = AcademicRecord::where('status', 'rejected')->count();
        $academicManualReview = AcademicRecord::where('status', 'manual_review')->count();
        
        return view('admin.security.dashboard', compact(
            'totalUsers', 'suspiciousUsers', 'vpnDetected', 'highBotScore', 'datacenters',
            'recentSuspicious', 'vpnUsers',
            'documentsVerified', 'documentsPending', 'documentsRejected', 'documentsManualReview',
            'locationMismatchCount',
            'academicVerified', 'academicPending', 'academicRejected', 'academicManualReview'
        ));
    }
    
    /**
     * List suspicious users
     */
    public function suspiciousUsers()
    {
        $users = User::where('is_suspicious', true)
            ->orWhere('bot_score', '>=', 0.65)
            ->with('graduate')
            ->paginate(15);
        
        return view('admin.security.suspicious-users', compact('users'));
    }
    
    /**
     * VPN users list
     */
    public function vpnUsers()
    {
        $users = User::where('vpn_detected', true)
            ->orWhere('security_flags->0', 'proxy_detected')
            ->with('graduate')
            ->paginate(15);
        
        return view('admin.security.vpn-users', compact('users'));
    }
    
    /**
     * Document verification queue
     */
    public function documentQueue()
    {
        $pending = Graduate::where('document_verification_status', 'pending')
            ->with('user')
            ->paginate(15);
        
        return view('admin.security.document-queue', compact('pending'));
    }
    
    /**
     * Verify or reject document
     */
    public function verifyDocument(Request $request, Graduate $graduate)
    {
        $validated = $request->validate([
            'status' => 'required|in:verified,rejected,manual_review',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $graduate->update([
            'document_verification_status' => $validated['status'],
            'document_verified_at' => now(),
            'document_verified_by' => auth()->user()->id,
        ]);
        
        // If there's an academic record, update its status too
        if ($graduate->academicRecords()->exists()) {
            $graduate->academicRecords()->update(['status' => $validated['status']]);
            if (!empty($validated['notes'])) {
                $graduate->academicRecords()->first()->update(['notes' => $validated['notes']]);
            }
        }
        
        return back()->with('success', 'Document status updated successfully.');
    }
    
    /**
     * View user details
     */
    public function viewUser(User $user)
    {
        $graduate = $user->graduate;
        $securityData = [
            'ip_address' => $user->ip_address,
            'user_agent' => $user->user_agent,
            'bot_score' => $user->bot_score,
            'vpn_detected' => $user->vpn_detected,
            'is_suspicious' => $user->is_suspicious,
            'security_flags' => $user->security_flags ?? [],
            'last_login_at' => $user->last_login_at,
            'last_suspicious_activity_at' => $user->last_suspicious_activity_at,
        ];
        
        return view('admin.security.user-details', compact('user', 'graduate', 'securityData'));
    }
    
    /**
     * Flag/unflag user as suspicious
     */
    public function toggleSuspicious(Request $request, User $user)
    {
        $user->update([
            'is_suspicious' => !$user->is_suspicious,
            'last_suspicious_activity_at' => now(),
        ]);
        
        return back()->with('success', 'User suspicious flag updated.');
    }
    
    /**
     * Export security log
     */
    public function exportLog()
    {
        $users = User::where('is_suspicious', true)
            ->orWhere('bot_score', '>=', 0.65)
            ->orWhere('vpn_detected', true)
            ->get();
        
        $csv = "Name,Email,IP Address,Bot Score,VPN Detected,Suspicious,Last Suspicious Activity\n";
        
        foreach ($users as $user) {
            $csv .= "\"" . $user->name . "\",";
            $csv .= "\"" . $user->email . "\",";
            $csv .= "\"" . ($user->ip_address ?? 'N/A') . "\",";
            $csv .= $user->bot_score . ",";
            $csv .= ($user->vpn_detected ? 'Yes' : 'No') . ",";
            $csv .= ($user->is_suspicious ? 'Yes' : 'No') . ",";
            $csv .= "\"" . ($user->last_suspicious_activity_at ?? 'N/A') . "\"\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="security-log-' . date('Y-m-d-H-i-s') . '.csv"');
    }
}
