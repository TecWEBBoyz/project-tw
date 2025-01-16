<?php
namespace PTW\Utility;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class ToastUtility
{
    // Aggiunge un messaggio di toast alla sessione
    public static function addToast(string $type, string $message): void
    {
        if (!isset($_SESSION['toasts'])) {
            $_SESSION['toasts'] = [];
        }

        $_SESSION['toasts'][] = [
            'type' => $type,
            'message' => $message
        ];
    }

    // Recupera i messaggi di toast e li svuota dalla sessione
    public static function getToasts(): array
    {
        $toasts = $_SESSION['toasts'] ?? [];
        $_SESSION['toasts'] = [];
        return $toasts;
    }
}
