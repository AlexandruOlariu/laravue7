<?php
/*
 * Copyright (c) KUBO Atsuhiro <kubo@iteman.jp> and contributors,
 * All rights reserved.
 *
 * This file is part of Workflower.
 *
 * This program and the accompanying materials are made available under
 * the terms of the BSD 2-Clause License which accompanies this
 * distribution, and is available at http://opensource.org/licenses/BSD-2-Clause
 */

namespace App\phpmentors\workflower\src\Definition;

use App\phpmentors\domainkata\src\Entity\EntityInterface;
use App\phpmentors\domainkata\src\Entity\Operation\IdentifiableInterface;

class Bpmn2File implements EntityInterface, IdentifiableInterface
{
    /**
     * @var string
     */
    private $file;

    /**
     * @param string $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return static::getWorkflowId($this->file);
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file
     *
     * @return string
     */
    public static function getWorkflowId($file)
    {
        return pathinfo($file, PATHINFO_FILENAME);
    }
}
