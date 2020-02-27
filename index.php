<?php

include "settings.php";
include "header.php";

?>
<body>
  <div class="grid-container">
    <div class="Title">
      <?php echo $config->site_name ?>
    </div>
    <div class="Buttons">
      <a class="sign_up" href=register.php>Sign Up<a>
    </div>
    <div class="Main">
      <p>
      This is will allow you to sign up for notifications of upcoming clear skies for astronomical viewing. It is basically an automated version of looking at <a href=http://clearoutside.com/>Clear Outside</a>. It uses the same data backend as Clear Outside (<a href=https://darksky.net/dev>Dark Skies API</a>) and just looks at a few factors to determine if a decent night is coming up for some amateur astronomy.</p>
      <p>
      It will look at a few (configurable) factors. Initially it will make sure that:</p>
      <ul>
       <li>It is past sunset</li>
       <li>The wind speed is below a set amount</li>
       <li>The cloud cover is below a set percentage</li>
       <li>There is a decent enough window to get some astronomy done</li>
      </ul>
      <p>This means you will only get notified if the parameters set by you are met. So if you want to do some astrophotography and need a good 4 hour window to get set up and some observations in, then you can set that as a notification requirement. </p>
      <p>This is all currently *very* experimental. Hence the bare basic look and feel. Once the core bugs are worked out and it has been tested, then the user experience can be updated</p>
    </div>
    <div class="Footer">
      Forecast information provided by: <br />
      <a href="https://darksky.net/poweredby/">
      <img class="darksky" src="https://darksky.net/dev/img/attribution/poweredby-oneline-darkbackground.png">
      </a>
    </div>
  </div>
