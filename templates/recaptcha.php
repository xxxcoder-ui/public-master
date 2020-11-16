<?php if ($recaptchaVersion == "funcaptcha"): ?>
  <script src="https://funcaptcha.com/fc/api/" async defer></script>
  <div id="funcaptcha" data-pkey='<?php echo $recaptchaKey; ?>'></div>
<?php elseif ($recaptchaVersion == 2): ?>
  <div style='margin: 0 auto; width: 304px; height: 78px;'>
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>
    <div class='g-recaptcha' data-sitekey='<?php echo $recaptchaKey; ?>'></div>
    <noscript>
      <div style='width: 302px; height: 352px;'>
        <div style='width: 302px; height: 352px; position: relative;'>
          <div style='width: 302px; height: 352px; position: absolute;'>
            <iframe src='https://www.google.com/recaptcha/api/fallback?k=<?php echo $recaptchaKey; ?>'
                    frameborder='0' scrolling='no'
                    style='width: 302px; height:352px; border-style: none;'>
            </iframe>
          </div>
          <div style='width: 250px; height: 80px; position: absolute; border-style: none;
                      bottom: 21px; left: 25px; margin: 0px; padding: 0px; right: 25px;'>
            <textarea id='g-recaptcha-response' name='g-recaptcha-response'
                      class='g-recaptcha-response'
                      style='width: 250px; height: 80px; border: 1px solid #c1c1c1;
                             margin: 0px; padding: 0px; resize: none;' value=''>
            </textarea>
          </div>
        </div>
      </div>
    </noscript>
  </div>
<?php else: ?>
  <div style='margin: 0 auto; width: 318px; height: 129px;'>
    <?php echo recaptcha_get_html($recaptchaKey); ?>
  </div>
<?php endif; ?>
