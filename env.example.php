<?php
  $variables = [
    'APP_URL' => 'http://localhost:8000',
    'APP_NAME' => 'PHP | GITHUB OAUTH',
    'OAUTH2_CLIENT_SECRET' => '',
    'OAUTH2_CLIENT_ID' => '',
  ];

  foreach ($variables as $key => $value) {
      putenv("$key=$value");
  }
?>