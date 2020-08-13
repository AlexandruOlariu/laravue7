<?php

namespace App\Participants;

use App\phpmentors\workflower\src\Workflow\Participant\ParticipantInterface;
use App\phpmentors\workflower\src\Workflow\Resource\ResourceInterface;

class Dev implements ParticipantInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     */
    public function __construct()
    {
        $this->id = 1;
        $this->name = 'dummy-dev';
    }

    public function getId()
    {
        return $this->id;
    }

    public function hasRole($role)
    {
        return 1;//$role === 'Lane_dev';
    }

    public function setResource(ResourceInterface $resource)
    {
        $this->resource = $resource;
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function getName()
    {
        return $this->name;
    }
}
