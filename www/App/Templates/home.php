<noscript>
    <style>
        .gallery-item a {
            display: block;
            text-decoration: none;
            color: inherit;
        }

        .gallery-item img {
            cursor: default;
        }
    </style>
</noscript>
<div class="gallery">
    <?php
    $images = [
        [
            "src" => "static/uploads/502A6233.JPG",
            "alt" => "Cantante su palco con chitarra elettrica rossa",
            "description" => "In una notte estiva a Milano, un musicista appassionato regala un'esibizione emozionante durante un festival locale, la sua musica si fonde con le luci rosse che riempiono l'atmosfera e il cuore del pubblico.",
            "location" => "Milano"
        ],
        [
            "src" => "static/uploads/502A6394.JPG",
            "alt" => "Uomo che suona il sax con effetti di luce",
            "description" => "L'immagine racconta di un musicista immerso nella passione della sua performance, un noto sassofonista sul palco di un festival jazz, catturato mentre coinvolge il pubblico con il suo talento e carisma. L'atmosfera è carica di emozioni e la folla è affascinata dalle melodie avvolgenti.",
            "location" => "Perugia"
        ],
        [
            "src" => "static/uploads/502A1075.JPG",
            "alt" => "Piste innevate nelle Dolomiti sotto cime rocciose.",
            "description" => "Una giornata invernale nelle Dolomiti, meta ambita dagli appassionati di sci e avventura. L'immagine cattura la tranquillità prima dell'arrivo dei turisti, con le piste pronte per una nuova stagione.",
            "location" => "Sesto, Italia"
        ],
        [
            "src" => "static/uploads/502A6195.JPG",
            "alt" => "Band su palco con luci rosse, sax e chitarra elettrica",
            "description" => "Una band si esibisce in un concerto estivo all'aperto, coinvolgendo il pubblico con un mix di suoni jazz e rock. L'atmosfera è complice e vibrante, con luci rosse che aggiungono calore alla serata. La foto cattura l'energia e la passione dei musicisti durante una performance memorabile.",
            "location" => "Napoli"
        ],
        [
            "src" => "static/uploads/502A1572.JPG",
            "alt" => "Fontana al tramonto con figura umana in silhouette",
            "description" => "Era un tardo pomeriggio d'autunno a Londra. Una giovane donna, avvolta nel suo cappotto arancione, passeggiava per Trafalgar Square. Contemplava il tramonto, mentre l'acqua della fontana creava riflessi scintillanti. In quel momento, un fotografo catturò la scena, immortalando un istante di quiete nel trambusto cittadino.",
            "location" => "Londra"
        ],
        [
            "src" => "static/uploads/502A4586.jpg",
            "alt" => "Cascata tra rocce e vegetazione rigogliosa",
            "description" => "La cascata, nascosta tra le montagne, attira gli escursionisti che cercano un momento di pace. È stata immortalata in una giornata estiva, quando il sole illumina il verde lussureggiante, esaltando la bellezza naturale del luogo.",
            "location" => "Cogne"
        ],
        [
            "src" => "static/uploads/502A4325.jpg",
            "alt" => "Strada montana tra verdi pendii e vette innevate",
            "description" => "Un viaggiatore solitario percorre un sentiero di montagna ai piedi delle Alpi, cercando la pace e l'ispirazione che solo la natura sa offrire. Lo scatto cattura la maestosità delle vette e la serenità del percorso, forse come ricordo di un momento di riflessione.",
            "location" => "Courmayeur"
        ],
        [
            "src" => "static/uploads/502A2847.jpg",
            "alt" => "Persona che fotografa un fiume con palazzi sullo sfondo",
            "description" => "In una giornata autunnale, un fotografo cattura la quiete del fiume prima che la pioggia arrivi. Il cielo è coperto e le sedie vuote suggeriscono un momento di riflessione solitaria, forse in cerca di ispirazione o di pace interiore.",
            "location" => "Praga"
        ],
        [
            "src" => "static/uploads/502A4319.jpg",
            "alt" => "Vallata montana avvolta da nuvole basse",
            "description" => "Questa immagine racconta la quiete di un pascolo alpino in estate, dove le nuvole basse avvolgono le cime, creando un'atmosfera mistica e tranquilla. Scattata per catturare la bellezza naturale e serena delle montagne, la scena invita a riflettere sulla maestosità della natura.",
            "location" => "Alpi Italiane"
        ],
        [
            "src" => "static/uploads/502A3141.jpg",
            "alt" => "Strada cittadina illuminata di notte, edifici storici",
            "description" => "Una serata tranquilla in città, dove i lampioni e le architetture storiche raccontano storie di un passato glorioso, evocando la bellezza della civiltà che ha costruito questi edifici. L'immagine cattura il silenzio e la maestosità di un angolo urbano, uno spazio fuori dal tempo che invita a passeggiare e riflettere.",
            "location" => "Praga"
        ],
        [
            "src" => "static/uploads/502A2935.jpg",
            "alt" => "Persone in una piazza con tram in movimento",
            "description" => "Una giornata autunnale in cui la piazza si anima di abbracci e saluti, con le persone che camminano frettolosamente sotto lo sguardo attento dei vecchi edifici, mentre un tram sfila in corsa. È un momento di quotidianità in cui storie diverse si intrecciano nel cuore della città.",
            "location" => "Praga"
        ],
        [
            "src" => "static/uploads/502A1549.JPG",
            "alt" => "Strada cittadina con taxi nero e auto parcheggiate",
            "description" => "Nel cuore di una giornata frenetica, i taxi si spostano velocemente lungo le vie storiche della città, trasportando persone indaffarate verso le loro destinazioni. L'immagine cattura un momento di transizione nel trambusto quotidiano, dove il passato incontra il presente.",
            "location" => "Londra"
        ],
        [
            "src" => "static/uploads/502A2441.jpg",
            "alt" => "Auto da corsa sulla pista con pubblico sullo sfondo",
            "description" => "Una gara importante, testimone della potenza e precisione della tecnologia Porsche. Scattata durante un evento automobilistico epico, cattura l'emozione degli spettatori e la velocità sul circuito.",
            "location" => "Monza, Italia"
        ],
        [
            "src" => "static/uploads/502A2305.jpg",
            "alt" => "Auto da corsa in pista con pubblico sugli spalti",
            "description" => "Un'auto sfreccia in una gara, un momento di eccitazione per il pubblico entusiasta che assiste al brivido della competizione, forse immortalata per celebrare la passione per le corse.",
            "location" => "Monza"
        ],
        [
            "src" => "static/uploads/502A3683.jpg",
            "alt" => "Auto da corsa sfrecciano su un circuito",
            "description" => "Durante una giornata di gara intensa, due vetture gareggiano per la vittoria su un noto circuito, catturando l'essenza della velocità e della competizione.",
            "location" => "Imola"
        ],
        [
            "src" => "static/uploads/502A0181.jpg",
            "alt" => "Interno di un'auto sportiva in bianco e nero",
            "description" => "Il fotografo cattura l'essenza di lusso e performance di una supercar. Un momento di contemplazione sul design sofisticato e sull'ingegneria avanzata.",
            "location" => "Maranello"
        ],
        [
            "src" => "static/uploads/502A3583.jpg",
            "alt" => "Volante di un'auto sportiva con mappa di un circuito",
            "description" => "L'immagine cattura l'eccitazione e la preparazione per una gara imminente. La mappa del circuito incollata sul volante rappresenta l'attenzione ai dettagli e la strategia del pilota. Scattata probabilmente durante una giornata di test, mostra l'intensità percettibile della competizione automobilistica.",
            "location" => "Monza"
        ],
        [
            "src" => "static/uploads/502A2974.jpg",
            "alt" => "Coppia che cammina in una strada a ciottoli tra edifici storici.",
            "description" => "Una coppia passeggia serenamente per una strada di Praga, immersi nelle bellezze storiche della città, lontani dal frastuono della vita moderna. La scena cattura un momento di tranquillità nell'affollata vita urbana, forse immortalata durante una vacanza rilassante o una giornata trascorsa a esplorare le meraviglie storiche della città.",
            "location" => "Praga"
        ],
        [
            "src" => "static/uploads/IMG_7357.JPG",
            "alt" => "Panorama invernale con montagne e neve",
            "description" => "Un fotografo ha deciso di immortalare la quiete delle Dolomiti, con le nuvole che sembrano accarezzare le cime innevate, simbolo di pace invernale.",
            "location" => "Cortina d'Ampezzo"
        ],
        [
            "src" => "static/uploads/502A3033.jpg",
            "alt" => "Banda militare in marcia davanti a un palazzo storico",
            "description" => "La banda militare sta partecipando a una cerimonia ufficiale, attirando l'attenzione dei turisti e dei passanti, in un evento tradizionale della città.",
            "location" => "Praga"
        ],
        [
            "src" => "static/uploads/502A1459.jpg",
            "alt" => "Persone camminano per strada in bianco e nero",
            "description" => "Una coppia passeggia lungo una strada affollata in una giornata di sole. Il momento è immortalato per catturare la vita quotidiana vibrante della città e l'architettura che la circonda.",
            "location" => "Londra"
        ]
    ];



    foreach ($images as $image) {
        $detailPage = "gallerydetails?src=" . urlencode($image['src']) . "&description=" . urlencode($image['description']). "&location=" . urlencode($image['location']) . "&alt=" . urlencode($image['alt']);
        #replace image extension with _25percent.jpg manage upper and lower case extensions
        $imagePathResized = preg_replace('/\.(jpg|jpeg|png)$/i', '_25percent.jpg', $image['src']);
        echo "<div class='gallery-item' data-description='" . htmlspecialchars($image['description'], ENT_QUOTES, 'UTF-8') . "'>
        <a href='" . htmlspecialchars($detailPage, ENT_QUOTES, 'UTF-8') . "'>
            <div class='image-wrapper'>
                <img class='main-image' src='" . htmlspecialchars($imagePathResized, ENT_QUOTES, 'UTF-8') . "' alt='" . htmlspecialchars($image['alt'], ENT_QUOTES, 'UTF-8') . "' loading='lazy'>
            </div>
        </a>
        <div class='info'>
            <span>" . htmlspecialchars($image['alt'], ENT_QUOTES, 'UTF-8') . "</span>
            <span>Location: " . htmlspecialchars($image['location'], ENT_QUOTES, 'UTF-8') . "</span>
        </div>
    </div>";
    }


    ?>
