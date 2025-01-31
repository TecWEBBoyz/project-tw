DROP TABLE IF EXISTS booking;
DROP TABLE IF EXISTS image_order_counter;
DROP TABLE IF EXISTS image;
DROP TABLE IF EXISTS user;

CREATE TABLE user (
    id CHAR(36) PRIMARY KEY DEFAULT UUID(), -- Chiave primaria unica per ogni utente
    name VARCHAR(255) NOT NULL UNIQUE,        -- Nome dell'utente, non nullo
    email VARCHAR(255) NOT NULL UNIQUE, -- Email unica per ogni utente
    telephone VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,    -- Password crittografata
    role ENUM("Administrator", "User") NOT NULL DEFAULT "User", -- Ruolo con valori predefiniti
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Data di creazione
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Data di aggiornamento
);

-- Indice aggiuntivo per velocizzare le ricerche per email
CREATE INDEX idx_email ON user (email);
CREATE INDEX idx_name ON user (name);

CREATE TABLE image (
   id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
   order_id INT NOT NULL,
   path VARCHAR(255) NOT NULL,
   alt VARCHAR(255) NOT NULL DEFAULT '',
   description VARCHAR(512) NOT NULL DEFAULT '',
   title VARCHAR(255) NOT NULL DEFAULT '',
   place VARCHAR(255) NOT NULL DEFAULT '',
   date DATE DEFAULT NULL,
   visible BOOLEAN DEFAULT FALSE,
   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
   category ENUM('Travels', 'Events', 'Racing-Cars') DEFAULT 'Events'
   -- UNIQUE (category, order_id) -- Impedisce duplicati nello stesso gruppo
);

CREATE TABLE image_order_counter (
     category ENUM('Travels', 'Events', 'Racing-Cars') PRIMARY KEY,
     last_order INT NOT NULL DEFAULT 0
);

DELIMITER $$

CREATE TRIGGER before_insert_image
    BEFORE INSERT ON image
    FOR EACH ROW
BEGIN
    DECLARE next_order INT;

    -- Ottieni il prossimo numero per la categoria
    SELECT last_order + 1 INTO next_order FROM image_order_counter WHERE category = NEW.category FOR UPDATE;

    -- Se la categoria non esiste nella tabella contatori, inizializzala
    IF next_order IS NULL THEN
        INSERT INTO image_order_counter (category, last_order) VALUES (NEW.category, 1);
        SET next_order = 1;
    ELSE
        -- Aggiorna il contatore
    UPDATE image_order_counter SET last_order = next_order WHERE category = NEW.category;
END IF;

-- Assegna il nuovo valore a order_id
SET NEW.order_id = next_order;
END$$

DELIMITER ;



CREATE TABLE booking (
    id CHAR(36) PRIMARY KEY DEFAULT UUID(),
    user CHAR(36) NOT NULL,
    status ENUM("pending", "confirmed", "cancelled") NOT NULL DEFAULT "pending",
    service TINYTEXT NOT NULL,
    date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notes TEXT NOT NULL DEFAULT "",
    FOREIGN KEY (user) REFERENCES user(id) ON DELETE CASCADE
);

DELIMITER $$

CREATE TRIGGER before_update_image
    BEFORE UPDATE ON image
    FOR EACH ROW
BEGIN
    SET NEW.updated_at = CURRENT_TIMESTAMP;
END$$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER before_update_booking
    BEFORE UPDATE ON booking
    FOR EACH ROW
BEGIN
    SET NEW.updated_at = CURRENT_TIMESTAMP;
END$$

DELIMITER ;

