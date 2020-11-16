                </div>
<?php if (!$isAdmin): ?>
                <div class='span3'>
                    <h4>Earn More <?php echo UP_COIN_NAME; ?>s</h4>
                    <p><?php echo $links; ?></p>
                    <?php echo getAd($squareAds, "ad_right_top"); ?>
                    <?php echo getAd($textAds); ?>
                    <?php echo getAd($squareAds, "ad_right_bottom"); ?>
                </div>
<?php endif; ?>
            </div>
            <strong style='font-size: 125%'>Powered by <a href='https://gitlab.com/minifaucet/public'>MiniFaucet v0.5</a> - get your own faucet!</strong>
            <br />
        </div>
    </body>
</html>
