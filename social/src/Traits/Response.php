<?php
namespace Core\Social\Traits;
use \Illuminate\Http\Response as Code;
trait Response
{
    public function Response(array $data = [], string $message = '' , int $code = Code::HTTP_OK){
        return response()->json([
            'status'=>true,
            'message'=>$message,
            'data'=>$data,
        ],$code);
    }

    public function ResponseError(string $e, string $message = 'Something went wrong ,Please contact us for assistance', int $code = Code::HTTP_BAD_REQUEST){
        return response()->json([
            'status'=>false,
            'message'=>$message,
            'error'=>$e,
        ],$code);
    }

    public function ResponseException(string $e, int $code = Code::HTTP_BAD_REQUEST){
        return response()->json([
            'status'=>false,
            'message'=>'Something went wrong ,Please contact us for assistance',
            'error'=>$e,
        ],$code);
    }

    public function ResponseRequest(array $e, string $message = 'Something went wrong ,Please contact us for assistance', int $code = Code::HTTP_UNPROCESSABLE_ENTITY){
        return response()->json([
            'status'=>false,
            'message'=>$message,
            'error'=>$e,
        ],$code);
    }
}
