<?php

$errors = [];
$data = [];

if (empty($_POST['fname'])) {
    $errors[] = 'First Name is required.<br>';
}
if (empty($_POST['email'])) {
    $errors[] = 'Last name is required.<br>';
}

if (empty($_POST['messages'])) {
    $errors[] = 'Email is required.<br>';
}



if (!empty($errors)) {
    $data['success'] = false;
    $data['message'] = $errors;
} else {
    $fname = $_POST['fname'];
    $email = $_POST['email'];
    $messages = $_POST['messages'];
  

    $template = "<h2>Details</h2><br>";
    $template .= "<p><strong>Name:</strong> $fname</p>";
    $template .= "<p><strong>E-Mail:</strong> $email</p>";
    $template .= "<p><strong>Message:</strong> $messages</p>";
   

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.sendgrid.com/v3/mail/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => '{
            "personalizations": [{"to": [{"email": "pandiyarajaniosde@gmail.com"}]}],
            "from": {"email": "' . $email . '"},
            "subject": "My Portfolio Website  Mail",
            "content": [{"type": "text/html", "value": "' . $template . '"}]
        }',
        CURLOPT_HTTPHEADER => array(
            "authorization: Bearer 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",
            "cache-control: no-cache",
            "content-type: application/json"
        ),
    ));
    $response = curl_exec($curl);
    $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $err = curl_error($curl);

    if ($err) {
        $data['success'] = false;
        $data['message'] = $err;
    } else {
        if ($statusCode == 403) {
            $data['success'] = false;
            $data['message'] = $response;
        } else {
            $data['success'] = true;
            $data['message'] = 'Your Mail has been Sent Successfully, Please check your inbox...';
        }
    }
    curl_close($curl);
}

echo json_encode($data);

?>