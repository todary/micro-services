# Extract Package
###### Version 1.0.0
## Introduction
This package used to data extraction and scraping such as media and posts from social networks. 
it handles extracting from facebook, twitter, instagram, linkedin, youtube
## Installation
1- Update Composer file
```php
"psr-4": {
    "App\\": "app/",
    "Skopenow\\Extract\\": "packages/Skopenow/Extract/src"
}
2- Update your bootstrap App
```php
$app->register(Skopenow\Extract\providers\ExtractServiceProvider::class);
```
And then we run this command from main folder:
```php
composer dump-autoload 
```
## Usage
#### 1- Internally
* To load the service 
$service = loadService("Extract");

``` Extracting Facebook posts:

$link = "https://www.facebook.com/rob.douglas.7923";
config(['state.report_id' => 1008874]);
$extractedData = $service->extractFacebookPosts($link);

print_r($extractedData);

``output is any array iterator of posts data

ArrayIterator Object
(
    [storage:ArrayIterator:private] => Array
        (
            [Posts] => Array
                (
                    [0] => Array
                        (
                            [postBody] => <a class="profileLink" href="https://www.facebook.com/kim.k.douglas?fref=mentions" data-hovercard="/ajax/hovercard/user.php?id=1626421190&extragetparams=%7B%22fref%22%3A%22mentions%22%7D" data-hovercard-prefer-more-content-show="1">Kim Koenigsberg Douglas</a>
                            [link] => https://www.facebook.com/rob.douglas.7923/posts/10101392332132088
                            [type] => post
                            [date] => Wednesday, November 23, 2016 at 1:23am
                            [time_stamp] => 1479882180
                        )

                    [1] => Array
                        (
                            [postBody] => 
                            [link] => https://www.facebook.com/rob.douglas.7923/posts/10101383967420038
                            [type] => post
                            [date] => Wednesday, November 16, 2016 at 1:44pm
                            [time_stamp] => 1479321840
                        )

                    [2] => Array
                        (
                            [postBody] => 
                            [link] => https://www.facebook.com/rob.douglas.7923/posts/10101364955779518
                            [type] => post
                            [date] => Wednesday, November 2, 2016 at 10:00am
                            [time_stamp] => 1478095200
                        )

                    [3] => Array
                        (
                            [postBody] => 
                            [link] => https://www.facebook.com/rob.douglas.7923/posts/10101339891618298
                            [type] => post
                            [date] => Friday, October 14, 2016 at 6:19pm
                            [time_stamp] => 1476483540
                        )

                    [4] => Array
                        (
                            [postBody] => 
                            [link] => https://www.facebook.com/rob.douglas.7923/posts/10101330688606208
                            [type] => post
                            [date] => Friday, October 7, 2016 at 6:44am
                            [time_stamp] => 1475837040
                        )

                )

            [requestOptions] => Array
                (
                    [pageIndex] => 0
                    [offset] => 5
                    [profileID] => 4713141
                )

        )

)

