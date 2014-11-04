<?php

/**
 * This is the model class for table "location".
 *
 * The followings are the available columns in table 'location':
 * @property string $id
 * @property string $realty_id
 * @property string $country
 * @property string $region
 * @property string $district
 * @property string $locality_name
 * @property string $sub_locality_name
 * @property string $non_admin_sub_locality
 * @property string $address
 * @property string $direction
 * @property string $distance
 * @property string $latitude
 * @property string $longitude
 * @property string $railway_station
 *
 * The followings are the available model relations:
 * @property Realty $realty
 * @property Metro[] $metros
 */
class Location extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'location';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			['realty_id, country', 'required', 'except' => ['import']],
			['realty_id', 'length', 'max'=>10],
			['country, region, district, locality_name, sub_locality_name, non_admin_sub_locality, address, direction, distance', 'length', 'max'=>255],
			['latitude, longitude, railway_station', 'length', 'max'=>100],
		];
	}

    /**
     * Заполняет атрибуты модели данными из XML
     * @param SimpleXMLElement $node данные XML
     */
    public function setXMLAttributes(SimpleXMLElement $node)
    {
        $this->country = $node->{'country'}->__toString();
        $this->region = $node->{'region'}->__toString();
        $this->district = $node->{'district'}->__toString();
        $this->locality_name = $node->{'locality-name'}->__toString();
        $this->sub_locality_name = $node->{'sub-locality-name'}->__toString();
        $this->non_admin_sub_locality = $node->{'non-admin-sub-locality'}->__toString();
        $this->address = $node->{'address'}->__toString();
        $this->direction = $node->{'direction'}->__toString();
        $this->distance = $node->{'distance'}->__toString();
        $this->latitude = $node->{'latitude'}->__toString();
        $this->longitude = $node->{'longitude'}->__toString();
        $this->railway_station = $node->{'railway_station'}->__toString();
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
			'metros' => [self::HAS_MANY, 'Metro', 'location_id'],
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
			'country' => 'Country',
			'region' => 'Region',
			'district' => 'District',
			'locality_name' => 'Locality Name',
			'sub_locality_name' => 'Sub Locality Name',
			'non_admin_sub_locality' => 'Non Admin Sub Locality',
			'address' => 'Address',
			'direction' => 'Direction',
			'distance' => 'Distance',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
			'railway_station' => 'Railway Station',
		];
	}


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Location the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
