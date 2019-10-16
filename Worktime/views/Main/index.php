hello

<?php


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://worktime-test.james52.ru/api/v1/calendar/date");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        // указываем, что у нас POST запрос
        curl_setopt($curl, CURLOPT_POST, true);
        // добавляем тело запроса
        $post_data = "{\"config\":{\"timezone\":\"America/Indianapolis\"}}";
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $out = curl_exec($curl);
        $res = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);

        curl_close($curl);
        debug($out);
?>