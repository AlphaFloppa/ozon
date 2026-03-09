<?php

namespace App\Common\Infrastructure;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class EnvProvider
{
    /**
     * @param ParameterBagInterface $params
     */
    public function __construct(
        private readonly ParameterBagInterface $params
    )
    {
        }

    /**
     * @return array ассоциативный массив с данными
     */
    public function getDBParams(): array {
        return $this->params->get('databaseSettings');
    }
}