<?php

namespace MyEngine\Module;
	
interface ConfigListenerInterface
{
    /**
     * getConfig
     *
     * @return array
     */
    public function getConfig();

    /**
     * setConfig
     *
     * @param  array $config
     * @return ConfigListenerInterface
     */
    public function setConfig(array $config);
}
