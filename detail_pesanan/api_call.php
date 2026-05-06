<?php
function callAPI($method, $url, $data = false) {
    $curl = curl_init();

    $method = strtoupper($method);

    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            }
            break;

        case "PUT":
        case "PATCH":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            if ($data) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            }
            break;

        case "DELETE":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            break;

        default: // GET
            if ($data && is_array($data)) {
                $url .= '?' . http_build_query($data);
            }
            break;
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return ["error" => $err];
    } else {
        return json_decode($response, true);
    }
}
