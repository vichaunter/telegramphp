<?php
/**
 * Created by PhpStorm.
 * User: vicha
 * Date: 13/03/2018
 * Time: 19:29
 */

//517124109:AAE0CKU4W1rlRFT7HLw1cHCjNejY5rAKjhs

namespace VicHaunter\Telegram;

class Api extends ApiModel {
    
    
    
    public function setMessage($message){
        $this->textMessage = $message;
    }
    
    /**
     * Use this method to send text messages. On success, the sent Message is returned
     *
     * @link https://core.telegram.org/bots/api#sendmessage
     *
     * @param array $data
     *
     */
    public function sendMessage($text)
    {
        do {
            //Chop off and send the first message
            $text = mb_substr($text, 0, 4096);
            $response     = $this->send('sendMessage', ['text' => $text]);
            //Prepare the next message
            $text = mb_substr($text, 4096);
        } while (mb_strlen($text, 'UTF-8') > 0);
        return $response;
    }
    
}