</div>

<div class="modal" id="image-modal">
    <img src="" alt="Zoomed image" id="modal-image">
    <div class="modal-description" id="modal-description"></div>
    <!-- loader -->
    <div class="loader">
        <div class="spinner"></div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const images = document.querySelectorAll(".gallery-item img");
        const modal = document.getElementById("image-modal");
        const modalImage = document.getElementById("modal-image");
        const modalDescription = document.getElementById("modal-description");
        const loader = document.querySelector(".loader");

        images.forEach(img => {
            img.addEventListener("click", (event) => {
                event.preventDefault(); // Evita la navigazione per JavaScript abilitato
                const galleryItem = event.target.closest(".gallery-item");
                const description = galleryItem.getAttribute("data-description");

                modal.style.display = "flex";
                let fullsizeImage = img.src.replace(/_.*\.(jpg|jpeg|png)$/i, '.$1');
                modalImage.onload = () => {
                    modalImage.style.display = "block";
                    modalDescription.textContent = description;
                    loader.style.display = "none";
                };
                modalImage.src = fullsizeImage;
                modalDescription.textContent = description;
            });
        });

        modal.addEventListener("click", () => {
            modal.style.display = "none";
            modalImage.src = "";
            modalDescription.textContent = "";
            loader.style.display = "flex";
        });
    });
</script>