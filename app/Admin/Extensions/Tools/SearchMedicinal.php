<?php
namespace App\Admin\Extensions\Tools;

use Encore\Admin\Grid\Concerns\HasQuickSearch;
use Illuminate\Support\Arr;
use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use App\Producer;
class SearchMedicinal extends AbstractTool{
    protected $placeholder = "请输入药品名称";
    protected $view = 'admin.tools.searchmedicinal';
    public function render()
    {
        $query = request()->query();
        Arr::forget($query, HasQuickSearch::$searchKey);
        $producer = Producer::get(['id','name'])->toArray();
        $vars = [
            'action'      => request()->url().'?'.http_build_query($query),
            'prolist'   => $producer,
            'producer'         => isset($query['producer'])?$query['producer']:'',
            'key'       => isset($query['key'])?$query['key']:'',
            'placeholder' => $this->placeholder,
        ];
        return view($this->view, $vars);
    }
}