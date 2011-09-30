<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class study extends Test_REST_Controller {

    public final function final_get() {
        
    }

    public final function final2_get() {
        
    }

    public function func_get() {
        $class = new ReflectionClass('Fb');
        $methods = $class->getMethods();
        $methods2 = $class->getMethods(ReflectionMethod::IS_PUBLIC & ReflectionMethod::IS_FINAL);

        $suite = array(
            array($this, 'test_1'),
            array($this, 'test_2')
        );

        $passed = 0;
        foreach ($suite as $case) {
            try {
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
        echo "| Passed " . $passed . ' of total ' . count($suite) . '<br>';
        echo '| =============================================================================<br>';
    }

    public function test_1() {
        $result = $this->testPost("connect with an 'invalid' Facebook account.", "api/fb/connect", array('fbId' => '111', 'fbAccessToken' => 'bypass me.', 'fbExpires' => 0));
        $this->assertArray($result, 'error_code', 401);
    }

    public function test_2() {
        $result = $this->testPost("connect with an 'invalid' Facebook account.", "api/fb/connect", array('fbId' => '111', 'fbAccessToken' => 'bypass me.', 'fbExpires' => 0));
        throw new Exception("yohoho", 50);
        $this->assertArray($result, 'error_code', 401);
    }

}

?>
