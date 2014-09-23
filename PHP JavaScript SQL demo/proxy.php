<?php
/*
	Serves as proxy for any remote server requests.
	
	Due to same-origin policy, it is not possible to get data from remote server
	with ajax.  One work-around is having ajax go through a proxy on the local server, 
	thus circumventing the same-origion policy.
	Advantages: 
		- No modification needed on remote server.
		- Potentially safer for end user, because result isn't eval()'d.
	Disadvantages:
		- Increases total ajax response time.
		- Possibly use more server resources like bandwidth and server CPU.
*/


/*
//this was the original code using cURL
//unfortunately, University of Kentucky servers didn't enable cURL on PHP...
if(isset($_GET["remoteURL"]) && is_string($_GET["remoteURL"]))
{
	//print_r( $_GET );
	//$_GET["remoteURL"] must be a fully qualified domain name
	$url	= $_GET["remoteURL"];
	
	//$_GET["sendData"] must be a properly formatted key-value pair string
	if(isset($_GET["sendData"]) && is_string($_GET["sendData"]))
	{
		$url	.= "?".$_GET["sendData"];
	}
	
	//error_log($url);
	
	$handler	= curl_init($url);
	curl_exec($handler);
	curl_close($handler);
}
*/



//this function was taken from http://www.jonasjohn.de/snippets/php/post-request.htm
//it allows access of external pages without cURL
//slightly modified from the original author's to handle GET instead of POST
function post_request($url, $data, $referer='') {
 
    // Convert the data array into URL Parameters like a=b&foo=bar etc.
    $data = http_build_query($data);
 
	
    // parse the given URL
    $url = parse_url($url);
 
    if ($url['scheme'] != 'http') { 
        die('Error: Only HTTP request are supported !');
    }
 
    // extract host and path:
    $host = $url['host'];
    $path = $url['path'];
 
    // open a socket connection on port 80 - timeout: 30 sec
    $fp = fsockopen($host, 80, $errno, $errstr, 30);
 
    if ($fp){
 
        // send the request headers:
        fputs($fp, "GET $path?".$data." HTTP/1.1\r\n");
        fputs($fp, "Host: $host\r\n");
 
        if ($referer != '')
            fputs($fp, "Referer: $referer\r\n");
 
        fputs($fp, "Content-type: text/html\r\n");
        //fputs($fp, "Content-length: ". strlen($data) ."\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        //fputs($fp, $data);
 
        $result = ''; 
        while(!feof($fp)) {
            // receive the results of the request
            $result .= fgets($fp, 128);
        }
    }
    else { 
        return array(
            'status' => 'err', 
            'error' => "$errstr ($errno)"
        );
    }
 
    // close the socket connection:
    fclose($fp);
 
    // split the result header from the content
    $result = explode("\r\n\r\n", $result, 2);
 
    $header = isset($result[0]) ? $result[0] : '';
    $content = isset($result[1]) ? $result[1] : '';
 
    // return as structured array:
    return array(
        'status' => 'ok',
        'header' => $header,
        'content' => $content
    );
}
 
// Send a request to example.com 
$result = post_request($_GET["remoteURL"], $_GET["sendData"]);
 
if ($result['status'] == 'ok'){
    // print the result of the whole request:
    echo $result['content'];
 
}
else {
    echo 'A error occured: ' . $result['error']; 
}


?>