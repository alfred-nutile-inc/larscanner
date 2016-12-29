<?php

namespace AlfredNutileInc\LarScanner;

use Guzzle\Http\Client;
use Illuminate\Support\Facades\Log;

class SendSlackNotice
{
    protected $incoming;

    protected $client;

    protected $slack_url = false;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function sendMessageToSlack($message)
    {
        try
        {
            $this->client->post(
                $this->getSlackUrl(), null, $this->message($message)
            )->send();
        }
        catch(\Exception $e)
        {
            Log::debug(sprintf("Error sending to Slack %s", $e->getMessage()));
        }


    }

    protected function message($message)
    {

        return json_encode(
            [
                'text' => $message
            ]
        );
    }

    public function getSlackUrl()
    {
        if(!$this->slack_url)
            return env('SLACK_URL');

        return $this->slack_url;
    }

    /**
     * @param boolean $slack_url
     */
    public function setSlackUrl($slack_url)
    {
        $this->slack_url = $slack_url;
        return $this;
    }
}
