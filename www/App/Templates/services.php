<div id="service-page">
    <article id="service-events">
        <div class="service-image"></div>
        <div class="service-content">
            <header>
                <p class="caption"><?php echo PTW\translationWithSpan("services-caption") ?></p>
                <h2><?php echo PTW\translationWithSpan("service-events-title") ?></h2>
            </header>
            <div>
                <p><?php echo PTW\translationWithSpan("service-events-description") ?></p>
                <a href="book-service?service=events" class="btn-outlined">
                    <?php echo PTW\translationWithSpan("service-book-btn") ?>
                    <?php echo file_get_contents("static/images/right-chevron.svg") ?>
                </a>
            </div>
        </div>
    </article>
    <article id="service-other">
        <div class="service-image"></div>
        <div class="service-content">
            <header>
                <p class="caption"><?php echo PTW\translationWithSpan("services-caption") ?></p>
                <h2><?php echo PTW\translationWithSpan("service-other-title") ?></h2>
            </header>
            <div>
                <p><?php echo PTW\translationWithSpan("service-other-description") ?></p>
                <a href="book-service?service=other" class="btn-outlined">
                    <?php echo PTW\translationWithSpan("service-book-btn") ?>
                    <?php echo file_get_contents("static/images/right-chevron.svg") ?>
                </a>
            </div>
        </div>
    </article>
</div>