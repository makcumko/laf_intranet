<?php

namespace App\Model;

class Application
{
    public function __construct()
    {
        Registry::Singleton("\App\Model\Service\Users")->GetFromSession();
    }

    public function ProcessRequest() {
        ob_start();
        try {
            $request = new Request();
            $response = new Response();
            Registry::Set("request", $request);
            Registry::Set("response", $response);
        } catch (\Exception $e) {
            echo "Error initializing framework: ".$e->getMessage();
            error_log("ERROR:".PHP_EOL.
                "Request IP: '{$_SERVER['REMOTE_ADDR']}'".PHP_EOL.
                "Request params: '".json_encode($request)."'".PHP_EOL.
                "Exception: '".$e->getMessage()."'");
            ob_end_flush();
            return;
        }

        try {
            if (!is_array($request->params))
            {
                throw new \Exception("Invalid request, request has wrong format and can't be parsed", \App\Model\Errors::ERR_BAD_REQUEST);
            }

            if (!$request->controller) $request->controller = "Default";
            if (!$request->method) $request->method = "Main";

            $params = $this->_parseParams($request);

            $controllerFile = "\\App\\Controller\\{$request->controller}Controller";
            try {
                $controller = new $controllerFile();
            } catch (\Exception $e) {
                throw new \Exception("Controller {$request->controller} is not supported", \App\Model\Errors::ERR_INVALID_METHOD);
            }

            if (!is_callable([$controller, $request->method]))
            {
                error_log("Try to call not exist method {$request->controller}->{$request->method}");
                throw new \Exception("Method {$request->method} is not supported", \App\Model\Errors::ERR_INVALID_METHOD);
            }


            $controller->_prepare();
            try {
                $result = call_user_func_array([$controller, $request->method], $params);
            } catch (\Exception $e) {
                $controller->bind("errors", [['code' => $e->getCode(), 'text' => $e->getMessage()]]);
            }
            if (isset($result)) $controller->bind("result", $result);

            $response = new \App\Model\Response($controller->getVars());

            if (LOG_REQUESTS) {
                error_log("Request IP: '{$_SERVER['REMOTE_ADDR']}'".PHP_EOL.
                    "Request params: '".json_encode($request)."'".PHP_EOL);
            }
        } catch (\Exception $e) {
            $controller = new \App\Controller\DefaultController();
            $controller->_prepare();
            call_user_func([$controller, "Error"], $e);

            $response = new \App\Model\Response($controller->getVars());
            $response->add("errors", $e->getMessage());
            error_log("ERROR:".PHP_EOL.
                "Request IP: '{$_SERVER['REMOTE_ADDR']}'".PHP_EOL.
                "Request params: '".json_encode($request)."'".PHP_EOL.
                "Exception: '".$e->getMessage()."'");
        }

        try {
            $trash = ob_get_contents();
            ob_end_clean();
            ob_start();
//            if (FLAG_DEBUG && !empty($trash)) $response->add("ob", $trash);
            $response->Output($request->format);
            if (FLAG_DEBUG) echo $trash;
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
        ob_end_flush();
    }

    private function _parseParams(Request $request) {
        /**
         * @var \App\Model\Data\MethodData
         */
        $methodData = new \App\Model\Data\MethodData($request->controller);
        $params = [];

        if (!isset($methodData[$request->method])) {
            try {
                if (method_exists("\\App\\Controller\\{$request->controller}Controller", $request->method)) {
                    // no json description for called method, but method exists
                    foreach ($request->extras as $val) $params[] = $val;
                    foreach ($request->params as $val) $params[] = $val;
                } else {
                    throw new \Exception();
                }
            } catch (\Exception $e) {
                throw new \Exception("Method {$request->controller}/{$request->method} is not supported", \App\Model\Errors::ERR_INVALID_METHOD);
            }
        } else {
            // checking request extras
            $paramIndex = 0;
            foreach ($methodData[$request->method] as $paramName => $paramData) {
                if (!isset($request->params[$paramName]) && isset($request->extras[$paramIndex])) $request->params[$paramName] = $request->extras[$paramIndex];
                $paramIndex++;
            }

            // Check parameters
            if (!empty($methodData[$request->method])) {
                foreach ($methodData[$request->method] as $paramName => $paramData) {
                    if ($paramData['required'] && !isset($paramData['default']) && !isset($request->params[$paramName])) {
                        throw new \Exception("Parameter {$paramName} is required", \App\Model\Errors::ERR_REQUIRED_FIELD);
                    }

                    if (isset($request->params[$paramName])) {
                        $val = $request->params[$paramName];
                        // if parameter was provided, check for type
                        switch ($paramData['type']) {
                            case "number":
                                if (!is_numeric($val)) {
                                    throw new \Exception("Parameter {$paramName} must be numeric", \App\Model\Errors::ERR_BAD_ARG_FORMAT);
                                }
                                break;
                            case "datetime":
                                if (!strtotime($val)) {
                                    throw new \Exception("Parameter {$paramName} must be valid date", \App\Model\Errors::ERR_BAD_ARG_FORMAT);
                                }
                                break;
                        }
                        $params[$paramName] = $val;
                    } else {
                        $params[$paramName] = isset($paramData['default']) ? $paramData['default'] : null;
                    }

                }

            }
        }

        return $params;
    }


}