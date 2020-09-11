<?php

namespace App\Admin\Controllers;

use App\Producer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProducerController extends AdminController {
	/**
	 * Title for current resource.
	 *
	 * @var string
	 */
	protected $title = '厂家';

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid() {
		$grid = new Grid(new Producer());
		$grid->column('id', 'ID');
		$grid->column('name', '厂家');
		$grid->column('productionlicense', '许可证书');
		$grid->column('productionaddress', '生产厂商');
		$grid->column('image', '图片')->image();
		$states = [
			'on' => ['value' => 1, 'text' => '是', 'color' => 'success'],
			'off' => ['value' => 0, 'text' => '否', 'color' => 'danger'],
		];
		$grid->column('is_top', '置顶')->switch($states);
		$grid->column('created_at', '创建时间');
		$grid->column('updated_at', '修改时间');
		$grid->disableFilter();
		$grid->disableExport();
		$grid->disableRowSelector();
		$grid->disableColumnSelector();
		return $grid;
	}

	/**
	 * Make a show builder.
	 *
	 * @param mixed $id
	 * @return Show
	 */
	protected function detail($id) {
		$show = new Show(Producer::findOrFail($id));
		$show->field('id', 'ID');
		$show->field('name', '厂家');
		$show->field('productionlicense', '许可证书');
		$show->field('productionaddress', '生产厂商');
		$show->field('created_at', '创建时间');
		$show->field('updated_at', '修改时间');

		$show->panel()
			->tools(function ($tools) {
				$tools->disableEdit();
				$tools->disableDelete();
			});
		return $show;
	}

	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form() {
		$form = new Form(new Producer());
		$form->text('name', '厂家名称')->rules('required|max:50');
		$form->text('productionlicense', '生产许可')->rules('required|max:50');
		$form->text('productionaddress', '生产厂商');
		$form->image('image', '图片')->help('图片尺寸：380*300');
		$states = [
			'on' => ['value' => 1, 'text' => '是', 'color' => 'success'],
			'off' => ['value' => 0, 'text' => '否', 'color' => 'danger'],
		];
		$form->switch('is_top', __('置顶'))->states($states)->default(0);
		$form->tools(function (Form\Tools $tools) {
			$tools->disableList();
			$tools->disableDelete();
			$tools->disableView();
		});
		$form->footer(function ($footer) {
			$footer->disableReset();
			$footer->disableViewCheck();
			$footer->disableEditingCheck();
			$footer->disableCreatingCheck();
		});
		return $form;
	}
}
