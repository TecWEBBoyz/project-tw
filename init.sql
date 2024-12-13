DROP TABLE IF EXISTS user;
CREATE TABLE user (
    id CHAR(36) PRIMARY KEY DEFAULT UUID(), -- Chiave primaria unica per ogni utente
    name VARCHAR(255) NOT NULL,        -- Nome dell'utente, non nullo
    email VARCHAR(255) NOT NULL UNIQUE, -- Email unica per ogni utente
    telephone VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,    -- Password crittografata
    role ENUM('admin', 'utente', 'moderatore') NOT NULL DEFAULT 'utente', -- Ruolo con valori predefiniti
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Data di creazione
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Data di aggiornamento
);

-- Indice aggiuntivo per velocizzare le ricerche per email
CREATE INDEX idx_email ON user (email);

-- Esempi di dati per la tabella utente
INSERT INTO user (name, email, password, role, telephone) VALUES
('Mario Rossi', 'mario.rossi@example.com', 'password123', 'utente', '+39 320 123 4567'),
('Luigi Verdi', 'luigi.verdi@example.com', 'securepass456', 'utente', '+39 320 234 5678'),
('Anna Bianchi', 'anna.bianchi@example.com', 'mypassword789', 'moderatore', '+39 320 345 6789'),
('Elena Neri', 'elena.neri@example.com', 'pass4elena', 'utente', '+39 320 456 7890'),
('Paolo Gialli', 'paolo.gialli@example.com', 'paolo2023', 'utente', '+39 320 567 8901'),
('Giulia Rosa', 'giulia.rosa@example.com', 'giulia_pwd', 'moderatore', '+39 320 678 9012'),
('Francesco Blu', 'francesco.blu@example.com', 'francesco_secure', 'admin', '+39 320 789 0123'),
('Silvia Marrone', 'silvia.marrone@example.com', 'silvia1234', 'utente', '+39 320 890 1234'),
('Roberto Viola', 'roberto.viola@example.com', 'robertopass', 'utente', '+39 320 901 2345'),
('Chiara Verde', 'chiara.verde@example.com', 'chiara_pw', 'utente', '+39 320 012 3456');