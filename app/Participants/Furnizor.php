<?php

namespace App\Participants;

use App\phpmentors\workflower\src\Workflow\Participant\ParticipantInterface;
use App\phpmentors\workflower\src\Workflow\Resource\ResourceInterface;

class Furnizor implements ParticipantInterface
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
        $this->name = 'dummy-reviewer';
    }

    public function getId()
    {
        return $this->id;
    }

    public function hasRole($role)
    {
        //return $role === 'Lane_reviewer';
        return 1;
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
