<?php
class mt4datareciver {
	var $socketPtr;
	var $secretHashValue = "fsdvgfygfsddsagsjh";
	var $encryptionKey = "asfas1sjh";
	function OpenConnection($server, $port) {
		$this->socketPtr = @fsockopen ( $server, $port, $errno, $errstr, 5 );
		if (! $this->socketPtr) {
			//echo $errno, $errstr;
			return - 1;
		} else {
			return 0;
		}
	}
	function SetSafetyData($secretHashValue, $encryptionKey) {
		$this->secretHashValue = $secretHashValue;
		$this->encryptionKey = $encryptionKey;
	}
	function MakeRequest($action, $params = array()) {
		if (! $this->socketPtr)
			return "error";
		
		$request_id = 6099; // rand();
		$request = "action=$action&request_id=$request_id";
		
		foreach ( $params as $key => $value ) {
			$request .= "&$key=$value";
		}
		
		if ($this->secretHashValue != "none") {
			$hash = $this->makeHash ( $action, $request_id );
			$request .= "&hash=$hash";
		}
		
		$request .= "\0"; // leading zero. It must be added to the end of each request
		if ($this->encryptionKey != "none") {
			$request = $this->cryptography ( $request );
		}
		
		if ($request == "")
			return "error";
			// echo $request;exit;
		$this->sendRequest ( $request );
		
		return $this->readAnswer ();
	}
	function CloseConnection() {
		if ($this->socketPtr) {
			fclose ( $this->socketPtr );
		}
	}
	function sendRequest($request) {
		fputs ( $this->socketPtr, $request );
	}
	function readAnswer() {
		$size = fgets ( $this->socketPtr, 64 );
		
		$answer = "";
		$readed = 0;
		
		while ( $readed < $size ) {
			$part = fread ( $this->socketPtr, $size - $readed );
			$readed += strlen ( $part );
			$answer .= $part;
		}
		if ($this->encryptionKey != "none") {
			$answer = $this->cryptography ( $answer, $this->encryptionKey );
		}
		return $answer;
	}
	function makeHash($action, $request_id) {
		return md5 ( $request_id . $action . $this->secretHashValue );
	}
	function cryptography($data) {
		$keyLen = strlen ( $this->encryptionKey );
		$keyIndex = 0;
		for($i = 0; $i < strlen ( $data ); $i ++) {
			$data [$i] = $data [$i] ^ $this->encryptionKey [$keyIndex];
			$keyIndex ++;
			if ($keyIndex == $keyLen)
				$keyIndex = 0;
		}
		return $data;
	}
	function sethashandkey(){
		$this->secretHashValue = 'none';
		$this->encryptionKey = 'none';
	}
}
?>