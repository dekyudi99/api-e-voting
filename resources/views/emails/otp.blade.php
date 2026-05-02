@component('mail::message')
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('images/logoUKMCatur.jpg') }}" alt="{{ config('app.name') }} Logo" style="width: 120px;">
</div>

# Halo, {{ $name }}!

Terima kasih telah bergabung. Gunakan kode OTP di bawah ini untuk memverifikasi akun Anda (**{{ $email }}**). Kode ini hanya berlaku selama **5 menit**.

@component('mail::panel')
<div style="text-align: center;">
<span style="font-size: 24px; letter-spacing: 5px; font-family: monospace;">{{ $otp }}</span>
</div>
@endcomponent

Jika Anda tidak merasa melakukan pendaftaran ini, abaikan saja email ini atau hubungi dukungan kami.

Terima kasih,<br>
**{{ config('app.name') }} Team**
@endcomponent