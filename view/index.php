<?php $domain = \General\EnvReader::getInstance()->get('DOMAIN');?>
<html <?php html()?>>
<head>

    <link rel="stylesheet" href="<?php echo Route('src/style.css') ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <script defer="defer" src="<?php echo Route('build/static/js/main.c95adc56.js') ?>"></script>
    <script type="text/javascript">
        window.domain = "<?php echo $domain; ?>";
        window.home = "<?php echo $domain . 't'; ?>";
    </script>
</head>
<div id="root" style="
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;"></div>
</html>