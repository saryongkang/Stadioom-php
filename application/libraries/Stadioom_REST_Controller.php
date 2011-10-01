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

}

class Test_REST_Controller extends Stadioom_REST_Controller {

    protected $last_request = null;

    protected function runTest($desc, $api, $param, $httpMethod = 'POST') {
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
                throw new Exception("Not Implemented: " . $httpMethod);
            case "DELETE":
                throw new Exception("Not Implemented: " . $httpMethod);
            default:
                throw new Exception("Unknown HTTP method: " . $httpMethod);
        }
        echo '| [RESPONSE] ' . $this->arrayToString($result) . '<br>';
        echo '| -----------------------------------------------------------------------------<br>';
        echo '</div>';

        return $result;
    }

    protected function sendPost($uri, $param) {
        $this->last_request = '[POST] ' . $uri . '<br> - with params {<br>' . $this->arrayToString($param) . '}';
        $this->curl->create($uri);
        $this->curl->post($param);

        return $this->curl->execute();
    }

    protected function sendGet($uri, $param = NULL) {
        $this->last_request = '[GET] ' . $uri . '<br> - with params {<br>' . $this->arrayToString($param) . '}';

        return $this->curl->simple_get($uri, $param);
    }

    protected function generateGrantCode($email, $password) {
        return base64_encode($email . ":" . $password);
    }

    protected function assertArray($result, $key, $value) {
        $json = json_decode($result);
        if ($json->$key != $value) {
            throw new Exception("[key:" . $key . "] expected:" . $value . ", actual:" . $json->$key);
        }
    }

    protected function assertEquals($result, $expected) {
        $actual = json_decode($result);
        if ($actual != $expected) {
            throw new Exception("[key] expected:" . $expected . ", actual:" . $actual);
        }
    }

    protected function assertArray_NotNull($result, $key) {
        $json = json_decode($result);
        if ($json->$key == NULL) {
            throw new Exception("[key:" . $key . "] expected: Not NULL." . "\nResult: " . $result);
        }
    }

    protected function createTestUser() {
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

    protected function arrayToString($array) {
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


    protected function getSuite($object) {
        $class = new ReflectionClass(get_class($object));
        $allMethods = $class->getMethods();
        
        $suite = array();
        foreach($allMethods as $method) {
            if (strpos($method->name, 'test') === 0) {
                array_push($suite, array($class->getName(), $method->name));
            }
        }
        
        return $suite;
    }


    public function all_get() {
        $suite = $this->getSuite($this);

        $passed = 0;
        foreach ($suite as $case) {
            try {
                echo 'Running.. ' . $case[1];
                call_user_func($case);
                $passed += 1;
                echo '=> PASSED.<br><br>';
            } catch (Exception $e) {
                echo '=> FAILED.<br><br>';
                echo "<pre>";
                echo 'Last Request: ' . $this->last_request . '<br>';
                echo 'Code: ' . $e->getCode() . '<br>';
                echo 'File: ' . $e->getFile() . '<br>';
                echo 'Line: ' . $e->getLine() . '<br>';
                echo 'Message: ' . $e->getMessage() . '<br>';
                echo 'Previous: ' . $e->getPrevious() . '<br>';
                echo 'Trace: <br>' . $e->getTraceAsString() . '<br>';
                echo "</pre>";
            }
        }
        echo '| =============================================================================<br>';
        echo "| Passed " . $passed . ' out of ' . count($suite) . '<br>';
        echo '| =============================================================================<br>';
    }
}