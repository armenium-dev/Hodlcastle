@component('mail::message')
    # Dear {{ $user->name }},

    It has come to our attention that your Phishmanager customer account appears to have been locked due to more than three failed login attempts.

    You can easily enable your account again by visiting our login page and using the password reset functionality. Simply follow the steps provided to reset your password and regain access to your account.

    If you are having trouble with Multi-Factor Authentication (MFA), please contact our support team for assistance (support@phishmanager.com). They will be able to help you resolve the issue and regain access to your account. Please do not reply to this email as it’s an automated response.

    We apologize for any inconvenience this may have caused and we appreciate your cooperation in keeping your account secure.

    Best regards,
    Team Phishmanager
@endcomponent
