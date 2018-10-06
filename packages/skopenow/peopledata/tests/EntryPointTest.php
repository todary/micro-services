<?php
use Skopenow\PeopleData\EntryPoint;
use Skopenow\PeopleData\Criteria;
use Skopenow\PeopleData\OutputModel;
use Illuminate\Support\Facades\File;
use Skopenow\PeopleData\ApiSoapClient;
use Skopenow\PeopleData\Sources\CURLSoapClient;

/**
class EntryPointTest extends TestCase
{
    public function test_search()
    {
        ## inputs
        $one_tlo_tinput = $this->oneTloTestInput();
        $one_pipl_tinput = $this->onePiplTestInput();
        $both_tinput = $this->bothTloAndPiplTestInput();
        $empty_input = $this->emptyCriteriaInput();

        ## accounts
        $tlo_account = $this->tloAccount();
        $pipl_account = $this->piplAccount();
        ## responses
        $pipl_content = $this->curlResponseMockForPipl();
        $tlo_content = $this->SoapResponseMockForTlo();
        $tlo_final_result= $this->tlo_final_result();
        $pipl_final_result= $this->pipl_final_result();
        $direct_tlo_result = $this->tlo_response();
        $direct_pipl_result = $this->pipl_response();
        ## mocks
        ## pipl
        setUrlMock("http://api.pipl.com/search/?key=oc4w8jdfp420v9pblg42fbzv&raw_name=Rob+Douglas&raw_address=Oyster+Bay%2C+NY",$pipl_content);
        setUrlMock("http://api.pipl.com/search/?key=oc4w8jdfp420v9pblg42fbzv&raw_name=Rob+Douglas&email=robert.m.douglas%40vanderbilt.edu&age=27-37&raw_address=92+Sunken+Orchard+Lane%2C+Oyster+Bay%2C+NY%2C+Oyster+Bay%2C+NY",$pipl_content);
        ## tlo_test
        $api_client = $this->getMockBuilder(ApiSoapClient::class)
            ->setConstructorArgs([
                    "account" => $tlo_account,
                    "wsdl" => base_path()."/packages/skopenow/peopledata/src/Sources/tlo.wsdl"
                ])
            ->getMock();
        $api_client->method('test')
            ->willReturn(true);
        $api_client->method('getAccount')
            ->willReturn($tlo_account);
        ## tlo response
        $curlSoap = $this->getMockBuilder(CURLSoapClient::class)
            ->disableOriginalConstructor()
            ->getMock();
            
        $curlSoap->method('callCurl')
            ->willReturn($tlo_content);
        ## initialize datapoint
            $people_data = new EntryPoint;
        ## results

        ## no criteria
        $actual = $people_data->search($empty_input);
        $this->assertEquals([], $actual);        
        ## 1 source with api tloxp

        $actual = $people_data->search($one_tlo_tinput);
        $this->assertEquals($tlo_final_result, $actual);
        ## 1 source with api pipl
        $actual = $people_data->search($one_pipl_tinput);
        $this->assertEquals($pipl_final_result, $actual);
        ## Multi source with Pipl & Tlo
        $people_data = new EntryPoint;
        $expected = [
            $direct_tlo_result,
            $direct_pipl_result,
        ];
        $actual = $people_data->search($both_tinput);
        $this->assertEquals($expected, $actual);
    }
     ############## Test Exceptions ###############

    public function testNotArrayCriteria()
    {
        $criteria = ["api"=>"tloxp"];
        $people_data = new EntryPoint;
        $this->expectException(\Exception::class);
        $actual = $people_data->search($criteria);
        $this->assertEquals(false, $actual);
    }
    public function testArrayAndEmptyCriteria()
    {
        $criteria =[[]];
        $people_data = new EntryPoint;
        $this->expectException(\Exception::class);
        $actual = $people_data->search($criteria);
        $this->assertEquals(false, $actual);
    }
    public function testCorrectInputWithFalseApiInOneSource()
    {
        $criteria = [
            [
                "test"=>[
                    "api"=>"test",
                    "name" => "Rob Douglas",
                    "city" => "Oyster Bay",
                    "state" => "NY"
                ]
            ]
        ];
        $people_data = new EntryPoint;
        $this->expectException(\Exception::class);
        $actual = $people_data->search($criteria);
        $this->assertEquals(false, $actual);
    }
    public function correctInputWithFalseApiInMultiSource()
    {
        $criteria = [
            [
                "test"=>["api"=>"test"],
                "tloxp"=>[
                    "api"=>"tloxp",
                    "name" => "Rob Douglas",
                    "city" => "Oyster Bay",
                    "state" => "NY"
                ],
                
            ]
        ];
        $people_data = new EntryPoint;
        $this->expectException(\Exception::class);
        $actual = $people_data->search($criteria);
        $this->assertEquals(false, $actual);
    }

    public function oneTloTestInput()
    {
        $criteria = [
            [
                "tloxp"=>[
                    "api"=>"tloxp",
                    "name" => "Rob Douglas",
                    "city" => "Oyster Bay",
                    "state" => "NY"
                ]
            ]
        ];
        return $criteria;
    }
    public function onePiplTestInput()
    {
        $criteria = [
            [
                "pipl" =>[
                    "api"=>"pipl",
                    "name" => "Rob Douglas",
                    "city" => "Oyster Bay",
                    "state" => "NY",
                    "address" => "92 Sunken Orchard Lane, Oyster Bay, NY",
                    "email" => "robert.m.douglas@vanderbilt.edu",
                    "username" => "robdouglas",
                    "age" => 32,
                    "company" => "Co-Founder and CTO - Skopenow",
                    "school" => "Obhs",
                    "report_id" => "e00a2eed1c4678a52ebfa56a4fe81dc588b551f33f83a98035b9e5a6fcaf5500d12f45fa33d2dbf11b0c5eda6ce9f8de8a1d300ef9521ab7489ebb5a9280d755b02a3620dfcbcd33d01445b3c68258e8a946293329452f2e65c3747b077d4f5e0983fac8785fb440c6f9043565d1b9e704a18fe612de2a01746ef9430113b3944dc8b4c605eb86ebf50e54ac2b2a7f2f59a8a2b82e15ec9a4e3f517934df2459c11b05e1660885c019e45a5daee9360a2e5aeac8aaad39441cc2f67414fd7ff498d5017f43d4cc80942c78dd894ac75b677059593ed4f4270f5064aabbb82aed7c7e42b0e878d13104b80e01d5f7c71a43f6c2dd3fb242bd872df461d9427a3cd5bf56364cf55e0f0f7cc2181f2faf4692c37d97b1b351a61497017f2f051fb5d98758821057af6b48daa2b67335ace30567ae3ad534c3e30501e808500f813dab1aa064a1a7709a9f2d30dfcc6e1288c3168a2a5ab340040d237f97d36a1de70324527e6130b9216eff138d873c1ab85204812b64ead53b35f5617e3a6d362b6ee77c012eaec1fda66fd5146b112d5156c7d93c98c92aabc51219c499c6e86bc9ce389d089186cce2db774b235af0d48032607a0d5e76fdce2e6fc150991d73d9470be43fc0b43d600f3f552de2cfa9125cc5b51bad6909da16886e9cd9d77a95b5e25c36a254093ec0f21e218be58a2012cd0cc7c082f6379ace043f288e335d6c17cee3698bea5ead876a82c6abeeeec8a72aeff3f6e3b08caf57051d00243d4d1e70dc8fad5306b32be609dce14b241345dac27b152ef0d399e33baf98ea0b9051641ad2e8f568b240f71c1e6baf7ca15a33212d0b56670a221046b4902c7dc64555aeb666399d2080ee385c590bcf2d483513af166fe5ed9d3ffd6c9a7106f0f6b53eeb6f72dd87c351f5ff17311af74693ce667722eaacbd8041bae40ca9fce5c2063c29606b49ad0957de8d4bc168e3a09709775d5064d62e83686c565164cacd0414051608c5d32d20fa6807adfc6755ca8cd5a3848acc55b7638b22de89e22a9b5cdc58cd29af4e2ebf0797d3230733752927b5e5c1db1bdae285db7361d6dca9028ff91a292ae6c156253501cd5b3d7271eebd3314b5626e67df86ccdc9b0e7a7c0c840c7a8ca46af5d8d750320564800b060d20b484f8cd0971af19ac4c25a072f2fa869e152fc002e5b1dba585b14531d54b2fccfb72807f29f9ebf4af3875de8723a47c718665bbda5d3ab2fc5fee40e96dd72fedcb3d8ccb417afba2f39d536621ce41028a55e1ec717dd20e5d6ff97f89b7f010841c1f1b3e5d992216c5964f65aacd480d0a8a0e96472e60d6ff16ddb0a52fa0209a3897e383265b8a46e843e89a8e9bf4b89010d735359a27aa05a8d3436f446fe6afa330d6ebf0b356c58c5a332e0f8e34b0e4abcbfacda312e9d95462a32da22d275bf8fa00b8e35031a9b30e0da5dabb627a29d591466c875aa5a74906c9858c88646132e65d2370ba96c088da4aa2ae4e1bfa7a0d726a727b1ec1c485ad7aa9b2615b76653a970cf98fd59c62cfc85d43ccaeb25a8ca2d399321c86eeb4eaa9cedfba33ad03dbdfdc7e14625613793a4644fe9c53b8438c8597beca3aa713725637ea8f24ba173bc55efe3450ef82a4f2fb0b962126d4b380c3a6e2ee3d3f25bb2535168618ea9989d87bd95625f277b1f5d616003ac921dd2e4322e7f51c37b36a4247a39b53729ea4a118e8e2fedb944c1023c4e52f502206183d3ddfff05f0f3bbe1a2f8a3d3572623dd49b73ae0aae101d00831fc32863ac755cc8dc51ca5d5603301bbec866e9eb60df4d0a31275418e872e047d420d8789a3aba701fa1bebbd62961826bab76247d46307f4672202b6b6895d8a7a80091dc9273e3f1282e4560055578cd26ec3ed57142afa57065b02815a13be3d718acc775d2cf7bc122f84ad91c49b9bbe35960c78acc49e4a988ee38d3f5fb998797f45d912722bf2731b3ca97be45da925487a8f03e47abe86ec9febd54d8755e65dbab5e2024dfd2b9391eda3a0ec53cd565aac921209afae62261aa38ba5cc67df60b30cdaed32bc9a38f07302d76e88895c00e9df2287703f038c342c4f1d5ca0b3b742b3118aca49d5a2a1b272e871309ba34f01762363553a1aef605f40f04cb76179487373ac21f73136a63ed69032cc525f0ac26397b1553d7fa4a8276829283faac5ac74f5771dff9aee14fd0186d71b7fb003ff964ddecfbb65410fe83514f5b970df5faebbf9e5fc38349ad060e05ab8b0289f9dae78b1ed4e36f945c633d9026087ef5a4eafe4439ecd4a40eaa7c5b764d7f459ef9b109bbef5a02ff6c1b6671a638f0a81842d5913668fcafbcd27e57491b5158a3076de3b1ff14ae1eb702c71702e3f2ee9fec871bd0dc1300cb63aed1b20965d7b0c2857b673a90848cad10dde52055f5e8709e9418a25251a6f6f77f86d1e5b0b9e18895afce8a27eb93e8043600fbad0d281a8ada2c6a6b9589b84b20cc41f9db300b5dffe0cf9eac677bd6a764687b7e3676ee2f3e340211b9259465e3de667d7a83faea301a71856b2d110eeb94bc0d69a2f6db68ed6fbab7bc7c618e2fe032ed80539c2e338ace9df7e575b2e52de144d396573bb2fa0f6b5fc68ec94091d9b9466ba3638b62afc1c9d22d417e528913b9a51bbbc33aa6418f4d5a4d8d8fdb826a37d113d4f35b18f290d09fc44d3d20b6543866d3c1b17b97fce1463c59daea6b4bf8934ca211f4a14110d8ea3e55230d1586e63b7d958ceff69552f53ded463758b11377a5d494077925fd9e90380d1b2d7ca072e414eab4499f04fa7af8c907d2caa80ac088214646daee779cda4e0030d5c103f59a83ac84f11bdc4ab05914370f97c58226f3cf8d3e195287413c3b9cc2a44e13346766485c5f139a8f7d04fc8165ead376e3c9376b3cda0bcb05da919146c5a915dcfba64332a439b2e1646403bd50d7d1c03c1ef1437e2e8f51577ea294f3fedf9c6868d1624499583d9930967803a710ea98f81fe55e110a25d32b796112b26f32c07025b6447dbe3cbec854d05deaba8c606cb4c3a5610cc0b0949986ef9998c414fbde8bd1780bf9b3998bd2162eeba9a1ce5a8734c7716543577549cc83781f29f2dec8fa1bdea42674145f566ddc61eae1cded3a0e35103d84dbdd53f4297cc9df71f5e743d49b65cabf15fec9503e1e36602627a3fa6edac24713c89366b0ec2f784358e3441f90980b6f9d5bcf3973448466ab451e44a5919364ff74a130db21319adc90762c4c7302d1f574aff4f0d2caeeb04f3e8d6a1d68f79a8c402fba92fd463fd93022bb32c7ea1e42dea4fd74584c0cb78f85bbe86e85464296127ea1fd873a74c11643b725a090f24785c69b0e3981e8e7f70c78b2bac7d7f8ccff5aa7b8a710ad60c0beb879d650040163bf23c579d63d831bd898cb68453e2fdbd0549e0efdc76efb459f42332c355e65d2eed9cc10a446b8fdb2d0581675ae36cf9dc78647f44b9310eac147a72e5cde75e37fe84ed31e9f6a05458266dcff495e5b6a5a7b9ff9bdb8c333d4b01992484358e55ab5dde35650fa6e8270b62912c71e4598d519dc5bcc883c7ab3170555f8893dbc5546503bce69821341bc97a4d82cbd50bbe07ab5054495298143c77d7d6bbc5617303dec87ac09b7559127ce88af8bda851b331000a616ecb5cfef141b9da9bb8fc42afba058d4379b5648c6b5c747501beb1f27ac38a6480cbbd4016a3981e9d1c5745f4378ec5c52db8b07efa84f64600dd74c11314c4b5d7440b51493a4832a1519406570bc346fd84306c8e776eae06915520bf30b25b08cb524e76839727be4e4d14b46730799d5ba3c833162f7dfb92db0e84c0067a32307f8c5e5bf17f03f5c99bafb38294d69d4f19f73c769c6b549b2129d4403201f51edd203c2e821a35634e94b205f01fa1b91f103492472557a9389b5edb6c74784d8327d9c4bc0e102cd318ea6b8812b0a30b6a4130f7f0c0a0a85bec42eaa53d7c310f86f4e9a4eb1d023d7a7a5294e4db8c4bc4ddafc5e1d50792ae4fe47a779e03823ee63d9eb1d6d3e2a2062790df40c6334f378e631fd4a7135d4cca13260dadfe33803197c4ffac1d4de211ef305d7076f7e2a4c10abc8248e19c897145a0378ce976de6e1af3e4c3f06b8b3fa009b29f48bbdcd44fb1090c4116b7789a711a943acc496898a7add6cddbac5130cf2525fb4325b27b4a960a6d54edfaddf5d8b2ffc60a6268c4d262f878d1b38b443c54a5474b0858db9e42211a5c19c81e4c36b299c1183f8fff2a9cdaa75a9096d960f5ad0692dceea6885e05f8f4e70cb0f893b33df61d2576a1ae2a0bd529ffb435e439d0ba2b75ec250c35b6dd5d4022b8726ad6e171b86559c1fb72691d940f7b95548835f6112e3dc00319485fef9a945a8eb1f2e01b77dc195bae84d33935a330f6fd2de917cf31706dd8a4f45e822c38774fd6c5f13460878452290e38308f503e702a3f2061e9f8bae2f649016892e2788296c5c8351f997df219c58cb430cce865561cb2be12061f024f3aedb25dc76c4c509c210e2a65b6397e73cb358e1c94a4301071ffb2e8a8d24d633f9a2d966ba69c93e4c1ebaaaa1042e357dd8cfe47155d617bf1f4bb65e8c426c45645c8f125b17e55bf0cbdf3373771f38f505de9c8e96f464123fd618c40585071dff932f73160f978a2fd80662cfbfe1ccede7333c40a5872f7cb0dec717913173721335b4636566b57c0e69954372b9b6096591a275227a3b0644c889c061bee0021aaf3020c40d42ffda6a7c35226d39b03956cfc69d855d17fe71682a1a71d9d9247fddcad7c3a2aa9aedb93f5ad46f320abc83e861cef10c6f43e5b33ecd8d178ac1d30518e91c66ac37c7eee36859ca4e78c80c8f2443130dba1e6123db6d9c655c5447c9af01561400d5421ea62f965530465944a140e3e7b6570d55fbd8bd1130d9cf4f39f28dc8e7574a495582fb6ad683b44ec61a6cf675645bf9d244305e2c10f5f4c168443449292a2a7ad73c3439e3a8dd466d4a894e8d1f61ff7ca4fad563b46a4e315ef5c302989860956ac7b69653fdf20c4fb91703cc67cedf260a1baab3e96a70f77b5e211974b1782cdad6dd06b8e622636ae2b7763a9933400dba661a317bcbdf10671aab17e54ac546385ca77f0dd26ce82ef8200e2169fd8bc1b6c44da960d2877e13d1cdb2ca56b2d524cb6ccc4b467f4afcb15bca9e007233c6bc0e1f0967263486400cc3082efc0c98546fd01e463bfa319043dc7a06ced61f41e1a92b505a7abbfa8ba27467abae6c2fed5afd8a9393d99cfadac1147f35b02795391b42f1b51d062900cef9edd66aaeb465ddd9f255383d100a66e934d3e7912020a30ebc1f3453cf314446450769f0db095f52f83158d6e3b5671ddf75cefa055197d0a20aa8d194a90a95b2e4ac8b3185a019d1db8a554dadebc68a79f2ab7d58c867fdbea18586d792798e5246466ab280d60f71f227e15e55e99666ca8f5c6931078979100e79d6a3345e777b759bf57950e73bae10c032a2622ff88aed156c23d1e0b3d4eef0f051c7f9f9fa37eb0698f4fe18af0edb708a22e13d0a09183d28aac6956e7ef9599526d78656d050a65a9de0d18510e7beb3f731387b13df7dbb063867f5d256682ddc9a6edde3282d82cd24e6ecaa94b4dc7456c82019126bf960171d341d42d391be084103c64ca7a1851772ebeb4a07d36f6196fecfa28e9a06b7b0bdf4f972cfada80beb5143b66f0d4f5aeb6d4d81e6a380d703df8c8bc4253da2cc25c8bd343b01cd09f0c7dc22d8b49c53d7dc546bbf40f208b6a77390717a84247bae6c5cf6fa7d736d730a8c54fe7cf431410888e59ee6e4a7a3342a11aa2de148a984c3157b61f2da2b97a67140a669445737aff26af4f419694db51d12d12119791c8587849d967dd1791b99b9ed038249732dff683b46ad785f61a47e1dc7f3a695b9ff16919286f049be27ab4eb7e965e7e250aaf97836cb8cd00ec6f93ff0aa0947c60a1319ccc061341023c2dc8d6405ff5d8bfbf3276a08d053c98b21e87fc5679b8b527b508fe4ddec17c0d3c74b48320192d8e65c8abb8f0c21cbfbf3ea9641b9082a5b0e0dee9a7301b1e898eb720b9d1909c69099d3c51315691655abc8e3a1b0b4f319f7fbcbdc1a9341baaad86f1da73935bb9dc135e2d66f9cf9d8f9954a48ffe5a189ee6dfc1295432c5f41165bfd3efeb559a64e08c7cd24f1b0b3219647402b88377678e6f7c6873089640d5f02fe3c5a18a89769d2b40b87a81727e511716c444007df61eeb0eda871a0579206dacfbc9c0d36028a8dcaa2dbf09a53c73c840cdb56a714a21a5fea431cfe37d07b7fa3bc8bd50789e92e423b9064a0fb5a6857ace86ac281f0ba50bf9d3834e2d05cfa9e7321c075ba2c541463c2b6821dc50d46ccb1d6cdec5e27388ffac895b9e5f731fafeaa0f9f0989c99dd24fdf11a9cf549f56efb0dd72f0d3d64cd4d20145da71362e26cab9712fd96b9c8a0cb2f99011de58cc011f91992a4b26e31114f2ed7028954009601eb308daf28314a498bf5b485b55c6f1d95c589165fe30e3c1e0998012aae066f94e5cfdbf58d57bf5ee454909d66ad47d5ccbb2334213d2b65ecb33dc9ebecd42d3e3e8ea3c30fe9cf75da19f8395da8a6a5064065fef28d2c98d61d8e8eea6c0ee58bf87d8241432c251e852e1ffcae9e6365ced25b02fb82a4d6429b261ce61271ec13fd996668d4f9d8aa7782f3fbbb5c20205a34c2509462ea5f0698aaf87d499543541537a8dd40f2b8e9a21fa3c41f02b52d20a10569e24c59b0d602fee03be7a534f8d44c9d714b309ba7b0d93708c78bb3f6c1d05dcc8a19a14c26c63c0aa9ae98df8d6bc09c2964807a6cf2826460bf1c73ebc4a60a418d79833133ed2b488134771604e56e033757ffac6584574f3c8416aae0187a8175e5ee287f66ab01e761beeb910b58d5f90b708e1ea9a62d6224ff3d4e723cf6671b44fa79806db59063ed88a0a5f23afe4668c24a3135069b23c7807386842567d5bd2a19add05918eb07995e9191f3dccc69f756867d82bfb6883eb7",
                ]
            ]

        ];
        return $criteria;
    }
    public function bothTloAndPiplTestInput()
    {
        $criteria = [
            [
                "tloxp"=>[
                    "api"=>"tloxp",
                    "name" => "Rob Douglas",
                    "city" => "Oyster Bay",
                    "state" => "NY"
                ], 
                "pipl"=>[
                    "api"=>"pipl",
                    "name" => "Rob Douglas",
                    "city" => "Oyster Bay",
                    "state" => "NY"
                ], 
            ],
        ];
        return $criteria;
    }
    public function emptyCriteriaInput()
    {
        return [];
    }
    public function tlo_final_result()
    {
        $expected = $this->tlo_response();
        $final =[];
        $final[] = $expected ;
        return $final ;
    }
    public function Pipl_final_result ()
    {
        $expected = $this->pipl_response();
        $final =[];
        $final[] = $expected ;
        return $final ;
    }

    public function tlo_response()
    {
        $expected = new OutputModel;
        $expected ->report_id = "HPRG-MY3D";
        $expected->link = "tloxp";
        $expected->source = "Tloxp";
        $expected->first_name = "Robert";
        $expected->last_name = "Douglas";
        $expected->full_name = "Robert Douglas";
        $expected->location = "New York, NY";
        $expected->address = "333 E 49th St Apt 2r, New York, NY";
        $expected->street = "333 E 49th St Apt 2r";
        $expected->city = "New York";
        $expected->state = "NY";
        $expected->zip = "10017";
        $expected->age = "";
        $expected->other_names = [
            [
                "first_name" => "Rob",
                "middle_name" => "",
                "last_name" => "Douglas",
                "full_name" => "Rob Douglas"
            ]
        ]; 
        $expected->phones = [5162205847];
        $expected->emails = [
            "romado12187@aol.com",
            "rdouglas2@yahoo.com",
            "rob.douglas@hotmail.com",
           "roamdo12187@aol.com"
        ] ;
        $expected->relatives = [
            [
                "fn" => "Barry",
                "mn" => "K",
                "ln" => "Douglas"
            ],
            [
                "fn" => "Kim",
                "mn" => "K",
                "ln" => "Douglas"
            ],
            [
                "fn" => "Lauren",
                "mn" => "B",
                "ln" => "Douglas"
            ],
            [
                "fn" => "Marc",
                "mn" => "Franklin",
                "ln" => "Douglas"
            ]
        ];

        $expected->addresses = [
            [
                "address" => "92 Sunken Orchard Ln",
                "city" => "Oyster Bay",
                "state" => "NY",
                "location" => "New York, NY",
                "zip" => "11771",
                "street" => "92 Sunken Orchard Ln, New York, NY"
            ]
        ];
        $expected = new \ArrayObject([$expected]);
        return $expected;
    }
    public function pipl_response()
    {
        $expected = new OutputModel;
        $expected->report_id = "e00a2eed1c4678a52ebfa56a4fe81dc588b551f33f83a98035b9e5a6fcaf5500d12f45fa33d2dbf11b0c5eda6ce9f8de8a1d300ef9521ab7489ebb5a9280d755b02a3620dfcbcd33d01445b3c68258e8a946293329452f2e65c3747b077d4f5e0983fac8785fb440c6f9043565d1b9e704a18fe612de2a01746ef9430113b3944dc8b4c605eb86ebf50e54ac2b2a7f2f59a8a2b82e15ec9a4e3f517934df2459c11b05e1660885c019e45a5daee9360a2e5aeac8aaad39441cc2f67414fd7ff498d5017f43d4cc80942c78dd894ac75b677059593ed4f4270f5064aabbb82aed7c7e42b0e878d13104b80e01d5f7c71a43f6c2dd3fb242bd872df461d9427a3cd5bf56364cf55e0f0f7cc2181f2faf4692c37d97b1b351a61497017f2f051fb5d98758821057af6b48daa2b67335ace30567ae3ad534c3e30501e808500f813dab1aa064a1a7709a9f2d30dfcc6e1288c3168a2a5ab340040d237f97d36a1de70324527e6130b9216eff138d873c1ab85204812b64ead53b35f5617e3a6d362b6ee77c012eaec1fda66fd5146b112d5156c7d93c98c92aabc51219c499c6e86bc9ce389d089186cce2db774b235af0d48032607a0d5e76fdce2e6fc150991d73d9470be43fc0b43d600f3f552de2cfa9125cc5b51bad6909da16886e9cd9d77a95b5e25c36a254093ec0f21e218be58a2012cd0cc7c082f6379ace043f288e335d6c17cee3698bea5ead876a82c6abeeeec8a72aeff3f6e3b08caf57051d00243d4d1e70dc8fad5306b32be609dce14b241345dac27b152ef0d399e33baf98ea0b9051641ad2e8f568b240f71c1e6baf7ca15a33212d0b56670a221046b4902c7dc64555aeb666399d2080ee385c590bcf2d483513af166fe5ed9d3ffd6c9a7106f0f6b53eeb6f72dd87c351f5ff17311af74693ce667722eaacbd8041bae40ca9fce5c2063c29606b49ad0957de8d4bc168e3a09709775d5064d62e83686c565164cacd0414051608c5d32d20fa6807adfc6755ca8cd5a3848acc55b7638b22de89e22a9b5cdc58cd29af4e2ebf0797d3230733752927b5e5c1db1bdae285db7361d6dca9028ff91a292ae6c156253501cd5b3d7271eebd3314b5626e67df86ccdc9b0e7a7c0c840c7a8ca46af5d8d750320564800b060d20b484f8cd0971af19ac4c25a072f2fa869e152fc002e5b1dba585b14531d54b2fccfb72807f29f9ebf4af3875de8723a47c718665bbda5d3ab2fc5fee40e96dd72fedcb3d8ccb417afba2f39d536621ce41028a55e1ec717dd20e5d6ff97f89b7f010841c1f1b3e5d992216c5964f65aacd480d0a8a0e96472e60d6ff16ddb0a52fa0209a3897e383265b8a46e843e89a8e9bf4b89010d735359a27aa05a8d3436f446fe6afa330d6ebf0b356c58c5a332e0f8e34b0e4abcbfacda312e9d95462a32da22d275bf8fa00b8e35031a9b30e0da5dabb627a29d591466c875aa5a74906c9858c88646132e65d2370ba96c088da4aa2ae4e1bfa7a0d726a727b1ec1c485ad7aa9b2615b76653a970cf98fd59c62cfc85d43ccaeb25a8ca2d399321c86eeb4eaa9cedfba33ad03dbdfdc7e14625613793a4644fe9c53b8438c8597beca3aa713725637ea8f24ba173bc55efe3450ef82a4f2fb0b962126d4b380c3a6e2ee3d3f25bb2535168618ea9989d87bd95625f277b1f5d616003ac921dd2e4322e7f51c37b36a4247a39b53729ea4a118e8e2fedb944c1023c4e52f502206183d3ddfff05f0f3bbe1a2f8a3d3572623dd49b73ae0aae101d00831fc32863ac755cc8dc51ca5d5603301bbec866e9eb60df4d0a31275418e872e047d420d8789a3aba701fa1bebbd62961826bab76247d46307f4672202b6b6895d8a7a80091dc9273e3f1282e4560055578cd26ec3ed57142afa57065b02815a13be3d718acc775d2cf7bc122f84ad91c49b9bbe35960c78acc49e4a988ee38d3f5fb998797f45d912722bf2731b3ca97be45da925487a8f03e47abe86ec9febd54d8755e65dbab5e2024dfd2b9391eda3a0ec53cd565aac921209afae62261aa38ba5cc67df60b30cdaed32bc9a38f07302d76e88895c00e9df2287703f038c342c4f1d5ca0b3b742b3118aca49d5a2a1b272e871309ba34f01762363553a1aef605f40f04cb76179487373ac21f73136a63ed69032cc525f0ac26397b1553d7fa4a8276829283faac5ac74f5771dff9aee14fd0186d71b7fb003ff964ddecfbb65410fe83514f5b970df5faebbf9e5fc38349ad060e05ab8b0289f9dae78b1ed4e36f945c633d9026087ef5a4eafe4439ecd4a40eaa7c5b764d7f459ef9b109bbef5a02ff6c1b6671a638f0a81842d5913668fcafbcd27e57491b5158a3076de3b1ff14ae1eb702c71702e3f2ee9fec871bd0dc1300cb63aed1b20965d7b0c2857b673a90848cad10dde52055f5e8709e9418a25251a6f6f77f86d1e5b0b9e18895afce8a27eb93e8043600fbad0d281a8ada2c6a6b9589b84b20cc41f9db300b5dffe0cf9eac677bd6a764687b7e3676ee2f3e340211b9259465e3de667d7a83faea301a71856b2d110eeb94bc0d69a2f6db68ed6fbab7bc7c618e2fe032ed80539c2e338ace9df7e575b2e52de144d396573bb2fa0f6b5fc68ec94091d9b9466ba3638b62afc1c9d22d417e528913b9a51bbbc33aa6418f4d5a4d8d8fdb826a37d113d4f35b18f290d09fc44d3d20b6543866d3c1b17b97fce1463c59daea6b4bf8934ca211f4a14110d8ea3e55230d1586e63b7d958ceff69552f53ded463758b11377a5d494077925fd9e90380d1b2d7ca072e414eab4499f04fa7af8c907d2caa80ac088214646daee779cda4e0030d5c103f59a83ac84f11bdc4ab05914370f97c58226f3cf8d3e195287413c3b9cc2a44e13346766485c5f139a8f7d04fc8165ead376e3c9376b3cda0bcb05da919146c5a915dcfba64332a439b2e1646403bd50d7d1c03c1ef1437e2e8f51577ea294f3fedf9c6868d1624499583d9930967803a710ea98f81fe55e110a25d32b796112b26f32c07025b6447dbe3cbec854d05deaba8c606cb4c3a5610cc0b0949986ef9998c414fbde8bd1780bf9b3998bd2162eeba9a1ce5a8734c7716543577549cc83781f29f2dec8fa1bdea42674145f566ddc61eae1cded3a0e35103d84dbdd53f4297cc9df71f5e743d49b65cabf15fec9503e1e36602627a3fa6edac24713c89366b0ec2f784358e3441f90980b6f9d5bcf3973448466ab451e44a5919364ff74a130db21319adc90762c4c7302d1f574aff4f0d2caeeb04f3e8d6a1d68f79a8c402fba92fd463fd93022bb32c7ea1e42dea4fd74584c0cb78f85bbe86e85464296127ea1fd873a74c11643b725a090f24785c69b0e3981e8e7f70c78b2bac7d7f8ccff5aa7b8a710ad60c0beb879d650040163bf23c579d63d831bd898cb68453e2fdbd0549e0efdc76efb459f42332c355e65d2eed9cc10a446b8fdb2d0581675ae36cf9dc78647f44b9310eac147a72e5cde75e37fe84ed31e9f6a05458266dcff495e5b6a5a7b9ff9bdb8c333d4b01992484358e55ab5dde35650fa6e8270b62912c71e4598d519dc5bcc883c7ab3170555f8893dbc5546503bce69821341bc97a4d82cbd50bbe07ab5054495298143c77d7d6bbc5617303dec87ac09b7559127ce88af8bda851b331000a616ecb5cfef141b9da9bb8fc42afba058d4379b5648c6b5c747501beb1f27ac38a6480cbbd4016a3981e9d1c5745f4378ec5c52db8b07efa84f64600dd74c11314c4b5d7440b51493a4832a1519406570bc346fd84306c8e776eae06915520bf30b25b08cb524e76839727be4e4d14b46730799d5ba3c833162f7dfb92db0e84c0067a32307f8c5e5bf17f03f5c99bafb38294d69d4f19f73c769c6b549b2129d4403201f51edd203c2e821a35634e94b205f01fa1b91f103492472557a9389b5edb6c74784d8327d9c4bc0e102cd318ea6b8812b0a30b6a4130f7f0c0a0a85bec42eaa53d7c310f86f4e9a4eb1d023d7a7a5294e4db8c4bc4ddafc5e1d50792ae4fe47a779e03823ee63d9eb1d6d3e2a2062790df40c6334f378e631fd4a7135d4cca13260dadfe33803197c4ffac1d4de211ef305d7076f7e2a4c10abc8248e19c897145a0378ce976de6e1af3e4c3f06b8b3fa009b29f48bbdcd44fb1090c4116b7789a711a943acc496898a7add6cddbac5130cf2525fb4325b27b4a960a6d54edfaddf5d8b2ffc60a6268c4d262f878d1b38b443c54a5474b0858db9e42211a5c19c81e4c36b299c1183f8fff2a9cdaa75a9096d960f5ad0692dceea6885e05f8f4e70cb0f893b33df61d2576a1ae2a0bd529ffb435e439d0ba2b75ec250c35b6dd5d4022b8726ad6e171b86559c1fb72691d940f7b95548835f6112e3dc00319485fef9a945a8eb1f2e01b77dc195bae84d33935a330f6fd2de917cf31706dd8a4f45e822c38774fd6c5f13460878452290e38308f503e702a3f2061e9f8bae2f649016892e2788296c5c8351f997df219c58cb430cce865561cb2be12061f024f3aedb25dc76c4c509c210e2a65b6397e73cb358e1c94a4301071ffb2e8a8d24d633f9a2d966ba69c93e4c1ebaaaa1042e357dd8cfe47155d617bf1f4bb65e8c426c45645c8f125b17e55bf0cbdf3373771f38f505de9c8e96f464123fd618c40585071dff932f73160f978a2fd80662cfbfe1ccede7333c40a5872f7cb0dec717913173721335b4636566b57c0e69954372b9b6096591a275227a3b0644c889c061bee0021aaf3020c40d42ffda6a7c35226d39b03956cfc69d855d17fe71682a1a71d9d9247fddcad7c3a2aa9aedb93f5ad46f320abc83e861cef10c6f43e5b33ecd8d178ac1d30518e91c66ac37c7eee36859ca4e78c80c8f2443130dba1e6123db6d9c655c5447c9af01561400d5421ea62f965530465944a140e3e7b6570d55fbd8bd1130d9cf4f39f28dc8e7574a495582fb6ad683b44ec61a6cf675645bf9d244305e2c10f5f4c168443449292a2a7ad73c3439e3a8dd466d4a894e8d1f61ff7ca4fad563b46a4e315ef5c302989860956ac7b69653fdf20c4fb91703cc67cedf260a1baab3e96a70f77b5e211974b1782cdad6dd06b8e622636ae2b7763a9933400dba661a317bcbdf10671aab17e54ac546385ca77f0dd26ce82ef8200e2169fd8bc1b6c44da960d2877e13d1cdb2ca56b2d524cb6ccc4b467f4afcb15bca9e007233c6bc0e1f0967263486400cc3082efc0c98546fd01e463bfa319043dc7a06ced61f41e1a92b505a7abbfa8ba27467abae6c2fed5afd8a9393d99cfadac1147f35b02795391b42f1b51d062900cef9edd66aaeb465ddd9f255383d100a66e934d3e7912020a30ebc1f3453cf314446450769f0db095f52f83158d6e3b5671ddf75cefa055197d0a20aa8d194a90a95b2e4ac8b3185a019d1db8a554dadebc68a79f2ab7d58c867fdbea18586d792798e5246466ab280d60f71f227e15e55e99666ca8f5c6931078979100e79d6a3345e777b759bf57950e73bae10c032a2622ff88aed156c23d1e0b3d4eef0f051c7f9f9fa37eb0698f4fe18af0edb708a22e13d0a09183d28aac6956e7ef9599526d78656d050a65a9de0d18510e7beb3f731387b13df7dbb063867f5d256682ddc9a6edde3282d82cd24e6ecaa94b4dc7456c82019126bf960171d341d42d391be084103c64ca7a1851772ebeb4a07d36f6196fecfa28e9a06b7b0bdf4f972cfada80beb5143b66f0d4f5aeb6d4d81e6a380d703df8c8bc4253da2cc25c8bd343b01cd09f0c7dc22d8b49c53d7dc546bbf40f208b6a77390717a84247bae6c5cf6fa7d736d730a8c54fe7cf431410888e59ee6e4a7a3342a11aa2de148a984c3157b61f2da2b97a67140a669445737aff26af4f419694db51d12d12119791c8587849d967dd1791b99b9ed038249732dff683b46ad785f61a47e1dc7f3a695b9ff16919286f049be27ab4eb7e965e7e250aaf97836cb8cd00ec6f93ff0aa0947c60a1319ccc061341023c2dc8d6405ff5d8bfbf3276a08d053c98b21e87fc5679b8b527b508fe4ddec17c0d3c74b48320192d8e65c8abb8f0c21cbfbf3ea9641b9082a5b0e0dee9a7301b1e898eb720b9d1909c69099d3c51315691655abc8e3a1b0b4f319f7fbcbdc1a9341baaad86f1da73935bb9dc135e2d66f9cf9d8f9954a48ffe5a189ee6dfc1295432c5f41165bfd3efeb559a64e08c7cd24f1b0b3219647402b88377678e6f7c6873089640d5f02fe3c5a18a89769d2b40b87a81727e511716c444007df61eeb0eda871a0579206dacfbc9c0d36028a8dcaa2dbf09a53c73c840cdb56a714a21a5fea431cfe37d07b7fa3bc8bd50789e92e423b9064a0fb5a6857ace86ac281f0ba50bf9d3834e2d05cfa9e7321c075ba2c541463c2b6821dc50d46ccb1d6cdec5e27388ffac895b9e5f731fafeaa0f9f0989c99dd24fdf11a9cf549f56efb0dd72f0d3d64cd4d20145da71362e26cab9712fd96b9c8a0cb2f99011de58cc011f91992a4b26e31114f2ed7028954009601eb308daf28314a498bf5b485b55c6f1d95c589165fe30e3c1e0998012aae066f94e5cfdbf58d57bf5ee454909d66ad47d5ccbb2334213d2b65ecb33dc9ebecd42d3e3e8ea3c30fe9cf75da19f8395da8a6a5064065fef28d2c98d61d8e8eea6c0ee58bf87d8241432c251e852e1ffcae9e6365ced25b02fb82a4d6429b261ce61271ec13fd996668d4f9d8aa7782f3fbbb5c20205a34c2509462ea5f0698aaf87d499543541537a8dd40f2b8e9a21fa3c41f02b52d20a10569e24c59b0d602fee03be7a534f8d44c9d714b309ba7b0d93708c78bb3f6c1d05dcc8a19a14c26c63c0aa9ae98df8d6bc09c2964807a6cf2826460bf1c73ebc4a60a418d79833133ed2b488134771604e56e033757ffac6584574f3c8416aae0187a8175e5ee287f66ab01e761beeb910b58d5f90b708e1ea9a62d6224ff3d4e723cf6671b44fa79806db59063ed88a0a5f23afe4668c24a3135069b23c7807386842567d5bd2a19add05918eb07995e9191f3dccc69f756867d82bfb6883eb7";
        $expected->gender = "male";
        $expected->link = "Pipl.api";
        $expected->source = "-";
        $expected->first_name = "Rob";
        $expected->last_name = "Douglas";
        $expected->full_name = "Rob Douglas";
        $expected->location = "Oyster Bay, NY";
        $expected->street = "92 Sunken Orchard Lane, Oyster Bay, NY";
        $expected->address = "92 Sunken Orchard Lane";
        $expected->city = "Oyster Bay";
        $expected->state = "NY";
        $expected->zip = '11771';
        $expected->other_names = [
            [
                "first_name" => "Robert",
                "middle_name" => "",
                "last_name" => "Douglas",
                 "full_name" => "Robert Douglas"
            ]
        ];
        $expected->phones = [
            "8179256254",
            "5169220464",
            "5169220465",
            "5162205847",
            "5088681597",
            "7132008155",
        ];
        $expected->emails = [
            "robert.m.douglas@vanderbilt.edu",
            "romado12187@aol.com",
            "rob.douglas@teamsalessupport.com",
            "rdouglas2@yahoo.com",
            "rob.douglas@hotmail.com",
            "robertmdouglas@gmail.com",
        ];
        $expected->addresses =[
            [
                "address" => "",
                "city" => "New York",
                "state" => "NY",
                "location" => "New York, NY",
                "street" => "New York, NY",
                "zip" => ""
            ],[
                "address" => "29 Dunham Avenue",
                "city" => "Vineyard Haven",
                "state" => "MA",
                "location" => "Vineyard Haven, MA",
                "street" => "29 Dunham Avenue, Vineyard Haven, MA",
                "zip" =>"02568"
            ],[
                "address" => "1700 Pacific Avenue",
                "city" => "Dallas",
                "state" => "TX",
                "location" => "Dallas, TX",
                "street" => "1700 Pacific Avenue, Dallas, TX",
                "zip" => "75201"
            ]
        ];
        $expected->usernames =[
            "robdouglas",
            "rob.douglas.7923",
            "romado12187"

        ];
        $expected->companies = [
            "Co-Founder and CTO - Skopenow",
            "CEO and Co-Founder - Skopenow",
            "Co-Founder - Inertia LLC",
            "Co-Fouder - Inertia LLC",
            "Technical Representative - Microsoft",
            "CEO and Founder - OOSTABOO",
            "Product Development / Video Production - Griffin Technology",
            "Director/Editor - Sony Pictures Entertainment",
            "Video Production - Sony Pictures Entertainment",
            "Buffett Senior Healthcare Corp.",
            "vanderbilt university",
        ];
        $expected->educations = [
            "Obhs",
            "Vanderbilt University",
            "Oyster Bay High School"
        ];
        $expected->images = [
            "https://media.licdn.com/mpr/mpr/shrinknp_200_200/AAEAAQAAAAAAAAhyAAAAJDliMGU0ZDcxLWU5ODYtNGI2Yi05NjFkLWUzYjdjMDlhZDVkNg.jpg",
            "http://graph.facebook.com/4713141/picture?type=large",
            "https://s-media-cache-ak0.pinimg.com/avatars/robdouglas7923_1391109566_140.jpg",
            "http://www.stickam.com/images/ver1/asset/media/default_live.jpg",
        ];
        $expected->profiles = [
            [
                "domain" => "linkedin.com",
                "url" => 'http://www.linkedin.com/in/robdouglas'
            ],
            [
                "domain" => "facebook.com",
                "url" => "http://www.facebook.com/people/_/4713141"
            ],
            [
                "domain" => "pinterest.com",
                "url" => "http://pinterest.com/robdouglas7923/"
            ],
            [
                "domain" => "twitter.com",
                "url" => "http://www.twitter.com/romado12187",
            ],
            [
                "domain" => "whitepages.plus",
                "url" => "https://whitepages.plus/n/Robert_Douglas/Oyster_bay_NY/98c10b6dad911f953a4d9d730a55d8ee"
            ],
            [
                "domain"=> "cqcounter.com",
                "url" => "http://cqcounter.com/whois/domain/robmdouglas.com.html"
            ],
            [
                "domain" => "dawhois.com",
                "url" => "http://dawhois.com/domain/robmdouglas.com.html"
            ],
            [
                "domain" => "linkedin.com",
                "url" => "http://www.linkedin.com/in/robdouglas"
            ],
            [
                "domain" => "whitepages.plus",
                "url" => "https://whitepages.plus/n/Rob_Douglas/Oyster_bay_NY/9a3776a071f15162087eda24ea60f65b"
            ],
            [
                "domain" => "whitepages.plus",
                "url" => "https://whitepages.plus/n/Rob_Douglas/_/0506dc58b83b451b8840fc554ff41b83"
            ],
            [
                "domain" =>"whitepages.plus",
                "url" =>"https://whitepages.plus/n/Robert_Douglas/Oyster_bay_NY/764e197a47c529302a5097174b307965"
            ],
            [
                "domain" => "whitepages.plus",
                "url" => "https://whitepages.plus/n/Robert_Douglas/Oyster_bay_NY/323a15a2b3ba939404cd09e499ffd3e2"
            ],
            [
                "domain" => "facebook.com",
                "url" => "http://www.facebook.com/rob.douglas.7923"
            ],
            [
                "domain" => "skopenow.com",
                "url" => "http://skopenow.com",
            ],
        ]; 
        $expected = new \ArrayObject([$expected]);
        return $expected;
    }
    public function SoapResponseMockForTlo()
    {
        return $this->getFile("tlo");
    }
    public function curlResponseMockForPipl()
    {
        return $this->getFile("pipl");
    }
    public function getFile($param)
    {
        $dir = base_path()."/packages/skopenow/peopledata/src/Sources/output.txt";
        $file = File::get($dir);
        $result = json_decode($file ,true);
        return $result[$param];
    }
    public function piplAccount()
    {
        $account = App\Models\ApiAccount::find(30);
        return $account;
    }
    public function tloAccount()
    {
        $account = App\Models\ApiAccount::find(31);
        return $account;
    }




    /**
    * 
    
    public function test_prepare_criteria()
    {
        $people_data = new EntryPoint;
        $actual = $people_data->prepareCriteria(["api"=>"pipl", "name"=>"Rob Douglas", "city"=>"Oyster Bay","state"=>"NY", "address"=>"333 E 49th St Apt 2r, New York, NY", "email"=>"romado12187@aol.com", "username"=>"romado12187", "age"=>1966, "company"=>"Co-Founder and CTO - Skopenow", "school"=>"Obhs", "report_id"=>"xxx"]);
        $expected = new Criteria;
        $expected->api = "pipl";
        $expected->full_name = "Rob Douglas";
        $expected->city = "Oyster Bay";
        $expected->state = "NY";
        $expected->address = "333 E 49th St Apt 2r, New York, NY";
        $expected->email = "romado12187@aol.com";
        $expected->username = "romado12187";
        $expected->age = "1966";
        $expected->company = "Co-Founder and CTO - Skopenow";
        $expected->school = "Obhs";
        $expected->report_id = "xxx";

        $this->assertEquals($expected, $actual);
    }
    public function test_prepare_criteria_with_false_input()
    {
        $this->expectException(Exception::class);
        $people_data = new EntryPoint;
        $actual = $people_data->prepareCriteria(["false_input"=>5]);
        $expected = new Criteria;
        $expected->false_input = 5;
        $this->assertEquals($expected, $actual);
    }
    public function test_search_with_wrong_api()
    {
        $this->expectException(Exception::class);
        $people_data = new EntryPoint;
        $actual = $people_data->search(["api"=>"xxx","name"=>"Rob Douglas" , "city"=>"Oyster Bay" , "state"=>"NY"]);  
    }
   public function test_result_from_pipl_input()
   {
        $people_data = new EntryPoint;
        $actual = $people_data->search(["api"=>"pipl","name"=>"Rob Douglas" , "city"=>"Oyster Bay" , "state"=>"NY"]);        
        $expected = $this->pipl_result_model();
        // print_r($actual);die;
        $this->assertEquals($expected, $actual);
   }
   public function test_result_from_tlo_input()
   {
        $people_data = new EntryPoint;
        $actual = $people_data->search(["api"=>"tlo","name"=>"Rob Douglas" , "city"=>"Oyster Bay" , "state"=>"NY"]);
        $expected = $this->tloxp_result_model();
        $this->assertEquals($expected, $actual);
   }
   public function pipl_result_model ()
   {
           $expected = new OutputModel;
        $expected->report_id = "4003b15ce817a29b2e8d51235aae628a6d59de5e241b8730472b47721f82c08c65b5f9acd27bce76d9f15e738541cfede7bc3e1cb8f7f372ee4a167e1d319887ea06c407fb3fbfdc68ab5281d5935396a8a56961016b3b786519398d1bf486c5acc833f135cbd577177db354bfec6900b3aad8634fcc5aa82c2c5db70550ba29b864a99bd171c57ca568a2c3c48058c6fc211a3ae2515dc8539f53d3b12bb20bff4eff93788b93b7c61da6b83dcb399c7cfaae5a236abb44a833969c32e775e90c8eb9f71de5e52bb08922288b84612d1d965a5d8618020fdf3fd2c55af636410af4bd4a99f6f6cea44d42cd09dbacac31d320f13c8eebcfbc6588afbc6bf9b5a0281ee97d9f6b5e6f8069b9cab369ab06aa88e9baf5f61d5ff7b4dfe4e914bd02fbe80dad7b6b0ed544ddd1f2a48ce5cc2ad5e939770286111f9ad9f990645bf1d0c293fad5da5339dc3d3c0a9c7c31aa66c56ea1f8f0e36e02ce2e69ee8018f8d84c43fded12e9180f79e28726f224fdc6f430c35a2b587b1645b3444eba85f099fe650734f6ba40437fd105c568d6cd64ebef50878347a7a9e674bc0a8412d0dad09710b8786cc590839a0ccecb49823572d4919c1763ad3477c211dfca690dfb6de5dec058ce67f70ba3165b1d34da7bd94cf7e1b9db82a61668d0ce81a0c88aa33c9a7bcd227a091ca775f25498176d2e8f86718ae6083c0949f32a28febd80431e96fb2518dea6775f62999da5a119903f6ac0b120fab8b3033f718e95da8aeab80d32060a102d8661e7526ba2b4212e5bad5d6ceda0086677e1541b68f442834cf5d7e0d59dec626742cbb736f6aa72a1a3bcd73f3429a5e81113da7efe4142dfde8864e1f4a0a417acb60d69d61db35ffdf39d383c67a97ef697a23e4cce3db7b87a23b0efb7e696e8e896de3a95d1194633203813669b7b1bf9ed8384d186947891d5d8b594c009cb49759346e89789c2f86723b13e546b875a9142b5083a4ae9685bdadc42c14bf6633aa86417fccd1348baf8de3e2ff74705907799bb677a1f049e7c58180e87f8f11a09cef13bf0dc5fd88f27cc7b0147d469038e592436160999716bc45c6d1d3afcebcd5966e40ee961697e36c1aae2976ff0e7097c4558b21f76b070a9227dae4a293f2ae141eeafde956bb5b3e3c85454cd0b5712a27c1debca6dbe039fb55148ad4bbb34bb96dbd6f9f3240f1733ed70c9f633e3ed61182aff8752d7696441e72f3e847fc3008ffb7e4e42058a6b26fa98ed946213fdeb19005ac627070a2b93601d3ad4db5bf3389df9d1687c96de0abed474082d8b090755ca90e2257bf9ae1e382e101071669a80ec8bf6efd0432c6395584bd4bda842b668335649b98056c20b685eb64e96baf805b990728da48d81b2ee12f7c0ae90817754f7f703978eb8c9500ccee1d3318e9969a74c8a8cf80f8d0a49ac7bc0050fb148f74ee4a3dd4557f722a4a657d8a9538225b5d8d4cbb7c5cf96daa26cd95cc208ed510a514ce44469db0271c84d533030f1cdbc88032ff78f417daf8c21be3196e6bcc8192d8816eb650efb7cd945854056a64eadd89dc7b3a30783a49fc6f5bd84ea137e9f45ba39a3c9887bd04a89f4fb5a6a27f0b027ccdb27204441a9c22a289a8b413bbed806f048b452ad5ad1f8cafcbaaedaafcc3268204c16dda7f00c99a7ed64371cfdaf19db7568fd04b89a8dc2ceda9b1576be4b6f307bfcbf1edc961a60e0e8a5f6a6aeefdf8c3fd948bb8bada034823796dcb3f2c0fbcfc9a479977dd428f110496a78f311d1434584818cf220c1b8d36b5b58955a2630638b771b9d34e4581ed9dd097dac4388cf58b910dc92dd7c8ecdd7b678ee3e41652b14031a2b949d380a81ae2ea77815c8b5e1e853efc35a734f41104e7fc5d403c5ddc6a5b2b2b6e6dd42676b752433cded61ac0c8588b0977a860f1ecb3d502ceb9c968fbf3447fd3ccf5ece5e0a14a8e1ffc6b8e2d165a96f1838ca1a6515c76833b21cc0be45aebbe04c0d7acf0ba55a51fd40ba5f6265fa3926da69209e2015c72d35d1f46e6103fe578c90aabaa65b0b4a691621d323c29a4fa00897c9389f5b1bbf05f56c23c4cd54f94a6be8a8fc0ff801525cf835e30660cf8d968c60e8e358b0f571480264250a59f7f56930fb6eae786839bae3cdd59deb320fe0cdfad55d8a9e214e51b99198a6e28eb9656d4e1250d1557f300d8dd9417055663a8bc7b973167aa7a2acfbce97222fd7e6c857643b324555f4a6f80b1efcb204b1d7519ba8fc6698a32c86139f909c47785d768ff033b701ad4755f5ac09c6e4293188bed5c3b40133391721a3c94884de8b1ad8895c136bd9d996252522af181cd7e35bc52ac7dc59258bdfe50f6c8d8c8032859ef9ea613a4c21ae3bf52d503eb2ffb1eb9870668d41acb6d2d3a974a2af476bbbd996eefc623a79f3721967f8096bc44d90f649f409bf39949ead868f80ee87f624db5141a7153b65980dec2ee6d35e5698bef43443824a8d3f01a4ff96775f063ab9a19a848e7f02b06fe098753432481352199acfaf7c2ac64de652dce09d14ac3fd52cba8f466d6555b0793a493dddab8936593c1a04e99b9c4fbd4b4f8968fd1f922bde2cb56dcd07a344f0990dda694320afe6c5b1a45599854ac2f2b71542ddcec5df41a92253977881d8a3495c423433c568a70100185b1fa7b69fd990be27e43b12118abc0f143a3d9f8c2cac02e0732984addcf96bbfa85c7592e1c4ad3d9e79253ab5fee685cdcd8232cb91a3b615696f4ecf2ae187870e91eac7863bf04dfcfd63ef44cc3c49cce4fff37290467153cf8dc05f3a8250b4b685068a4b5ee8647a5d8175d93b7575ab2f3229085fa02d8492e8b8f685f8a88c9e693f4d3a44dbe417cc84b8f3197a40c2567b579e51429243d3d69f06049602d19efd2ae92d2bf841ab22071be55420a0b4c4df3f6582b3746cc764b59d0613043d163f5fb6cced3bb5d0466bd8c652f9a0bb582fc5dc89920a92e06b30cb183676cf2b480633b990a3de8dda3d1b1ce0a2906ee294a4f94d6dadf367fcbb214c3508299d0a90403ac657a57207efd23e41b7daf0496e0923765bdf0f5ad16e4076882a9613cd24dd4ea507f48a8507be05a4f0336944e054a70e8c57918bba96438e5e602a73934194bd6643b54071d444f0b08f36e94ff15c0376c7419f910001fa01a0a1362473b15d9fd8e2e525a3a0ff71a18bdef6ac57d09318d7f02449dd269aacc42db86ce85529094da10fbd994c236f30004a055fa170e3ba745039c2d4258444c0886c8c771050a28ad51a3900180dd59dc0825c89f1afaefda2f00d45f5d7e5ab8dca8537e46fd20a253ac5742b33267d801908883f2bb7b8e4fe5bedacf82492fb42a275553a958ef51e1393e78b3874732f412f9a038393ba21ccef7d2f644a1b0b8c3f05e8d1da4419ebacbeeb0eac513302c47be29330115c581416332afaa97b9caf398377198fe9d8ae190113669e025c5def758d37afc7359cfe8e1d758f12a6cf014a4545fc1e0303a8ae9308076c3a04a2efea5779c03a1223bb0d70ba819a3fe385ee44720ee5d1235a63dbbe12cc32f9774fb2cf5b8ce7749838766b6f55aaa22f4a418c78e5f218025868e547cc5c099f644c82706a359f2861dcffcd2ace976e147570c973ce8383e04e653b6c5207f052a476c0048d76d210c586433927291c202e2f4a8aae3f1e85039c3bf828f8dab65dbc74650734ef3e723a256780784bde39c83637e15fbd35658173ec22e61c9b5cc4aa19736cc82ff184595f9f4eadb53ef01ddfaddd67ad1bd76a27e151303e07a3039218c18dc29a999686d6508da696247fa9cb133141f2f97cc3bf7d56b6fc3779e70aa219dc3b27545d0072345abbd70dd23d4ac72bf7a7f4fef7f2d2199fc1647b363c653c91c6a0db08bb44f9deb103353a9c774363ee66faf7c34e32f2816180f3390c302d2e29a5413743c3282b0b143f965a97c4e4b97566f7c1b4d211a828976b25e68511dc1113e90d064ad3fbb99825149bc182e9d48ee16c54a5562a08f86cec8749b930136049a44b66e94876f6832853b22d7c043f99e39b9fd4d10f674911eb113f10631f8ed8776b310c56b23c2b5787276160a98cc9603eb25d535c473801ea3da1a59c8324af95261fb2b288e760e35b00c0d365ce80fadca36d9046540e3bc36b8a87630207eb1c384994654353e5a882f53f96c8a9f7d41abe4d18918aaf0c7255eea2cfa491719e7fba347d02e85079dd7d5d408cd5a3b27be5815cb273459b4cff2a289b98b1f7c53c6d94a9294e9822c1ec49f129f642dbe5e82f3cd5c806988448a613acf818c5a4d345427ef4a15150ea43b9d98201eb72d93e5f179601228ef542761590ffb9a8ece55e2fa493cfaa0c3b0b1be437e54f052afa286d0224604b2eeb3a406b6b63e5cd20d9f86cafa9c1a4d960341d2204d31c86e3c36759f19723ec16288e09766e2b2132d51af13bec3baa8d5dd2646ef53f4f77b22afca94ea91fe0d229ab45e8b12a31b370879e1377ee4618cf3bb2de5a920299f58645aa90616a0522b0648921bbf7b93b413a13b477ea8d3b95ac771a83edd8d679d6350924ca792f21dd75ff8d25fb749a20a9ed11e479bbab2ccb27bd9542a4b344181267ef1ccb88883382dee5fb6c514694a8fcd41ee13c5f551258f2ded0661445cedc9332943acd666932f59d4b9536e3a340c2ce83f65af6a81221fd3ab9cf478d239bc2bdb043f0cc2aeeb35ecf093ee782289804cc1fb67010146b65cf94f4a97c0b10efb2c07268985d319d822feea532c73b705e057f1153cb387ba8ff9e986a71fc849a087c452c85f8c3025e34b9107fc3718d3fa77215c8acde435735fdbf0f3e67ffeae9fd19881535ccb5eedeee1dd27e9841604903176af356a78f800eb8983d5d51a3b28ce3df3e639348c7effc9433729613e4e1fdb8f72a6398898b4fd28d8e63ecc6c16cded274f0e1db84cb0897b6b4916a47b731f740350b976e146eff51f98122022f2f59a05eb53d724eafd7233f436de309bf01f22ea5b10f8f6daccbe625590afa96339e8a15560b90e735ba2bdbbe11a694b8d92d95b1a4916c5b43b2e87df5799737566588ede6c2018f08b488b0d8312d4fc246ac200feb18b822c5784487559ba89fc02932b2c0069d3665a80dae5bbc7abb54efe285c44996443db553afb6257cf6439a5ccec0298357dfb80704d80aef8d1c85acfa08d651b18fa794d9713b7630625e9c6766caa60955fe26be4b2ebc902837c1f8e2f91766375056d1a47224b6edf73dc7d6c586041c51e763dfaee2f5492756b6c08186af962c250712d31435d7cee22c8322061342c4bb15740ecc311f1e0d3b608307dce5ae8d0650e6194922394910a7acaa7664a733215b5fa6420aaa3fe835855a93ab66068d13bb7aff3b9efe8e1bcd8f039c417364861effb1902684f7fa9876a2c2525c3b4dc20ee2b79df68eb245c70878384e3dc7";
        $expected->gender = "male";
        $expected->link = "Pipl.api";
        $expected->modified = false ;  
        $expected->first_name = "Rob";
        $expected->middle_name = "";
        $expected->last_name = "Douglas";
        $expected->full_name = "Rob Douglas";
        $expected->location = "Oyster Bay, NY";
        $expected->street = "92 Sunken Orchard Lane, Oyster Bay, NY";
        $expected->address = "92 Sunken Orchard Lane";
        $expected->city = "Oyster Bay";
        $expected->state = "NY";
        $expected->zip = '11771';
        $expected->age = null;
        $expected->other_names = [
            [
                "first_name" => "Robert",
                "middle_name" => "",
                "last_name" => "Douglas",
                 "full_name" => "Robert Douglas"
            ]
        ];
        $expected->phones = [
            "5169220464",
            "5169220465",
            "5162205847",
            "5088681597",
            "7132008155",
        ];
        $expected->emails = [
            "robert.m.douglas@vanderbilt.edu",
            "rob.douglas@hotmail.com",
            "rdouglas2@yahoo.com",
            "romado12187@aol.com",
        ];
        $expected->relatives = [];
        $expected->addresses =[
            [
                "address" => "",
                "city" => "New York",
                "state" => "NY",
                "location" => "New York, NY",
                "street" => "New York, NY",
                "zip" => ""
            ],[
                "address" => "",
                "city" => "",
                "state" => "MA",
                "location" => "MA",
                "street" => "MA",
                "zip" =>""
            ],[
                "address" => "",
                "city" => "",
                "state" => "TX",
                "location" => "TX",
                "street" => "TX",
                "zip" => ""
            ]
        ];
        $expected->usernames =[
            "robdouglas",
            "rob.douglas.7923",
            "romado12187"

        ];
        $expected->companies = [
            "Co-Founder and CTO - Skopenow",
            "CEO and Co-Founder - Skopenow",
            "Co-Founder - Inertia LLC",
            "Co-Fouder - Inertia LLC",
            "Technical Representative - Microsoft",
            "CEO and Founder - OOSTABOO",
            "Product Development / Video Production - Griffin Technology",
            "Director/Editor - Sony Pictures Entertainment",
            "Video Production - Sony Pictures Entertainment",
            "vanderbilt university",
        ];
        $expected->educations = [
            "Obhs",
            "Vanderbilt University",
            "Oyster Bay High School"
        ];
        $expected->images = [
            "https://media.licdn.com/mpr/mpr/shrinknp_200_200/AAEAAQAAAAAAAAhyAAAAJDliMGU0ZDcxLWU5ODYtNGI2Yi05NjFkLWUzYjdjMDlhZDVkNg.jpg",
            "http://graph.facebook.com/4713141/picture?type=large",
            "https://s-media-cache-ak0.pinimg.com/avatars/robdouglas7923_1391109566_140.jpg",
        ];
        $expected->profiles = [
            [
                "domain" => "linkedin.com",
                "url" => 'http://www.linkedin.com/in/robdouglas'
            ],
            [
                "domain" => "facebook.com",
                "url" => "http://www.facebook.com/people/_/4713141"
            ],
            [
                "domain" => "pinterest.com",
                "url" => "http://pinterest.com/robdouglas7923/"
            ],
            [
                "domain" => "twitter.com",
                "url" => "http://www.twitter.com/romado12187",
            ],
            [
                "domain" =>"whitepages.plus",
                "url" =>"https://whitepages.plus/n/Robert_Douglas/Oyster_bay_NY/764e197a47c529302a5097174b307965"
            ],
            [
                "domain" => "dawhois.com",
                "url" => "http://dawhois.com/domain/robmdouglas.com.html"
            ],
            [
                "domain"=> "cqcounter.com",
                "url" => "http://cqcounter.com/whois/domain/robmdouglas.com.html"
            ],
            [
                "domain" => "linkedin.com",
                "url" => "http://www.linkedin.com/in/robdouglas"
            ],
            [
                "domain" => "whitepages.plus",
                "url" => "https://whitepages.plus/n/Rob_Douglas/_/0506dc58b83b451b8840fc554ff41b83"
            ],
            [
                "domain" => "whitepages.plus",
                "url" => "https://whitepages.plus/n/Robert_Douglas/Oyster_bay_NY/98c10b6dad911f953a4d9d730a55d8ee"
            ],
            [
                "domain" => "whitepages.plus",
                "url" => "https://whitepages.plus/n/Rob_Douglas/Oyster_bay_NY/9a3776a071f15162087eda24ea60f65b"
            ],
            [
                "domain" => "whitepages.plus",
                "url" => "https://whitepages.plus/n/Robert_Douglas/Oyster_bay_NY/323a15a2b3ba939404cd09e499ffd3e2"
            ],
            [
                "domain" => "facebook.com",
                "url" => "http://www.facebook.com/rob.douglas.7923"
            ],
            [
                "domain" => "skopenow.com",
                "url" => "http://skopenow.com",
            ],
        ]; 
        $expected->source = "-";
        $expected->comb_fields = [];
        $expected->searchType = null;
        $expected->recall = false;
        $expected = [$expected];
        return new \ArrayObject($expected);
   }
   public function tloxp_result_model()
   {
        $expected = new OutputModel;
        $expected ->report_id = "HPRG-MY3D";
        $expected->gender = "" ;
        $expected->link = "tloxp";
        $expected->modified = "";
        $expected->first_name = "Robert";
        $expected->middle_name = "";
        $expected->last_name = "Douglas";
        $expected->full_name = "Robert Douglas";
        $expected->location = "New York, NY";
        $expected->address = "333 E 49th St Apt 2r, New York, NY";
        $expected->street = "333 E 49th St Apt 2r";
        $expected->city = "New York";
        $expected->state = "NY";
        $expected->zip = "10017";
        $expected->age = "";
        $expected->other_names = [
            [
                "first_name" => "Rob",
                "middle_name" => "",
                "last_name" => "Douglas",
                "full_name" => "Rob Douglas"
            ]
        ]; 
        $expected->phones = [5162205847];
        $expected->emails = [
            "romado12187@aol.com",
            "rdouglas2@yahoo.com",
            "rob.douglas@hotmail.com",
           "roamdo12187@aol.com"
        ] ;
        $expected->relatives = [
            [
                "first_name" => "Barry",
                "middle_name" => "K",
                "last_name" => "Douglas",
                "full_name" => "Barry K Douglas",
            ],
            [
                "first_name" => "Kim",
                "middle_name" => "K",
                "last_name" => "Douglas",
                "full_name" => "Kim K Douglas",
            ],
            [
                "first_name" => "Lauren",
                "middle_name" => "B",
                "last_name" => "Douglas",
                "full_name" => "Lauren B Douglas",
            ],
            [
                "first_name" => "Marc",
                "middle_name" => "Franklin",
                "last_name" => "Douglas",
                "full_name" => "Marc Franklin Douglas",
            ]
        ];

        $expected->addresses = [
            [
                "address" => "92 Sunken Orchard Ln",
                "city" => "Oyster Bay",
                "state" => "NY",
                "location" => "New York, NY",
                "zip" => "11771",
                "street" => "92 Sunken Orchard Ln, New York, NY"
            ]
        ];
        $expected->usernames = [];
        $expected->companies = [];

        $expected->educations = [];
        $expected->images = [];
        $expected->profiles = [];
        $expected->source = "Tloxp";
        $expected->comb_fields = [] ;
        $expected->searchType = "" ; 
        $expected->recall = false ;

        $expected2 = new OutputModel;
        $expected2->report_id = "JRGQ-HH67";
        $expected2->gender = "" ;
        $expected2->link = "tloxp";
        $expected2->modified = "";
        $expected2->first_name = "Rob";
        $expected2->middle_name = "A";
        $expected2->last_name = "Douglas";
        $expected2->full_name = "Rob A Douglas";
        $expected2->location = "New York, NY";
        $expected2->street = "10 Manhattan Ave Apt 6f, New York, NY";
        $expected2->address = "10 Manhattan Ave Apt 6f";
        $expected2->city = "New York";
        $expected2->state = "NY";
        $expected2->zip = "10025";
        $expected2->age = "1966";
        $expected2->other_names = []; 
        $expected2->phones = [];
        $expected2->emails = [] ;
        $expected2->relatives = [];
        $expected2->addresses = [];
        $expected2->usernames = [];
        $expected2->companies = [];
        $expected2->educations = [];
        $expected2->images = [];
        $expected2->profiles = [];
        $expected2->source = "Tloxp";
        $expected2->comb_fields = [] ;
        $expected2->searchType = "" ; 
        $expected2->recall = false ;
        $expected = [$expected , $expected2];
        return new \ArrayObject($expected);
   }
* /
}
*/
?>