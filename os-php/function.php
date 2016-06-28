<?php

function do_request($url, $data = array(), $method = 'POST', $optional_headers = null) {
    $params = array('http' => array(
            'method' => $method,
            'content' => http_build_query($data)
    ));
    if ($optional_headers !== null) {
        $params['http']['header'] = $optional_headers;
    }
    $ctx = stream_context_create($params);
    $fp = @fopen($url, 'rb', false, $ctx);
    if (!$fp) {
        throw new Exception("Problem with $url, $php_errormsg");
    }
    $response = @stream_get_contents($fp);
    if ($response === false) {
        throw new Exception("Problem reading data from $url, $php_errormsg");
    }
    return $response;
}

function do_post_request($url, $data = array(), $optional_headers = null) {
    return do_request($url, $data, 'POST', $optional_headers);
}

function do_get_request($url, $data = array(), $optional_headers = null) {
    return do_request($url, $data, 'GET', $optional_headers);
}
