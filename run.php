<?php
require __DIR__ . '/vendor/autoload.php';

use Curl\Curl;

$curl = new Curl();

echo "\n[?] Input List [data] : "; 
$input = trim(fgets(STDIN));
$list = file_get_contents($input);

// explode and replace data for looping
$datas = explode("\n", str_replace("\r", "", $list));

echo '[+] Ada ' . count($datas) . " data.\n\n";

for ($i = 0; $i < count($datas); $i++) {

    $address = $datas[$i];
    echo '[+] ' . $address . "\n";

    $curl->get('https://api.micromedia.id/v1/zilliqa/transactions?address=' . $address);

    if ($curl->error) {
        echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
    } else {
        $response = $curl->response;
        
        if ($response->status == 200) {
            foreach ($response->data as $key => $data) {
                $message = $data->message ?? null;

                if($message){
                    echo $data->message;
                    break;
                } else {
                    $isSuccess = ($data->receiptSuccess) ? 'Success' : 'Failed';
                    echo $key+1 . ". " . $data->direction . " | ". $data->hash . " | " . $data->value . " | " . $isSuccess . "\n";
                }
            }
        }

        echo "\n";
    }
}