-- Esempi di dati per la tabella utente
INSERT INTO user (name, email, password, role, telephone) VALUES
("admin", "admin", SHA2("admin", 256), "Administrator", "+39 320 123 4567"),
("Mario Rossi", "mario.rossi@example.com", SHA2("password123", 256), "User", "+39 320 123 4568"),
("Luigi Verdi", "luigi.verdi@example.com", SHA2("securepass456", 256), "User", "+39 320 234 5678"),
("Anna Bianchi", "anna.bianchi@example.com", SHA2("mypassword789", 256), "User", "+39 320 345 6789"),
("Elena Neri", "elena.neri@example.com", SHA2("pass4elena", 256), "User", "+39 320 456 7890"),
("Paolo Gialli", "paolo.gialli@example.com", SHA2("paolo2023", 256), "User", "+39 320 567 8901"),
("Giulia Rosa", "giulia.rosa@example.com", SHA2("giulia_pwd", 256), "User", "+39 320 678 9012"),
("Francesco Blu", "francesco.blu@example.com", SHA2("francesco_secure", 256), "User", "+39 320 789 0123"),
("Silvia Marrone", "silvia.marrone@example.com", SHA2("silvia1234", 256), "User", "+39 320 890 1234"),
("Roberto Viola", "roberto.viola@example.com", SHA2("robertopass", 256), "User", "+39 320 901 2345"),
("Chiara Verde", "chiara.verde@example.com", SHA2("chiara_pw", 256), "User", "+39 320 012 3456"),
("user", "user@example.com", SHA2("user", 256), "User", "+39 333 333 3333");

