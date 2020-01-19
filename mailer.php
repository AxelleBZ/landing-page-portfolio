<?php

  require 'vendor/autoload.php';

  // Get the form fields and remove whitespace.
  $firstname = strip_tags(trim($_POST["firstname"]));
  $firstname = str_replace(array("\r","\n"),array(" "," "),$firstname);
  $lastname = strip_tags(trim($_POST["lastname"]));
  $lastname = str_replace(array("\r","\n"),array(" "," "),$lastname);
  $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
  $message = trim($_POST["message"]);

  // Check that data was sent to the mailer.
  if ( empty($firstname) OR empty($lastname) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      // Set a 400 (bad request) response code and exit.
      http_response_code(400);
      echo "Oops! There was a problem with your submission. Please complete the form and try again";
      exit;
  }

  // Set the recipient email address.
  $recipient = "axellezeller@hotmail.fr";

  // Set the email subject.
  $subject = "New contact from $firstname $lastname";

  // Build the email content.
  $email_content = "firstname: $firstname\n";
  $email_content .= "lastname: $lastname\n";
  $email_content .= "Email: $email\n\n";
  $email_content .= "Message:\n$message\n";

  // Send the email.
  $request_body2 = json_decode('{
    "personalizations": [
      {
        "to": [
          {
            "email": $recipient
          }
        ],
        "subject": $subject
      }
    ],
    "from": {
      "email": $email
    },
    "content": [
      {
        "type": "text/plain",
        "value": $email_content
      }
    ]
  }');

  $request_body = json_decode('{
    "personalizations": [
      {
        "to": [
          {
            "email": '. $recipient .'
          }
        ],
        "subject": '. $subject .'
      }
    ],
    "from": {
      "email": '. $email .'
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

  // Send the email.
  if ($sg->client->mail()->send()->post($request_body)) {
      // Set a 200 (okay) response code.
      // http_response_code(200);
      // echo "Thank You! Your message has been sent.";
      // echo $response->statusCode();
      // echo $response->body();
      // echo $response->headers();
  } else {
      // Set a 500 (internal server error) response code.
      // http_response_code(500);
      // echo "Oops! Something went wrong and we couldn't send your message.";
  }

?>
