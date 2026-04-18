<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Inscription - décision</title></head>
<body style="font-family:sans-serif;background:#f3f4f6;padding:20px">
    <div style="max-width:600px;margin:0 auto;background:#fff;border-radius:12px;padding:40px;box-shadow:0 2px 8px rgba(0,0,0,.1)">
        <div style="text-align:center;margin-bottom:32px">
            <h1 style="color:#2563eb;font-size:24px;margin:0">🔧 M3allem<span style="color:#f97316">Click</span></h1>
        </div>
        <h2 style="color:#374151;font-size:20px">Bonjour {{ $user->name }},</h2>
        <p style="color:#374151;line-height:1.7">
            Après examen de votre profil, nous ne pouvons pas l'approuver pour le moment.
        </p>
        @if($user->rejection_reason)
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:16px;margin:24px 0">
            <p style="color:#dc2626;margin:0;font-weight:bold">Motif :</p>
            <p style="color:#7f1d1d;margin:8px 0 0">{{ $user->rejection_reason }}</p>
        </div>
        @endif
        <p style="color:#374151;line-height:1.7">
            Vous pouvez modifier votre profil et soumettre à nouveau votre demande en vous connectant à votre espace.
        </p>
        <div style="text-align:center;margin:32px 0">
            <a href="{{ url('/pro/dashboard') }}" style="background:#2563eb;color:#fff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:bold">
                Modifier mon profil
            </a>
        </div>
        <hr style="border:none;border-top:1px solid #e5e7eb;margin:32px 0">
        <p style="color:#9ca3af;font-size:12px;text-align:center">© {{ date('Y') }} M3allemClick — Maroc</p>
    </div>
</body>
</html>
