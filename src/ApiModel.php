<?php
/**
 * Created by PhpStorm.
 * User: vicha
 * Date: 13/03/2018
 * Time: 20:07
 */
namespace VicHaunter\Telegram;

use GuzzleHttp\Client;


function dd($s){
    dump($s);
    die();
}

class ApiModel {
    
    /**
     * URI of the Telegram API
     *
     * @var string
     */
    private $apiUrl = 'https://api.telegram.org/bot[apiKey]/[action]';
    
    /** @var $client Client */
    private $client;
    
    private $apiKey;
    private $request;
    private $action;
    private $chatID;
    
    private $botUsername;
    
    protected $textMessage;
    
    public function __construct( $apiKey, $botUserName = null) {
        $this->apiKey = $apiKey;
        $this->client = new Client();
        if($botUserName) $this->botUsername = $botUserName;
    }
    
    private function getApiUrl(){
        $url = str_replace('[apiKey]', $this->apiKey, $this->apiUrl);
        $url = str_replace('[action]', $this->action, $url);
        return $url;
    }
    
    public function getBotUsername(){
        return $this->botUsername;
    }
    
    public function setChat($id){
     $this->chatID = $id;
    }
    /**
     * Properly set up the request params
     *
     * If any item of the array is a resource, reformat it to a multipart request.
     * Else, just return the passed data as form params.
     *
     * @param array $data
     *
     * @return array
     */
    private function setUpRequestParams(array $data)
    {
        $has_resource = false;
        $multipart    = [];
        // Convert any nested arrays into JSON strings.
        array_walk($data, function (&$item) {
            is_array($item) && $item = json_encode($item);
        });
        //Reformat data array in multipart way if it contains a resource
        foreach ($data as $key => $item) {
            $has_resource |= (is_resource($item) || $item instanceof \GuzzleHttp\Psr7\Stream);
            $multipart[]  = ['name' => $key, 'contents' => $item];
        }
        if ($has_resource) {
            return ['multipart' => $multipart];
        }
        return ['form_params' => $data];
    }
    
    public function send($action, array $data = [])
    {
        $data['chat_id'] = $this->chatID;
        
        $this->action = $action;
//        self::ensureValidAction($action);
//        self::addDummyParamIfNecessary($action, $data);

//        self::ensureNonEmptyData($data);
//        self::limitTelegramRequests($action, $data);
        $response = json_decode($this->execute($data), true);
        if (null === $response) {
            throw new \Exception('Telegram returned an invalid response! Please review your bot name and API key.');
        }
        return new \Exception($response.' -> '.$this->getBotUsername());
    }
    
    private function execute($data){

        $url = $this->getApiUrl();
        $request = $this->client->post($url, $this->setUpRequestParams($data));
        
        $result = (string) $request->getBody();
        
        return $result;
    }
}