<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\File;


/**
 * Class Flower
 *
 * @OA\Schema(
 *     description="Flower model",
 *     title="Flower model",
 *     required={"name", "price"},
 *
 *     @OA\Xml(
 *         name="Flower"
 *     )
 * )
 */
class Flower extends Model
{
    protected $guarded=[];

    /**
     * @OA\Property(
     *
     *     description="Flower name",
     *     title="Flower name",
     *      example="ghiocel",
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     *
     *     description="Flower price",
     *     title="Flower price",
     * example="10",
     * )
     *
     * @var string
     */
    private $price;

    /**
     * @OA\Property(
     *
     *    description="Flower url",
     *     title="Flower url",
     *     type="array",
     *                  @OA\Items(
     *                       type="string",
     *                       format="binary",
     *                  ),
     * )
     *
     *
     */
    private $url;


}
