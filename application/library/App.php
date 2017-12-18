<?php

/**
 * Created by PhpStorm.
 * User: EtimEsuOyoIta
 * Date: 12/4/17
 * Time: 11:06 AM
 */
abstract class App
{
    private $api;
    private $errors = [];
    private $title = APP_NAME;
    private $controller;
    private $user;

    public function __construct($url = APP_URL) {
    }

    public static function begin($url = null) {
        // initialise the data source
        DataSource::init();

        // resolve the URL (GET route)
        // assemble controllers and views
        // load data as required
    }

    public function frontControl($url = null) {

    }

    public function login($url = null) {
        
    }
    
    public function logout() {
        
    }

    /**
     * @return mixed
     */
    public function getApi() {
        return $this->api;
    }

    /**
     * @param mixed $api
     * @return App
     */
    public function setApi($api) {
        $this->api = $api;
        return $this;
    }

    /**
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * @param array $errors
     * @return App
     */
    public function setErrors($errors) {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $title
     * @return App
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getController() {
        return $this->controller;
    }

    /**
     * @param mixed $controller
     * @return App
     */
    public function setController($controller) {
        $this->controller = $controller;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return App
     */
    public function setUser($user) {
        $this->user = $user;
        return $this;
    }

}
