<?php
namespace PTW\Utility;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class ScrollToUtility
{
    // Imposta un elemento per lo scroll automatico
    public static function setScrollTarget(string $elementId): void
    {
        $_SESSION['scroll_target'] = $elementId;
    }

    // Recupera e cancella il target dello scroll
    public static function getScrollTarget(): ?string
    {
        $scrollTarget = $_SESSION['scroll_target'] ?? null;
        unset($_SESSION['scroll_target']); // Resetta il target dopo l'uso
        return $scrollTarget;
    }
}
