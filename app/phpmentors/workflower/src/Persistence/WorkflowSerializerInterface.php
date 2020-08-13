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

namespace App\phpmentors\workflower\src\Persistence;

use App\phpmentors\domainkata\src\Service\ServiceInterface;
use App\phpmentors\workflower\src\Workflow\Workflow;

interface WorkflowSerializerInterface extends ServiceInterface
{
    /**
     * @param Workflow $workflow
     *
     * @return string
     */
    public function serialize(Workflow $workflow);

    /**
     * @param string $workflow
     *
     * @return Workflow
     */
    public function deserialize($workflow);
}
