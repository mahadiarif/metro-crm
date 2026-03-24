<div>
    <p>Dear {{ $proposal->lead->contact_person }},</p>
    
    <p>Thank you for your interest in our services. We are pleased to submit the attached proposal for your review.</p>
    
    <p><strong>Service:</strong> {{ $proposal->service->name }}</p>
    <p><strong>Package:</strong> {{ $proposal->servicePackage->name ?? 'Custom' }}</p>
    
    <p>Please find the detailed proposal attached to this email.</p>
    
    <p>Best regards,<br>
    {{ $proposal->user->name }}<br>
    Sales Executive</p>
</div>
