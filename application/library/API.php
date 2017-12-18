<?php

/**
 * Created by PhpStorm.
 * User: EtimEsuOyoIta
 * Date: 12/14/17
 * Time: 2:30 AM
 */
abstract class API
{
    protected $uri;
    protected $httpMethod;
    protected $data = [];
    protected $dataFormat;
    protected $qualifiers = [];
    protected $factors = [];

    protected $result;
    protected $message;
    protected $errors = [];
    protected $redirect;

    const HTTPMethods = ['GET', 'POST', 'PUT', 'DELETE', 'HEAD', 'TRACE', 'OPTIONS'];
    const DataFormats = ['json', 'xml', 'html'];

    function __construct($uri = null) {
        $this->presetQualifiers();
        if (isset($uri)) $this->setUri($uri);
        $factors = $this->parseURI($uri);
        if (is_array($factors)) $this->setFactors($factors);
    }

    private function preloadAPI(API $api) {
        // reload from this object

        $api->setHttpMethod($this->getHttpMethod());
        $api->setData($this->getData());
        $api->setDataFormat($this->getDataFormat());
        $api->setFactors($this->getFactors());
    }

    private function loadResponse($api) {
        // reload into this object
        $this->setResult($api->getResult());
        $this->setMessage($api->getMessage());
        $this->setRedirect($api->getRedirect());
        $this->setErrors($api->getErrors());
    }

    /**
     * @param $httpMethod
     * @param $data
     * @param string $dataFormat
     * @param null $qualifiers
     */
    public function processRequest($httpMethod, $data, $dataFormat = 'json', $qualifiers = null) {
        if (in_array($httpMethod, API::HTTPMethods)) {
            $this->setHttpMethod($httpMethod);
            if (in_array($dataFormat, API::DataFormats)) $this->setDataFormat($dataFormat);
            if (is_array($qualifiers)) $this->setQualifiers($qualifiers);
            $this->setData($data);

            // set the play by the composition of factors:

            // resourceApi : data API
            // resourceApi/identifier : resource from a data API
            // resourceApi/identifier/action : identifier-specific action on a data API
            // resourceApi/action : action on a data API
            // nonResourceApi/action : action on a non-data API
            // action : possible alias

            // does it have an alias first?
            $numFactors = count($this->factors);

            if ($numFactors == 1) {
                if (array_key_exists('action', $this->factors)) {
                    // find the alias
                    $trueURI = RouteAlias::findTrueURI($this->uri);

                    // reset the API
                    $newAPI = clone $this;
                    $newAPI->setUri($trueURI);

                    $this->preloadAPI($newAPI);

                    // process that request
                    $newAPI->processRequest($httpMethod, $data, $dataFormat, $qualifiers);

                    // load the response
                    $this->loadResponse($newAPI);
                }
            } elseif ($numFactors == 2) {
                // possibles:
                // resourceApi/identifier : resource from a data API
                // resourceApi/action : action on a data API
                // nonResourceApi/action : action on a non-data API

                /**
                 * checks:
                 * does API class exist?
                 * does specified action or identifier exist?
                 * distinguish between identifiers and actions: IDs are always numeric
                 */

                // first, check if this is a data API
                $api = $this->factors['api'];

                if (!class_exists($api)) $this->setErrors('This resource does not exist.');

                $apiInstance = new $api($this->uri);
                $this->preloadAPI($apiInstance);

                if (array_key_exists("action", $this->factors)) {
                    $action = $this->factors['action'];
                    if (!method_exists($apiInstance, $action)) $this->setErrors('This resource does not have this action.');

                    if (empty($this->getErrors())) {
                        $apiInstance->{$action}();
                    }
                } elseif (array_key_exists("identifier", $this->factors)) {
                    $identifier = intval($this->factors['identifier']);
                    if ($identifier < 1) $this->setErrors('ID invalid.');
                    $table = substr($api, 0, -3);
                    if (!DataSource::exists($table, $identifier)) $this->setErrors('Resource does not exist.');
                    if (!$this->hasErrors()) {
                        $apiInstance->single($identifier);
                    }
                }

                // load the response
                $this->loadResponse($apiInstance);

            } elseif ($numFactors == 3) {
                $api = $this->factors['api'];
                $identifier = $this->factors['identifier'];
                $action = $this->factors['action'];

                if (!class_exists($api)) $this->setErrors('This resource does not exist.');

                $apiInstance = new $api($this->uri);
                $this->preloadAPI($apiInstance);

                // resourceApi/identifier/action : identifier-specific action on a data API
                $apiInstance->{$action}($identifier);

                // load the response
                $this->loadResponse($apiInstance);

            /*} else { // where numFactors >= 4 :
                $api = $this->factors['api'];

                if (!class_exists($api)) $this->setErrors('This resource does not exist.');

                $apiInstance = new $api($this->uri);
                $this->preloadAPI($apiInstance);

                // load the response
                $this->loadResponse($apiInstance);*/
            }
        }
    }

