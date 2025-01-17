<?php

namespace PTW\Modules\Auth;

use InvalidArgumentException;
use PTW\Models\UserType;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

enum Role: string {
    case Administrator = 'Administrator';
    case User = 'User';

    public static function fromCaseName(string $caseName): self {
        if (defined("self::$caseName")) {
            return constant("self::$caseName");
        }
        throw new InvalidArgumentException("Invalid case name: $caseName");
    }
}

class SessionManager
{
    // Salva i dati dell'utente nella sessione
    public static function saveUserData(array $userData, Role $role): void
    {
        $_SESSION['user'] = [
            'data' => $userData,
            'role' => $role->value,
        ];
    }

    // Recupera i dati dell'utente dalla sessione
    public static function getUserData(): ?array
    {
        return $_SESSION['user']['data'] ?? null;
    }

    // Recupera il ruolo dell'utente dalla sessione
    public static function getUserRole(): ?Role
    {
        return isset($_SESSION['user']['role']) ? Role::from($_SESSION['user']['role']) : null;
    }

    public static function getUserId(): string | null
    {
        return $_SESSION['user']['data'][UserType::id->value] ?? null;
    }

    // Verifica se un utente è autenticato
    public static function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }

    // Filtra l'accesso in base al ruolo
    public static function authorize(Role $requiredRole): bool
    {
        if (!self::isAuthenticated()) {
            return false;
        }

        $currentRole = self::getUserRole();

        return $currentRole === $requiredRole;
    }

    // Elimina i dati della sessione per logout
    public static function logout(): void
    {
        unset($_SESSION['user']);
        session_destroy();
    }
}

// Esempio di utilizzo
// Salvataggio nella sessione
// SessionManager::saveUserData(['id' => 1, 'name' => 'John Doe'], Role::Administrator);

// Recupero dati
// $userData = SessionManager::getUserData();
// $userRole = SessionManager::getUserRole();

// Verifica autenticazione
// $isAuthenticated = SessionManager::isAuthenticated();

// Autorizzazione
// $isAuthorized = SessionManager::authorize(Role::Administrator);

?>