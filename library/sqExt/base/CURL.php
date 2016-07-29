<?php
namespace sq\base;

class CURL {

    public static function get($url, $json = false){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $res = curl_exec($ch);
        curl_close($ch);
		
        if($json)
			return json_decode($res, true);
        return $res;
    }

	public static function post($strUrl, $fields, $files=array(), $json = false)
	{
		
		// $arrHeader[] = 'Content-Length: ' . strlen($strXml);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $strUrl);
		// curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$res = curl_exec($ch);
		curl_close($ch);
        if($json) $res = json_decode($res, true);
		return $res;
	}
}
