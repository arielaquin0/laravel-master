<?php

namespace App\Repositories;

use App\Models\BaseModel;

class BaseRepository
{
    protected $cModel;

    public function create($data)
    {
        return $this->cModel->create($data);
    }

    public function updateById($id,$upData)
    {
        return $this->cModel->updateById($id,$upData);
    }

    public function count($where,$infield='',$inArray=[])
    {
        return $this->cModel->count($where,$infield,$inArray);
    }

    public function fetchAll($where=array(),$group='',$with=array(),$field =['*'])
    {
        if(empty($group))
        {
            return $this->cModel->where($where)
                ->with($with)
                ->get($field)->toArray();
        }
        elseif (empty($with))
        {
            return $this->cModel->where($where)
                ->groupBy($group)
                ->get($field)->toArray();
        }
        else
        {
            return $this->cModel->where($where)
                ->with($with)
                ->groupBy($group)
                ->get($field)->toArray();
        }
    }
}
