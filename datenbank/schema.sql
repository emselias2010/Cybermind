CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL,
    phone VARCHAR(40) DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    plan VARCHAR(30) DEFAULT 'trial',
    invite_code VARCHAR(50) DEFAULT NULL,
    role VARCHAR(30) DEFAULT 'member',
    paypal_subscription_id VARCHAR(100) DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (username, email, phone, password, plan, role, created_at) VALUES
    ('admin', 'admin@example.com', NULL, '$2y$10$dtvm7NqWDmwL95AmTC0r/ut7BRj8i5PsKnGxAAsczU10UHLr/3ckq', 'business', 'owner', NOW());

CREATE TABLE phishing_exercises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE phishing_exercise_user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exercise_id INT NOT NULL,
    completed TINYINT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE phishing_quiz (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer_a VARCHAR(255) NOT NULL,
    answer_b VARCHAR(255) NOT NULL,
    answer_c VARCHAR(255) NOT NULL,
    correct CHAR(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE phishing_quiz_user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    score INT NOT NULL DEFAULT 0,
    completed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO phishing_exercises (title, content) VALUES ('Einführung in Phishing','Grundlagen und typische Gefahren im Bereich Phishing.'),('Phishing im Alltag','Wie du Risiken rund um Phishing im Alltag erkennst und vermeidest.'),('Schutzmaßnahmen','Konkrete Maßnahmen, um dich vor Angriffen im Bereich Phishing zu schützen.');
INSERT INTO phishing_quiz (question, answer_a, answer_b, answer_c, correct) VALUES ('Worum geht es im Kurs Phishing?','Um Kochrezepte','Um IT-Sicherheit','Um Sport','b'),('Was solltest du bei Phishing tun?','Risiken ignorieren','Wachsam bleiben und schützen','Daten öffentlich teilen','b');

CREATE TABLE social_exercises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE social_exercise_user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exercise_id INT NOT NULL,
    completed TINYINT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE social_quiz (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer_a VARCHAR(255) NOT NULL,
    answer_b VARCHAR(255) NOT NULL,
    answer_c VARCHAR(255) NOT NULL,
    correct CHAR(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE social_quiz_user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    score INT NOT NULL DEFAULT 0,
    completed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO social_exercises (title, content) VALUES ('Einführung in Social Engineering','Grundlagen und typische Gefahren im Bereich Social Engineering.'),('Social Engineering im Alltag','Wie du Risiken rund um Social Engineering im Alltag erkennst und vermeidest.'),('Schutzmaßnahmen','Konkrete Maßnahmen, um dich vor Angriffen im Bereich Social Engineering zu schützen.');
INSERT INTO social_quiz (question, answer_a, answer_b, answer_c, correct) VALUES ('Worum geht es im Kurs Social Engineering?','Um Kochrezepte','Um IT-Sicherheit','Um Sport','b'),('Was solltest du bei Social Engineering tun?','Risiken ignorieren','Wachsam bleiben und schützen','Daten öffentlich teilen','b');

CREATE TABLE password_exercises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE password_exercise_user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exercise_id INT NOT NULL,
    completed TINYINT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE password_quiz (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer_a VARCHAR(255) NOT NULL,
    answer_b VARCHAR(255) NOT NULL,
    answer_c VARCHAR(255) NOT NULL,
    correct CHAR(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE password_quiz_user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    score INT NOT NULL DEFAULT 0,
    completed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO password_exercises (title, content) VALUES ('Starke Passwörter verstehen','Ein sicheres Passwort hat mindestens 12 Zeichen und kombiniert Groß- und Kleinbuchstaben, Zahlen und Sonderzeichen.'),('Keine Wiederverwendung','Verwende für jeden Dienst ein eigenes Passwort, damit ein geleaktes Konto nicht alle anderen gefährdet.'),('Passwort-Manager nutzen','Ein Passwort-Manager erzeugt und speichert lange Zufallspasswörter, du musst dir nur ein Master-Passwort merken.'),('Zwei-Faktor-Authentifizierung','Aktiviere wo möglich 2FA, damit ein gestohlenes Passwort allein nicht ausreicht.'),('Phishing erkennen','Gib Passwörter nie auf verlinkten Seiten aus E-Mails ein, sondern rufe die Seite direkt auf.'),('Passwort-Check','Teste unten die Stärke eines Beispielpassworts.');
INSERT INTO password_quiz (question, answer_a, answer_b, answer_c, correct) VALUES ('Wie lang sollte ein sicheres Passwort mindestens sein?','6 Zeichen','8 Zeichen','12 Zeichen oder mehr','c'),('Was ist der beste Umgang mit vielen Passwörtern?','Ein Passwort für alles','Ein Passwort-Manager','Passwörter aufschreiben','b');

CREATE TABLE malware_exercises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE malware_exercise_user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exercise_id INT NOT NULL,
    completed TINYINT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE malware_quiz (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer_a VARCHAR(255) NOT NULL,
    answer_b VARCHAR(255) NOT NULL,
    answer_c VARCHAR(255) NOT NULL,
    correct CHAR(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE malware_quiz_user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    score INT NOT NULL DEFAULT 0,
    completed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO malware_exercises (title, content) VALUES ('Einführung in Malware & Viren','Grundlagen und typische Gefahren im Bereich Malware & Viren.'),('Malware & Viren im Alltag','Wie du Risiken rund um Malware & Viren im Alltag erkennst und vermeidest.'),('Schutzmaßnahmen','Konkrete Maßnahmen, um dich vor Angriffen im Bereich Malware & Viren zu schützen.');
INSERT INTO malware_quiz (question, answer_a, answer_b, answer_c, correct) VALUES ('Worum geht es im Kurs Malware & Viren?','Um Kochrezepte','Um IT-Sicherheit','Um Sport','b'),('Was solltest du bei Malware & Viren tun?','Risiken ignorieren','Wachsam bleiben und schützen','Daten öffentlich teilen','b');

CREATE TABLE datenschutz_exercises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE datenschutz_exercise_user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exercise_id INT NOT NULL,
    completed TINYINT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE datenschutz_quiz (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer_a VARCHAR(255) NOT NULL,
    answer_b VARCHAR(255) NOT NULL,
    answer_c VARCHAR(255) NOT NULL,
    correct CHAR(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE datenschutz_quiz_user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    score INT NOT NULL DEFAULT 0,
    completed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO datenschutz_exercises (title, content) VALUES ('Einführung in Datenschutz','Grundlagen und typische Gefahren im Bereich Datenschutz.'),('Datenschutz im Alltag','Wie du Risiken rund um Datenschutz im Alltag erkennst und vermeidest.'),('Schutzmaßnahmen','Konkrete Maßnahmen, um dich vor Angriffen im Bereich Datenschutz zu schützen.');
INSERT INTO datenschutz_quiz (question, answer_a, answer_b, answer_c, correct) VALUES ('Worum geht es im Kurs Datenschutz?','Um Kochrezepte','Um IT-Sicherheit','Um Sport','b'),('Was solltest du bei Datenschutz tun?','Risiken ignorieren','Wachsam bleiben und schützen','Daten öffentlich teilen','b');

CREATE TABLE sicheres_surfen_exercises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE sicheres_surfen_exercise_user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exercise_id INT NOT NULL,
    completed TINYINT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE sicheres_surfen_quiz (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer_a VARCHAR(255) NOT NULL,
    answer_b VARCHAR(255) NOT NULL,
    answer_c VARCHAR(255) NOT NULL,
    correct CHAR(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE sicheres_surfen_quiz_user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    score INT NOT NULL DEFAULT 0,
    completed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO sicheres_surfen_exercises (title, content) VALUES ('Einführung in Sicheres Surfen','Grundlagen und typische Gefahren im Bereich Sicheres Surfen.'),('Sicheres Surfen im Alltag','Wie du Risiken rund um Sicheres Surfen im Alltag erkennst und vermeidest.'),('Schutzmaßnahmen','Konkrete Maßnahmen, um dich vor Angriffen im Bereich Sicheres Surfen zu schützen.');
INSERT INTO sicheres_surfen_quiz (question, answer_a, answer_b, answer_c, correct) VALUES ('Worum geht es im Kurs Sicheres Surfen?','Um Kochrezepte','Um IT-Sicherheit','Um Sport','b'),('Was solltest du bei Sicheres Surfen tun?','Risiken ignorieren','Wachsam bleiben und schützen','Daten öffentlich teilen','b');

CREATE TABLE mobile_security_exercises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE mobile_security_exercise_user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exercise_id INT NOT NULL,
    completed TINYINT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE mobile_security_quiz (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer_a VARCHAR(255) NOT NULL,
    answer_b VARCHAR(255) NOT NULL,
    answer_c VARCHAR(255) NOT NULL,
    correct CHAR(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE mobile_security_quiz_user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    score INT NOT NULL DEFAULT 0,
    completed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO mobile_security_exercises (title, content) VALUES ('Einführung in Mobile Sicherheit','Grundlagen und typische Gefahren im Bereich Mobile Sicherheit.'),('Mobile Sicherheit im Alltag','Wie du Risiken rund um Mobile Sicherheit im Alltag erkennst und vermeidest.'),('Schutzmaßnahmen','Konkrete Maßnahmen, um dich vor Angriffen im Bereich Mobile Sicherheit zu schützen.');
INSERT INTO mobile_security_quiz (question, answer_a, answer_b, answer_c, correct) VALUES ('Worum geht es im Kurs Mobile Sicherheit?','Um Kochrezepte','Um IT-Sicherheit','Um Sport','b'),('Was solltest du bei Mobile Sicherheit tun?','Risiken ignorieren','Wachsam bleiben und schützen','Daten öffentlich teilen','b');

CREATE TABLE social_media_exercises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE social_media_exercise_user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exercise_id INT NOT NULL,
    completed TINYINT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE social_media_quiz (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer_a VARCHAR(255) NOT NULL,
    answer_b VARCHAR(255) NOT NULL,
    answer_c VARCHAR(255) NOT NULL,
    correct CHAR(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE social_media_quiz_user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    score INT NOT NULL DEFAULT 0,
    completed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO social_media_exercises (title, content) VALUES ('Einführung in Social Media Sicherheit','Grundlagen und typische Gefahren im Bereich Social Media Sicherheit.'),('Social Media Sicherheit im Alltag','Wie du Risiken rund um Social Media Sicherheit im Alltag erkennst und vermeidest.'),('Schutzmaßnahmen','Konkrete Maßnahmen, um dich vor Angriffen im Bereich Social Media Sicherheit zu schützen.');
INSERT INTO social_media_quiz (question, answer_a, answer_b, answer_c, correct) VALUES ('Worum geht es im Kurs Social Media Sicherheit?','Um Kochrezepte','Um IT-Sicherheit','Um Sport','b'),('Was solltest du bei Social Media Sicherheit tun?','Risiken ignorieren','Wachsam bleiben und schützen','Daten öffentlich teilen','b');

CREATE TABLE cloud_security_exercises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE cloud_security_exercise_user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exercise_id INT NOT NULL,
    completed TINYINT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE cloud_security_quiz (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer_a VARCHAR(255) NOT NULL,
    answer_b VARCHAR(255) NOT NULL,
    answer_c VARCHAR(255) NOT NULL,
    correct CHAR(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE cloud_security_quiz_user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    score INT NOT NULL DEFAULT 0,
    completed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO cloud_security_exercises (title, content) VALUES ('Einführung in Cloud Sicherheit','Grundlagen und typische Gefahren im Bereich Cloud Sicherheit.'),('Cloud Sicherheit im Alltag','Wie du Risiken rund um Cloud Sicherheit im Alltag erkennst und vermeidest.'),('Schutzmaßnahmen','Konkrete Maßnahmen, um dich vor Angriffen im Bereich Cloud Sicherheit zu schützen.');
INSERT INTO cloud_security_quiz (question, answer_a, answer_b, answer_c, correct) VALUES ('Worum geht es im Kurs Cloud Sicherheit?','Um Kochrezepte','Um IT-Sicherheit','Um Sport','b'),('Was solltest du bei Cloud Sicherheit tun?','Risiken ignorieren','Wachsam bleiben und schützen','Daten öffentlich teilen','b');
