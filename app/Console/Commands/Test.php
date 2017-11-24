<?php

namespace App\Console\Commands;

use App\Exceptions\VKException;
use App\Http\Services\VKOAuth;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $vk = new  VKOAuth('{APP_ID}', '{SECRET}');
        $currentUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
        dd($currentUrl);
        try {
            if (!isset($_GET['code'])) {
                $url = $vk->getAuthenticationUrl($currentUrl, 'friends,wall');
                echo '<a href="'.$url.'">Enter using VK account</a>';
            } else {
                $token = $vk->getAccessToken($_GET['code'], $currentUrl);
                $timeToLive = $token['expires_in'];
                $userId = $token['user_id'];
                echo 'User ID is ' . $userId . '. Token is: ' . $token['access_token'];
                echo ' and it is valid until ' . date('Y-m-d H:i:s', time() + $token['expires_in']);
                echo '<br>Now you can <a href="wall_get.php?token='.$token['access_token'].'">move to the next example</a>.';
            }
        } catch ( VKException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