``` Extracting Facebook user images:
$link = "https://www.facebook.com/rob.douglas.7923";
config(['state.report_id' => 1008874]);
$extractedData = $service->extractFacebookUserImages($link);

print_r($extractedData);

``output is any array iterator of facebook image data

ArrayIterator Object
(
    [storage:ArrayIterator:private] => Array
        (
            [https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/s417x417/1150798_10100354817769188_183339226_n.jpg?oh=7012219d69e19cd7b340b4b69d57f055&amp;oe=5A504CE5] => Array
                (
                    [link] => https://www.facebook.com/photo.php?fbid=10100354817769188
                    [direct_link] => https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/s417x417/1150798_10100354817769188_183339226_n.jpg?oh=7012219d69e19cd7b340b4b69d57f055&oe=5A504CE5
                )

            [https://scontent-iad3-1.xx.fbcdn.net/v/t1.0-0/p417x417/1934427_10101083567553778_6492692034320531222_n.jpg?oh=8529af4edbda5f3c1bdb3be849af9fff&amp;oe=5A585FB6] => Array
                (
                    [link] => https://www.facebook.com/photo.php?fbid=10101083567553778
                    [direct_link] => https://scontent-iad3-1.xx.fbcdn.net/v/t1.0-0/p417x417/1934427_10101083567553778_6492692034320531222_n.jpg?oh=8529af4edbda5f3c1bdb3be849af9fff&oe=5A585FB6
                )

            [https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/p417x417/11182052_10100859027513818_1240716446056248529_n.jpg?oh=f437fef4554b7e3809d9600e9f9b8472&amp;oe=5A5CFF74] => Array
                (
                    [link] => https://www.facebook.com/photo.php?fbid=10100859027513818
                    [direct_link] => https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/p417x417/11182052_10100859027513818_1240716446056248529_n.jpg?oh=f437fef4554b7e3809d9600e9f9b8472&oe=5A5CFF74
                )

            [https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-9/10998900_10100803013551248_8290685865393055962_n.jpg?oh=75d31875b40001d5cdfc6563488c135b&amp;oe=5A171A32] => Array
                (
                    [link] => https://www.facebook.com/photo.php?fbid=10100803013551248
                    [direct_link] => https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-9/10998900_10100803013551248_8290685865393055962_n.jpg?oh=75d31875b40001d5cdfc6563488c135b&oe=5A171A32
                )

            [https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/s417x417/10947201_10100787643902148_8169549061568285853_n.jpg?oh=59418d712e0048796e8cf44c2663bb82&amp;oe=5A5F8296] => Array
                (
                    [link] => https://www.facebook.com/photo.php?fbid=10100787643902148
                    [direct_link] => https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/s417x417/10947201_10100787643902148_8169549061568285853_n.jpg?oh=59418d712e0048796e8cf44c2663bb82&oe=5A5F8296
                )

            [https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/p417x417/10153126_10100533307329678_1297708181_n.jpg?oh=daf5c986cb51e90e6f42fc181566c27a&amp;oe=5A13AF95] => Array
                (
                    [link] => https://www.facebook.com/photo.php?fbid=10100533307329678
                    [direct_link] => https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/p417x417/10153126_10100533307329678_1297708181_n.jpg?oh=daf5c986cb51e90e6f42fc181566c27a&oe=5A13AF95
                )

            [https://scontent-lga3-1.xx.fbcdn.net/v/t31.0-0/p417x417/1052286_10100481996282428_53306410_o.jpg?oh=f73f87bfd5e98284f6ca4209bbe6580b&amp;oe=5A5A85EC] => Array
                (
                    [link] => https://www.facebook.com/photo.php?fbid=10100481996282428
                    [direct_link] => https://scontent-lga3-1.xx.fbcdn.net/v/t31.0-0/p417x417/1052286_10100481996282428_53306410_o.jpg?oh=f73f87bfd5e98284f6ca4209bbe6580b&oe=5A5A85EC
                )

            [https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/p417x417/1098422_10100366529014758_1722098622_n.jpg?oh=3e5ca9705f2b3f65819004be423a43c0&amp;oe=5A532AEA] => Array
                (
                    [link] => https://www.facebook.com/photo.php?fbid=10100366529014758
                    [direct_link] => https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/p417x417/1098422_10100366529014758_1722098622_n.jpg?oh=3e5ca9705f2b3f65819004be423a43c0&oe=5A532AEA
                )

            [https://scontent-lga3-1.xx.fbcdn.net/v/t31.0-8/980230_10100322071647678_1297783897_o.jpg?oh=b8053de210ce9b31f28ff4fce7459f38&amp;oe=5A4EE2C4] => Array
                (
                    [link] => https://www.facebook.com/photo.php?fbid=10100322071647678
                    [direct_link] => https://scontent-lga3-1.xx.fbcdn.net/v/t31.0-8/980230_10100322071647678_1297783897_o.jpg?oh=b8053de210ce9b31f28ff4fce7459f38&oe=5A4EE2C4
                )

            [https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/s417x417/164939_10100282773646188_1473341443_n.jpg?oh=791c165e1af5bb3827088f00adaa4538&amp;oe=5A17EC4C] => Array
                (
                    [link] => https://www.facebook.com/photo.php?fbid=10100282773646188
                    [direct_link] => https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/s417x417/164939_10100282773646188_1473341443_n.jpg?oh=791c165e1af5bb3827088f00adaa4538&oe=5A17EC4C
                )

            [https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/p417x417/164256_10100279292677078_1928157336_n.jpg?oh=56e0c2df89a60dd9bcd773e918529e49&amp;oe=5A194E1E] => Array
                (
                    [link] => https://www.facebook.com/photo.php?fbid=10100279292677078
                    [direct_link] => https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/p417x417/164256_10100279292677078_1928157336_n.jpg?oh=56e0c2df89a60dd9bcd773e918529e49&oe=5A194E1E
                )

            [https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/p417x417/318766_10100279059409548_491678792_n.jpg?oh=f6f3d643a986c498ceb256b1a2fc0b30&amp;oe=5A55A8D8] => Array
                (
                    [link] => https://www.facebook.com/photo.php?fbid=10100279059409548
                    [direct_link] => https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/p417x417/318766_10100279059409548_491678792_n.jpg?oh=f6f3d643a986c498ceb256b1a2fc0b30&oe=5A55A8D8
                )

            [https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-9/536783_10100268628957258_290679756_n.jpg?oh=4fb998fb8dde7469bb3a559f63bf14ed&amp;oe=5A57ABCC] => Array
                (
                    [link] => https://www.facebook.com/photo.php?fbid=10100268628957258
                    [direct_link] => https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-9/536783_10100268628957258_290679756_n.jpg?oh=4fb998fb8dde7469bb3a559f63bf14ed&oe=5A57ABCC
                )

            [https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/p417x417/551151_10100204451494408_1862782656_n.jpg?oh=8d5e16c48a860f4b9cf106f18f31f05b&amp;oe=5A15DCFC] => Array
                (
                    [link] => https://www.facebook.com/photo.php?fbid=10100204451494408
                    [direct_link] => https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/p417x417/551151_10100204451494408_1862782656_n.jpg?oh=8d5e16c48a860f4b9cf106f18f31f05b&oe=5A15DCFC
                )

            [https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/p417x417/227635_10100204449772858_2141855544_n.jpg?oh=cb590a3ab22cea7a40d8cfbdfc022d21&amp;oe=5A4B42DA] => Array
                (
                    [link] => https://www.facebook.com/photo.php?fbid=10100204449772858
                    [direct_link] => https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/p417x417/227635_10100204449772858_2141855544_n.jpg?oh=cb590a3ab22cea7a40d8cfbdfc022d21&oe=5A4B42DA
                )

            [https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/s417x417/542149_10100203897569478_648417463_n.jpg?oh=f5c97c1cf783b4686eef67cc171fd404&amp;oe=5A51493B] => Array
                (
                    [link] => https://www.facebook.com/photo.php?fbid=10100203897569478
                    [direct_link] => https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/s417x417/542149_10100203897569478_648417463_n.jpg?oh=f5c97c1cf783b4686eef67cc171fd404&oe=5A51493B
                )

            [https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/s417x417/268512_10100203720324678_1456394216_n.jpg?oh=2d8e0ff77af204355fbd363c4ae6cd47&amp;oe=5A49A7D5] => Array
                (
                    [link] => https://www.facebook.com/photo.php?fbid=10100203720324678
                    [direct_link] => https://scontent-lga3-1.xx.fbcdn.net/v/t1.0-0/s417x417/268512_10100203720324678_1456394216_n.jpg?oh=2d8e0ff77af204355fbd363c4ae6cd47&oe=5A49A7D5
                )

            [https://scontent-lga3-1.xx.fbcdn.net/v/t31.0-0/p417x417/706190_10100186195339888_845741170_o.jpg?oh=d79f91ccad21a206d3ba24f1d56e1a0c&amp;oe=5A5EC375] => Array
                (
                    [link] => https://www.facebook.com/photo.php?fbid=10100186195339888
                    [direct_link] => https://scontent-lga3-1.xx.fbcdn.net/v/t31.0-0/p417x417/706190_10100186195339888_845741170_o.jpg?oh=d79f91ccad21a206d3ba24f1d56e1a0c&oe=5A5EC375
                )

            [https://scontent-lga3-1.xx.fbcdn.net/v/t31.0-0/p417x417/705999_10100185661819068_884042624_o.jpg?oh=eed4d172f502c7bdc284d856af4cd8ee&amp;oe=5A5E6100] => Array
                (
                    [link] => https://www.facebook.com/photo.php?fbid=10100185661819068
                    [direct_link] => https://scontent-lga3-1.xx.fbcdn.net/v/t31.0-0/p417x417/705999_10100185661819068_884042624_o.jpg?oh=eed4d172f502c7bdc284d856af4cd8ee&oe=5A5E6100
                )

        )

)

``` Extracting Facebook page images:
$link = "https://www.facebook.com/cairopost";
$extractedData = $service->extractFacebookPageImages($link);

print_r($extractedData);

``output is any array iterator of facebook image data

