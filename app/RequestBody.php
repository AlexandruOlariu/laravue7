<?php

/**
 * @license Apache 2.0
 */

/**
 *
 * @OA\RequestBody(
 *     request="Flower",
 *     description="Flower object that needs to be added to the db",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/Flower"),
 *     @OA\MediaType(
 *         mediaType="application/xml",
 *         @OA\Schema(ref="#/components/schemas/Flower")
 *     )
 * )
 */


