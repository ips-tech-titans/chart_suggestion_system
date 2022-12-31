<?php
namespace App\Helpers;


class CurlRequests
{
    public function curlRequests($url, $headers, $data, $method)
    {
        $curlOptions = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 70,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        );
        if (isset($headers) && (count($headers) > 0)) {
            $curlOptions[CURLOPT_HTTPHEADER] = $headers;
        }
        if (isset($data) && (!empty($data))) {
            $curlOptions[CURLOPT_POSTFIELDS] = json_encode($data);
        }
        $curl = curl_init();
        curl_setopt_array($curl, $curlOptions);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            Log::error('curl-error====>' . $err);
            return array("success" => false, 'message' => $err);
        } else {
            $response = json_decode($response, true);
            if (isset($response->error)) {
                Log::error('api-error====>' . json_encode($response));
                return array("success" => false, 'message' => $response->error);
            } else {
                return array("success" => true, 'data' => $response);
            }
        }
    }
}