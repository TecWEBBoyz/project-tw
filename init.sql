DROP TABLE IF EXISTS animal;
DROP TABLE IF EXISTS booking;
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS service;

CREATE TABLE service (
    id CHAR(36) DEFAULT UUID(),
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT DEFAULT '',
    price DECIMAL(10, 2) NOT NULL,
    duration INT NOT NULL,
    max_people INT NOT NULL,

    PRIMARY KEY (id)
);


CREATE TABLE user (
    id CHAR(36) DEFAULT UUID(),
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    telephone VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM("Administrator", "User") NOT NULL DEFAULT "User",

    PRIMARY KEY (id)
);

CREATE TABLE animal (
    id CHAR(36) DEFAULT UUID(),
    species VARCHAR(50) NOT NULL,
    name VARCHAR(50) NOT NULL DEFAULT '',
    age VARCHAR(50) NOT NULL,
    habitat VARCHAR(100),
    dimensions VARCHAR(100),
    lifespan VARCHAR(100),
    diet VARCHAR(100),
    description TEXT DEFAULT '',
    image VARCHAR(255) NOT NULL DEFAULT '',

    PRIMARY KEY (id)
);

CREATE TABLE booking (
    id CHAR(36) DEFAULT UUID(),
    user_id CHAR(36) NOT NULL,
    service_id CHAR(36) NOT NULL,
    date DATE NOT NULL,
    num_people INT NOT NULL,
    notes VARCHAR(255) DEFAULT '',

    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (service_id) REFERENCES service(id),

    PRIMARY KEY (id)
);


INSERT INTO user (id, username, email, telephone, password, role) VALUES
('77d449dd-d5c4-4d77-a404-c61cc56744b6', 'admin', 'admin@gmail.com', '1234567890', 'admin', 'Administrator'),
('e2a26785-c0f3-4261-a2ad-be4f1574cca4', 'user', 'user@gmail.com', '0987654361', 'user', 'User'),
('a1b2c3d4-e5f6-7890-g1h2-i3j4k5l6m7n8', 'john_doe', 'john.doe@example.com', '1122334455', 'password123', 'User'),
('b2c3d4e5-f6g7-8901-h2i3-j4k5l6m7n8o9', 'jane_smith', 'jane.smith@example.com', '2233445566', 'securepass', 'User'),
('c3d4e5f6-g7h8-9012-i3j4-k5l6m7n8o9p0', 'alice_wonder', 'alice.wonder@example.com', '3344556677', 'wonderland', 'User');

INSERT INTO service (id, name, description, price, duration, max_people) VALUES
("43591738-588f-4a58-a04f-f3826de22345", "Safari", "Un'esperienza unica per osservare gli animali nel loro habitat naturale.", 50.00, 120, 10),
("b2c3d4e5-f6g7-8901-h2i3-j4k5l6m7n8o9", "Ingresso", "Accesso al parco per una giornata indimenticabile.", 20.00, 480, 100),
("c3d4e5f6-g7h8-9012-i3j4-k5l6m7n8o9p0", "Visita guidata", "Tour guidato del parco con un esperto zoologo.", 30.00, 90, 15);

