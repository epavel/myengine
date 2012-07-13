<?php

namespace MyEngine\View;

use MyEngine\Event\Event;
use MyEngine\Mvc\Request;
use MyEngine\Mvc\Response;

class ViewEvent extends Event
{
    const EVENT_RENDERER = 'renderer';
    const EVENT_RESPONSE = 'response';

    /**
     * @var null|Model
     */
    protected $model;

    /**
     * @var Renderer
     */
    protected $renderer;

    /**
     * @var null|Request
     */
    protected $request;

    /**
     * @var null|Response
     */
    protected $response;

    /**
     * @var mixed
     */
    protected $result;

    /**
     * Set the view model
     *
     * @param  Model $model
     * @return ViewEvent
     */
    public function setModel(ModelInterface $model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Set the MVC request object
     *
     * @param  Request $request
     * @return ViewEvent
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Set the MVC response object
     *
     * @param  Response $response
     * @return ViewEvent
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * Set result of rendering
     *
     * @param  mixed $result
     * @return ViewEvent
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * Retrieve the view model
     *
     * @return null|Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set value for renderer
     *
     * @param  Renderer $renderer
     * @return ViewEvent
     */
    public function setRenderer(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     * Get value for renderer
     *
     * @return null|Renderer
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * Retrieve the MVC request object
     *
     * @return null|Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Retrieve the MVC response object
     *
     * @return null|Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Retrieve the result of rendering
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }
}