    /**
     * Parses a URI to identify relevant factors of the request, or returns false.
     * @param $uri the endpoint URI
     * @return array|bool
     */
    private function parseURI($uri) {
        if (strlen($uri) > 0) {
            $factors = [];
            
            $parts = explode(DIRECTORY_SEPARATOR, $uri, 3);
            $nParts = count($parts);

            $identifier = null;
            $action = null;
            $subResource = null;

            $part1 = $parts[0];
            if (DataSource::hasTable($part1)) {
                $factors['api'] = ucfirst($part1) . 'API';
            } else {
                $factors['action'] = $part1;
            }

            if ($nParts == 1) {
                if (DataSource::hasTable($parts[0])) {
                    $factors['action'] = "index";
                }
            }

            if ($nParts >= 2) {
                $part2 = intval($parts[1]);
                if ($part2 > 0) {
                    $factors['identifier'] = $part2;
                } else {
                    $factors['action'] = $parts[1];
                }
                if ($nParts >= 3) {
                    if (array_key_exists('identifier', $factors)) {
                        $part3 = $parts[2];
                        if (DataSource::hasTable($part3)) {
                            $factors['subresource'] = $part3;
                        } else {
                            $factors['action'] = $part3;
                        }
                    }
                }
            }

            return $factors;
        }

        return false;
    }

    /**
     * Sets up a default set of qualifiers.
     */
    private function presetQualifiers() {
        $this->setQualifiers([
            'recordsPerPage' => 10,
            'currentPage' => 1
        ]);
        return $this;
    }

    // jsonToXML and xmlToJSON functions used courtesy of Maurits van der Schee.
    // Copyright: Maurits van der Schee <maurits@vdschee.nl>
    // Description: Convert from JSON to XML and back.
    // License: MIT

    private function jsonToXML($json) {
        $a = json_decode($json);
        $d = new DOMDocument();
        $c = $d->createElement("root");
        $d->appendChild($c);
        $t = function($v) {
            $type = gettype($v);
            switch($type) {
                case 'integer': return 'number';
                case 'double':  return 'number';
                default: return strtolower($type);
            }
        };
        $f = function($f,$c,$a,$s=false) use ($t,$d) {
            $c->setAttribute('type', $t($a));
            if ($t($a) != 'array' && $t($a) != 'object') {
                if ($t($a) == 'boolean') {
                    $c->appendChild($d->createTextNode($a?'true':'false'));
                } else {
                    $c->appendChild($d->createTextNode($a));
                }
            } else {
                foreach($a as $k=>$v) {
                    if ($k == '__type' && $t($a) == 'object') {
                        $c->setAttribute('__type', $v);
                    } else {
                        if ($t($v) == 'object') {
                            $ch = $c->appendChild($d->createElementNS(null, $s ? 'item' : $k));
                            $f($f, $ch, $v);
                        } else if ($t($v) == 'array') {
                            $ch = $c->appendChild($d->createElementNS(null, $s ? 'item' : $k));
                            $f($f, $ch, $v, true);
                        } else {
                            $va = $d->createElementNS(null, $s ? 'item' : $k);
                            if ($t($v) == 'boolean') {
                                $va->appendChild($d->createTextNode($v?'true':'false'));
                            } else {
                                $va->appendChild($d->createTextNode($v));
                            }
                            $ch = $c->appendChild($va);
                            $ch->setAttribute('type', $t($v));
                        }
                    }
                }
            }
        };
        $f($f,$c,$a,$t($a)=='array');
        return $d->saveXML($d->documentElement);
    }

