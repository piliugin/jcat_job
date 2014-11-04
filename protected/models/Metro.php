<?php

/**
 * This is the model class for table "metro".
 *
 * The followings are the available columns in table 'metro':
 * @property string $id
 * @property string $location_id
 * @property string $name
 * @property integer $time_on_transport
 * @property integer $time_on_foot
 *
 * The followings are the available model relations:
 * @property Location $location
 */
class Metro extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'metro';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			['location_id', 'required', 'except' => ['import']],
			['time_on_transport, time_on_foot', 'numerical', 'integerOnly'=>true],
			['location_id', 'length', 'max'=>10],
			['name', 'length', 'max'=>150]
		];
	}

    /**
     * Заполняет атрибуты модели данными из XML
     * @param SimpleXMLElement $node данные XML
     */
    public function setXmlAttributes(SimpleXMLElement $node)
    {
        $this->name = $node->{'name'}->__toString();
        $this->time_on_transport = $node->{'time-on-transport'}->__toString();
        $this->time_on_foot = $node->{'time-on-foot'}->__toString();
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return [
			'location' => [self::BELONGS_TO, 'Location', 'location_id'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'location_id' => 'Location',
			'name' => 'Name',
			'time_on_transport' => 'Time On Transport',
			'time_on_foot' => 'Time On Foot',
		];
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Metro the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
