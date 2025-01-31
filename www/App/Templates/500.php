<div class="not-found">
    <div>
        <h1>500</h1>
        <p class="not-found-error"><?php if(isset($TEMPLATE_DATA['errorstring']) and $TEMPLATE_DATA['errorstring'] != "") echo $TEMPLATE_DATA['errorstring']; else echo \PTW\translationWithSpan("system-error-title"); ?></p>
        <p><?php echo \PTW\translationWithSpan("system-error-text"); ?></p>
        <a href="home" class="link"><?php echo \PTW\translation("go-homepage"); ?></a>
    </div>
    <div class="image-500"></div>
</div>