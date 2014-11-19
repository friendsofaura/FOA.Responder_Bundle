<?php

namespace FOA\Responder_Bundle\Renderer;

use Smarty as SmartyEngine;

/**
 *  A Renderer using the Smarty Templating Engine
 */
class Smarty implements RendererInterface
{
    /**
     * Smarty Engine Object
     * @var SmartyEngine
     */
    protected $engine;

    /**
     * Template Extension file
     * @var string
     */
    protected $extension = '.tpl';

    /**
     * Layout main variable name
     * @var string
     */
    protected $layoutVariableName = 'content';

    /**
     * Create a new instance of the Renderer
     *
     * @param SmartyEngine $engine
     */
    public function __construct(SmartyEngine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Set Template Extension file
     *
     * @param string $extension
     */
    public function setTemplateExtension($extension)
    {
        $extension = (string) $extension;
        $extension = trim($extension);
        if ('.' != $extension[0]) {
            $extension = '.'.$extension;
        }

        $this->extension = $extension;
    }

    /**
     * Get Template Extension file
     *
     * @return string
     */
    public function getTemplateExtension()
    {
        return $this->extension;
    }

    /**
     * Set Layout main variable name
     *
     * @param string $name
     */
    public function setLayoutVariableName($name)
    {
        $name = (string) $name;
        $name = trim($name);
        if (! preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/', $name)) {
            throw new InvalidArgumentException('Invalid variable name');
        }
        $this->layoutVariableName = $name;
    }

    /**
     * Get Layout main variable name
     *
     * @return string
     */
    public function getLayoutVariableName()
    {
        return $this->layoutVariableName;
    }

    /**
     * {@inheritdoc}
     */
    public function render($data, $view, $layout = null)
    {
        $this->engine->assign((array) $data);
        $content = $this->engine->fetch($view.$this->extension);
        if (is_null($layout)) {
            return $content;
        }
        $this->engine->assign($this->layoutVariableName, $content);

        return $this->engine->fetch($layout.$this->extension);
    }

    /**
     * {@inheritdoc}
     */
    public function getEngine()
    {
        return $this->engine;
    }
}
