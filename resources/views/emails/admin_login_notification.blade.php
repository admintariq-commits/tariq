<div style="font-family: Inter, system-ui, -apple-system, sans-serif; color:#111827;">
    <h2>Administrator Login Alert</h2>
    <p>An administrator account just signed in to TARIQ:</p>
    <ul>
        <li><strong>Name:</strong> {{ $user->name ?? ($user->first_name . ' ' . $user->last_name ?? $user->email) }}</li>
        <li><strong>Email:</strong> {{ $user->email }}</li>
        <li><strong>Role:</strong> {{ $user->role->name ?? 'admin' }}</li>
        <li><strong>Time:</strong> {{ now()->toDayDateTimeString() }}</li>
        <li><strong>IP:</strong> {{ $ip ?? request()->ip() }}</li>
    </ul>
    <p>If this was not expected, please investigate immediately.</p>
</div>