    private function xmlToJSON($xml) {
        $a = dom_import_simplexml(simplexml_load_string($xml));
        $t = function($v) {
            return $v->getAttribute('type');
        };
        $f = function($f,$a) use ($t) {
            $c = null;
            if ($t($a)=='null') {
                $c = null;
            } else if ($t($a)=='boolean') {
                $b = substr(strtolower($a->textContent),0,1);
                $c = in_array($b,array('1','t'));
            } else if ($t($a)=='number') {
                $c = $a->textContent+0;
            } else if ($t($a)=='string') {
                $c = $a->textContent;
            } else if ($t($a)=='object') {
                $c = array();
                if ($a->getAttribute('__type')) {
                    $c['__type'] = $a->getAttribute('__type');
                }
                for ($i=0;$i<$a->childNodes->length;$i++) {
                    $v = $a->childNodes[$i];
                    $c[$v->nodeName] = $f($f,$v);
                }
                $c = (object)$c;
            } else if ($t($a)=='array') {
                $c = array();
                for ($i=0;$i<$a->childNodes->length;$i++) {
                    $v = $a->childNodes[$i];
                    $c[$i] = $f($f,$v);
                }
            }
            return $c;
        };
        $c = $f($f,$a);
        return json_encode($c,64);//64=JSON_UNESCAPED_SLASHES
    }

    /**
     * @param $a
     * @param $b
     */
    public function addQualifier($a, $b) {
        if (!is_string($a)) {
            $this->setErrors('Qualifier key has to be associative.');
        } else {
            $this->setQualifiers([$a => $b]);
        }
    }

    /**
     * @param array $array
     */
    public function addQualifiersAsArray(array $array) {
        $this->setQualifiers($array);
    }

    /**
     * Get or put out the response to the API request.
     * @param bool $ajax
     * @return null|string
     */
    public function getResponse($ajax = false) {

        $response = [];
        $response['success'] = !$this->hasErrors();
        if ($this->hasErrors()) {
            $response['errors'] = $this->errors;
        } else {
            $response['message'] = $this->message;
            $response['result'] = $this->result;
            $response['redirect'] = $this->redirect;
        }

        $jsonResponse = json_encode($response);
        $finalResponse = null;
        if ($this->dataFormat == 'html') {

        } elseif ($this->dataFormat == 'xml') {
            $finalResponse = $this->jsonToXML($jsonResponse);
        } else {
            $finalResponse = $jsonResponse;
        }

        if ($ajax) {
            $headerFormats = [
                'json' => 'application/json',
                'xml' => 'text/xml',
                'html' => 'text/html'
            ];
            header('Content-type: '. $headerFormats[$this->dataFormat]);
            echo $finalResponse;
        } else {
            return $finalResponse;
        }
    }

    /**
     * @return mixed
     */
    public function getUri() {
        return $this->uri;
    }

    /**
     * @param mixed $uri
     * @return API
     */
    public function setUri($uri) {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataFormat() {
        return $this->dataFormat;
    }

    /**
     * @param $dataFormat
     * @return $this
     */
    public function setDataFormat($dataFormat) {
        if (!in_array($dataFormat, self::DataFormats)) $this->setErrors('Invalid data format');
        if (!$this->hasErrors()) {
            $this->dataFormat = $dataFormat;
            return $this;
        }
    }

    /**
     * @return array
     */
    public function getQualifiers() {
        return $this->qualifiers;
    }

    /**
     * @param array $qualifiers
     * @return API
     */
    public function setQualifiers(array $qualifiers) {
        $this->qualifiers = array_merge($this->qualifiers, $qualifiers);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResult() {
        return $this->result;
    }

    /**
     * @param mixed $result
     * @return API
     */
    public function setResult($result) {
        $this->result = $result;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * @param mixed $message
     * @return API
     */
    public function setMessage($message) {
        $this->message = $message;
        return $this;
    }

    /**
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * @param $errors
     * @return $this
     */
    public function setErrors($errors)
    {
        if (is_array($errors)) {
            $this->errors = array_merge($this->errors, $errors);
        } else {
            $this->errors[] = $errors;
        }
        return $this;
    }

    /**
     * Indicates if the API has errors or not.
     * @return bool
     */
    public function hasErrors() {
        return count($this->errors) > 0 ? true : false;
    }

    /**
     * @return mixed
     */
    public function getRedirect() {
        return $this->redirect;
    }

    /**
     * @param mixed $redirect
     * @return API
     */
    public function setRedirect($redirect) {
        $this->redirect = $redirect;
        return $this;
    }

    /**
     * @return array
     */
    public function getFactors() {
        return $this->factors;
    }

    /**
     * @param array $factors
     * @return API
     */
    public function setFactors($factors) {
        $this->factors = $factors;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHttpMethod() {
        return $this->httpMethod;
    }

    /**
     * @param mixed $httpMethod
     * @return API
     */
    public function setHttpMethod($httpMethod) {
        $this->httpMethod = $httpMethod;
        return $this;
    }

    /**
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param array $data
     * @return API
     */
    public function setData($data) {
        $this->data = $data;
        return $this;
    }

}
