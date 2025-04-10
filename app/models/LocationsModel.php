<?php

/**
 * Class LocationsModel
 * 
 * Manages plant locations
 */ 
class LocationsModel extends \Asatru\Database\Model {
    /**
     * @param $only_active
     * @return mixed
     * @throws \Exception
     */
    public static function getAll($only_active = true)
    {
        try {
            if ($only_active) {
                return static::raw('SELECT * FROM `@THIS` WHERE active = 1 ORDER BY name ASC');
            } else {
                return static::raw('SELECT * FROM `@THIS` ORDER BY name ASC');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public static function getNameById($id)
    {
        try {
            return static::raw('SELECT * FROM `@THIS` WHERE id = ? LIMIT 1', [$id])->first()?->get('name');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return int
     * @throws \Exception
     */
    public static function getCount()
    {
        try {
            return static::raw('SELECT COUNT(*) as count FROM `@THIS`')->first()->get('count');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public static function getLocationById($id)
    {
        try {
            return static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$id])->first();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return string|bool
     * @throws \Exception
     */
    public static function getOutdoorStatus($id)
    {
        try {
            $data = static::raw('SELECT outdoor FROM `@THIS` WHERE id = ?', [$id])->first();
            return $data !== null ? $data->get('outdoor') : false;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @param $icon
     * @param bool $outdoor
     * @return void
     * @throws \Exception
     */
    public static function addLocation($name, $icon, $outdoor = false)
    {
        try {
            static::raw('INSERT INTO `@THIS` (name, icon, outdoor) VALUES(?, ?, ?)', [
                $name, $icon, $outdoor
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $name
     * @param $icon
     * @param $active
     * @param $outdoor
     * @return void
     * @throws \Exception
     */
    public static function editLocation($id, $name, $icon, $outdoor = false)
    {
        try {
            static::raw('UPDATE `@THIS` SET name = ?, icon = ?, outdoor = ? WHERE id = ?', [
                $name, $icon, $outdoor, $id
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $notes
     * @return void
     * @throws \Exception
     */
    public static function saveNotes($id, $notes)
    {
        try {
            static::raw('UPDATE `@THIS` SET notes = ? WHERE id = ?', [
                $notes, $id
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $target
     * @return void
     * @throws \Exception
     */
    public static function removeLocation($id, $target)
    {
        try {
            if ((static::getCount() <= 1) && (PlantsModel::getPlantCount($id))) {
                throw new \Exception(__('app.error_room_not_empty'));
            }

            PlantsModel::migratePlants($id, $target);

            static::raw('DELETE FROM `@THIS` WHERE id = ?', [$id]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function isActive($id)
    {
        try {
            $data = static::raw('SELECT * FROM `@THIS` WHERE id = ? AND active = 1', [$id])->first();
            return $data !== null;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}