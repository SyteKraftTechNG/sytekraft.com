<?php

/**
 * Created by PhpStorm.
 * User: EtimEsuOyoIta
 * Date: 12/14/17
 * Time: 2:34 AM
 */
abstract class Controller
{
    protected $model;
    protected $view;
    protected $data;
    protected $domain = 'frontend';

    const Domains = ['frontend', 'backend'];

    function __construct($name = 'index') {

    }

    /**
     * @return mixed
     */
    public function getModel() {
        return $this->model;
    }

    /**
     * @param mixed $model
     * @return Controller
     */
    public function setModel($model) {
        $this->model = $model;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getView() {
        return $this->view;
    }

    /**
     * @param mixed $view
     * @return Controller
     */
    public function setView($view) {
        $this->view = $view;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return Controller
     */
    public function setData($data) {
        $this->data = $data;
        return $this;
    }

    /**
     * @return string
     */
    public function getDomain() {
        return $this->domain;
    }

    /**
     * @param string $domain
     * @return Controller
     */
    public function setDomain($domain = null) {
        $this->domain = isset($domain) && in_array($domain, self::Domains) ? $domain : 'frontend';
        return $this;
    }

    /**
     * Default action for any controller.
     */
    public function index() {
        
    }

    public function bySingleId($id) {
    }

    public function bySlug($slug) {
    }

    public function bySearch($params, $pageSize, $page = 1) {
    }

}
