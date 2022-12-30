<?php

namespace App\Helper;

class OpenAIHelper
{
    public function test()
    {
        
        $promptContent = "Suggest chart types for the following data, including the recommended x and y axis in the format {{chart: '', x: '', y: ''}}:";
        $engine = "text-davinci-003";
        $api_key = "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";
        

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
            print_r($getCurlResponse['data']['choices'][0]['text']);
        } else {
            print_r($getCurlResponse['data']['error']['message']);
        }
    }
}
