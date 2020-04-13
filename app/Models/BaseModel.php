<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class BaseModel extends Eloquent
{
    public function create($data)
    {
        if(empty($data))
        {
            return false;
        }
        $this->fillable(array_keys($data));
        $this->fill($data);
        if($this->save()==false)
        {
            return false;
        }
        return $this->id;
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['id'] = $this->id;
        unset($array['_id']);
        return $array;
    }

    public function updateById($id,$upData)
    {
        $re=$this->where("id","=",$id)->update($upData);
        if($re===false)
        {
            return false;
        }
        return true;
    }

    public function getRow($where=array(),$field = ['*'])
    {
        if(empty($where))
        {
            $re = $this->query()->get($field)->first();
            if(empty($re))
            {
                return array();
            }
            else
            {
                return $re->toArray();
            }
        }
        else
        {
            $re=$this->where($where)->get($field)->first();
            if(empty($re))
            {
                return array();
            }
            else
            {
                return $re->toArray();
            }
        }
    }

    public function getRowById($id,$field = ['*'])
    {
        if(empty($id))
        {
            return array();
        }
        $re=$this->find($id,$field);
        if(empty($re))
        {
            return array();
        }
        return  $re->toArray();
    }

    public function count($where,$infield='',$inArray=[])
    {
        if($infield && $inArray)
        {
            return $this->where($where)->wherein($infield,$inArray)->count();
        }
        else
        {
            return $this->where($where)->count();
        }

    }

    public function basePageList($where,$currPage,$pageSize,$sort=array(),$infield='',$inArray=[])
    {
        if(empty($sort))
        {
            $sort['sort_field'] = "id";
            $sort['sort_order'] = "desc";
        }
        if($infield && $inArray)
        {
            return  $this->where($where)
                ->wherein($infield,$inArray)
                ->orderBy($sort['sort_field'],$sort['sort_order'])
                ->limit($pageSize)->offset(($currPage - 1) * $pageSize)->get()->toArray();
        }
        else
        {
            return $this->where($where)
                ->orderBy($sort['sort_field'],$sort['sort_order'])
                ->limit($pageSize)->offset(($currPage - 1) * $pageSize)->get()->toArray();
        }

    }

    public function fetchAll($where=array(),$field = ['*'])
    {
        if(empty($where))
        {
            return  $this->query()->get($field)->toArray();
        }
        else
        {
            return $this->where($where)->get($field)->toArray();
        }
    }

    public function getPageList($where,$currPage,$pageSize,$with=array(),$sort=array(),$infield='',$inArray=[])
    {
        if(empty($sort))
        {
            $sort['sort_field'] = "id";
            $sort['sort_order'] = "desc";
        }
        if($infield && $inArray)
        {
            return $this->where($where)
                ->with($with)
                ->wherein($infield,$inArray)
                ->orderBy($sort['sort_field'],$sort['sort_order'])
                ->limit($pageSize)->offset(($currPage - 1) * $pageSize)->get()->toArray();
        }
        else
        {
            return $this->where($where)
                ->with($with)
                ->orderBy($sort['sort_field'],$sort['sort_order'])
                ->limit($pageSize)->offset(($currPage - 1) * $pageSize)->get()->toArray();
        }
    }

}
