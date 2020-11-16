<html>
    <head>
        <title><?php echo  $title." - ".$siteName; ?></title>
        <link href='//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css' rel='stylesheet'>
        <link href='favicon.ico' rel='shortcut icon' type='image/x-icon' />
        <script src='//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
        <script src='//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js'></script>
        <style type='text/css'>
            body {
                background-color: #EEE0A7;
            }
            .container {
                text-align: center;
            }
            .tenpx {
                margin-bottom: 10px;
            }
            ul, li {
                list-style-type: none;
            }
        </style>
<?php if(!empty($googleAnalyticsId)): ?>
        <!-- Google Analytics -->
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', '<?php echo $googleAnalyticsId; ?>', 'auto');
            ga('send', 'pageview');

        </script>
        <!-- End Google Analytics -->
<?php endif; ?>
    </head>
    <body>
        <div class='container'>
            <div class='row-fluid'>
                <div class='span12 header'>
                    <h2><?php echo $siteName; ?></h2>
                    <p>Absolutely free <?php echo UP_COIN_NAME; ?>s!</p>
                </div>
            </div>
            <div class='row-fluid'>
<?php if ($isAdmin): ?>
                <div class='span12'>
<?php else: ?>
                <div class='span3'>
                    <?php echo getAd($squareAds, "ad_left_top"); ?>
                    <h2>Our Rewards</h2>
                    <p>There is an equal chance to get either of the rewards.</p>
<?php foreach($rewards as $r): ?>
                        <strong><?php echo number_format($r); ?></strong> <?php echo SUB_UNIT_NAME; ?> <br />
<?php endforeach; ?>
                    <?php echo getAd($squareAds, "ad_left_bottom"); ?>
                </div>
                <div class='span6'>
                    <?php echo getAd($bannerAds, "ad_top"); ?>
                    <?php echo getAd($textAds); ?>
<?php endif; ?>
<?php if(!empty($error)): ?>
                    <div class='alert alert-error'><?php echo $error; ?></div>
<?php endif; ?>
<?php if(!empty($success)): ?>
                    <div class='alert alert-success'><?php echo $success; ?></div>
<?php endif; ?>
