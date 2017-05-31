<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 31/05/2017
 * Time: 4:45 PM
 */

namespace App\Repositories\Dashboard;


use App\Contracts\Repositories\Dashboard\WidgetContract;
use App\Models\Widget;

class WidgetRepository implements WidgetContract
{

    /**
     * load all or filtered widgets
     * @param array $data
     * @return mixed
     */
    public function filterAll(array $data = [])
    {

    }

    /**
     * get widget by id
     * @param $widget_id
     * @param bool $throw
     * @return mixed
     */
    public function get($widget_id, $throw = true)
    {
        if ($throw === true) {
            return Widget::findOrFail($widget_id);
        } else {
            return Widget::find($widget_id);
        }
    }

    /**
     * create new widget
     * @param array $data
     * @return mixed
     */
    public function store(array $data = [])
    {
        $widget = Widget::create($data);
        return $widget;
    }

    /**
     * update existing widget
     * @param Widget $widget
     * @param array $data
     * @return mixed
     */
    public function update(Widget $widget, array $data = [])
    {
        $widget->update($data);
        return $widget;
    }

    /**
     * remove an existing widget
     * @param Widget $widget
     * @return mixed
     */
    public function destroy(Widget $widget)
    {
        $widget->delete();
    }
}