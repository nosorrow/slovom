<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
include_once 'NumberToWordClass.php';

class Test extends PHPUnit_Framework_TestCase
{
    public $numtoword;
    public $currency;


    /**
     * RouterTest constructor.
     */
    public function __construct()
    {
        $this->numtoword = new NumberToWord('number');
        $this->currency = new NumberToWord('currency');

    }

    public function testNumberParse()
    {

        $n = [1, 2, 3, 4, 5, 1000000, 23, 22, 31,
            10021, 21021.22, 222000000, 6781222501, 301000000, 501501000,
            5005000000, 5.005, '5.050', '2.055006', '0.1200000', 12.21,
            1.2001
        ];

        $val = ['едно', 'две', 'три', 'четири', 'пет',
            'един милион', 'двадесет и три',
            'двадесет и две', 'тридесет и едно', 'десет хиляди двадесет и едно',
            'двадесет и една хиляди двадесет и едно цяло и двадесет и две стотни',
            'двеста двадесет и два милиона',
            'шест милиарда седемстотин осемдесет и един милиона двеста двадесет и две хиляди петстотин и едно',
            'триста и един милиона', 'петстотин и един милиона петстотин и една хиляди',
            'пет милиарда и пет милиона', 'пет цяло и пет хилядни', 'пет цяло и петдесет хилядни',
            'две цяло и петдесет и пет хиляди и шест милионни', 'нула цяло и един милион и двеста хиляди десетомилионни',
            'дванадесет цяло и двадесет и една стотни',
            'едно цяло и две хиляди и една десетохилядни'
            ];

        $array = array_combine($n, $val);

        foreach ($array as $key => $value) {
            $result = $this->numtoword->slovom($key);
            $this->assertEquals($value, $result);
        }

        $result = $this->numtoword->slovom('1');
        $this->assertEquals('едно', $result);

        $result = $this->numtoword->slovom('2');
        $this->assertEquals('две', $result);

        $result = $this->numtoword->slovom('3');
        $this->assertEquals('триj', $result);

        $result = $this->numtoword->slovom('1000');
        $this->assertEquals('хиляда', $result);

        $result = $this->numtoword->slovom('21021');
        $this->assertEquals('двадесет и една хиляди двадесет и едно', $result);

        $result = $this->numtoword->slovom('1001');
        $this->assertEquals('хиляда и едно', $result);

        $result = $this->numtoword->slovom('1000000000000');
        $this->assertEquals('един трилион', $result);

        $result = $this->numtoword->slovom('1000000');
        $this->assertEquals('един милион', $result);

        $result = $this->numtoword->slovom('2000000');
        $this->assertEquals('два милиона', $result);

        $result = $this->numtoword->slovom('22');
        $this->assertEquals('двадесет и две', $result);

        $result = $this->numtoword->slovom('6582.51');
        $this->assertEquals('шест хиляди петстотин осемдесет и две цяло и петдесет и една стотни', $result);

        $result = $this->numtoword->slovom('1.612');
        $this->assertEquals('едно цяло и шестстотин и дванадесет хилядни', $result);
        // Currency test
        $curency = $this->currency->slovom('2');
        $this->assertEquals('два лв.', $curency);

        $curency = $this->currency->slovom('325');
        $this->assertEquals('триста двадесет и пет лв.', $curency);

        $curency = $this->currency->slovom('321');
        $this->assertEquals('триста двадесет и един лв.', $curency);

        $curency = $this->currency->slovom('2 180 497.40');
        $this->assertEquals('два милиона сто и осемдесет хиляди четиристотин деветдесет и седем лв. и четиридесет ст.', $curency);

        $curency = $this->currency->slovom('325.22');
        $this->assertEquals('триста двадесет и пет лв. и двадесет и две ст.', $curency);

        $curency = $this->currency->slovom('2000000');
        $this->assertEquals('два милиона лв.', $curency);

        $curency = $this->currency->slovom('1001');
        $this->assertEquals('хиляда и един лв.', $curency);

        $curency = $this->currency->slovom('1568355.45');
        $this->assertEquals('един милион петстотин шестдесет и осем хиляди триста петдесет и пет лв. и четиридесет и пет ст.', $curency);

        $curency = $this->currency->slovom('2398547.15');
        $this->assertEquals('два милиона триста деветдесет и осем хиляди петстотин четиридесет и седем лв. и петнадесет ст.', $curency);

        $curency = $this->currency->slovom('181708.12 ');
        $this->assertEquals('сто осемдесет и една хиляди седемстотин и осем лв. и дванадесет ст.', $curency);

        $curency = $this->currency->slovom('1002000');
        $this->assertEquals('един милион и две хиляди лв.', $curency);

        $curency = $this->currency->slovom('1 782 959.72');
        $this->assertEquals('един милион седемстотин осемдесет и две хиляди деветстотин петдесет и девет лв. и седемдесет и две ст.',
            $curency);

        $curency = $this->currency->slovom('1 691 183.24');
        $this->assertEquals('един милион шестстотин деветдесет и една хиляди сто осемдесет и три лв. и двадесет и четири ст.', $curency);
    }

}