ArrayIterator Object
(
    [storage:ArrayIterator:private] => Array
        (
            [/cairopost/photos/a.270610506300064.83083.215108358516946/270611579633290/?type=3&amp;source=54] => Array
                (
                    [link] => https://www.facebook.com/cairopost/photos/a.270610506300064.83083.215108358516946/270611579633290/?type=3&amp;source=54
                    [direct_link] => https\3a //scontent-atl3-1.xx.fbcdn.net/v/t1.0-9/cp0/e15/q65/c14.0.68.68/311160_270611579633290_5963691_n.jpg?efg\3d eyJpIjoidCJ9\26 oh\3d 584b005015bf357a0c556d690dce8d2a\26 oe\3d 5A14514D
                )

            [/cairopost/photos/a.270610506300064.83083.215108358516946/270611309633317/?type=3&amp;source=54] => Array
                (
                    [link] => https://www.facebook.com/cairopost/photos/a.270610506300064.83083.215108358516946/270611309633317/?type=3&amp;source=54
                    [direct_link] => https\3a //scontent-atl3-1.xx.fbcdn.net/v/t1.0-9/cp0/e15/q65/c14.0.68.68/318765_270611309633317_5211691_n.jpg?efg\3d eyJpIjoidCJ9\26 oh\3d 0483eb0d0b0c0c72883e0ea1a086ede0\26 oe\3d 5A5AE8A9
                )

            [/cairopost/photos/a.270610506300064.83083.215108358516946/270610992966682/?type=3&amp;source=54] => Array
                (
                    [link] => https://www.facebook.com/cairopost/photos/a.270610506300064.83083.215108358516946/270610992966682/?type=3&amp;source=54
                    [direct_link] => https\3a //scontent-atl3-1.xx.fbcdn.net/v/t1.0-9/cp0/e15/q65/c14.0.68.68/297726_270610992966682_4532624_n.jpg?efg\3d eyJpIjoidCJ9\26 oh\3d c5a9b68cc39f0f58344fd0fd34120591\26 oe\3d 5A5DEB32
                )

            [/cairopost/photos/a.270610506300064.83083.215108358516946/270610552966726/?type=3&amp;source=54] => Array
                (
                    [link] => https://www.facebook.com/cairopost/photos/a.270610506300064.83083.215108358516946/270610552966726/?type=3&amp;source=54
                    [direct_link] => https\3a //scontent-atl3-1.xx.fbcdn.net/v/t1.0-9/cp0/e15/q65/c14.0.68.68/300384_270610552966726_2761582_n.jpg?efg\3d eyJpIjoidCJ9\26 oh\3d 3dac2becac09b1472b9c00ab7f5d24c8\26 oe\3d 5A55E34A
                )

            [/cairopost/photos/a.215108455183603.69664.215108358516946/266446220049826/?type=3&amp;source=54] => Array
                (
                    [link] => https://www.facebook.com/cairopost/photos/a.215108455183603.69664.215108358516946/266446220049826/?type=3&amp;source=54
                    [direct_link] => https\3a //scontent-atl3-1.xx.fbcdn.net/v/t1.0-0/cp0/e15/q65/c0.15.110.110/p110x80/303555_266446220049826_5679726_n.jpg?efg\3d eyJpIjoidCJ9\26 oh\3d 14dfba21d931be2c03d904f47b7903e0\26 oe\3d 5A43EE8E
                )

            [/cairopost/photos/a.215108455183603.69664.215108358516946/266435340050914/?type=3&amp;source=54] => Array
                (
                    [link] => https://www.facebook.com/cairopost/photos/a.215108455183603.69664.215108358516946/266435340050914/?type=3&amp;source=54
                    [direct_link] => https\3a //scontent-atl3-1.xx.fbcdn.net/v/t1.0-0/cp0/e15/q65/c0.15.110.110/p110x80/313196_266435340050914_6839812_n.jpg?efg\3d eyJpIjoidCJ9\26 oh\3d 81f8eafeffb5aef2cd947cc2cfce99f7\26 oe\3d 5A46D3B5
                )

            [/cairopost/photos/a.215108455183603.69664.215108358516946/217280214966427/?type=3&amp;source=54] => Array
                (
                    [link] => https://www.facebook.com/cairopost/photos/a.215108455183603.69664.215108358516946/217280214966427/?type=3&amp;source=54
                    [direct_link] => https\3a //scontent-atl3-1.xx.fbcdn.net/v/t1.0-0/cp0/e15/q65/p110x80/222236_217280214966427_6075411_n.jpg?efg\3d eyJpIjoidCJ9\26 oh\3d 207c3c919cb0acaf55ace545f72be71e\26 oe\3d 5A5755B6
                )

            [/cairopost/photos/a.215108455183603.69664.215108358516946/217279608299821/?type=3&amp;source=54] => Array
                (
                    [link] => https://www.facebook.com/cairopost/photos/a.215108455183603.69664.215108358516946/217279608299821/?type=3&amp;source=54
                    [direct_link] => https\3a //scontent-atl3-1.xx.fbcdn.net/v/t1.0-0/cp0/e15/q65/p110x80/230786_217279608299821_1269025_n.jpg?efg\3d eyJpIjoidCJ9\26 oh\3d 613a1703a79d2d2b7f50dfad505b3f6c\26 oe\3d 5A4A14D6
                )

            [/cairopost/photos/a.215108455183603.69664.215108358516946/215108458516936/?type=3&amp;source=54] => Array
                (
                    [link] => https://www.facebook.com/cairopost/photos/a.215108455183603.69664.215108358516946/215108458516936/?type=3&amp;source=54
                    [direct_link] => https\3a //scontent-atl3-1.xx.fbcdn.net/v/t1.0-0/cp0/e15/q65/p110x80/226530_215108458516936_1367162_n.jpg?efg\3d eyJpIjoidCJ9\26 oh\3d 9eadbee8fe660053dd054f68b0ec495a\26 oe\3d 5A12A9C0
                )

        )

)

