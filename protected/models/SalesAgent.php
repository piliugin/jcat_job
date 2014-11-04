<?php

/**
 * This is the model class for table "sales_agent".
 *
 * The followings are the available columns in table 'sales_agent':
 * @property string $id
 * @property string $realty_id
 * @property string $name
 * @property string $category
 * @property string $organization
 * @property integer $agency_id
 * @property string $url
 * @property string $phone
 * @property string $email
 * @property string $partner
 *
 * The followings are the available model relations:
 * @property Realty $realty
 */
class SalesAgent extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sales_agent';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			['realty_id, category', 'required', 'except' => ['import']],
			['agency_id', 'numerical', 'integerOnly'=>true],
			['realty_id', 'length', 'max'=>10],
			['name, organization', 'length', 'max'=>150],
			['category', 'in', 'range'=>['owner','agency']],
			['url, partner', 'length', 'max'=>255],
			['phone', 'length', 'max'=>45],
			['email', 'length', 'max'=>100]
		];
	}

    /**
     * Выполняется перед валидацией данных модели
     * @return bool
     */
    public function beforeValidate()
    {
        # заменяем кирилические данные на латинские
        $this->category = str_replace(['владелец', 'агентство'], ['owner', 'agency'], $this->category);
        return parent::beforeValidate();
    }

    /**
     * Заполняет атрибуты модели данными из XML
     * @param SimpleXMLElement $node данные XML
     */
    public function setXmlAttributes(SimpleXMLElement $node)
    {
        $this->name = $node->{'name'}->__toString();
        $this->category = $node->{'category'}->__toString();
        $this->organization = $node->{'organization'}->__toString();
        $this->agency_id = $node->{'agency-id'}->__toString();
        $this->url = $node->{'url'}->__toString();
        $this->phone = $node->{'phone'}->__toString();
        $this->email = $node->{'email'}->__toString();
        $this->partner = $node->{'partner'}->__toString();
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return [
			'realty' => [self::BELONGS_TO, 'Realty', 'realty_id'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'realty_id' => 'Realty',
			'name' => 'Name',
			'category' => 'Category',
			'organization' => 'Organization',
			'agency_id' => 'Agency',
			'url' => 'Url',
			'phone' => 'Phone',
			'email' => 'Email',
			'partner' => 'Partner',
		];
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SalesAgent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
