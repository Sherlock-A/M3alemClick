<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Profil approuvé</title></head>
<body style="font-family:sans-serif;background:#f3f4f6;padding:20px">
    <div style="max-width:600px;margin:0 auto;background:#fff;border-radius:12px;padding:40px;box-shadow:0 2px 8px rgba(0,0,0,.1)">
        <div style="text-align:center;margin-bottom:32px">
            <h1 style="color:#2563eb;font-size:24px;margin:0">🔧 M3allem<span style="color:#f97316">Click</span></h1>
        </div>
        <h2 style="color:#16a34a;font-size:20px">Félicitations, {{ $user->name }} ! 🎉</h2>
        <p style="color:#374151;line-height:1.7">
            Votre profil professionnel a été <strong>approuvé</strong> par notre équipe.
            Vous êtes maintenant visible sur la plateforme et les clients peuvent vous contacter.
        </p>
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:16px;margin:24px 0">
            <p style="color:#15803d;margin:0">✅ Votre profil est maintenant <strong>actif et public</strong>.</p>
        </div>
        <p style="color:#374151;line-height:1.7">
            Connectez-vous à votre tableau de bord pour compléter votre profil et commencer à recevoir des contacts.
        </p>
        <div style="text-align:center;margin:32px 0">
            <a href="{{ url('/pro/dashboard') }}" style="background:#2563eb;color:#fff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:bold">
                Accéder à mon dashboard
            </a>
        </div>
        <hr style="border:none;border-top:1px solid #e5e7eb;margin:32px 0">
        <p style="color:#9ca3af;font-size:12px;text-align:center">© {{ date('Y') }} M3allemClick — Maroc</p>
    </div>
</body>
</html>