``` Extracting youtube videos:
$link = "https://www.youtube.com/user/wishniecompany";
$extractedData = $service->extractYoutubeProfiles($link, "videos");

print_r($extractedData);

``output is any array iterator of youtube videos data

ArrayIterator Object
(
    [storage:ArrayIterator:private] => Array
        (
            [0] => Array
                (
                    [url] => https://www.youtube.com/watch?v=pPl5EOscRGc
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [1] => Array
                (
                    [url] => https://www.youtube.com/watch?v=pPl5EOscRGc
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [2] => Array
                (
                    [url] => https://www.youtube.com/watch?v=gMn9P5saBG0
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [3] => Array
                (
                    [url] => https://www.youtube.com/watch?v=gMn9P5saBG0
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [4] => Array
                (
                    [url] => https://www.youtube.com/watch?v=5ZEve0Vr4oo
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [5] => Array
                (
                    [url] => https://www.youtube.com/watch?v=5ZEve0Vr4oo
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [6] => Array
                (
                    [url] => https://www.youtube.com/watch?v=ATDoryd2sNo
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [7] => Array
                (
                    [url] => https://www.youtube.com/watch?v=ATDoryd2sNo
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [8] => Array
                (
                    [url] => https://www.youtube.com/watch?v=0faJFiimKro
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [9] => Array
                (
                    [url] => https://www.youtube.com/watch?v=0faJFiimKro
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [10] => Array
                (
                    [url] => https://www.youtube.com/watch?v=ZIen8_NbP9c
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [11] => Array
                (
                    [url] => https://www.youtube.com/watch?v=ZIen8_NbP9c
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [12] => Array
                (
                    [url] => https://www.youtube.com/watch?v=ZBiuR9vqR6c
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [13] => Array
                (
                    [url] => https://www.youtube.com/watch?v=ZBiuR9vqR6c
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [14] => Array
                (
                    [url] => https://www.youtube.com/watch?v=X8XeS3WMWgg
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [15] => Array
                (
                    [url] => https://www.youtube.com/watch?v=X8XeS3WMWgg
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [16] => Array
                (
                    [url] => https://www.youtube.com/watch?v=_ugXORYRBmA
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [17] => Array
                (
                    [url] => https://www.youtube.com/watch?v=_ugXORYRBmA
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [18] => Array
                (
                    [url] => https://www.youtube.com/watch?v=8T7fpkHG3uA
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [19] => Array
                (
                    [url] => https://www.youtube.com/watch?v=8T7fpkHG3uA
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [20] => Array
                (
                    [url] => https://www.youtube.com/watch?v=89yaqWDdiU4
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [21] => Array
                (
                    [url] => https://www.youtube.com/watch?v=89yaqWDdiU4
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [22] => Array
                (
                    [url] => https://www.youtube.com/watch?v=PMqEpuJDlPc
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [23] => Array
                (
                    [url] => https://www.youtube.com/watch?v=PMqEpuJDlPc
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [24] => Array
                (
                    [url] => https://www.youtube.com/watch?v=29h7p1BVdV8
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [25] => Array
                (
                    [url] => https://www.youtube.com/watch?v=29h7p1BVdV8
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [26] => Array
                (
                    [url] => https://www.youtube.com/watch?v=aCkJ7tVrl5s
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [27] => Array
                (
                    [url] => https://www.youtube.com/watch?v=aCkJ7tVrl5s
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [28] => Array
                (
                    [url] => https://www.youtube.com/watch?v=21Ka9KI4ghk
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [29] => Array
                (
                    [url] => https://www.youtube.com/watch?v=21Ka9KI4ghk
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [30] => Array
                (
                    [url] => https://www.youtube.com/watch?v=IzfrhUWVH94
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [31] => Array
                (
                    [url] => https://www.youtube.com/watch?v=IzfrhUWVH94
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [32] => Array
                (
                    [url] => https://www.youtube.com/watch?v=Gym7BLmjml4
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [33] => Array
                (
                    [url] => https://www.youtube.com/watch?v=Gym7BLmjml4
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [34] => Array
                (
                    [url] => https://www.youtube.com/watch?v=Q5T2riOadSc
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [35] => Array
                (
                    [url] => https://www.youtube.com/watch?v=Q5T2riOadSc
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [36] => Array
                (
                    [url] => https://www.youtube.com/watch?v=lRDDwFfUthc
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [37] => Array
                (
                    [url] => https://www.youtube.com/watch?v=lRDDwFfUthc
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [38] => Array
                (
                    [url] => https://www.youtube.com/watch?v=iUCCv3Qq4MQ
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [39] => Array
                (
                    [url] => https://www.youtube.com/watch?v=iUCCv3Qq4MQ
                    [image] => 
                    [title] => 
                    [type] => video
                )

            [40] => Array
                (
                    [url] => https://www.youtube.com/watch?v=lRDDwFfUthc
                    [image] => 
                    [title] => 
                    [type] => playlist
                )

        )

)

``` Extracting twitter posts:
$postsLink= "https://www.twitter.com/RobDouglas";
config(['state.report_id' => 011002047]);
config(['state.combination_id' => 10075947]);
$extractedData = $service->extractTwitterPosts($postsLink);

print_r($extractedData);

``output is any array iterator of twitter posts data

ArrayIterator Object
(
    [storage:ArrayIterator:private] => Array
        (
            [0] => Array
                (
                    [tweet_id] => 904440574488563717
                    [permalinkUrl] => http://twitter.com/RobDouglas/status/904440574488563717
                )

            [1] => Array
                (
                    [tweet_id] => 904149791713751040
                    [permalinkUrl] => http://twitter.com/RobDouglas/status/904149791713751040
                )

            [2] => Array
                (
                    [tweet_id] => 903779767178199040
                    [permalinkUrl] => http://twitter.com/RobDouglas/status/903779767178199040
                )

            [3] => Array
                (
                    [tweet_id] => 903065721961373699
                    [permalinkUrl] => http://twitter.com/RobDouglas/status/903065721961373699
                )

            [4] => Array
                (
                    [tweet_id] => 903065360982827009
                    [permalinkUrl] => http://twitter.com/RobDouglas/status/903065360982827009
                )

            [5] => Array
                (
                    [tweet_id] => 901236597546303489
                    [permalinkUrl] => http://twitter.com/RobDouglas/status/901236597546303489
                )

            [6] => Array
                (
                    [tweet_id] => 899947610806652929
                    [permalinkUrl] => http://twitter.com/RobDouglas/status/899947610806652929
                )

            [7] => Array
                (
                    [tweet_id] => 898925591566725120
                    [permalinkUrl] => http://twitter.com/RobDouglas/status/898925591566725120
                )

            [8] => Array
                (
                    [tweet_id] => 897824166002524164
                    [permalinkUrl] => http://twitter.com/RobDouglas/status/897824166002524164
                )

            [9] => Array
                (
                    [tweet_id] => 897772758326812672
                    [permalinkUrl] => http://twitter.com/RobDouglas/status/897772758326812672
                )

            [10] => Array
                (
                    [tweet_id] => 897614413422891011
                    [permalinkUrl] => http://twitter.com/RobDouglas/status/897614413422891011
                )

            [11] => Array
                (
                    [tweet_id] => 897163698757914625
                    [permalinkUrl] => http://twitter.com/RobDouglas/status/897163698757914625
                )

            [12] => Array
                (
                    [tweet_id] => 895989591085166592
                    [permalinkUrl] => http://twitter.com/RobDouglas/status/895989591085166592
                )

            [13] => Array
                (
                    [tweet_id] => 894906340795183105
                    [permalinkUrl] => http://twitter.com/RobDouglas/status/894906340795183105
                )

            [14] => Array
                (
                    [tweet_id] => 894575056717742081
                    [permalinkUrl] => http://twitter.com/RobDouglas/status/894575056717742081
                )

            [15] => Array
                (
                    [tweet_id] => 893548768389242880
                    [permalinkUrl] => http://twitter.com/RobDouglas/status/893548768389242880
                )

            [16] => Array
                (
                    [tweet_id] => 893524669256609792
                    [permalinkUrl] => http://twitter.com/RobDouglas/status/893524669256609792
                )

            [17] => Array
                (
                    [tweet_id] => 893420796827381760
                    [permalinkUrl] => http://twitter.com/RobDouglas/status/893420796827381760
                )

            [18] => Array
                (
                    [tweet_id] => 893134425101406210
                    [permalinkUrl] => http://twitter.com/RobDouglas/status/893134425101406210
                )

            [19] => Array
                (
                    [tweet_id] => 892406612379488256
                    [permalinkUrl] => http://twitter.com/RobDouglas/status/892406612379488256
                )

        )

)

