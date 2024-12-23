<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Staggered Gallery</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            box-sizing: border-box;
        }

        .gallery {
            column-count: 3;
            column-gap: 20px;
            padding: 20px;
            width: 100%;
            max-width: 1200px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        .gallery-item {
            position: relative;
            break-inside: avoid;
            display: inline-block;
            margin-bottom: 20px;
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease-in-out;
        }

        .gallery-item img {
            width: 100%;
            height: auto;
            object-fit: cover;
            display: block;
            cursor: pointer;
        }

        .gallery-item:hover {
            transform: scale(1.05);
        }

        .info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(180deg, transparent, rgba(0, 0, 0, 0.7));
            color: #fff;
            padding: 10px;
            font-size: 14px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            text-align: left;
        }

        .info span {
            margin: 2px 0;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            justify-content: center;
            align-items: center;
            z-index: 1000;
            flex-direction: column;
            color: #fff;
            text-align: center;
        }

        .modal img {
            max-width: 90%;
            max-height: 70%;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .modal-description {
            font-size: 18px;
            padding: 10px;
        }

        @media (max-width: 1024px) {
            .gallery {
                column-count: 2;
                column-gap: 15px;
            }
        }

        @media (max-width: 768px) {
            .gallery {
                column-count: 1;
                column-gap: 10px;
                padding: 10px;
            }

            .gallery-item {
                margin-bottom: 15px;
            }

            .info {
                font-size: 12px;
                padding: 8px;
            }

            .modal-description {
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .info {
                font-size: 10px;
                padding: 6px;
            }

            .gallery {
                padding: 5px;
            }

            .modal-description {
                font-size: 14px;
            }
        }

    </style>
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
</head>
<body>

<div class="gallery">
    <?php
    $images = [
        [
            "src" => "https://images.unsplash.com/photo-1506748686214-e9df14d4d9d0",
            "alt" => "Beautiful View 1",
            "description" => "A serene view of Lake Tahoe surrounded by mountains.",
            "location" => "Lake Tahoe"
        ],
        [
            "src" => "https://images.unsplash.com/photo-1517816743773-6e0fd518b4a6",
            "alt" => "Beautiful View 2",
            "description" => "A majestic Yosemite landscape with lush greenery.",
            "location" => "Yosemite"
        ],
        [
            "src" => "https://images.unsplash.com/photo-1473625247510-8ceb1760943f",
            "alt" => "Beautiful View 3",
            "description" => "A breathtaking shot of the Grand Canyon at sunset.",
            "location" => "Grand Canyon"
        ],
        [
            "src" => "https://images.unsplash.com/photo-1506748686214-e9df14d4d9d0",
            "alt" => "Beautiful View 4",
            "description" => "A picturesque view of the Rocky Mountains covered in snow.",
            "location" => "Rocky Mountains"
        ],
        [
            "src" => "https://images.unsplash.com/photo-1506748686214-e9df14d4d9d0",
            "alt" => "Beautiful View 5",
            "description" => "A powerful scene of the Niagara Falls in full flow.",
            "location" => "Niagara Falls"
        ],
        [
            "src" => "https://images.unsplash.com/photo-1473625247510-8ceb1760943f",
            "alt" => "Beautiful View 6",
            "description" => "An awe-inspiring shot of the Swiss Alps.",
            "location" => "Swiss Alps"
        ],
        [
            "src" => "https://images.unsplash.com/photo-1517816743773-6e0fd518b4a6",
            "alt" => "Beautiful View 7",
            "description" => "A vibrant sunset view of Banff National Park.",
            "location" => "Banff National Park"
        ],
        [
            "src" => "https://images.unsplash.com/photo-1506748686214-e9df14d4d9d0",
            "alt" => "Beautiful View 8",
            "description" => "Crystal clear waters in the Maldives.",
            "location" => "Maldives"
        ],
        [
            "src" => "https://images.unsplash.com/photo-1506748686214-e9df14d4d9d0",
            "alt" => "Beautiful View 9",
            "description" => "A tranquil view of a lush green valley in Ireland.",
            "location" => "Ireland"
        ],
        [
            "src" => "https://images.unsplash.com/photo-1517816743773-6e0fd518b4a6",
            "alt" => "Beautiful View 10",
            "description" => "A panoramic view of the Sahara Desert at sunset.",
            "location" => "Sahara Desert"
        ]
    ];


    foreach ($images as $image) {
        $detailPage = "details.php?src=" . urlencode($image['src']) . "&description=" . urlencode($image['description']) . "&alt=" . urlencode($image['alt']);
        echo "<div class='gallery-item' data-description='{$image['description']}'>
            <a href='{$detailPage}'>
                <div class='image-wrapper'>
                    <img class='main-image' src='{$image['src']}' alt='{$image['alt']}' loading='lazy'>
                </div>
            </a>
            <div class='info'>
                <span>{$image['alt']}</span>
                <span>Location: {$image['location']}</span>
            </div>
        </div>";
    }

    ?>
</div>

<div class="modal" id="image-modal">
    <img src="" alt="Zoomed image" id="modal-image">
    <div class="modal-description" id="modal-description"></div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const images = document.querySelectorAll(".gallery-item img");
        const modal = document.getElementById("image-modal");
        const modalImage = document.getElementById("modal-image");
        const modalDescription = document.getElementById("modal-description");

        images.forEach(img => {
            img.addEventListener("click", (event) => {
                event.preventDefault(); // Evita la navigazione per JavaScript abilitato
                const galleryItem = event.target.closest(".gallery-item");
                const description = galleryItem.getAttribute("data-description");

                modal.style.display = "flex";
                modalImage.src = img.src;
                modalDescription.textContent = description;
            });
        });

        modal.addEventListener("click", () => {
            modal.style.display = "none";
            modalImage.src = "";
            modalDescription.textContent = "";
        });
    });
</script>

</body>
</html>
