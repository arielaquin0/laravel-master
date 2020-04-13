<?php
// *************************** DISCLAIMER ***************************
// Adding auto-incrementing id to Mongo using _id. is not good idea
// considering race condition it can cause duplicate key error.
// So, DONT'T USE IT IN _id!!! Instead, create another attribute
// to use as auto-increment ID.
namespace App\Traits;

use DB;
use MongoDB\Operation\FindOneAndUpdate;

trait UseAutoIncrementID
{
    /**
     * Increment the counter and get the next sequence
     *
     * @param $collection
     * @return mixed
     */
//    protected static function getID($collection)
//    {
//        $seq = DB::getCollection('_data_counters')->findOneAndUpdate(
//            ['model' => $collection],
//            ['$inc' => ['seq' => 1]],
//            ['new' => true, 'upsert' => true, 'returnDocument' => FindOneAndUpdate::RETURN_DOCUMENT_AFTER]
//        );
//        return $seq->seq;
//    }

    /**
     * Get the casts array.
     *
     * @return array
     */
    public function getCasts()
    {
        return $this->casts;
    }

}
