<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class Settings extends Component
{
    // General
    public string $appName        = '';
    public string $appEmail       = '';
    public string $appPhone       = '';
    public string $appAddress     = '';

    // WhatsApp
    public string $whatsappMessage = '';

    // Limits
    public int    $maxProsPerCity  = 50;
    public int    $maxReviewsPerDay = 10;

    // Sections
    public string $activeTab = 'general';

    public bool $saved = false;

    public function mount(): void
    {
        $this->appName        = config('app.name', 'M3allemClick');
        $this->appEmail       = env('MAIL_FROM_ADDRESS', 'contact@m3allemclick.ma');
        $this->appPhone       = '+212 6XX XXX XXX';
        $this->appAddress     = 'Casablanca, Maroc';
        $this->whatsappMessage = 'Bonjour, j\'ai trouvé votre profil sur M3allemClick. Êtes-vous disponible ?';
        $this->maxProsPerCity  = 50;
        $this->maxReviewsPerDay = 10;
    }

    public function saveGeneral(): void
    {
        $this->validate([
            'appName'    => 'required|min:2|max:60',
            'appEmail'   => 'required|email',
            'appPhone'   => 'nullable|max:30',
            'appAddress' => 'nullable|max:100',
        ]);

        $this->updateEnv([
            'APP_NAME'          => '"'.$this->appName.'"',
            'MAIL_FROM_ADDRESS' => '"'.$this->appEmail.'"',
        ]);

        $this->saved = true;
        $this->dispatch('toast-success', message: 'Paramètres généraux sauvegardés.');
    }

    public function saveWhatsapp(): void
    {
        $this->validate(['whatsappMessage' => 'required|min:10|max:300']);
        $this->saved = true;
        $this->dispatch('toast-success', message: 'Message WhatsApp sauvegardé.');
    }

    public function saveLimits(): void
    {
        $this->validate([
            'maxProsPerCity'   => 'required|integer|min:1|max:500',
            'maxReviewsPerDay' => 'required|integer|min:1|max:100',
        ]);
        $this->saved = true;
        $this->dispatch('toast-success', message: 'Limites sauvegardées.');
    }

    public function clearCache(): void
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        $this->dispatch('toast-success', message: 'Cache vidé avec succès.');
    }

    private function updateEnv(array $data): void
    {
        $envPath = base_path('.env');
        $content = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            if (preg_match("/^{$key}=.*/m", $content)) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
            } else {
                $content .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envPath, $content);
    }

    public function render()
    {
        return view('livewire.admin.settings')
            ->layout('layouts.admin', ['title' => 'Paramètres', 'active' => 'settings']);
    }
}
