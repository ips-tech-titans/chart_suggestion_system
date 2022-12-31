<?php

namespace App\Helper;

use Illuminate\Support\Facades\Log;

class OpenAIHelper
{
    public function getType($string = '')
    {   

        $promptContent = "Which charts can be created using following data. \n $string.  format: type:'',x:'',y:''. Note:we are using only Line chart,Bar chart, Scatter Chart and Pie chart.";
        // $promptContent = "We have following $string data from csv. suggest all possible chart. format: chart_type:'',x-axis: '',y-axis: ''. \n Note:we are using only Line chart,Bar chart and Pie chart."; // format: chart_type:'',x-axis: '',y-axis: ''
        // $promptContent = "We have following $string data from csv. Which charts will be best. format: chart_type:'',x-axis: '',y-axis: ''."; // format: chart_type:'',x-axis: '',y-axis: ''
        // $promptContent = "Suggest Charts with X-axis and Y-axis in format: chart_type:'',x: '',y: ''. \n Our Data is: $string.";
        // $promptContent = "Suggest various Chart with X-axis and Y-axis in format: chart_type:'',x: '',y: ''. \n Data is: \n customer \n product: \n orderdate \n amount. \n Note:we are using only Line chart,Bar chart and Pie chart.";
        $engine = "text-davinci-003";
        $api_key = config('app.OE');        


        $fields = array(
            "prompt" => $promptContent,
            "temperature" => 0,
            "max_tokens" => 500,
            "top_p" => 1,
            "n" => 1,
            "frequency_penalty" => 0,
            "presence_penalty" => 0
        );

        $url = "https://api.openai.com/v1/engines/" . $engine . "/completions";
        $headers = array("authorization: Bearer " . $api_key, "content-type: application/json");
        $getCurlResponse = $this->curlRequests($url, $headers, $fields, "POST");
        if (isset($getCurlResponse['data']) && isset($getCurlResponse['data']['choices'])) {            
            // echo "<pre>";
            // print_r($getCurlResponse['data']['choices'][0]['text']);
            // exit();
            return $getCurlResponse['data']['choices'][0]['text'];
        } else {
            print_r($getCurlResponse['data']['error']['message']);
            exit();
        }
    }

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
