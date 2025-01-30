<div class="not-found">
    <h1>500 - <?php if(isset($TEMPLATE_DATA['errorstring']) and $TEMPLATE_DATA['errorstring'] != "") echo $TEMPLATE_DATA['errorstring']; else echo \PTW\translationWithSpan("system-error-title"); ?></h1>
    <p><?php echo \PTW\translationWithSpan("system-error-text"); ?></p>
    <a href="home"><?php echo \PTW\translation("system-error-home"); ?></a>
</div>