INSERT INTO image (path, alt, description, title, place, date) VALUES
("502A6233.JPG", "Cantante su palco con chitarra elettrica rossa", "In una notte estiva a Milano, un musicista appassionato regala un\'esibizione emozionante durante un festival locale, la sua musica si fonde con le luci rosse che riempiono l\'atmosfera e il cuore del pubblico.", "Concerto sotto luce scarlatta", "Milano", "2023-08-15"),
("502A6394.JPG", "Uomo che suona il sax con effetti di luce", "L\'immagine racconta di un musicista immerso nella passione della sua performance, un sassofonista sul palco di un festival locale, catturato mentre coinvolge il pubblico con il suo talento e carisma. L\'atmosfera è carica di emozioni e la folla è affascinata dalle melodie avvolgenti.", "Momento di Jazz Incandescente", "Milano, Italia", "2023-08-15"),
("502A1075.JPG", "Piste innevate nelle Dolomiti sotto cime rocciose.", "Una giornata invernale nelle Dolomiti, meta ambita dagli appassionati di sci e avventura. L\'immagine cattura la tranquillità prima dell\'arrivo dei turisti, con le piste pronte per una nuova stagione.", "Inverno nelle Dolomiti di Sesto", "Sesto, Italia", "2022-12-11"),
("502A6195.JPG", "Band su palco con luci rosse, sax e chitarra elettrica", "Una band si esibisce in un concerto estivo all\'aperto, coinvolgendo il pubblico con un mix di suoni jazz e rock. L\'atmosfera è complice e vibrante, con luci rosse che aggiungono calore alla serata. La foto cattura l\'energia e la passione dei musicisti durante una performance memorabile.", "Concerto estivo vibrante", "Milano, Italia", "2023-08-15"),
("502A1572.JPG", "Fontana al tramonto con silhouette femminile", "Era un tardo pomeriggio d\'autunno a Londra. Una giovane donna, avvolta nel suo cappotto arancione, passeggiava per Trafalgar Square. Contemplava il tramonto, mentre l\'acqua della fontana creava riflessi scintillanti. In quel momento, un fotografo catturò la scena, immortalando un istante di quiete nel trambusto cittadino.", "Tramonto in piazza", "Londra, Regno Unito", "2023-05-21"),
("502A1434_BW.JPG", "Persone camminano in una strada ombreggiata", "Una coppia cammina tranquillamente lungo una strada animata di Londra, catturando un momento di quotidiana serenità in una giornata di sole. L\'immagine è stata scattata per immortalare la vita urbana e il contrasto tra ombra e luce.", "Passeggiata a Londra", "Londra, Regno Unito", "2023-05-20"),
("502A4586.jpg", "Cascata tra rocce e vegetazione rigogliosa", "La cascata, nascosta tra le montagne, attira gli escursionisti che cercano un momento di pace. È stata immortalata in una giornata estiva, quando il sole illumina il verde lussureggiante, esaltando la bellezza naturale del luogo.", "Cascata alpina taglia la vegetazione", "Alpi Italiane", "2022-12-13"),
("502A4325.jpg", "Strada montana tra verdi pendii e vette innevate", "Un sentiero di montagna ai piedi delle Alpi, immerso nella pace e nell\'ispirazione che solo la natura sa offrire. Lo scatto cattura la maestosità delle vette e la serenità del percorso, come ricordo di un momento di riflessione.", "Sentiero fra le Alpi", "Alpi Italiane", "2022-12-13"),
("502A2847.jpg", "Persona che fotografa un fiume con palazzi sullo sfondo", "In una giornata autunnale, un fotografo cattura la quiete del fiume. Il cielo è coperto e le sedie vuote suggeriscono un momento di riflessione solitaria, forse in cerca di ispirazione o di pace interiore.", "Scatto di serenità sul fiume", "Praga, Repubblica Ceca", "2022-09-04"),
("502A4319.jpg", "Vallata montana avvolta da nuvole basse", "Questa immagine racconta la quiete di un pascolo alpino in estate, dove le nuvole basse avvolgono le cime, creando un\'atmosfera mistica e tranquilla. Scattata per catturare la bellezza naturale e serena delle montagne, la scena invita a riflettere sulla maestosità della natura.", "Quiete Alpina", "Alpi Italiane", "2022-12-12"),
("502A3141.jpg", "Strada cittadina tra edifici storici illuminata di notte", "Una serata tranquilla in città, dove i lampioni e le architetture storiche raccontano storie di un passato glorioso, evocando la bellezza della civiltà che ha costruito questi edifici. L\'immagine cattura il silenzio e la maestosità di un angolo urbano, uno spazio fuori dal tempo che invita a passeggiare e riflettere.", "Notturno di Eleganza Urbana", "Praga, Repubblica Ceca", "2022-09-04"),
("502A2935.jpg", "Abbraccio tra persone in piazza con tram in movimento", "Una giornata autunnale in cui la piazza si anima di abbracci e saluti, con le persone che camminano frettolosamente sotto lo sguardo attento dei vecchi edifici, mentre un tram sfila in corsa. È un momento di quotidianità in cui storie diverse si intrecciano nel cuore della città.", "Un abbraccio in città", "Praga, Repubblica Ceca", "2022-09-05"),
("502A1549.JPG", "Strada cittadina con auto nera e altre auto parcheggiate", "Nel cuore di una giornata frenetica, un'auto si sposta velocemente lungo le vie storiche della città, trasportando persone indaffarate verso le loro destinazioni. L\'immagine cattura un momento di transizione nel trambusto quotidiano, dove il passato incontra il presente.", "Vita Urbana in Movimento", "Londra, Regno Unito", "2023-05-21"),
("502A2441.jpg", "Auto da formula uno in pista con pubblico in sfondo", "Auto immortalata durante una gara frenetica, a testimoniare la velocità e inafferrabilità dei singoli istanti della vita. La foto scattata durante un evento automobilistico, cattura l\'emozione degli spettatori e la velocità sul circuito.", "Velocità in pista", "Monza, Italia", "2023-10-27"),
("502A2305.jpg", "Auto da corsa in pista con pubblico sugli spalti", "Un\'auto sfreccia in una gara, un momento di eccitazione per il pubblico entusiasta che assiste al brivido della competizione, forse immortalata per celebrare la passione per le corse.", "La Velocità che Incanta", "Monza, Italia", "2023-10-27"),
("502A3683.jpg", "Auto da corsa sfrecciano su un circuito", "Durante una giornata di gara intensa, due vetture gareggiano per la vittoria su un circuito, catturando l\'essenza della velocità e della competizione.", "Duello in pista", "Imola, Italia", "2024-02-13"),
("502A0181.jpg", "Interno di un\'auto sportiva in bianco e nero", "Foto che immortala l\'essenza di lusso e performance di una supercar. Un momento di contemplazione sul design sofisticato e sull\'ingegneria avanzata.", "Eleganza Automobilistica", "Padova, Italia", "2023-09-30"),
("502A3583.jpg", "Volante di un\'auto sportiva con mappa di un circuito", "L\'immagine cattura l\'eccitazione e la preparazione per una gara imminente. La mappa del circuito incollata sul volante rappresenta l\'attenzione ai dettagli e la strategia del pilota. Lo scatto mostra l\'intensità percettibile della competizione automobilistica.", "Preparazione alla Gara", "Monza, Italia", "2023-10-27"),
("502A2974.jpg", "Coppia che cammina in una ampia strada tra edifici storici.", "Una coppia passeggia serenamente per una strada di Praga, immersi nelle bellezze storiche della città, lontani dal frastuono della vita moderna. La scena cattura un momento di tranquillità nell\'affollata vita urbana.", "Passeggiata tra le vie storiche di Praga", "Praga, Repubblica Ceca", "2022-09-05"),
("IMG_7357.JPG", "Vallata invernale fra alberi, montagne e neve", "La foto immortala la quiete delle Dolomiti, con le nuvole che sembrano accarezzare le cime innevate, simbolo di pace invernale.", "Vallata bianca fra le Dolomiti", "Alpi Italiane", "2022-12-12"),
("502A3033.jpg", "Banda militare in marcia davanti a un palazzo storico", "Una banda militare sta partecipando a una cerimonia ufficiale, attirando l\'attenzione dei turisti e dei passanti, in un evento tradizionale della città.", "Parata della Banda Militare", "Praga, Repubblica Ceca", "2022-09-04"),
("502A1459.jpg", "Persone camminano per strada affollata in bianco e nero", "Una coppia passeggia lungo una strada affollata in una giornata di sole. Il momento è immortalato per catturare la vita quotidiana vibrante della città e l\'architettura che la circonda.", "Passeggiata in città", "Londra, Regno Unito", "2023-05-19"),
("502A1800.jpg", "Persona con cappellino Ferrari su spalti affollati", "Un tifoso di Formula 1 è immortalato di spalle, colto mentre guarda con attenzione e interesse una competizione automobilistica. Lo sfondo si chiude attorno alla figura girata, di cui è distinguibile il solo cappellino Ferrari, firmato da un'idolo dello spettatore.", "Giorno di gara al circuito", "Monza, Italia", "2023-10-27"),
("502A0194.JPG", "Ferrari rossa in mostra, catturata frontalmente", "Un\'icona del design automobilistico esposta in un museo prestigioso, per celebrare l\'eleganza e le prestazioni di un\'epoca passata.", "Eleganza Rossa in Mostra", "Maranello, Italia", "2024-04-29"),
("502A0216.jpg", "Interno di un\'auto sportiva in bianco e nero", "L\'immagine cattura l\'interno di un\'auto sportiva di lusso, simbolo di innovazione e velocità, durante un\'esposizione nel museo Ferrari di Maranello.", "Visione dall\'interno di Ferrari da corsa", "Maranello, Italia", "2024-05-29"),
("502A0329.jpg", "Auto catturata drante la corsa.", "Una giornata di velocità e adrenalina per un test drive dinamico. L/'auto è catturata in pieno movimento: la velocità la confonde con lo sfondo indecifrabile", "Velocità in bianco e nero", "Padova, Italia", "2024-07-02"),
("502A6569.JPG", "Concerto con sassofonista e chitarrista sotto luci blu", "Una serata estiva in cui musicisti si esibiscono in un festival musicale, creando un\'atmosfera magica con le loro melodie coinvolgenti.", "Notte di Jazz sotto le Stelle", "Noventa Padovana, Italia", "2023-01-30"),
("502A4473_N.JPG", "Chitarrista in penombra suona tra luci e fumo sul palco", "La foto cattura l\'intensità di un concerto rock. L\'atmosfera fumosa e le luci teatrali esaltano la figura del chitarrista mentre si esibisce, evocando un momento magico di connessione tra artista e pubblico.", "Concerto Rock sotto le Stelle", "Noventa Padovana, Italia", "2023-01-30"),
("502A4643.JPG", "Cantante sul palco tra fumo e luci viola", "Un giovane artista colto durante un\'esibizione energica, catturando l\'essenza della musica dal vivo in una serata indimenticabile.", "Passione sul palco", "Noventa Padovana, Italia", "2023-01-30"),
("502A3862.jpg", "Auto da corsa in pista durante una gara.", "L\'immagine cattura l\'adrenalina di una corsa automobilistica. Un\'auto sfreccia rapidamente lungo un circuito, simboleggiando di velocità e frenesia. Scattata per celebrare l\'abilità dei piloti e la tecnologia delle auto, questa foto trasmette tutta la tensione delle gare di velocità.", "Velocità in pista", "Imola, Italia", "2024-02-13"),
("502A2345.jpg", "Auto da corsa su pista con pubblico sugli spalti", "La foto è stata scattata durante una gara automobilistica emozionante, dove la velocità e l\'abilità dei piloti hanno catturato l\'attenzione di una folla numerosa. La scena rappresenta l\'adrenalina del momento e la passione per il motorsport.", "Emozioni in Pista", "Monza, Italia", "2023-10-27"),
("502A9758.JPG", "Gondola con persone in un canale stretto", "Un momento di serenità mentre il sole illumina i canali stretti di Venezia. Una gondola scivola dolcemente sull\'acqua, portando turisti curiosi a scoprire angoli nascosti e suggestivi. L\'atmosfera è di curiosità e relax, catturata per chi ama immergersi nel fascino senza tempo di una città sull\'acqua.", "Giro in gondola", "Venezia, Itala", "2022-07-15"),
("502A1372.JPG", "Auto in movimento in città", "Un'auto londinese percorre le strade frenetiche della città. L\'immagine cattura l\'essenza del quotidiano cittadino, con passanti e edifici sfocati sullo sfondo, simboleggiando l\'incessante dinamismo urbano. Forse il conducente sta trasportando un passeggero verso una destinazione importante.", "Auto corre nel cuore di Londra", "Londra, Regno Unito", "2023-05-19"),
("502A2449.jpg", "Auto da corsa in pista davanti a pubblico.", "Durante una giornata di gara, l\'adrenalina regna sovrana. Gli spettatori, in attesa del climax della competizione, osservano con entusiasmo le vetture sfrecciare sul circuito. Un\'immagine rappresentativa del fascino e della velocità del mondo delle corse. Scattata per immortalare l\'emozione delle gare automobilistiche.", "Curva stretta", "Imola, Italia", "2024-02-13"),
("502A4270.jpg", "Uccello vola fra i monti annebbiati", "L\'immagine cattura la tranquillità delle montagne annebbiate, con un uccello che solca il cielo, evocando un senso di libertà e pace interiore.", "Libertà annebbiata fra i monti", "Alpi Italiane", "2022-12-11"),
("502A9418.JPG", "Auto rossa da competizione corre in pista.", "L\'immagine cattura un momento di velocità e competizione durante una gara automobilistica, immortalando una vettura rossa che sfreccia sulla pista, simbolo di abilità e tecnologia all\'avanguardia.", "Velocità e Passione in Pista", "Imola, Italia", "2024-02-13"),
("502A0204.jpg", "Vista frontale di Ferrari su sfondo scuro", "Un omaggio alla bellezza e alla potenza meccanica del cavallino. L\'immagine è stata scattata durante una mostra per celebrare l\'iconico design della vettura e suscitare emozioni negli appassionati di motori.", "Ingegneria e precisione in rosso", "Maranello, Italia", "2024-05-29"),
("502A4254.jpg", "Cottage di montagna con sfondo alpino", "L\'immagine cattura un tranquillo villaggio alpino al tramonto, con le vette illuminate dall\'ultimo sole della giornata. La cattura mostra la pace e la bellezza naturale del luogo.", "Tramonto alpino fra le Alpi", "Sesto, Italia", "2022-12-11"),
("502A4272.jpg", "Montagne illuminate dalla luce del'alba", "In questo scatto, ho voluto catturare la bellezza delle Alpi italiane avvolte da una calda luce mattutina, regalando un senso di pace e meraviglia.", "Alba fra le Alpi", "Alpi Italiane", "2022-12-13"),
("502A1331.JPG", "Treno in movimento alla stazione Lambeth North", "Lo scatto riprende la frenesia quotidiana della metro di Londra mentre le persone si affrettano a raggiungere le loro destinazioni, forse riflettendo sulla vita cittadina.", "Frenesia in metropolitana", "Londra, Regno Unito", "2023-05-20"),
("502A4280.jpg", "Un campanile di pietra fra montagne maestose", "La fotografia immortala un momento di pace ai piedi delle Alpi, trasmettendo il senso di calma che solo un piccolo borgo di montagna può offrire. Il campanile si erge come testimone di storie passate e di una comunità che vive in armonia con la natura circostante.", "Quieto borgo alpino", "Alpi Italiane", "2022-12-12"),
("502A1635.JPG", "Galleria con luci soffuse e persone che passeggiano", "Un\'istantanea cattura l\'essenza di un mercato coperto storico, dove la vita quotidiana si intreccia con la storia. La foto è stata scattata per mostrare il fascino senza tempo di questo luogo frequentato da turisti e locali.", "Passeggiando nel Tempo", "Londra, Regno Unito", "2023-05-22"),
("502A4614.JPG", "Band in penombra suona sul palco illuminato", "Una band si esibisce in un piccolo locale, catturata nel momento in cui il pubblico si immerge nell\'intensità della musica. L\'atmosfera è carica di energia, e le luci calde del palco creano un contrasto drammatico con il fumo che avvolge i musicisti. Questa foto è stata scattata per immortalare la potenza dell\'esecuzione dal vivo e il legame tra i membri del gruppo mentre suonano in sintonia.", "Concerto Intimo", "Noventa Padovana, Italia", "2023-01-30"),
("502A4332.jpg", "Cime montuose avvolte da nuvole", "La foto cattura la serenità delle Alpi in bianco e nero, cercando di esprimere la bellezza del paesaggio naturale e l’effetto suggestivo del gioco di luce tra le montagne e le nuvole.", "Silenzio tra le Alpi", "Alpi Italiane", "2023-12-27"),
("502A1318.jpg", "Interno di un autobus con passeggeri seduti", "Un momento di tranquillità durante un viaggio quotidiano in autobus, dove i passeggeri approfittano del tempo per osservare il mondo esterno o perdersi al telefono. L\'atmosfera rilassata suggerisce una routine abituale, offrendo un piccolo squarcio di vita urbana.", "Un Giorno in autobus", "Londra, Regno Unito", "2023-05-22"),
("502A4625.JPG", "Band rock su un palco fra luci e fumo", "Un concerto serale in cui una band emergente mette in mostra il proprio talento musicale, cercando di conquistare il pubblico locale con il loro suono energico e coinvolgente.", "Notte di rock e musica", "Noventa Padovana, Italia", "2023-01-30"),
("502A4283.jpg", "Antiche mura con apertura ad arco e persone che camminano", "In una giornata tranquilla, i passanti attraversano un antico arco romano, simbolo della continuità tra passato e presente, immersi nella bellezza storica che la città offre.", "Passaggio tra le epoche", "Aosta", "2022-08-19"),
("502A4327.jpg", "Escursionisti su un piccolo pendio di montagna.", "Due escursionisti si avventurano nelle maestose Alpi durante una limpida giornata di sole. Cercano serenità e avventura in un ambiente naturale incontaminato.", "Oltre le vette alpine", "Alpi Italiane", "2023-12-27"),
("502A1840.JPG", "Riflesso di Londra in una vetrina", "La foto racconta un momento di introspezione urbana, catturato attraverso riflessi che sovrappongono lo storico Big Ben e l\'interno di un caffè moderno. La foto è stata scattata per esplorare il contrasto tra il passato e il presente di Londra.", "Riflessi di Londra", "Londra, Regno Unito", "2023-05-19"),
("502A4640.JPG", "Band su palco con luci viola e fumo avvolgente", "Una band giovane si esibisce con passione durante un concerto estivo, portando energia e musica tra il pubblico di una piccola città. Le luci colorate e l\'atmosfera vibrante creano un\'esperienza indimenticabile per i fan.", "Concerto serale", "Noventa Padovana", "2023-01-30"),
("502A4682.JPG", "Musicisti su un palco illuminato da luci viola", "Una giovane band esibisce la propria energia sul palco di un festival musicale, cercando di conquistare il pubblico con il suono rock della loro chitarra e basso.", "Notte di Rock al Festival", "Noventa Padovana", "2023-01-30");

UPDATE image
SET updated_at = CURRENT_TIMESTAMP
WHERE updated_at IS NULL;

UPDATE image
SET category = CASE
WHEN place LIKE '%Praga%' OR place LIKE '%Londra%' OR place LIKE '%Venezia%' OR place LIKE '%Dolomiti%' OR place LIKE '%Alpi%' THEN 'Travels'
WHEN place LIKE '%Monza%' OR place LIKE '%Imola%' OR title LIKE '%Velocità%' OR title LIKE '%auto%' THEN 'Racing-Cars'
ELSE 'Events'
END;

SET @row_number = 0;
SET @current_category = '';

    UPDATE image i
        JOIN (
        SELECT id,
        category,
        @row_number := IF(@current_category = category, @row_number + 1, 1) AS new_order_id,
        @current_category := category
        FROM image
        ORDER BY category, created_at, id
        ) AS ordered_images ON i.id = ordered_images.id
        SET i.order_id = ordered_images.new_order_id;


UPDATE image
SET visible = true;
