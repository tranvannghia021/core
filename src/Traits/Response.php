<?php

namespace Devtvn\Social\Traits;

use \Illuminate\Http\Response as Code;

trait Response
{
    /**
     * response
     * @param array $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function Response(array $data = [], string $message = '', int $code = Code::HTTP_OK)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * @param string $e
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function ResponseError(
        string $e,
        string $message = 'Something went wrong ,Please contact us for assistance',
        int $code = Code::HTTP_BAD_REQUEST
    ) {
        return response()->json([
            'status' => false,
            'message' => $message,
            'error' => $e,
        ], $code);
    }

    /**
     * @param string $e
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function ResponseException(string $e, int $code = Code::HTTP_BAD_REQUEST)
    {
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong ,Please contact us for assistance',
            'error' => $e,
        ], $code);
    }

    /**
     * @param array $e
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function ResponseRequest(
        array $e,
        string $message = 'Something went wrong ,Please contact us for assistance',
        int $code = Code::HTTP_UNPROCESSABLE_ENTITY
    ) {
        return response()->json([
            'status' => false,
            'message' => $message,
            'error' => $e,
        ], $code);
    }
}
