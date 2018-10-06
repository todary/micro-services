<?php
namespace App\Libraries;

/**
 *  class DataPoint Bridge
 *  @author Mostafa Ameen
 */
class ProgressBridge extends DataPointBridge
{
    /**
     * [get one recored]
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function get(BridgeCriteria $criteria = null, $cache = 0)
    {
        if ($criteria === null) {
            $criteria = new BridgeCriteria;
        }

        if ($this->is_old) {
            return self::oldGet($criteria, $cache);
        }

        $criteria = clone $criteria;

        $criteria->compare('key', '-');
        $row = parent::get($criteria);
        if ($row) {
            if (!empty($row['report_id'])) {
                $row['person_id'] = $row['report_id'];
            }

            $defaultData = [
                'id' => 0,
                'start' => 0,
                'time_taken' => 0,
                'time_remaining' => 0,
                'total_combinations' => 0,
                'completed_combinations' => 0,
                'relatives' => 0,
                'phones' => 0,
                'emails' => 0,
                'usernames' => 0,
                'addresses' => 0,
                'photos' => 0,
                'profiles' => 0,
                'websites' => 0,
                'results' => 0,
                'fullcontact' => 0,
                'whitepages' => 0,
                'google' => 0,
                'twitter' => 0,
                'youtube' => 0,
                'facebook_live_in' => 0,
                'facebook_hometown' => 0,
                'facebook_nearby' => 0,
                'pinterest' => 0,
                'spokeo' => 0,
                'lookup' => 0,
                'linkedin' => 0,
                'courtcasefinder' => 0,
                'intelius' => 0,
                'instantcheckmate' => 0,
                'beenverified' => 0,
                'tendigits' => 0,
                'locate411' => 0,
                'instagram' => 0,
                'pipl' => 0,
                'myspace' => 0,
                'mylife' => 0,
                'peekyou' => 0,
                'facebook_by_school' => 0,
                'facebook_by_company' => 0,
                'facebook_by_relatives' => 0,
                'fullcontact_total' => 0,
                'whitepages_total' => 0,
                'google_total' => 0,
                'googleplus' => 0,
                'googleplus_total' => 0,
                'twitter_total' => 0,
                'vine_total' => 0,
                'facebook_live_in_total' => 0,
                'facebook_hometown_total' => 0,
                'facebook_nearby_total' => 0,
                'pinterest_total' => 0,
                'spokeo_total' => 0,
                'lookup_total' => 0,
                'linkedin_total' => 0,
                'courtcasefinder_total' => 0,
                'intelius_total' => 0,
                'instantcheckmate_total' => 0,
                'beenverified_total' => 0,
                'tendigits_total' => 0,
                'locate411_total' => 0,
                'instagram_total' => 0,
                'pipl_total' => 0,
                'myspace_total' => 0,
                'mylife_total' => 0,
                'peekyou_total' => 0,
                'facebook_by_school_total' => 0,
                'facebook_by_company_total' => 0,
                'facebook_by_relatives_total' => 0,
                'case_usernames' => 0,
                'case_usernames_total' => 0,
                'yellowpages' => 0,
                'yellowpages_total' => 0,
                'whitepages_phone' => 0,
                'whitepages_phone_total' => 0,
                'whitepages_address' => 0,
                'whitepages_address_total' => 0,
                'twitterstatus' => 0,
                'twitterstatus_total' => 0,
                'youtube_total' => 0,
                'websites_total' => 0,
                'usernames_total' => 0,
                'phones_data' => null,
                'addresses_data' => null,
                'emails_data' => null,
                'relatives_data' => null,
                'profiles_data' => null,
                'assoc_profiles_data' => null,
                'assoc_keys_data' => null,
                'work_experiences_data' => null,
                'schools_data' => null,
                'websites_data' => null,
                'nicknames_data' => null,
                'names_data' => null,
                'age_data' => null,
                'added_usernames_data' => null,
                'avatar_result_id' => null,
                'default_profile' => null,
            ];

            $row += $defaultData;
        }

        return $row;
    }

    /**
     * [get description]
     * @param  \BridgeCriteria $criteria [description]
     * @return [type]                    [description]
     */
    public function getAll(BridgeCriteria $criteria = null, $cache = 0)
    {
        if ($criteria === null) {
            $criteria = new BridgeCriteria;
        }

        if ($this->is_old) {
            return self::oldGetall($criteria, $cache);
        }

        $criteria = clone $criteria;

        $criteria->compare('key', '-');
        return parent::getAll($criteria, $cache = 0);
    }

