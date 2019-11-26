<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->ajax()) {
            // 判断响应数据json
            if ($exception instanceof ValidationException) {
                // 是否是表单验证的异常
                // 返回响应数据json
                return response()->json(['status' => 1, 'msg' => '验证失败', 'data' => $exception->validator->messages()],422);
            }
            // 如果不是表单验证错误，是什么错误就返回什么错误
            return parent::render($request,$exception);
        }

        // 接口判断
        if ($exception instanceof LoginException){
            // 1账号有误
            $data = ['status' => $exception->getCode(),'msg' => $exception->getMessage()];
            return response()->json($data,401);
        }elseif ($exception instanceof MyValidateException){
            $data = ['status' => $exception->getCode(),'msg' => $exception->getMessage()];
            return response()->json($data,401);
        }


        return parent::render($request, $exception);
    }
}
