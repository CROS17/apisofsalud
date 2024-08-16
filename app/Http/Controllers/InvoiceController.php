<?php

namespace App\Http\Controllers;

use Exception;
use App\DTO\InvoiceDTO;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\InvoiceService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\InvoiceRequest;
use App\Http\Resources\InvoiceResource;

class InvoiceController extends Controller
{
    private InvoiceService $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try{
            $itemsPerPage = request('per_page', env('ITEMS_PER_PAGE'));
            $items = Invoice::with('items')->paginate($itemsPerPage);

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
            $item = Invoice::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' =>  new InvoiceResource($item)],
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
    public function store(InvoiceRequest $request): JsonResponse
    {
        try{
            $invoiceDTO = new InvoiceDTO($request->all());
            $invoice = $this->invoiceService->createInvoice($invoiceDTO);

            return response()->json([
                'success' => true,
                'message' => 'Registro Correcto',
                'data' => $invoice->id],
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
    public function update(InvoiceRequest $request, string $id): JsonResponse
    {
        try{

            $invoice  = Invoice::query()->findOrFail($id);
            $invoiceDTO = new InvoiceDTO($request->all());
            $updatedInvoice = $this->invoiceService->updateInvoice($invoice, $invoiceDTO);

            return response()->json([
                'success' => true,
                'message' => 'Actualizar Correcto',
                'data' => $updatedInvoice->id],
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
            $invoice = Invoice::findOrFail($id);
            $invoice->items()->delete();
            $invoice->delete();

            return response()->json([
                'success' => true,
                'message' => 'Eliminado con éxito'],
                Response::HTTP_CREATED
            );

        }catch (Exception $e){
            return response()->json(
                ['error' => $e->getMessage()],
                Response::HTTP_NOT_FOUND);
        }
    }


    public function generatepdf($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);

            $pdf = Pdf::loadView('invoice_a4', ['data' => $invoice])
                    ->setPaper('a4', 'portrait');

            $filename = "INVOICE-PERU.pdf";

            return $pdf->stream($filename);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

}
