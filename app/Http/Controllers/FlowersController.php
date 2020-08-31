<?php

namespace App\Http\Controllers;

use App\Flower;
use App\Listeners\DeleteAnEntryEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use mysql_xdevapi\Exception;

class FlowersController extends Controller implements ShouldQueue
{
    public function __construct()
    {
      //  $this->middleware('flowertask', ['only' => ['store','update','destroy']]);
    }



        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     *
     * @OA\Get(
     *     path="/flowers/",
     *     tags={"flower"},
     *     summary="Get all existing flowers",

     *     description="Home page with flowers",
     *     @OA\Response(response="200", description="Succsess on loading flowers"),
     *
     *     @OA\Response(response="500", description="Server error")
     * )
     */
    public function index()
    {

        $utilizatoriInreg=json_encode(request()->user()::all());
        $currUtil=request()->user()->id;
        $flowers=Flower::all();
        return view('pag1',compact('flowers','utilizatoriInreg','currUtil'));
    }


    public function create()
    {

        $flowers=Flower::all();
        return back(compact('flowers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     *
     * @OA\Post(
     *     path="/flowers",
     *     tags={"flower"},
     *     operationId="store",
     *     summary="Add a new flower to the store",
     *     description="",
     *     @OA\RequestBody(
     *         description="Flower object that needs to be added to the store",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Flower"),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/Flower")
     *         ),
     *     ),
     *     @OA\RequestBody(
     *         description="Flower object that needs to be added to the store",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/xml",
     *             @OA\Schema(ref="#/components/schemas/Flower")
     *         )
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Invalid input",
     *     ),@OA\Response(
     *         response=200,
     *         description="Success on insert",
     *     ),
     *     security={{"flowerstore_auth":{"write:flowers", "read:flowers"}}}
     * )
     */
    public function store(Request $request)
    {

        $this->authorize('create', Flower::class);

        $flower=Flower::create($this->validateRequest1($request));
        $this->storeImage($request,$flower);
        $adresa=$flower->url.'';


        return response()->json([$adresa,$this->validateRequest()], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Flower  $flower
     * @return \Illuminate\Http\Response
     */
    public function show(Flower $flower)
    {
        return back(compact('flower'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Flower  $flower
     * @return \Illuminate\Http\Response
     */
    public function edit(Flower $flower)
    {
        return back(compact('flower'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Flower  $flower
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\POST(
     *   path="/flowers/{flower}",
     *   tags={"flower"},
     *   summary="Updates a flower in the store with form data",
     *   description="",
     *   operationId="update",
     *   @OA\RequestBody(
     *       required=false,
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="name",
     *                   description="Updated name of the flower",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="price",
     *                   description="Updated price of the flower",
     *                   type="string"
     *               ),
     *                  @OA\Property(
     *                   property="url",
     *                   description="Updated url of the flower",
     *                    type="array",
     *                  @OA\Items(
     *                       type="string",
     *                       format="binary",
     *                  ),
     *               ),@OA\Property(
     *                   property="_method",
     *                   description="te rog sa scrii 'PUT' aici",
     *                   type="string",
     *                  default="PUT",
     *               ),
     *           )
     *       )
     *   ),
     *   @OA\Parameter(
     *     name="flower",
     *     in="path",
     *     description="ID of flower that needs to be updated",
     *     required=true,
     *     @OA\Schema(
     *         type="integer",
     *         format="int64"
     *     )
     *   ),
     *
     *   @OA\Response(response="405",description="Invalid input"),
     *     @OA\Response(response="200",description="Success of edit"),
     *   security={{
     *     "flowerstore_auth": {"write:flowers", "read:flowers"}
     *   }}
     * )
     */
    public function update(Request $request, Flower $flower)
    {

        $this->authorize('update', $flower);


        $flower->update($this->validateRequest($request));
        $this->storeImage($request,$flower);
        $adresa=$flower->url.'';
        return response()->json($adresa, 200);
    }


 /**
     * @OA\Delete(
     *     path="/flowers/{flower}",
     *     summary="Deletes a flower",
     *     description="",
     *     operationId="delete",
     *     tags={"flower"},
     *     @OA\Parameter(
     *         description="Flower id to delete",
     *         in="path",
     *         name="flower",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Header(
     *         header="api_key",
     *         description="Api key header",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Flower not found"
     *     ),
     *     security={{"flowerstore_auth":{"write:flowers", "read:flowers"}}}
     * )
     */
    public function destroy(Flower $flower)
    {


        $flower->delete();

        return response()->json(null, 200);
    }
    private function validateRequest1($request)
    {

        return $request->validate([
            'name' => 'required|min:3',
            'price'=>'required',

        ]);

    }
    private function validateRequest()
    {

        return request()->validate([
            'name' => 'required|min:3',
            'price'=>'required',
            'url' => 'sometimes|file|image|max:50000',
        ]);

    }
    public function storeImage($request ,$flower)
    {

        if ($request->has('url')) {

            $flower->update([
                'url' => $request->url->store('uploads', 'public'),
            ]);

            $image = Image::make(public_path('storage/' . $flower->url))->fit(300, 300, null, 'top-left');
            $image->save();

        }
    }


}