``` Extracting twitter media:
$mediaLink= "https://www.twitter.com/RobDouglas/media";
config(['state.report_id' => 011002047]);
config(['state.combination_id' => 10075947]);
$extractedData = $service->extractTwitterMedia($mediaUrl);

print_r($extractedData);

``output is any array iterator of twitter media data

ArrayIterator Object
(
    [storage:ArrayIterator:private] => Array
        (
            [imagestweets] => Array
                (
                    [0] => Array
                        (
                            [tweet_id] => 857984490920128513
                            [permalinkUrl] => http://twitter.com/waelE2020/status/857984490920128513
                            [imageUrl] => Array
                                (
                                    [imageUrl] => https://pbs.twimg.com/media/C-gsRV9XsAEFPND.jpg
                                )

                        )

                    [1] => Array
                        (
                            [tweet_id] => 857977626618724352
                            [permalinkUrl] => http://twitter.com/waelE2020/status/857977626618724352
                            [imageUrl] => Array
                                (
                                    [imageUrl] => https://pbs.twimg.com/media/C-gmFQGXYAEf6qb.jpg
                                )

                        )

                    [2] => Array
                        (
                            [tweet_id] => 853918917097553920
                            [permalinkUrl] => http://twitter.com/waelE2020/status/853918917097553920
                            [imageUrl] => Array
                                (
                                    [imageUrl] => https://pbs.twimg.com/media/C9m6toeW0AAbFhe.jpg
                                )

                        )

                    [3] => Array
                        (
                            [tweet_id] => 853911828782620672
                            [permalinkUrl] => http://twitter.com/waelE2020/status/853911828782620672
                            [imageUrl] => Array
                                (
                                    [imageUrl] => https://pbs.twimg.com/media/C9m0Q5iXsAArJ3I.jpg
                                )

                        )

                    [4] => Array
                        (
                            [tweet_id] => 853904782221873152
                            [permalinkUrl] => http://twitter.com/waelE2020/status/853904782221873152
                            [imageUrl] => Array
                                (
                                    [imageUrl] => https://pbs.twimg.com/media/C9mt3PsXUAAMTLo.jpg
                                )

                        )

                    [5] => Array
                        (
                            [tweet_id] => 852810479101657090
                            [permalinkUrl] => http://twitter.com/waelE2020/status/852810479101657090
                            [imageUrl] => Array
                                (
                                    [imageUrl] => https://pbs.twimg.com/media/C9XKk_mUAAAefjE.jpg
                                )

                        )

                    [6] => Array
                        (
                            [tweet_id] => 845197801063497729
                            [permalinkUrl] => http://twitter.com/waelE2020/status/845197801063497729
                            [imageUrl] => Array
                                (
                                    [imageUrl] => https://pbs.twimg.com/media/C7q-6U-VsAAxFVo.jpg
                                )

                        )

                    [7] => Array
                        (
                            [tweet_id] => 840526765294518272
                            [permalinkUrl] => http://twitter.com/waelE2020/status/840526765294518272
                            [imageUrl] => Array
                                (
                                    [imageUrl] => https://pbs.twimg.com/media/C6omoAxW0AALSry.jpg
                                )

                        )

                    [8] => Array
                        (
                            [tweet_id] => 840521940133675009
                            [permalinkUrl] => http://twitter.com/waelE2020/status/840521940133675009
                            [imageUrl] => Array
                                (
                                    [imageUrl] => https://pbs.twimg.com/media/C6oiPfGWcAAi2s3.jpg
                                )

                        )

                    [9] => Array
                        (
                            [tweet_id] => 840505651315015680
                            [permalinkUrl] => http://twitter.com/waelE2020/status/840505651315015680
                            [imageUrl] => Array
                                (
                                    [imageUrl] => https://pbs.twimg.com/media/C6oTa5PWoAE_n7h.jpg
                                )

                        )

                    [10] => Array
                        (
                            [tweet_id] => 840500142465789954
                            [permalinkUrl] => http://twitter.com/waelE2020/status/840500142465789954
                            [imageUrl] => Array
                                (
                                    [imageUrl] => https://pbs.twimg.com/media/C6oOagGWgAESbFi.jpg
                                )

                        )

                    [11] => Array
                        (
                            [tweet_id] => 824398565766205440
                            [permalinkUrl] => http://twitter.com/waelE2020/status/824398565766205440
                            [imageUrl] => Array
                                (
                                    [imageUrl] => https://pbs.twimg.com/media/C3DaGXPXgAAskKu.jpg
                                )

                        )

                    [12] => Array
                        (
                            [tweet_id] => 822787130971877377
                            [permalinkUrl] => http://twitter.com/waelE2020/status/822787130971877377
                            [imageUrl] => Array
                                (
                                    [imageUrl] => https://pbs.twimg.com/media/C2sghWdXcAE8r6T.jpg
                                )

                        )

                    [13] => Array
                        (
                            [tweet_id] => 822776973151895552
                            [permalinkUrl] => http://twitter.com/waelE2020/status/822776973151895552
                            [imageUrl] => Array
                                (
                                    [imageUrl] => https://pbs.twimg.com/media/C2sXQ-mXUAQJXo_.jpg
                                )

                        )

                    [14] => Array
                        (
                            [tweet_id] => 822746754424705024
                            [permalinkUrl] => http://twitter.com/waelE2020/status/822746754424705024
                            [imageUrl] => Array
                                (
                                    [imageUrl] => https://pbs.twimg.com/media/C2r7zneXUAALxwP.jpg
                                )

                        )

                    [15] => Array
                        (
                            [tweet_id] => 822734963414016000
                            [permalinkUrl] => http://twitter.com/waelE2020/status/822734963414016000
                            [imageUrl] => Array
                                (
                                    [imageUrl] => https://pbs.twimg.com/media/C2rxFFLXUAAb-XQ.jpg
                                )

                        )

                    [16] => Array
                        (
                            [tweet_id] => 815120861795680257
                            [permalinkUrl] => http://twitter.com/waelE2020/status/815120861795680257
                            [imageUrl] => Array
                                (
                                    [imageUrl] => https://pbs.twimg.com/media/Cw5QbotWEAAhnyx.jpg
                                )

                        )

                    [17] => Array
                        (
                            [tweet_id] => 789960977617915904
                            [permalinkUrl] => http://twitter.com/waelE2020/status/789960977617915904
                            [imageUrl] => Array
                                (
                                    [imageUrl] => https://pbs.twimg.com/media/CvaA4auXYAQXSYM.jpg
                                )

                        )

                    [18] => Array
                        (
                            [tweet_id] => 788858165903306754
                            [permalinkUrl] => http://twitter.com/waelE2020/status/788858165903306754
                            [imageUrl] => Array
                                (
                                    [imageUrl] => https://pbs.twimg.com/media/CvKWUI8WAAA7vSe.jpg
                                )

                        )

                )

            [vidoetweets] => Array
                (
                )

        )

)

``` Extracting instagram images:
$link = "https://www.instagram.com/zachreiner";
$limit = 48;
$extractedData = $service->extractInstagramImages($link, $limit);

