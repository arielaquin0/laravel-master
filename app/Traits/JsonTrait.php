<?php

namespace App\Traits;

//use App\Helpers\AppHelper;

trait JsonTrait
{
    public function json($code = 0, $message = "ok", $data = array(), $other = array())
    {
        header('Content-type: text/json');

        if(empty($data))
        {
//            if(AppHelper::getAppName()==AppHelper::Api)
//            {
//                $data = (object)[];
//            }
//            else
//            {
//                $data = [];
//            }

            $data = (object)[];
        }
        $re = array(
            "code" => $code,
            "msg" => $message,
            "data" => $data,
        );
        if($other)
        {
            foreach ($other as $k => $v)
            {
                $re[$k] = $v;
            }
        }
        echo json_encode($re);
        exit();
        return true;
    }

}
