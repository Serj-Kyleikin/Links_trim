<?php

namespace libraries;

class ScanURL {

    // Сканирование ссылки на вирусы

	public function scanUrl($check) {

        $url = "https://www.virustotal.com/vtapi/v2/url/scan";
        $api = '9b9334e22404ca439b95a6e768541677d47ec1fe443dcd85b8dcafde1bc9bb7b';

		$post = array('apikey' => $api,'url'=> $check);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Apikey: ' . $api, 'Accept: application/json']);
		curl_setopt($ch, CURLOPT_POST, True);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER ,True);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

		$result=curl_exec ($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);

		if($status == 200) {
			$js = json_decode($result, true);
			$status = ($js['response_code'] == 1) ? 'clear' : 'infected';
		} else {

            $to = 'admin@domain.site';
            $subject = "Сообщение с сайта "; 
            $text = 'Ошибка подключения к VirusTotal API. Статус ответа: ' . $status;

            mail($to, $subject, $text);
		}

		return $status;
	}
}