INSERT INTO animal (id, species, name, age, habitat, dimensions, lifespan, diet, description, image) VALUES
('c97a5ca5-2d29-4e45-b406-1169eca56e68', 'Elefante', 'Dumbo', '45', 'Savane e foreste africane', 'Fino a 4 metri di altezza e 6.000 kg', '60-70 anni', 'Erbivoro – foglie, frutti e cortecce', 'A differenza di molti altri animali, gli elefanti vivono in gruppi familiari guidati da una femmina anziana, la matriarca. Questi giganti gentili sono noti per la loro incredibile memoria e per il legame profondo che instaurano con i membri del branco. Comunicano tra loro attraverso suoni a bassa frequenza, vibrazioni nel terreno e il contatto fisico. Passano gran parte della giornata a cercare cibo e acqua, e sono fondamentali per l’ecosistema, poiché aprono sentieri e disperdono semi. Nonostante la loro mole, gli elefanti possono muoversi con sorprendente grazia e sensibilità.', './static/images/elephant.jpg'),
('9e22ab02-6f91-4c41-a391-88d7e3bbc504', 'Tigre', 'Strisce', '8', 'Foreste tropicali e taiga', 'Fino a 3 metri di lunghezza, 200-300 kg', '10-15 anni', 'Carnivora – cervi, cinghiali, bufali', 'La tigre è un predatore solitario e furtivo che vive e caccia da sola nel cuore delle foreste. Contrariamente ad altri felini sociali, stabilisce e difende un proprio territorio marcandolo con odori e graffi sugli alberi. Si muove silenziosa attraverso la vegetazione, sfruttando il mimetismo offerto dalle sue strisce, e attacca con precisione fulminea. Le tigri sono anche eccellenti nuotatrici e spesso si rinfrescano nei corsi d’acqua. Fiere, misteriose e potenti, sono considerate simboli di forza e bellezza selvaggia.', './static/images/tiger.jpg'),
('126f98d9-1b64-4fbc-819d-ffb76be6b7e7', 'Giraffa', 'Macchia', '12', 'Savane africane', 'Fino a 5,5 metri di altezza, 800-1.200 kg', '20-25 anni', 'Foglie di acacia e arbusti', 'Le giraffe sono animali sociali che si muovono in piccoli gruppi o in branchi flessibili, spesso guidati da femmine adulte. Il loro lunghissimo collo non contiene più vertebre di quello umano, ma queste sono allungate e permettono loro di raggiungere le foglie più alte degli alberi. I maschi si sfidano in combattimenti rituali chiamati “necking”, dove si colpiscono con il collo per stabilire la dominanza. Malgrado l’apparenza placida, possono correre a oltre 50 km/h per sfuggire ai predatori. Con il loro sguardo curioso e la loro eleganza innata, le giraffe sono tra le creature più affascinanti della savana.', './static/images/giraffe.jpg'),
('e520441b-670f-46f3-b5be-c070bf41fe1f', 'Zebra', 'Righetta', '10', 'Praterie africane', 'Circa 1,5 metri al garrese, 300 kg', '20-25 anni', 'Erbivora – erba e piante basse', 'Le zebre vivono in branchi compatti dove la cooperazione è essenziale per la sopravvivenza. Ogni zebra ha un disegno unico di strisce, che sembra aiutare a confondere i predatori e a rafforzare il legame con i membri del gruppo. Sono animali estremamente vigili, pronti a lanciare un grido d’allarme al minimo segnale di pericolo. Quando attaccate, corrono in gruppo, proteggendo i piccoli al centro del branco. Sebbene sembrino simili ai cavalli, le zebre sono più selvatiche e difficilmente addomesticabili. La loro resistenza e il loro spirito di gruppo sono le chiavi della loro sopravvivenza.', './static/images/zebra.jpg'),
('33fb8b3d-e144-40b6-b934-7e6305614ca2', 'Coccodrillo', 'Scagliotto', '30', 'Fiumi e paludi tropicali', 'Fino a 6 metri, 1.000 kg', '50-70 anni', 'Carnivoro – pesci, uccelli, mammiferi', 'Il coccodrillo è un rettile preistorico che vive principalmente in ambienti d’acqua dolce, come fiumi e paludi. Passa ore immobile, con solo gli occhi e le narici fuori dall’acqua, pronto a scattare con forza incredibile per catturare una preda. Sebbene solitario nella caccia, può convivere pacificamente con altri individui nella stessa area. Dopo l’accoppiamento, la femmina costruisce un nido e protegge le uova fino alla schiusa, mostrando un’insospettabile cura materna. Dotato di una potente mascella e di un metabolismo lento, può rimanere settimane senza mangiare. È un predatore paziente e perfettamente adattato all’ambiente acquatico.', './static/images/crocodile.jpg'),
('19257a81-2366-48b7-b7d7-0734ba094660', 'Gorilla', 'Berto', '20', 'Foreste equatoriali africane', 'Fino a 1,8 metri, 160-200 kg', '35-40 anni', 'Frutta, foglie e radici', 'Il gorilla è un primate straordinariamente intelligente e sociale, che vive in gruppi familiari guidati da un maschio dominante, chiamato “silverback” per la striscia argentea sulla schiena. Nonostante la sua stazza imponente, il gorilla ha un’indole pacifica e trascorre le giornate nutrendosi, riposando e socializzando. Comunica attraverso vocalizzazioni, gesti e sguardi, mostrando una gamma emotiva sorprendente. Il gruppo è unito e protetto, con ruoli ben definiti e grande attenzione verso i cuccioli. Il gorilla incarna forza e gentilezza, ed è un simbolo vivente dell’equilibrio nella foresta tropicale.', './static/images/gorilla.jpg'),
('71e9bd7d-03b9-4e31-b49f-9e295455fd82', 'Pinguino Re', 'Pippo', '5', 'Coste e ghiacci antartici', '60-90 cm, 15-40 kg', '15-20 anni', 'Pesce, calamari, krill', 'Il pinguino è un uccello acquatico che ha scambiato il volo per una straordinaria abilità nel nuoto. Vive in colonie numerose e rumorose, soprattutto nelle fredde regioni dell’Antartide. Durante la stagione riproduttiva, maschi e femmine si alternano nel proteggere le uova, affrontando temperature estreme e lunghi digiuni. I pinguini comunicano con richiami vocali unici per riconoscersi tra migliaia di individui. Hanno un comportamento curioso e cooperativo, e si muovono in gruppo sia sulla terra che in acqua. Dietro l’andatura buffa e i movimenti rigidi, si nasconde una straordinaria resistenza e determinazione.', './static/images/king-penguin.jpg'),
('3f56a0a0-3020-4bff-afd1-2f635f4507c2', 'Lama', 'Paco', '8', 'Ande sudamericane', '1,7-1,8 metri, 130-200 kg', '15-20 anni', 'Erbivoro – erba e fieno', 'Il lama è un animale domestico originario delle Ande, da secoli utilizzato come animale da soma e compagno dell’uomo. Ha un’indole calma ma può diventare capriccioso se infastidito, spesso risolvendo le dispute con sputi e ringhi. Vive in piccoli gruppi sociali con una gerarchia ben definita. Dotato di piedi morbidi e adattati alla montagna, riesce a muoversi agilmente anche su terreni impervi. Ha un carattere curioso, osservatore e spesso affettuoso con chi gli è familiare. Il suo aspetto soffice e il muso espressivo lo rendono irresistibile agli occhi di molti.', './static/images/llama.jpg'),
('ff5aa28f-4cba-471a-b81c-cf51faf38353', 'Ippopotamo', 'Tonno', '18', 'Fiumi e laghi africani', 'Fino a 5 metri, 1.500-3.000 kg', '40-50 anni', 'Erbivoro – erbe acquatiche', 'L’ippopotamo trascorre gran parte della giornata immerso nell’acqua per rinfrescarsi e proteggere la pelle sensibile dal sole. Nonostante l’aspetto goffo e placido, è uno degli animali più pericolosi della savana, capace di muoversi rapidamente sia in acqua che sulla terra. Vive in gruppi composti da femmine e piccoli, dominati da un maschio territoriale. Quando non è in acqua, pascola silenziosamente di notte. Comunica attraverso suoni gravi che si propagano anche sott’acqua. L’ippopotamo è una presenza imponente, tra il mondo acquatico e quello terrestre, con una forza sorprendente e un comportamento imprevedibile.', './static/images/hippo.jpg'),
('24a2950b-40b1-4aa2-ba1f-896c4b467cc6', 'Panda', 'Bambù', '6', 'Foreste montane cinesi', '1,5 metri, 100-150 kg', '20-25 anni', 'Quasi solo bambù', 'Il panda gigante è noto per la sua dieta quasi esclusivamente a base di bambù, che consuma per ore ogni giorno grazie a potenti mascelle e uno “pseudo-pollice” che gli permette di afferrare i fusti. Vive da solo nelle foreste montane della Cina, in territori ben definiti che marca con segnali odorosi. È un animale riservato e pacifico, che raramente si mostra aggressivo. Nonostante l’apparente pigrizia, può arrampicarsi sugli alberi e muoversi agilmente. Il suo aspetto tenero e il comportamento tranquillo lo hanno reso un simbolo universale di conservazione e armonia con la natura.', './static/images/panda.jpg'),
('91c5219c-4c0f-4388-84c4-a41a707bd5da', 'Struzzo', 'Corridò', '7', 'Savane e deserti africani', 'Fino a 2,7 metri, 150 kg', '40-45 anni', 'Semi, frutta, insetti', 'Lo struzzo è l’uccello più grande al mondo e, anche se non può volare, è uno dei più veloci corridori tra gli animali terrestri, capace di raggiungere i 70 km/h. Vive nelle savane e nei deserti, spesso in piccoli gruppi o associato a branchi di altri erbivori. Le sue potenti zampe non servono solo per correre, ma anche come arma difensiva contro i predatori. Ha un carattere curioso ma diffidente e usa l’altezza per sorvegliare l’ambiente circostante. Durante la stagione degli amori, i maschi eseguono spettacolari danze per attirare le femmine. Lo struzzo è un animale vigile e sorprendente, che unisce forza, eleganza e spirito libero.', './static/images/ostrich.jpg');

INSERT INTO booking (user_id, service_id, date, num_people, notes) VALUES
('77d449dd-d5c4-4d77-a404-c61cc56744b6', '43591738-588f-4a58-a04f-f3826de22345', '2025-10-01', 20, 'Nessun appunto'),
('e2a26785-c0f3-4261-a2ad-be4f1574cca4', 'b2c3d4e5-f6g7-8901-h2i3-j4k5l6m7n8o9', '2025-10-02', 5, 'Nessun appunto'),
('e2a26785-c0f3-4261-a2ad-be4f1574cca4', 'b2c3d4e5-f6g7-8901-h2i3-j4k5l6m7n8o9', '2024-10-02', 5, 'Prenotazione che non dovrebbe essere vista'),
('e2a26785-c0f3-4261-a2ad-be4f1574cca4', 'b2c3d4e5-f6g7-8901-h2i3-j4k5l6m7n8o9', '2025-11-02', 10, 'Una nota particolare');