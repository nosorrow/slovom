<?php
/**
 * Class NumberToWord
 * Number to words (number spelling) in Bulgarian
 */

final class NumberToWord
{
    // мъжки род
    protected $edinici = array('нула', 'един', 'два', 'три', 'четири', 'пет', 'шест', 'седем', 'осем', 'девет');
    // женски род
    protected $edinici_fm = array('нула', 'една', 'две', 'три', 'четири', 'пет', 'шест', 'седем', 'осем', 'девет');
    // среден род
    protected $edinici_sreden = array('нула', 'едно', 'две', 'три', 'четири', 'пет', 'шест', 'седем', 'осем', 'девет');

    protected $deset = array('десет', 'единадесет', 'дванадесет', 'тринадесет', 'четиринадесет', 'петнадесет', 'шестнадесет', 'седемнадесет', 'осемнадесет', 'деветнадесет');

    protected $desetici = array(2 => 'двадесет', 'тридесет', 'четиридесет', 'петдесет', 'шестдесет', 'седемдесет', 'осемдесет', 'деветдесет');

    protected $stotici = array('', 1 => 'сто', 'двеста', 'триста', 'четиристотин', 'петстотин', 'шестстотин', 'седемстотин', 'осемстотин', 'деветстотин');

    protected $suf = array('', 'хиляда', 'милион', 'милиард', 'трилион', 'квадрилион', 'квинтилион', 'секстилион', 'септилион', 'октилион', 'нонилион');

    protected $suf1 = array('', 'хиляди', 'милиона', 'милиарда', 'трилиона', 'квадрилиона', 'квинтилиона', 'секстилиона', 'септилиона', 'октилиона', 'нонилиона');

    protected $fraction_suffix = array('', 'десети', 'стотни', 'хилядни', 'десетохилядни', 'стохилядни', 'милионни', 'десетомилионни', 'стомилионни', 'милиардни', 'десетомилиардни', 'стомилиардни', 'трилиони', 'десетотрилиони', 'стотрилиони', 'квадрилионни', 'десетоквадрилионни', 'стоквадрилионни', 'квинтилионни', 'десетоквинтилионни', 'стоквинтилионни', 'секстилионни', 'десетосекстилионни', 'стосекстилионни', 'септилионни', 'десетосептилионни', 'стосептилионни', 'октилионни', 'десетооктилионни', 'стооктилионни', 'нонилионни');

    protected $negative;

    protected $default_currencies_options = ['suffix' => 'лв.', 'fraction' => 'ст.', 'and' => 'и', 'negative_word' => 'минус', 'negative' => true, 'exp' => 30, 'decimal' => 2];

    protected $default_number_options = ['suffix' => 'цяло', 'fraction' => '', 'and' => 'и', 'negative_word' => 'минус', 'negative' => true, 'exp' => 30, 'decimal' => false];

    protected $options = [];

    protected $class_opt;

    protected $max_num;

    protected $and;

    public $word;

    public $number;

    public $error;


    /**
     * NumberToWordClass constructor.
     * @internal param array $edinici
     */
    public function __construct($options = 'number')
    {
        $this->class_opt = $options;

        if ($options == 'number') {
            $this->options = $this->default_number_options;

        }

        if ($options == 'currency') {
            $this->options = $this->default_currencies_options;

        }

        $this->and = sprintf(' %s ', $this->options['and']);

    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        if ($this->class_opt === 'currency') {
            $this->options = array_merge($this->default_currencies_options, $options);

        } else {
            $this->options = array_merge($this->default_number_options, $options);

        }
    }

    /**
     * @param $int
     * @param null $rod
     * @return string
     */
    protected function get_edinici($int, $rod = null)
    {
        if ($rod == 3) {
            $edinici = $this->edinici[$int];

        } elseif ($rod == 1) {
            $edinici = $this->edinici_fm[$int];

        } else {
            if ($this->class_opt == 'number') {
                $edinici = $this->edinici_sreden[$int];

            } else {
                $edinici = $this->edinici[$int];
            }
        }

        return $edinici;
    }