print_r($extractedData);

``output is any array iterator of instagram images

ArrayIterator Object
(
    [storage:ArrayIterator:private] => Array
        (
            [0] => Array
                (
                    [__typename] => GraphImage
                    [id] => 1594620601007233518
                    [comments_disabled] => 
                    [dimensions] => Array
                        (
                            [height] => 1080
                            [width] => 1080
                        )

                    [gating_info] => 
                    [media_preview] => ACoqwgKUj9KnWPPXj0pWUZ/wrMqxXxRtqwqjq2f6Ypki7Tj0/wD10BYplcU2pnqHNWiS99q3EADA7+4/Hp+FK7BBv69qoA4qxMBgEH8PwpWKvoWJTK67wAFHOB78/p+dT2RV42BxnPPr7fnSxP8A6OQQeB09eKit7ciQRoeWUFz6DrgfhgZ9TSK21KMhGTj9ahrpJ7FJeSMYGBjqMdK51l2kr6HH5VSIFC1rERR4kkwWxwPX3x/nFZv8Qq9cD9yPZiBUvoUtLmgiK+Q4IJGQf58Z/lnPtS2aDc7jpwoPsB/+qo9oaAEjJCpgntzVm2/1f4n+dW0ktCbt7k+a5ScASN/vN/M11a1yU/8ArG/3j/OpQM//2Q==
                    [owner] => Array
                        (
                            [id] => 1920718607
                        )

                    [thumbnail_src] => https://ig-s-b-a.akamaihd.net/h-ak-igx/t51.2885-15/s640x640/sh0.08/e35/21294376_737347319797041_6941913502600658944_n.jpg
                    [thumbnail_resources] => Array
                        (
                        )

                    [is_video] => 
                    [code] => BYhOvzqjlnu
                    [date] => 1504313610
                    [display_src] => https://ig-s-b-a.akamaihd.net/h-ak-igx/t51.2885-15/e35/21294376_737347319797041_6941913502600658944_n.jpg
                    [caption] => #fromosutoido
                    [comments] => Array
                        (
                            [count] => 2
                        )

                    [likes] => Array
                        (
                            [count] => 58
                        )

                )

            [1] => Array
                (
                    [__typename] => GraphImage
                    [id] => 1560328895214752061
                    [comments_disabled] => 
                    [dimensions] => Array
                        (
                            [height] => 1080
                            [width] => 1080
                        )

                    [gating_info] => 
                    [media_preview] => ACoq52iiigAq2luSfr0wMj/PcUtpamcEjgLVlJDA/IAA9B7/AMzmlfoVbqQy2wj44z/nn+nFUsGtK4lUglf0P5Z45rMzQhMcu0Z3Z6cY7H1P+FWLa283viqygscDkniti1wrlMZwvX/P9KTY1uMtlELGMnnOevBH+RSXKSTZMYJTOc+wBHU/jVwQLLKG4Kx5H9f0961NuRgdPShLqNvocXnNJWvfaeUBkjHA5IH8xVBbWVgGCkgjI/GqIIYxlhitjBtQCejY59Kyofvitu45CA9yB+GazlukXHZsmtMHPuc/pWkKxNN++49G4/M1t+laLQhu7FYVV8sDgHA7D09qtGoCKYj/2Q==
                    [owner] => Array
                        (
                            [id] => 1920718607
                        )

                    [thumbnail_src] => https://ig-s-c-a.akamaihd.net/h-ak-igx/t51.2885-15/s640x640/sh0.08/e35/20065230_1441279232630970_858992406933012480_n.jpg
                    [thumbnail_resources] => Array
                        (
                        )

                    [is_video] => 
                    [code] => BWnZtz8DZE9
                    [date] => 1500225720
                    [display_src] => https://ig-s-c-a.akamaihd.net/h-ak-igx/t51.2885-15/e35/20065230_1441279232630970_858992406933012480_n.jpg
                    [comments] => Array
                        (
                            [count] => 0
                        )

                    [likes] => Array
                        (
                            [count] => 36
                        )

                )

            [2] => Array
                (
                    [__typename] => GraphImage
                    [id] => 1559584052314322116
                    [comments_disabled] => 
                    [dimensions] => Array
                        (
                            [height] => 750
                            [width] => 750
                        )

                    [gating_info] => 
                    [media_preview] => ACoqvRR5q8qACmIuKnFYlDCtMZAalNRSzRwjLsFHvQBnzw5PFUNlbLMrruUgg9COlZxj5rRMRpEsTtU7eM5xnmpoXLA56g4+vvWVbXpkbnpjoPXipFuihPuxIB4J/wAis7O5V1Y1M1Ru7dZypfkDI/rU6Sq/3TSuflOODg4+tAr2KFvGIlZV+7u49uOaacVNu+UDvjn61SIbPWrQm/kRRvHu3AAN6gVbWVAS2Bk9fesQVOpoaLNONljOQSSfU05rhT1rOzTDRYC7NdDHWskumerfnVpeakwPSrWhJ//Z
                    [owner] => Array
                        (
                            [id] => 1920718607
                        )

                    [thumbnail_src] => https://ig-s-a-a.akamaihd.net/h-ak-igx/t51.2885-15/s640x640/sh0.08/e35/20065474_927156994092452_5151708671918473216_n.jpg
                    [thumbnail_resources] => Array
                        (
                        )

                    [is_video] => 
                    [code] => BWkwW67jzTE
                    [date] => 1500136928
                    [display_src] => https://ig-s-a-a.akamaihd.net/h-ak-igx/t51.2885-15/e35/20065474_927156994092452_5151708671918473216_n.jpg
                    [caption] => Benbot and Hannah tying the knot in a couple hours. Flashback pic is in order
                    [comments] => Array
                        (
                            [count] => 0
                        )

                    [likes] => Array
                        (
                            [count] => 20
                        )

                )
    )
)

