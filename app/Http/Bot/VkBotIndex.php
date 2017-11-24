<?php


namespace App\Http\Bot;
use Illuminate\Support\Facades\Log;

define('CALLBACK_API_EVENT_CONFIRMATION', 'confirmation');
define('CALLBACK_API_EVENT_MESSAGE_NEW', 'message_new');
//require_once 'config.php';
//require_once 'global.php';
//require_once 'api/vk_api.php';
//require_once 'api/yandex_api.php';
//require_once 'bot/bot.php';


class VkBotIndex
{

//if (!isset($_REQUEST)) {
//exit;

//callback_handleEvent();
    public function callback_handleEvent()
    {
        $event = $this->_callback_getEvent();
        try {
            switch ($event['type']) {
                //Подтверждение сервера
                case CALLBACK_API_EVENT_CONFIRMATION:
                    $this->_callback_handleConfirmation();
                    break;
                //Получение нового сообщения
                case CALLBACK_API_EVENT_MESSAGE_NEW:
                    $this->_callback_handleMessageNew($event['object']);
                    break;
                default:
                    $this->_callback_response('Unsupported event');
                    break;
            }
        } catch (\Exception $e) {
            Log::alert($e);
        }
        $this->_callback_okResponse();
    }

    public function _callback_getEvent()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    public function _callback_handleConfirmation()
    {
        $token = env('CALLBACK_API_CONFIRMATION_TOKEN');
        $this->_callback_response($token);
    }

    public function _callback_handleMessageNew($data)
    {
        $user_id = $data['user_id'];
        (new VkBot)->bot_sendMessage($user_id);
        $this->_callback_okResponse();
    }

    public function _callback_okResponse()
    {
        $this->_callback_response('ok');
    }

    public function _callback_response($data)
    {
        return $data;
    }
}

//require_once 'config.php';
//require_once 'global.php';
//require_once 'api/vk_api.php';
//require_once 'api/yandex_api.php';
//require_once 'bot/bot.php';