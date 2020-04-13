<?php

namespace App\Helpers;

class RunEnvHelper
{
    const __RUN_DEV__ = "dev";
    const __RUN_PROD__ = "prod";
    const __RUN_TEST__ = "test";
    const __RUN_PP_TEST__ = "pptest";
    const __RUN_NODE2_PROD__ = "node2prod";
    const __RUN_NODE3_PROD__ = "node3prod";

    public static function getRunEnv()
    {
        $runEnv = get_cfg_var("run_dev");

        if(empty($runEnv))
        {
            $runEnv = self::__RUN_DEV__;
        }

        if($runEnv== self::__RUN_PROD__ || $runEnv== self::__RUN_TEST__)
        {
            return $runEnv;
        }

        if($runEnv==self::__RUN_PP_TEST__)
        {
            return $runEnv;
        }

        if($runEnv == self::__RUN_NODE2_PROD__)
        {
            return self::__RUN_NODE2_PROD__;
        }

        if($runEnv == self::__RUN_NODE3_PROD__)
        {
           return self::__RUN_NODE3_PROD__;
        }

        $runEnv =self::__RUN_DEV__;
        return $runEnv;
    }

}
