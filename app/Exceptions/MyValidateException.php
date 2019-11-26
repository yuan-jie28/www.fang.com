<?php

namespace App\Exceptions;

use Exception;

class MyValidateException extends Exception
{
    private $status = [
      3 => '验证不通过'
    ];
}
