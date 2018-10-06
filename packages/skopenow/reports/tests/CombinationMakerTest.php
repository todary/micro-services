<?php
use Skopenow\Reports\CombinationCreators\CombinationsMaker;
use App\Models\SearchCriteria;

/**
*
*/
class CombinationMakerTest extends TestCase
{

    public function testGetComb()
    {
        // $combinationMaker = new CombinationMaker();
        // $data = $combinationMaker->withNames(['Mahmoud Magdy', 'Mahmoud Magdy Mahmoud'])
        //         ->withCompanies(['Skopenow', 'Queen Tech'])->get();
        // dd($data);
    }

    /** @test */
    public function try()
    {
        $combinationMaker = new CombinationsMaker();
        $combinationMaker->set('name', [['full_name' => 'mohammed Attya', 'first_name' => 'Mohammed', 'last_name' => 'Attya']]);
        $combinationMaker->set('location', [['city' => 'NewYork', 'state' => 'NY']]);
        $combinationMaker->set('email', ['Cairo@gmail.com', 'Benha@gmail.com']);
        $data = $combinationMaker->withEach(['name', 'location'])->get();
        foreach ($data as $comb) {
            $dt = $this->buildSearchCriteria($comb);
            $dt->state = '';
            dd($dt);
        }
    }

    protected function buildSearchCriteria($combination)
    {
        $data = $combination->getData();
        $criteria = new SearchCriteria();
        $criteria->name = $data['name'][0]['full_name']??'';
        $criteria->first_name = $data['name'][0]['first_name']??'';
        $criteria->middle_name = $data['name'][0]['middle_name']??'';
        $criteria->last_name = $data['name'][0]['last_name']??'';
        $criteria->city = $data['location'][0]['city']??'';
        $criteria->state = $data['location'][0]['state']??'';
        $criteria->country_code = $data['country_code'][0]??'US';
        $criteria->birth_date = $data['date_of_birth'][0]??'';
        $criteria->phone = $this->implode($data, 'phone');
        $criteria->email = $this->implode($data, 'email');
        $criteria->company = $this->implode($data, 'company');
        $criteria->school = $this->implode($data, 'school');
        return $criteria;
    }

    protected function implode($data, $key)
    {
        return implode('|', $data[$key]??[]);
    }
}
