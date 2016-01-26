<?php
namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_post".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $tags
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $author_id
 */
class Actives extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%actives}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    public function fields()
    {
        $fields = parent::fields();
        $fields['bookings_count'] = function () {
            return (int)$this->getBookingsCount();
        };

        return $fields;
    }
    
    private function getBookingsCount()
    {
        if ($this->actives_group === NULL)
            return false;
        if (!$this->actives_group) {
            return TransactionHistory::find()
                ->where("`class_id` IN (SELECT `class_id` FROM `classes` WHERE `class_activity` = {$this->actives_id}) ")
                ->andWhere("`status` != 'REFUNDED'")
                ->count();
        } else {
            return TransactionHistory::find()
            ->where("`class_id` IN 
					(SELECT `class_id` FROM `classes` WHERE `class_activity` IN 
					(SELECT `actives_id` FROM `actives` WHERE `actives_group_parent` = {$this->actives_id})
					)
				")
            ->andWhere("`status` != 'REFUNDED'")
            ->count();
        }
    }
    
    public function getUserActivesList($ids)
    {
        return static::findAll(explode(',', $ids));
    }
}
