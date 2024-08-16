<?php

namespace App\Http\Controllers;

use Exception;
use App\DTO\ItemDTO;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Services\ItemService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ItemRequest;
use App\Http\Resources\ItemResource;
use Symfony\Component\HttpFoundation\Response;

class ItemController extends Controller
{
    private ItemService $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try{
            $itemsPerPage = request('per_page', env('ITEMS_PER_PAGE'));
            $items = Item::paginate($itemsPerPage);

            return response()->json([
                'success' => true,
                'data' => $items->items(),
                'pagination' => [
                    'total' => $items->total(),
                    'per_page' => $items->perPage(),
                    'current_page' => $items->currentPage(),
                    'last_page' => $items->lastPage(),
                    'from' => $items->firstItem(),
                    'to' => $items->lastItem(),
                ]],
                Response::HTTP_OK
            );
        }catch (Exception $e){
            return response()->json(
                ['error' => $e->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try{
            $item = Item::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' =>  new ItemResource($item)],
                Response::HTTP_OK
            );
        }catch (Exception $e){
            return response()->json(
                ['error' => $e->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemRequest $request): JsonResponse
    {
        try{

            $itemDTO = new ItemDTO($request->validated());
            $item = $this->itemService->createItem($itemDTO);

            return response()->json([
                'success' => true,
                'message' => "Registro Exitoso",
                'data' => $item->id],
                Response::HTTP_CREATED
            );
        }catch (Exception $e){
            return response()->json(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ItemRequest $request, string $id): JsonResponse
    {
        try{

            $item  = Item::query()->findOrFail($id);
            $itemDTO = new ItemDTO($request->validated());
            $updatedItem = $this->itemService->updateItem($item, $itemDTO);

            return response()->json([
                'success' => true,
                'message' => "Registro Actualizado",
                'data' => $updatedItem->id],
                Response::HTTP_OK
            );
        }catch (Exception $e){
            return response()->json(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try{
            $client = Item::findOrFail($id);
            $client->delete();

            return response()->json([
                'success' => true,
                'message' => 'Eliminado con éxito'],
                Response::HTTP_NO_CONTENT
            );

        }catch (Exception $e){
            return response()->json(
                ['error' => $e->getMessage()],
                Response::HTTP_NOT_FOUND);
        }
    }

}