``` Extracting linkedin skills:
$profileId = "jkevinscott";
$extractedData = $service->extractLinkedinSkills($profileId);

print_r($extractedData);

``output is any array iterator of linkedin data

ArrayIterator Object
(
    [storage:ArrayIterator:private] => Array
        (
            [0] => Array
                (
                    [name] => Software Engineering
                    [skillId] => 10
                    [profileId] => ACoAAACK3usBrG5QSCrPcFkuMIIzmWv0AsQyMFc
                )

            [1] => Array
                (
                    [name] => Distributed Systems
                    [skillId] => 7
                    [profileId] => ACoAAACK3usBrG5QSCrPcFkuMIIzmWv0AsQyMFc
                )

            [2] => Array
                (
                    [name] => Java
                    [skillId] => 35
                    [profileId] => ACoAAACK3usBrG5QSCrPcFkuMIIzmWv0AsQyMFc
                )

            [3] => Array
                (
                    [name] => Leadership
                    [skillId] => 36
                    [profileId] => ACoAAACK3usBrG5QSCrPcFkuMIIzmWv0AsQyMFc
                )

            [4] => Array
                (
                    [name] => Engineering Management
                    [skillId] => 9
                    [profileId] => ACoAAACK3usBrG5QSCrPcFkuMIIzmWv0AsQyMFc
                )

            [5] => Array
                (
                    [name] => Big Data
                    [skillId] => 54
                    [profileId] => ACoAAACK3usBrG5QSCrPcFkuMIIzmWv0AsQyMFc
                )

            [6] => Array
                (
                    [name] => Machine Learning
                    [skillId] => 8
                    [profileId] => ACoAAACK3usBrG5QSCrPcFkuMIIzmWv0AsQyMFc
                )

            [7] => Array
                (
                    [name] => Python
                    [skillId] => 11
                    [profileId] => ACoAAACK3usBrG5QSCrPcFkuMIIzmWv0AsQyMFc
                )
        )
)

``` Extracting linkedin skills with endorsers:
$profileId = "jkevinscott";
$skills = $service->extractLinkedinSkills($profileId);
$skillsWithEndorsers = $service->extractLinkedinEndorsersUsingSkills($skills);

``output is any array iterator of linkedin data

ArrayIterator Object
(
    [storage:ArrayIterator:private] => Array
        (
            [0] => Array
                (
                    [skill] => Array
                        (
                            [name] => Engineering Management
                        )

                    [endorsers] => Array
                        (
                            [0] => Array
                                (
                                    [name] => Richard Lee
                                    [profileId] => richardleelinkedin
                                )

                            [1] => Array
                                (
                                    [name] => Chris Pettitt
                                    [profileId] => chrismpettitt
                                )

                            [2] => Array
                                (
                                    [name] => Pierre Keeley
                                    [profileId] => pkeeley
                                )

                            [3] => Array
                                (
                                    [name] => Cheryl Holmes
                                    [profileId] => cheryl-holmes-0b90292
                                )

                            [4] => Array
                                (
                                    [name] => Sanjit Bakshi
                                    [profileId] => sanjitbakshi
                                )

                            [5] => Array
                                (
                                    [name] => Craig Nevill-Manning
                                    [profileId] => craignm
                                )

                            [6] => Array
                                (
                                    [name] => Anil Rao
                                    [profileId] => anilrrao
                                )

                            [7] => Array
                                (
                                    [name] => David Carpe
                                    [profileId] => carpe
                                )

                            [8] => Array
                                (
                                    [name] => Jo Chou
                                    [profileId] => jochou
                                )

                            [9] => Array
                                (
                                    [name] => Brandon Ballinger
                                    [profileId] => brandonballinger
                                )

                            [10] => Array
                                (
                                    [name] => Manish Joshi
                                    [profileId] => mrjoshi
                                )

                            [11] => Array
                                (
                                    [name] => Hans van de Bruggen
                                    [profileId] => verbiate
                                )

                            [12] => Array
                                (
                                    [name] => Prabhu GV
                                    [profileId] => ganapathyprabhu
                                )

                            [13] => Array
                                (
                                    [name] => Alvin Dias
                                    [profileId] => alvindias
                                )

                            [14] => Array
                                (
                                    [name] => Igor Perisic
                                    [profileId] => igorperisic
                                )

                            [15] => Array
                                (
                                    [name] => Avery Moon
                                    [profileId] => averymoon
                                )

                            [16] => Array
                                (
                                    [name] => Brent Vincent
                                    [profileId] => brentvincent
                                )

                            [17] => Array
                                (
                                    [name] => Venu Javarappa
                                    [profileId] => venujavarappa
                                )

                            [18] => Array
                                (
                                    [name] => Greg Arnold
                                    [profileId] => arnoldgreg
                                )

                            [19] => Array
                                (
                                    [name] => Chris Pruett
                                    [profileId] => chrispruett
                                )

                            [20] => Array
                                (
                                    [name] => Rahul Vohra
                                    [profileId] => rahulvohra
                                )

                            [21] => Array
                                (
                                    [name] => Erran Berger
                                    [profileId] => erranberger
                                )

                            [22] => Array
                                (
                                    [name] => Erica Lockheimer
                                    [profileId] => ericalockheimer
                                )

                            [23] => Array
                                (
                                    [name] => Matthew Hayes
                                    [profileId] => matthewterencehayes
                                )

                            [24] => Array
                                (
                                    [name] => Peter Skomoroch
                                    [profileId] => peterskomoroch
                                )

                            [25] => Array
                                (
                                    [name] => Viral Kadakia
                                    [profileId] => viralk
                                )

                            [26] => Array
                                (
                                    [name] => Vinodh Jayaram
                                    [profileId] => vinodhjayaram
                                )

                            [27] => Array
                                (
                                    [name] => Prachi Gupta
                                    [profileId] => prachigupta
                                )

                        )

                )

            [1] => Array
                (
                    [skill] => Array
                        (
                            [name] => Leadership
                        )

                    [endorsers] => Array
                        (
                            [0] => Array
                                (
                                    [name] => Mike Silva
                                    [profileId] => 4msilva
                                )

                            [1] => Array
                                (
                                    [name] => Eli Calderón- Morin
                                    [profileId] => elimorin
                                )

                            [2] => Array
                                (
                                    [name] => Richard Lee
                                    [profileId] => richardleelinkedin
                                )

                            [3] => Array
                                (
                                    [name] => Robert Scranton
                                    [profileId] => robertscranton
                                )

                            [4] => Array
                                (
                                    [name] => Brian Rumao
                                    [profileId] => brianrumao
                                )

                            [5] => Array
                                (
                                    [name] => Chris Pettitt
                                    [profileId] => chrismpettitt
                                )

                            [6] => Array
                                (
                                    [name] => Rose (Roselyn) Tantraphol
                                    [profileId] => rtantraphol
                                )

                            [7] => Array
                                (
                                    [name] => David Dang 
                                    [profileId] => ddang1020
                                )

                            [8] => Array
                                (
                                    [name] => Pierre Keeley
                                    [profileId] => pkeeley
                                )

                            [9] => Array
                                (
                                    [name] => Cheryl Holmes
                                    [profileId] => cheryl-holmes-0b90292
                                )

                            [10] => Array
                                (
                                    [name] => Craig Nevill-Manning
                                    [profileId] => craignm
                                )

                            [11] => Array
                                (
                                    [name] => Jo Chou
                                    [profileId] => jochou
                                )

                            [12] => Array
                                (
                                    [name] => Brandon Ballinger
                                    [profileId] => brandonballinger
                                )

                            [13] => Array
                                (
                                    [name] => Manish Joshi
                                    [profileId] => mrjoshi
                                )

                            [14] => Array
                                (
                                    [name] => Pam Brown
                                    [profileId] => pamwilliamsbrown
                                )

                            [15] => Array
                                (
                                    [name] => Hans van de Bruggen
                                    [profileId] => verbiate
                                )

                            [16] => Array
                                (
                                    [name] => Prabhu GV
                                    [profileId] => ganapathyprabhu
                                )

                            [17] => Array
                                (
                                    [name] => Robert Bertoldi
                                    [profileId] => rtbertoldi
                                )

                            [18] => Array
                                (
                                    [name] => Brent Vincent
                                    [profileId] => brentvincent
                                )

                            [19] => Array
                                (
                                    [name] => Venu Javarappa
                                    [profileId] => venujavarappa
                                )

                            [20] => Array
                                (
                                    [name] => Rahul Vohra
                                    [profileId] => rahulvohra
                                )

                            [21] => Array
                                (
                                    [name] => Chris Pruett
                                    [profileId] => chrispruett
                                )

                            [22] => Array
                                (
                                    [name] => Erran Berger
                                    [profileId] => erranberger
                                )

                            [23] => Array
                                (
                                    [name] => Jeff Weiner
                                    [profileId] => jeffweiner08
                                )

                            [24] => Array
                                (
                                    [name] => Erica Lockheimer
                                    [profileId] => ericalockheimer
                                )

                            [25] => Array
                                (
                                    [name] => Matthew Hayes
                                    [profileId] => matthewterencehayes
                                )

                            [26] => Array
                                (
                                    [name] => Peter Skomoroch
                                    [profileId] => peterskomoroch
                                )

                            [27] => Array
                                (
                                    [name] => Viral Kadakia
                                    [profileId] => viralk
                                )

                            [28] => Array
                                (
                                    [name] => Vinodh Jayaram
                                    [profileId] => vinodhjayaram
                                )

                        )

                )
)
)

