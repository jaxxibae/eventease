<?php
class Requests
{
    private $base_url = 'http://localhost:5550/';

    function __construct(string $base_url = 'http://localhost:5550/')
    {
        $this->base_url = $base_url;
    }

    function get_as_json(string $path)
    {
        $url = $this->join_url($path);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        $response = curl_exec($ch);
        curl_close($ch);

        $json_response = json_decode($response);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Failed to parse JSON response');
        }

        return $json_response;
    }

    function post_as_json(string $path, array $data)
    {
        $url = $this->join_url($path);
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        return json_decode($response, true);
    }

    private function join_url(string $path)
    {
        return $this->base_url . $path;
    }
}
