<div style="font-family: Inter, system-ui, -apple-system, sans-serif; color:#111827;">
    <h2>New Graduate Registration</h2>
    <p>A new graduate submitted their details:</p>
    <ul>
        <li><strong>Name:</strong> {{ $graduate->first_name }} {{ $graduate->last_name }}</li>
        <li><strong>Email:</strong> {{ $graduate->user->email ?? 'N/A' }}</li>
        <li><strong>Phone:</strong> {{ $graduate->phone }}</li>
        <li><strong>University:</strong> {{ $graduate->university }}</li>
        <li><strong>Course:</strong> {{ $graduate->course }}</li>
        <li><strong>Time:</strong> {{ now()->toDayDateTimeString() }}</li>
    </ul>
    <p>Open the admin panel to review the full graduate profile.</p>
</div>