<?php

  require 'vendor/autoload.php';

  // Send the email.
  $request_body = json_decode('{
    "personalizations": [
      {
        "to": [
          {
            "email": "axellezeller@hotmail.fr"
          }
        ],
        "subject": "Hello World from the SendGrid PHP Library!"
      }
    ],
    "from": {
      "email": "test@example.com"
    },
    "content": [
      {
        "type": "text/plain",
        "value": "Hello, Email!"
      }
    ]
  }');

  $apiKey = getenv('SENDGRID_API_KEY');
  $sg = new \SendGrid($apiKey);

  $response = $sg->client->mail()->send()->post($request_body);
  echo $response->statusCode();
  echo $response->body();
  echo $response->headers();

?>
