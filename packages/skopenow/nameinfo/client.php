<?php
require_once __DIR__ . "/vendor/autoload.php";

// use NameInfo\NameSplitter\Parser\NameSplitterParser;
// use NameInfo\NickNames\NickNames;

use NameInfo\UniqueName\Search\PiplUniqueNameSearch;
use NameInfo\UniqueName\Search\HowManyOfMeUniqueNameSearch;
use NameInfo\EntryPoint;
use NameInfo\NameInfoController;


$entryPoint = new EntryPoint();
echo "<pre>";
echo "<h2>Testing Name Splitter</h2>";
// NameSplitter Testing
$data = json_decode('["Rob Douglas jr","John Smith david-will","Victorio Gruezo jr"]');
$nameSplitterIterator = new \ArrayIterator($data);
print_r($nameSplitterIterator);

$output = $entryPoint->nameSplit($nameSplitterIterator);
print_r($output);
// $newFormat = [];
//      foreach($output as $key => $value){
//          $newFormat[$key] = $value;
//      }
//      echo json_encode($newFormat, true);

echo "<hr>";
echo "<h2>Testing Nick Names</h2>";
$nickNamesIterator = new \ArrayIterator([
        "Rob Douglas",
        "Kazi Anwarul Mamun",
        "Mohnish Magarde",
        "David Will"
    ]);
$output = $entryPoint->nickNames($nickNamesIterator);
$newFormat = [];
        foreach($output as $key => $value){
            $newFormat[$key] = $value;
        }
        echo json_encode($newFormat, true);
echo "<hr>";
echo "<h2>Testing Unique Names</h2>";
    $data = [
                                            [
                                                "firstName" => "Rob",
                                                "middleName" => "",
                                                "lastName" => "Douglas"
                                            ],
                                            [
                                                "firstName" => "Kazi",
                                                "middleName" => "",
                                                "lastName" => "Magarde"
                                            ],
                                             [
                                                 "firstName" => "David",
                                                 "middleName" => "",
                                                 "lastName" => "Will"
                                             ],
                                         [
                                             "firstName" => "Wael",
                                             "middleName" => "Salah",
                                             "lastName" => "Elbadry"
                                         ]
    ];
$data = json_encode($data);
echo $data;
$data2 = json_decode($data);
echo "<hr/>";
$uniqueNamesIterator = new \ArrayIterator($data2);
$output = $entryPoint->uniqueName($uniqueNamesIterator);
print_r($output);
// $newFormat = [];
//         foreach($output as $key => $value){
//             $newFormat[$key] = $value;
//         }
//         echo json_encode($newFormat, true);
echo "</pre>";

// $piplUniqueName = new PiplUniqueNameSearch("Rob", "Douglas", "CONTACT-gmcr1h343kx5nk01ncew52aw");
// print_r($piplUniqueName->search());
// echo "<hr>";
// echo "<h2>Testing HowManyOfMe Unique Names</h2>";
// $HowManyOfMe = new HowManyOfMeUniqueNameSearch("Rob", "Douglas");
// print_r($HowManyOfMe->search());

// echo "<hr>";
// echo "<h2>Testing Unique Names Search</h2>";
// $uniqueNamesIterator = new ArrayIterator([
//                                          [
//                                              "firstName" => "Rob",
//                                              "middleName" => "",
//                                              "lastName" => "Douglas"
//                                          ],
//                                          [
//                                              "firstName" => "Kazi",
//                                              "middleName" => "",
//                                              "lastName" => "Magarde"
//                                          ],
//                                          [
//                                              "firstName" => "David",
//                                              "middleName" => "",
//                                              "lastName" => "Will"
//                                          ],
//                                          [
//                                              "firstName" => "Wael",
//                                              "middleName" => "Salah",
//                                              "lastName" => "Elbadry"
//                                          ]
//  ]);
// $nickNames = new UniqueName($uniqueNamesIterator, false, "CONTACT-gmcr1h343kx5nk01ncew52aw");
// print_r($nickNames->checkUniqueName());

// $newFormat = [];
//      foreach($nameSplitterIterator as $key => $value){
//          $newFormat[$key] = $value;
//      }
//      echo json_encode($newFormat);
// echo "<hr>";
// echo "<h2>Testing Restfull controller</h2>";
// $controller = new NameInfoController();

// echo $controller->nameSplit($nameSplitterIterator);
// echo "</pre>";
$namesFound = [
                [
                    "nickNames" => [
                        "Rob",
                        "Douglas"
                    ]
                ],
                [
                    "nickNames" => [
                        "Magarde",
                    ]
                ],
                [
                    "nickNames" => [
                        "Will",
                        "David"
                    ]
                ]
];