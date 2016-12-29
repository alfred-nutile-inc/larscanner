<?php


namespace AlfredNutileInc\LarScanner\Listeners;


use AlfredNutileInc\LarScanner\ResultDTO;
use AlfredNutileInc\LarScanner\SendSlackNotice;
use Guzzle\Http\Client;
use Illuminate\Support\Facades\Log;

class SlackNotify
{

    /**
     * @var SendSlackNotice
     */
    protected $send_slack;

    public function handle($from, array $messages) {

        if(!env('SECURITY_NOTICE_SLACK_URL')) {
            throw new \Exception("SECURITY_NOTICE_SLACK_URL not set in .env");
        }

        $this->getSendSlack()->setSlackUrl(env('SECURITY_NOTICE_SLACK_URL'));

        /** @var ResultDTO $message */
        foreach($messages as $message) {
            $message_formatted = $this->processMessage($message, $from);
            $this->getSendSlack()->sendMessageToSlack($message_formatted);
        }
    }

    protected function processMessage(ResultDTO $message, $from) {

        $app_name = $this->getAppName();

        $message_formatted = sprintf("
                From App: %s \n
                Security Tool: %s  \n
                Message: \n
                %s\n
                %s 
                \n
                Library: %s
            ", $app_name, $from, $message->title, $message->body, $message->library);

        return $message_formatted;
    }

    /**
     * @return SendSlackNotice
     */
    public function getSendSlack()
    {
        if(!$this->send_slack) {
            $this->setSendSlack();
        }
        return $this->send_slack;
    }

    /**
     * @param SendSlackNotice $send_slack
     */
    public function setSendSlack($send_slack = null)
    {
        if(!$send_slack) {
            $send_slack = new SendSlackNotice(new Client());
        }
        $this->send_slack = $send_slack;
    }

    private function getAppName()
    {
        if($name = env("APP_NAME")) {
            return $name;
        }

        return url()->current();
    }

}