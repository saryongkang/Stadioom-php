<?php

require(APPPATH . '/libraries/REST_Controller.php');

/**
 * Common REST controller for Stadioom REST APIs.
 */
class Stadioom_REST_Controller extends REST_Controller {

    private $DEFAULT_SUCCEED_MSG = "OK";

    private function ex2Array($e) {
        return array('error_code' => $e->getCode(), 'error_msg' => $e->getMessage());
    }

    protected function responseOk($res = null) {
        if ($res != null) {
            $this->response($res, 200);
        }
        $this->response($this->DEFAULT_SUCCEED_MSG, 200);
    }

    protected function responseError($e) {
        $this->response($this->ex2Array($e), $e->getMessage());
    }

    /**
     * Checks whether the given access token is valid or not.
     * Then returns the token owner's user ID.
     * 
     * @param string $accessToken 
     * @returns string The user's ID.
     */
    protected function verifyToken($accessToken) {
        if ($accessToken == NULL) {
            throw new Exception("Invalid access token. (NULL)", 400);
        }
        $this->load->library('encrypt');
        $decodedToken = $this->encrypt->decode($accessToken);
        $magicCode = strtok($decodedToken, ":");
        $userId = strtok(":");
        $expired = strtok(":");

        if ($magicCode != "SeedShock" || $userId <= 0) {
            throw new Exception("Invaild access token. (" . $magicCode . ':' . $userId . ':' . $expired . ")", 400);
        }

        $curDate = new DateTime();
        $curDate = $curDate->getTimestamp();
        if ($expired != 0 && $expired < $curDate) {
            throw new Exception("Token expired.", 401);
        }

        return $userId;
    }

    protected function filter($array, $filterKeys) {
        $keys = array_keys($array);
        $filteredArray = array();
        foreach ($keys as $key) {
            $filtered = false;
            foreach ($filterKeys as $filterKey) {
                if ($key == $filterKey) {
                    $filtered = true;
                    break;
                }
            }
            if (!$filtered) {
                $filteredArray[$key] = $array[$key];
            }
        }
        return $filteredArray;
    }

}

class Test_REST_Controller extends Stadioom_REST_Controller {

    protected $last_request = null;

    protected final function runTest($desc, $api, $param, $httpMethod = 'POST') {
        $uri = $this->config->item('base_url') . $api;

        echo '<div>';
        echo '| -----------------------------------------------------------------------------<br>';
        echo '| ' . $desc . '<br>';
        echo '| -----------------------------------------------------------------------------<br>';

        echo '| [REQUEST] - ' . $httpMethod . '<br>';
        echo '| URI: ' . $uri . '<br>';
        echo '| PARAM {<br>' . $this->arrayToString($param) . '| }<br>';
        switch ($httpMethod) {
            case "POST":
                $result = $this->sendPost($uri, $param);
                break;
            case "GET":
                $result = $this->sendGet($uri, $param);
                break;
            case "PUT":
                $result = $this->sendPut($uri, $param);
                break;
            case "DELETE":
                $result = $this->sendDelete($uri, $param);
                break;
            default:
                throw new Exception("Unknown HTTP method: " . $httpMethod);
        }
        echo '| [RESPONSE] ' . $this->arrayToString($result) . '<br>';
        echo '| -----------------------------------------------------------------------------<br>';
        echo '</div>';

        return $result;
    }

    protected final function sendPost($uri, $param) {
        $this->last_request = '[POST] ' . $uri . '<br> - with params {<br>' . $this->arrayToString($param) . '}';
        $this->curl->create($uri);
        $this->curl->post($param);

        return $this->curl->execute();
    }

    protected final function sendDelete($uri, $param) {
        $this->last_request = '[DELETE] ' . $uri . '<br> - with params {<br>' . $this->arrayToString($param) . '}';
        $this->curl->create($uri);
        $this->curl->delete($param);

        return $this->curl->execute();
    }

    protected final function sendPut($uri, $param) {
        $this->last_request = '[PUT] ' . $uri . '<br> - with params {<br>' . $this->arrayToString($param) . '}';
        $this->curl->create($uri);
        $this->curl->put($param);

        return $this->curl->execute();
    }

    protected final function sendGet($uri, $param = NULL) {
        $this->last_request = '[GET] ' . $uri . '<br> - with params {<br>' . $this->arrayToString($param) . '}';

        return $this->curl->simple_get($uri, $param);
    }

    public static function generateGrantCode($email, $password) {
        return base64_encode($email . ":" . $password);
    }

