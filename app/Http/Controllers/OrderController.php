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
                if (($order = Order::select()->where(array_values($filters))->orderBy($order_by, $order)->skip($skip)->take($limit)->get()) && count($order)) {
                    return response()->json($order);
                }
            }

            if ($order = Order::select()->orderBy($order_by, $order)->skip($skip)->take($limit)->get()) {
                return response()->json($order);
            }

            return response('No articles found', 404);
        } catch (\Exception $error) {
            return response($error->getMessage(), $error->getCode() ?: 404);
        }
    }
}