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

namespace App\phpmentors\workflower\src\Process;

use App\phpmentors\domainkata\src\Entity\EntityInterface;
use App\phpmentors\workflower\src\Workflow\Participant\ParticipantInterface;

interface WorkItemContextInterface extends EntityInterface
{
    /**
     * @return int|string
     */
    public function getActivityId();

    /**
     * @return ParticipantInterface
     */
    public function getParticipant();

    /**
     * @return ProcessContextInterface
     */
    public function getProcessContext();
}
