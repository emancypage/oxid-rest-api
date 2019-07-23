<?php

namespace App\Http\Controllers;

use App\Helpers\FilterHelper;
use App\Models\Order;
use Illuminate\Support\Facades\Input;

class OrderController extends Controller
{

    /**
     *
     * @OA\Get(
     *      path="/rest/v1/order",
     *      description="Get all order",
     *      operationId="getAll",
     *      security={
     *          { "apiToken": {"t6PEqwkBpbdsf93osDSF913Bmcsd78pYWLtEgvs"} }
     *      },
     *      tags={"Order"},
     *      @OA\Response(response="200", description="An example resource"),
     *      @OA\Response(response="404", description="No articles found")
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAllOrder()
    {
        try {
            $limit = (Input::get('limit') ? Input::get('limit') : '100');
            $page = (Input::get('page') ? Input::get('page') : '1');
            $skip = ($page > 1 ? ($page - 1) * $limit : 0);
            $order_by = (Input::get('order_by') ? Input::get('order_by') : 'oxordernr');
            $order = (Input::get('order') ? Input::get('order') : 'asc');

            if (!empty($filters = FilterHelper::prepareFilters())) {
                if (($order = Order::where(array_values($filters))->orderBy($order_by, $order)->skip($skip)->take($limit)->get()) && count($order)) {
                    return response()->json($order);
                }
            }

            if ($order = Order::orderBy($order_by, $order)->skip($skip)->take($limit)->get()) {
                return response()->json($order);
            }

            return response('No articles found', 404);
        } catch (\Exception $error) {
            return response($error->getMessage(), $error->getCode() ?: 404);
        }
    }

    /**
     *
     * @OA\Get(
     *      path="/rest/v1/order/{id}",
     *      description="Get only one order",
     *      operationId="getOne",
     *      security={
     *          { "apiToken": {"t6PEqwkBpbdsf93osDSF913Bmcsd78pYWLtEgvs"} }
     *      },
     *      tags={"Order"},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="String ID of the Order to get",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(response="200", description=""),
     *      @OA\Response(response="404", description="Order with id not found"),
     *
     * )
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showOneOrder($id) {
        try {
            $order = Order::findOrFail($id);
            return response()->json($order);
        } catch (\Exception $error) {
            return response($error->getMessage(), $error->getCode() ?: 404);
        }
    }
}