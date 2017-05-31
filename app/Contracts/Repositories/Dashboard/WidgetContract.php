<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 31/05/2017
 * Time: 4:42 PM
 */

namespace App\Contracts\Repositories\Dashboard;


use App\Models\Widget;

interface WidgetContract
{
    /**
     * load all or filtered widgets
     * @param array $data
     * @return mixed
     */
    public function filterAll(array $data = []);

    /**
     * get widget by id
     * @param $widget_id
     * @param bool $throw
     * @return mixed
     */
    public function get($widget_id, $throw = true);

    /**
     * create new widget
     * @param array $data
     * @return mixed
     */
    public function store(array $data = []);

    /**
     * update existing widget
     * @param Widget $widget
     * @param array $data
     * @return mixed
     */
    public function update(Widget $widget, array $data = []);

    /**
     * remove an existing widget
     * @param Widget $widget
     * @return mixed
     */
    public function destroy(Widget $widget);
}