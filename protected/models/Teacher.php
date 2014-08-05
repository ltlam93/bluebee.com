<?php

/**
 * This is the model class for table "tbl_teacher".
 *
 * The followings are the available columns in table 'tbl_teacher':
 * @property integer $teacher_id
 * @property string $teacher_name
 * @property string $teacher_personal_page
 * @property string $teacher_avatar
 * @property string $teacher_description
 * @property integer $teacher_work_place
 * @property integer $teacher_active
 * @property integer $teacher_status
 * @property string $teacher_acadamic_title
 * @property string $teacher_birthday
 * @property integer $teacher_sex
 * @property integer $teacher_faculty
 * @property integer $teacher_dept
 * @property integer $teacher_rate
 */
class Teacher extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_teacher';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('teacher_work_place, teacher_active, teacher_status, teacher_sex, teacher_faculty, teacher_dept, teacher_rate', 'numerical', 'integerOnly'=>true),
			array('teacher_name, teacher_personal_page, teacher_description, teacher_acadamic_title, teacher_birthday', 'length', 'max'=>45),
			array('teacher_avatar', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('teacher_id, teacher_name, teacher_personal_page, teacher_avatar, teacher_description, teacher_work_place, teacher_active, teacher_status, teacher_acadamic_title, teacher_birthday, teacher_sex, teacher_faculty, teacher_dept, teacher_rate', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'teacher_id' => 'Teacher',
			'teacher_name' => 'Teacher Name',
			'teacher_personal_page' => 'Teacher Personal Page',
			'teacher_avatar' => 'Teacher Avatar',
			'teacher_description' => 'Teacher Description',
			'teacher_work_place' => 'Teacher Work Place',
			'teacher_active' => 'Teacher Active',
			'teacher_status' => 'Teacher Status',
			'teacher_acadamic_title' => 'Teacher Acadamic Title',
			'teacher_birthday' => 'Teacher Birthday',
			'teacher_sex' => 'Teacher Sex',
			'teacher_faculty' => 'Teacher Faculty',
			'teacher_dept' => 'Teacher Dept',
			'teacher_rate' => 'Teacher Rate',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('teacher_id',$this->teacher_id);
		$criteria->compare('teacher_name',$this->teacher_name,true);
		$criteria->compare('teacher_personal_page',$this->teacher_personal_page,true);
		$criteria->compare('teacher_avatar',$this->teacher_avatar,true);
		$criteria->compare('teacher_description',$this->teacher_description,true);
		$criteria->compare('teacher_work_place',$this->teacher_work_place);
		$criteria->compare('teacher_active',$this->teacher_active);
		$criteria->compare('teacher_status',$this->teacher_status);
		$criteria->compare('teacher_acadamic_title',$this->teacher_acadamic_title,true);
		$criteria->compare('teacher_birthday',$this->teacher_birthday,true);
		$criteria->compare('teacher_sex',$this->teacher_sex);
		$criteria->compare('teacher_faculty',$this->teacher_faculty);
		$criteria->compare('teacher_dept',$this->teacher_dept);
		$criteria->compare('teacher_rate',$this->teacher_rate);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Teacher the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
