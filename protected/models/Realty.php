<?php

/**
 * This is the model class for table "realty".
 *
 * The followings are the available columns in table 'realty':
 * @property string $id
 * @property string $type
 * @property string $property_type
 * @property string $category
 * @property string $url
 * @property string $creation_date
 * @property string $last_update_date
 * @property string $expire_date
 * @property integer $payed_adv
 * @property integer $manually_added
 *
 * The followings are the available model relations:
 * @property Location[] $locations
 * @property SalesAgent[] $salesAgents
 */
class Realty extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'realty';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			['type, property_type, category, url, payed_adv, manually_added', 'required'],
            ['type', 'in', 'range' => ['sale', 'rent']],
			['payed_adv, manually_added', 'in', 'range'=>[0,1]],
			['property_type', 'in', 'range'=>['living']],
			['category', 'in', 'range'=>['flat','room','house','lot','cottage']],
			['url', 'length', 'max'=>255],
			['creation_date, last_update_date, expire_date', 'safe']
		];
	}

    /**
     * Выполняется перед валидацией данных модели
     * @return bool
     */
    public function beforeValidate()
    {

        # заменяем кирилические данные на латинские
        $this->type = str_replace(['продажа', 'аренда'], ['sale', 'rent'], $this->type);

        $this->category = str_replace([
            'комната','квартира','дом','участок'
        ], [
            'flat','room','house','lot'
        ], $this->category);

        $this->property_type = str_replace('жилая', 'living', $this->property_type);

        return parent::beforeValidate();
    }

    /**
     * Заполняет атрибуты модели данными из XML
     * @param SimpleXMLElement $node данные XML
     */
    public function setXmlAttributes(SimpleXMLElement $node)
    {
        $this->type = $node->{'type'}->__toString();
        $this->property_type = $node->{'property-type'}->__toString();
        $this->category = $node->{'category'}->__toString();
        $this->url = $node->{'url'}->__toString();
        $this->creation_date = $node->{'creation-date'}->__toString();
        $this->last_update_date = $node->{'last-update-date'}->__toString();
        $this->expire_date = $node->{'expire-date'}->__toString();
        $this->payed_adv = $node->{'payed-adv'}->__toString();
        $this->manually_added = $node->{'manually-added'}->__toString();
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return [
			'locations' => [self::HAS_MANY, 'Location', 'realty_id'],
			'salesAgents' => [self::HAS_MANY, 'SalesAgent', 'realty_id'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'type' => 'Type',
			'property_type' => 'Property Type',
			'category' => 'Category',
			'url' => 'Url',
			'creation_date' => 'Creation Date',
			'last_update_date' => 'Last Update Date',
			'expire_date' => 'Expire Date',
			'payed_adv' => 'Payed Adv',
			'manually_added' => 'Manually Added'
		];
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Realty the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