    protected final function createTestUser() {
        $response = $this->curl->simple_get("https://graph.facebook.com/200987663288876/accounts/test-users?installed=true&permissions=publish_stream,email,offline_access&method=post&access_token=200987663288876%7C6d3dd0e7aa9dae300920ec05552bddee");
        $json_decoded = json_decode($response);

        $testUser = array('fbId' => $json_decoded->id,
            'email' => $json_decoded->email,
            'password' => $json_decoded->password,
            'fbAccessToken' => $json_decoded->access_token,
            'name' => $this->getFbUserName($json_decoded->access_token));

        return $testUser;
    }

    private function getFbUserName($fbAccessToken) {
        $this->load->library('fb_connect');
        $this->fb_connect->setAccessToken($fbAccessToken);

        try {
            $fbMe = $this->fb_connect->api('/me', 'GET');
            return $fbMe['first_name'] . ' ' . $fbMe['last_name'];
        } catch (FacebookApiException $e) {
            throw new Exception("Failed to get authorized by Facebook.", 401, $e);
        }
    }

    protected final function arrayToString($array) {
        if ($array == NULL) {
            return "NULL";
        }
        if (!is_array($array)) {
            return $array;
        }

        $result = '';

        $keys = array_keys($array);
        foreach ($keys as $key) {
            $value = $array[$key];
            if (is_array($value)) {
                $result = $result . '| ' . $key . ': {<br>';
                $result = $result . $this->arrayToString($value);
                $result = $result . '| }<br>';
            } else {
                $result = $result . '| ' . $key . ': ' . $array[$key] . '<br>';
            }
        }

        return $result;
    }

    private function getSuite($object) {
        $class = new ReflectionClass(get_class($object));
        $allMethods = $class->getMethods();

        $suite = array();
        foreach ($allMethods as $method) {
            if (strpos($method->name, 'test') === 0) {
                array_push($suite, array($class->getName(), $method->name));
            }
        }

        return $suite;
    }

    protected function beforeClass() {
        // will be overrided by child class if required.
    }

    protected function before() {
        // will be overrided by child class if required.
    }

    protected function after() {
        // will be overrided by child class if required.
    }

    protected function afterClass() {
        // will be overrided by child class if required.
    }

    public final function index_get() {
        $suite = $this->getSuite($this);

        $passed = 0;
        try {
            $this->beforeClass();

            foreach ($suite as $case) {
                try {
                    $this->before();

                    echo 'Running.. ' . $case[1];
                    call_user_func($case);
                    $passed += 1;
                    echo '=> PASSED.<br><br>';

                    $this->after();
                } catch (Exception $e) {
                    echo '=> FAILED.<br><br>';
                    echo "<pre>";
                    echo 'Last Request: ' . $this->last_request . '<br>';
                    $this->printException($e);
                    echo "</pre>";
                }
            }

            $this->afterClass();
        } catch (Exception $e) {
            echo "<pre>";
            echo 'Errors on beforeClass or afterClass:<br>';
            $this->printException($e);
            echo "</pre>";
        }
        echo '| =============================================================================<br>';
        echo "| Passed " . $passed . ' out of ' . count($suite) . '<br>';
        echo '| =============================================================================<br>';
    }

    private function printException($e) {
        echo 'Code: ' . $e->getCode() . '<br>';
        echo 'File: ' . $e->getFile() . '<br>';
        echo 'Line: ' . $e->getLine() . '<br>';
        echo 'Message: ' . $e->getMessage() . '<br>';
        echo 'Previous: ' . $e->getPrevious() . '<br>';
        echo 'Trace: <br>' . $e->getTraceAsString() . '<br>';
    }

}

class Assert {

    public static function assertArray($result, $key, $value) {
        $json = json_decode($result);
        if ($json->$key != $value) {
            throw new Exception("[key:" . $key . "] expected:" . $value . ", actual:" . $json->$key);
        }
    }

    public static function assertInArray($result, $index, $key, $value) {
        $json = json_decode($result);
        if ($json[$index]->$key != $value) {
            throw new Exception("[index:" . $index . "] [key:" . $key . "] expected:" . $value . ", actual:" . $json[$index]->$key);
        }
    }

    public static function assertContainsInArray($result, $key, $value) {
        $json = json_decode($result);
        foreach ($json as $element) {
            if ($element->$key == $value) {
                return;
            }
        }
        throw new Exception("[key:" . $key . "] expected:" . $value);
    }

    public static function assertError($result, $errorCode) {
        $json = json_decode($result);
        if ($json->error_code != $errorCode) {
            throw new Exception("Expected Error Code:" . $value . ", actual:" . $json->error_code);
        }
    }

