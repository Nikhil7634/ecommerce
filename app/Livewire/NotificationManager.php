<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class NotificationManager extends Component
{
    public array $notifications = [];
    public bool $loginAlert = false;

    #[On('showNotification')]
    public function addNotification(array $data)
    {
        $id = uniqid();

        $this->notifications[$id] = [
            'id'      => $id,
            'type'    => $data['type'] ?? 'info',
            'title'   => $data['title'] ?? $this->getDefaultTitle($data['type'] ?? 'info'),
            'message' => $data['message'] ?? '',
            'time'    => now()->format('h:i A'),
        ];
    }

    #[On('removeNotification')]
    public function removeNotification(string $id)
    {
        unset($this->notifications[$id]);
    }

    #[On('showLoginAlert')]
    public function showLoginAlert()
    {
        $this->loginAlert = true;
    }

    #[On('hideLoginAlert')]
    public function hideLoginAlert()
    {
        $this->loginAlert = false;
    }

    /* ---------- PUBLIC HELPERS (Blade needs these) ---------- */

    public function getDefaultTitle(string $type): string
    {
        return match ($type) {
            'success' => 'Success!',
            'error'   => 'Error!',
            'warning' => 'Warning!',
            'info'    => 'Information',
            default   => 'Notification',
        };
    }

    public function getNotificationIcon(string $type): string
    {
        return match ($type) {
            'success' => 'fa-check-circle',
            'error'   => 'fa-times-circle',
            'warning' => 'fa-exclamation-triangle',
            'info'    => 'fa-info-circle',
            default   => 'fa-bell',
        };
    }

    public function render()
    {
        return view('livewire.notification-manager');
    }
}
