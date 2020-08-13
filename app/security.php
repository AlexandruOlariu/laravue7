<?php
/**
 * @OA\SecurityScheme(
 *   securityScheme="api_key",
 *   type="apiKey",
 *   in="header",
 *   name="api_key"
 * )
 */

/**
 * @OA\SecurityScheme(
 *   securityScheme="flowerstore_auth",
 *   type="oauth2",
 *   @OA\Flow(
 *      authorizationUrl="http://flowerstore.swagger.io/oauth/dialog",
 *      flow="implicit",
 *      scopes={
 *         "read:flowers": "read your flowers",
 *         "write:flowers": "modify flowers in your account"
 *      }
 *   )
 * )
 */