    /**
     * @param  [array] $attributes
     * @return [bool]
     */
    public function insert(array $attributes)
    {
        if ($this->is_old) {
            return self::oldInsert($attributes);
        }

        $attributes['key'] = '-';
        $attributes['data_key'] = '-';
        return parent::insert($attributes);
    }

    /**
     * [update description]
     * @param  [type]          $data [description]
     * @param  \BridgeCriteria $criteria        [description]
     * @return [type]                           [description]
     */
    public function update(array $data, BridgeCriteria $criteria = null)
    {
        if ($criteria === null) {
            $criteria = new BridgeCriteria;
        }

        if ($this->is_old) {
            return self::oldUpdate($data, $criteria);
        }

        $criteria = clone $criteria;

        $criteria->compare('key', '-');
        return parent::update($data, $criteria);
    }

    public function delete(BridgeCriteria $criteria = null)
    {
        if ($criteria === null) {
            $criteria = new BridgeCriteria;
        }

        $data = array('is_deleted' => 1);
        return $this->update($data, $criteria);
    }

    public function exists(BridgeCriteria $criteria = null)
    {
        return !!$this->get($criteria);
    }

    /**
     * @param  [array] $attributes
     * @return [bool]
     */
    public function oldInsert(array $attributes)
    {
        $attributes['person_id'] = $this->person_id;
        unset($attributes['report_id']);
        return Yii::app()->db->createCommand()->insert('progress', $attributes);
    }

    /**
     * [get one recored]
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function oldGet(BridgeCriteria $criteria, $cache = 0)
    {
        $criteria = clone $criteria;

        $criteria->condition = str_ireplace('[] IN', ' IN', $criteria->condition);
        $criteria->condition = str_ireplace('report_id', 'person_id', $criteria->condition);
        if (stripos($criteria->condition, 'person_id') === false) {
            $criteria->compare('person_id', $this->person_id);
        }

        $model = \Progress::model()->cache($cache)->find($criteria);

        if ($model) {
            return $model->attributes;
        } else {
            return null;
        }
    }

    /**
     * [get description]
     * @param  \BridgeCriteria $criteria [description]
     * @return [type]                    [description]
     */
    public function oldGetall(BridgeCriteria $criteria, $cache = 0)
    {
        $criteria = clone $criteria;

        $criteria->condition = str_ireplace('[] IN', ' IN', $criteria->condition);
        $criteria->condition = str_ireplace('report_id', 'person_id', $criteria->condition);
        if (stripos($criteria->condition, 'person_id') === false) {
            $criteria->compare('person_id', $this->person_id);
        }
        return \Progress::model()->cache($cache)->findAll($criteria);
    }

    /**
     * [update description]
     * @param  [type]          $data [description]
     * @param  BridgeCriteria $criteria        [description]
     * @return [type]                           [description]
     */
    public function oldUpdate(array $data, BridgeCriteria $criteria)
    {
        $criteria = clone $criteria;

        $criteria->condition = str_ireplace('[] IN', ' IN', $criteria->condition);
        $criteria->condition = str_ireplace('report_id', 'person_id', $criteria->condition);
        if (stripos($criteria->condition, 'person_id') === false) {
            $criteria->compare('person_id', $this->person_id);
        }

        try {
            return \Progress::model()->updateAll($data, $criteria);
        } catch (\Exception $ex) {
            if (stripos($ex->getMessage(), "No columns are being updated") !== false) {
                return 0;
            }
            throw $ex;
        }
    }
}