    /**
     * @return int
     */
    public function get_max_number()
    {
        $n = $this->options['exp'];
        $k = $n / 3;
        $max = 0;
        for ($i = 0; $i <= $k; $i++) {
            $max += 999 * pow(10, $n);
            $n -= 3;
        }
        return $max;
    }

    /**
     * @param $i
     * @return float|int|mixed
     */
    protected function normalize($i)
    {
        $negative = null;

        if ($i < 0 && $this->options['negative'] === true) {
            $this->negative = sprintf(' %s ', $this->options['negative_word']);
            $negative = '-';
            $i = abs($i);

        } elseif ($i < 0 && $this->options['negative'] === false) {
            $this->error = 'Въведете положително число!';
            return false;

        } else {
            $this->negative = '';
        }

        $i = preg_replace('#[^\d\.]#', '', $i);

        /*
         * get all numbers in string -?\d+\.?\d*
         */
        if (!preg_match('/^(?!0\d)\d*(\.\d+)?$/', $i)) {
            $this->error = ('Въведете число!');
            return false;
        }

        if ($i > $this->get_max_number()) {
            $this->error = ('Твърде голямо число!');
            return false;
        }

        if ($i === '') {
            $i = 0;

        } else {
            $i = ($this->options['decimal']) ? number_format($i, $this->options['decimal'], '.', '') : (string)$i;

        }

        $this->number = $negative . $i;

        return $i;
    }

    /**
     * @param $int
     * @param null $rod
     * @return mixed|string
     */
    protected function twodigit($int, $rod = null)
    {
        //за двуцифрени числа $rod = 1{$edinici_fm} ; 2 {$edinici_sreden}; 3 {$edinici}
        if ($rod == 1) {
            if ($int[0] == '1') {
                $slov = $this->deset[$int[1]];

            } elseif ($int % 10 == 0) {
                $slov = $this->desetici[$int[0]];

            } else {
                $edinici = $int[1] == 0 ? "" : $this->edinici_fm[(int)$int[1]];
                $desetici = $int[0] == 0 ? "" : $this->desetici[(int)$int[0]];
                $and = ($edinici == "" || $desetici == "") ? " " : $this->and;

                $slov = $desetici . $and . $edinici;
            }

        } else {
            if ($int[0] == '1') {
                $slov = $this->deset[$int[1]];

            } elseif ($int % 10 == 0) {
                $slov = $this->desetici[$int[0]];

            } else {
                $edinici = $this->get_edinici($int[1], $rod);

                $slov = $this->desetici[$int[0]] . $this->and . $edinici;

            }
        }

        return trim($slov);

    }

    /**
     * @param $int
     * @param null $rod
     * @return mixed|string
     */
    protected function thrdigit($int, $rod = NULL)
    {
        if ($int == 000) {
            $slov = '';

        } elseif ($int % 100 == 0)//300
        {
            $slov = $this->stotici[$int[0]];

        } elseif ($int[1] == 0)//301
        {
            $edinici = $this->get_edinici($int[2], $rod);

            $slov = $this->stotici[$int[0]] . $this->and . $edinici;//$this->edinici[$int[2]];

        } elseif ($int[2] == 0 || $int[1] == 1)//350 или 310
        {
            $two = $this->twodigit(substr($int, 1), $rod);

            $slov = $this->stotici[$int[0]] . $this->and . $two;

        } else {
            $two = $this->twodigit(substr($int, 1), $rod);

            $slov = $this->stotici[$int[0]] . ' ' . $two;

        }

        return $slov;

    }

    /**
     * @param $int
     * @param null $rod
     * @return mixed|string
     */
    protected function numtoword($int, $rod = null)
    {
        switch (strlen($int)) {
            case 1:
                if ($rod === 1) {
                    $slov = $this->edinici_fm[$int];

                } else {
                    $slov = $this->edinici[$int];
                }
                break;

            case 2:
                $slov = $this->twodigit($int, $rod);
                break;

            case 3;
                $slov = $this->thrdigit($int, $rod);
                break;

        }

        return trim($slov);

    }

