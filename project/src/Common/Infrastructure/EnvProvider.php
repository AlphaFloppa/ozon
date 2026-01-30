<?php

namespace App\Common\Infrastructure;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class EnvProvider
{
    public function __construct(
        private readonly ParameterBagInterface $params
    ){}

    public function getDBParams(): array
    {
        return $this->params->get('databaseSettings');
    }

}