    public static function assertEquals($result, $expected) {
        $actual = json_decode($result);
        if ($actual != $expected) {
            throw new Exception("[key] expected:" . $expected . ", actual:" . $actual);
        }
    }
    
    public static function fail() {
        throw new Exception("Assertion failed.");
    }
    
    public static function assertTrue($value) {
        if (!$value) {
            throw new Exception("expected: true, actual: " . $value);
        }
    }

    public static function assertArrayCount($result, $expectedCount) {
        $actual = json_decode($result);
        if ($expectedCount != count($actual)) {
            throw new Exception("Expected count:" . $expectedCount . ", actual:" . count($actual));
        }
    }

    public static function assertArray_NotNull($result, $key) {
        $json = json_decode($result);
        if ($json->$key == NULL) {
            throw new Exception("[key:" . $key . "] expected: Not NULL." . "\nResult: " . $result);
        }
    }

}

class XrayVision {

    protected $id;

    function export($object) {
        $this->id = 1;
        list($value, $input) = $this->parse(serialize($object));
        return $value;
    }

    protected function parse($input) {
        if (substr($input, 0, 2) === 'N;') {
            return array(array('type' => 'null', 'id' => $this->id++,
                    'value' => null), substr($input, 2));
        }
        $pos = strpos($input, ':');
        $type = substr($input, 0, $pos);
        $input = substr($input, $pos + 1);
        switch ($type) {
            case 's':
                return $this->s($input);
            case 'i':
                return $this->i($input);
            case 'd':
                return $this->d($input);
            case 'b':
                return $this->b($input);
            case 'O':
                return $this->o($input);
            case 'a':
                return $this->a($input);
            case 'r':
                return $this->r($input);
        }
        throw new Exception("Unhandled type '$type'");
    }

    protected function s($input) {
        $pos = strpos($input, ':');
        $length = substr($input, 0, $pos);
        $input = substr($input, $pos + 1);
        $value = substr($input, 1, $length);
        return array(array('type' => 'string', 'id' => $this->id++,
                'value' => $value), substr($input, $length + 3));
    }

    protected function i($input) {
        $pos = strpos($input, ';');
        $value = (integer) substr($input, 0, $pos);
        return array(array('type' => 'integer', 'id' => $this->id++,
                'value' => $value), substr($input, $pos + 1));
    }

    protected function d($input) {
        $pos = strpos($input, ';');
        $value = (float) substr($input, 0, $pos);
        return array(array('type' => 'float', 'id' => $this->id++, 'value'
                => $value), substr($input, $pos + 1));
    }

    protected function b($input) {
        $pos = strpos($input, ';');
        $value = substr($input, 0, $pos) === '1';
        return array(array('type' => 'boolean', 'id' => $this->id++,
                'value' => $value), substr($input, $pos + 1));
    }

    protected function r($input) {
        $pos = strpos($input, ';');
        $value = (integer) substr($input, 0, $pos);
        return array(array('type' => 'recursion', 'id' => $this->id++,
                'value' => $value), substr($input, $pos + 1));
    }

    protected function o($input) {
        $id = $this->id++;
        $pos = strpos($input, ':');
        $name_length = substr($input, 0, $pos);
        $input = substr($input, $pos + 1);
        $name = substr($input, 1, $name_length);
        $input = substr($input, $name_length + 3);
        $pos = strpos($input, ':');
        $length = (int) substr($input, 0, $pos);
        $input = substr($input, $pos + 2);
        $values = array();
        for ($ii = 0; $ii < $length; $ii++) {
            list($key, $input) = $this->parse($input);
            $this->id--;
            list($value, $input) = $this->parse($input);
            if (substr($key['value'], 0, 3) === "\000*\000") {
                $values['protected:' . substr($key['value'], 3)] = $value;
            } elseif ($pos = strrpos($key['value'], "\000")) {
                $values['private:' . substr($key['value'], $pos + 1)] = $value;
            } else {
                $values[str_replace("\000", ':', $key['value'])] = $value;
            }
        }
        return array(
            array('type' => 'object', 'id' => $id, 'class' => $name, 'value'
                => $values),
            substr($input, 1));
    }

    protected function a($input) {
        $id = $this->id++;
        $pos = strpos($input, ':');
        $length = (int) substr($input, 0, $pos);
        $input = substr($input, $pos + 2);
        $values = array();
        for ($ii = 0; $ii < $length; $ii++) {
            list($key, $input) = $this->parse($input);
            $this->id--;
            list($value, $input) = $this->parse($input);
            $values[$key['value']] = $value;
        }
        return array(
            array('type' => 'array', 'id' => $id, 'value' => $values),
            substr($input, 1));
    }

}