```
#### 2-Via API
* Facebook Posts
``` To call extractFacebookPosts() api use url ```
project url/extract/extractFacebookPosts/[post-data]

Input must be json object/array like this
```input
[
  "link": "https://www.facebook.com/rob.douglas.7923",
  "sessId (optional)": ""
]
```

* Facebook User Images
``` To call extractFacebookUserImages() api use url ```
project url/extract/extractFacebookUserImages/[post-data]

Input must be json object/array like this
```input
[
  "link": "https://www.facebook.com/rob.douglas.7923",
  "sessId (optional)": "",
  "limit (optional)": ,
  "oldResult (optional)": [],
  "extractLevel (optional)": 0
]
```

* Facebook Page Images
``` To call extractFacebookPageImages() api use url ```
project url/extract/extractFacebookPageImages/[post-data]

Input must be json object/array like this
```input
[
  "link": "https://www.facebook.com/rob.douglas.7923",
  "sessId (optional)": "",
  "oldResult (optional)": null,
]
```

* Youtube Profiles
``` To call extractYoutubeProfiles() api use url ```
project url/extract/extractYoutubeProfiles/[post-data]

Input must be json object/array like this
```input
[
  "profileUrl": "https://www.youtube.com/user/wishniecompany",
  "type (optional)": ""
]
```

* Twitter Posts
``` To call extractTwitterPosts() api use url ```
project url/extract/extractTwitterPosts/[post-data]

Input must be json object/array like this
```input
[
  "url": "https://www.twitter.com/RobDouglas"
]
```

* Twitter Media
``` To call extractTwitterMedia() api use url ```
project url/extract/extractTwitterMedia/[post-data]

Input must be json object/array like this
```input
[
  "url": "https://www.twitter.com/RobDouglas/media"
]
```

* Instagram Images
``` To call extractInstagramImages() api use url ```
project url/extract/extractInstagramImages/[post-data]

Input must be json object/array like this
```input
[
  "link": "https://www.instagram.com/zachreiner",
  "limit": 20,
  "oldResult (default)": []
]
```

* Linkedin Skills
``` To call extractLinkedinSkills() api use url ```
project url/extract/extractLinkedinSkills/[post-data]

Input must be json object/array like this
```input
[
  "profileId": "jkevinscott"
]
```

* Linkedin Endorsers
``` To call extractLinkedinEndorsersUsingSkills() api use url ```
project url/extract/extractLinkedinEndorsersUsingSkills/[post-data]

Input must be json object/array like this
```input
[
  "skills": [] (array iterator object representing the skills you get from the previous step)
]
```

* API Output:
#### output is a json object like this output
 
output from facebook posts

```JSON

{
  "Posts": [
    {
      "postBody": "<a class=\"profileLink\" href=\"https:\/\/www.facebook.com\/kim.k.douglas?fref=mentions\" data-hovercard=\"\/ajax\/hovercard\/user.php?id=1626421190&extragetparams=%7B%22fref%22%3A%22mentions%22%7D\" data-hovercard-prefer-more-content-show=\"1\">Kim Koenigsberg Douglas<\/a>",
      "link": "https:\/\/www.facebook.com\/rob.douglas.7923\/posts\/10101392332132088",
      "type": "post",
      "date": "Wednesday, November 23, 2016 at 1:23am",
      "time_stamp": 1479882180
    },
    {
      "postBody": "",
      "link": "https:\/\/www.facebook.com\/rob.douglas.7923\/posts\/10101383967420038",
      "type": "post",
      "date": "Wednesday, November 16, 2016 at 1:44pm",
      "time_stamp": 1479321840
    },
    {
      "postBody": "",
      "link": "https:\/\/www.facebook.com\/rob.douglas.7923\/posts\/10101364955779518",
      "type": "post",
      "date": "Wednesday, November 2, 2016 at 10:00am",
      "time_stamp": 1478095200
    },
    {
      "postBody": "",
      "link": "https:\/\/www.facebook.com\/rob.douglas.7923\/posts\/10101339891618298",
      "type": "post",
      "date": "Friday, October 14, 2016 at 6:19pm",
      "time_stamp": 1476483540
    },
    {
      "postBody": "",
      "link": "https:\/\/www.facebook.com\/rob.douglas.7923\/posts\/10101330688606208",
      "type": "post",
      "date": "Friday, October 7, 2016 at 6:44am",
      "time_stamp": 1475837040
    }
  ],
  "requestOptions": {
    "pageIndex": 0,
    "offset": 5,
    "profileID": "4713141"
  }
}

#### 3- To use the service directly from the Entry Point

$service = new \Skopenow\Extract\EntryPoint();

and call any method you want as illustraited previously like this

$extractedData = $service->extractFacebookPosts($link);