    /**
     * @param $int
     * @param bool $coins
     * @return string
     */
    public function slovom($int, $coins = true)
    {
        $int = $this->normalize($int);
        if ($int === false) {
            return $this->error;
        }

        $fraction = '';
        $suffix = $this->options['suffix'];

        if (strpos($int, '.') !== FALSE) {

            if ($this->class_opt == 'number') {
                $fract = substr(strstr($int, '.'), 1);
                $fract_suffix = (count($this->fraction_suffix) > strlen($fract)) ? $this->fraction_suffix[strlen($fract)] : '';

                $fract = sprintf('%s', $fract);
                // Remove leading zero 00560 = 560
                $fract = (ltrim($fract, 0));

            } else {
                $fract = sprintf('%s', (substr(strstr($int, '.'), 1)));

                $fract_suffix = $this->options['fraction'];

            }

            if ($fract == 0) {
                $fraction = '';

            } else {

                if ($coins === true) {
                    $fraction = $this->and . '' . $this->words_generator($fract, 1, true) . ' ' . $fract_suffix;

                } else {
                    $fraction = $this->and . '' . $fract . ' ' . $fract_suffix;

                }

            }

            $int = strstr($int, '.', TRUE);
        }

        $whole_part = $this->words_generator($int);

        if ($fraction || $this->class_opt == 'currency') {
            $whole_part .= ' ' . $suffix;

        }

        $word = $this->negative . $whole_part . $fraction;

        return trim($word);

    }

    protected function words_generator($int, $rod = '', $is_fract = false)
    {
        $collect = array();
        $i = 0;

        while (strlen($int) > 3) {

            $st = substr($int, -3);

            if ($st > 0) {
                // only for dump
               // $s[$i] = $st;

                if ($st[0] == 0 && $st[1] == 0 && $i <> 1) {
                   // var_dump($st);
                    if($is_fract === true){
                        $collect[] = $this->get_edinici($st[2], 1) . ' ' . $this->suf1[$i];

                    } else {
                        $collect[] = $this->get_edinici($st[2]) . ' ' . $this->suf1[$i];

                    }

                } elseif ($i == 1 && (substr($st, -1) == 2 || substr($st, -1) == 1)) {
                    // 21000 or 22000 or 321000
                    $st = ltrim($st, 0);

                    $collect[] = $this->numtoword($st, 1) . ' ' . $this->suf1[$i];

                } elseif ($i > 1) {
                    $collect[] = $this->numtoword($st, 3) . ' ' . $this->suf1[$i];

                } else {
                    $collect[] = $this->numtoword($st, $rod) . ' ' . $this->suf1[$i];
                }
            }
            $int = substr($int, 0, -3);

            $i++;

        }

        if ($int == 1 && $i == 1) {
            $collect[] = 'хиляда';

        } elseif ($int == 1 && $i == 0 && $this->class_opt == "number") {
            $collect[] = 'едно';

        } elseif ($int == 2 && $i == 0 && $this->class_opt == "number") {
            $collect[] = 'две';

        } elseif ($i == 1 && (substr($int, -1) == 2 || substr($int, -1) == 1)) {
            // 22000
            $collect[] = $this->numtoword($int, 1) . ' ' . $this->suf1[$i];

        } elseif ($int == 1 && ($i > 1)) {
            $collect[] = $this->numtoword($int) . ' ' . $this->suf[$i];

        } elseif ($i > 1) {
            // 251 000 000
            $collect[] = $this->numtoword($int, 3) . ' ' . $this->suf1[$i];

        } else {
            $collect[] = $this->numtoword($int, $rod) . ' ' . $this->suf1[$i];
        }

        $collect = array_reverse($collect);

        if (count($collect) > 1 AND strpos($collect[count($collect) - 1], $this->and) === false) {
            $collect[] = $collect[count($collect) - 1];
            $collect[count($collect) - 2] = $this->options['and'];

        }

        return trim(implode(' ', $collect));

    }

    /**
     * @param $int
     * @param bool $coin
     * @return $this
     */
    public function setNumber($int, $coin = true)
    {
        $this->word = $this->slovom($int, $coin);

        return $this;
    }

    public function toNumber()
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function toWord()
    {
        return $this->word;
    }
}
