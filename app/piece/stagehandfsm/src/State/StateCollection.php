<?php
/*
 * Copyright (c) 2015 KUBO Atsuhiro <kubo@iteman.jp>,
 * All rights reserved.
 *
 * This file is part of Stagehand_FSM.
 *
 * This program and the accompanying materials are made available under
 * the terms of the BSD 2-Clause License which accompanies this
 * distribution, and is available at http://opensource.org/licenses/BSD-2-Clause
 */

namespace App\piece\stagehandfsm\src\State;

use App\phpmentors\domainkata\src\Entity\EntityCollectionInterface;
use App\phpmentors\domainkata\src\Entity\EntityInterface;

/**
 * @since Class available since Release 2.2.0
 */
class StateCollection implements EntityCollectionInterface
{
    /**
     * @var array
     */
    private $states;

    /**
     * @param array $states
     */
    public function __construct(array $states = array())
    {
        $this->states = $states;
    }

    /**
     * {@inheritdoc}
     */
    public function add(EntityInterface $entity)
    {
        assert($entity instanceof StateInterface);

        /* @var $entity StateInterface */
        $this->states[$entity->getStateId()] = $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        if (array_key_exists($key, $this->states)) {
            return $this->states[$key];
        } else {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function remove(EntityInterface $entity)
    {
        assert($entity instanceof StateInterface);

        /* @var $entity StateInterface */
        if (array_key_exists($entity->getStateId(), $this->states)) {
        }
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->states);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->states);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return $this->states;
    }
}
