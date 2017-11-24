<?php


namespace App\Http\Bot;


use App\Http\Api\VkApi;

class VkBot extends VkApi
{
    public  function bot_sendMessage($user_id) {
        $users_get_response = $this->vkApi_usersGet($user_id);
        $user = array_pop($users_get_response);
        $msg = "Привет, {$user['first_name']}!";
        $imgDir = storage_path('images');
        $photo = $this->_bot_uploadPhoto($user_id, $imgDir .'/'.time().'jpeg');
        $voice_message_file_name = $this->yandexApi_getVoice($msg);
        $doc = $this->_bot_uploadVoiceMessage($user_id, $voice_message_file_name);
        $attachments = array(
            'photo'.$photo['owner_id'].'_'.$photo['id'],
            'doc'.$doc['owner_id'].'_'.$doc['id'],
        );
        $this->vkApi_messagesSend($user_id, $msg, $attachments);
    }
    public function _bot_uploadPhoto($user_id, $file_name) {
        $upload_server_response = $this->vkApi_photosGetMessagesUploadServer($user_id);
        $upload_response = $this->vkApi_upload($upload_server_response['upload_url'], $file_name);
        $photo = $upload_response['photo'];
        $server = $upload_response['server'];
        $hash = $upload_response['hash'];
        $save_response = $this->vkApi_photosSaveMessagesPhoto($photo, $server, $hash);
        $photo = array_pop($save_response);
        return $photo;
    }
    public function _bot_uploadVoiceMessage($user_id, $file_name) {
        $upload_server_response = $this->vkApi_docsGetMessagesUploadServer($user_id, 'audio_message');
        $upload_response = $this->vkApi_upload($upload_server_response['upload_url'], $file_name);
        $file = $upload_response['file'];
        $save_response = $this->vkApi_docsSave($file, 'Voice message');
        $doc = array_pop($save_response);
        return $doc;
    }
}