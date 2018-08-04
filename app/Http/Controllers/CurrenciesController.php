<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CurrenciesController extends Controller
{

    public function index()
    {
        $this->updateCurrencies();

        $currencies  = DB::table('currencies')->get();
        $conversions = $this->getConversionRates();

        return view('currency.index', [
            'currencies'  => $currencies,
            'conversions' => $conversions
        ]);
    }

    public function getXMLData()
    {
        // get XML data
        $url  = "http://www.floatrates.com/daily/usd.xml";
        $xml  = simpleXML_load_file($url, "SimpleXMLElement", LIBXML_NOCDATA);

        // convert XML data
        return json_decode(json_encode($xml), TRUE);
    }

    public function getConversionRates ()
    {
        $conversions = DB::table('currency_conversions')
            ->join('currencies', 'currency_conversions.curr_id', '=', 'currencies.id')
            ->where([
                ['currency_conversions.created_at', '>', date('Y-m-d H:i:s',strtotime(Carbon::now()->subDays(1)))],
                ['currency_conversions.updated_at', '<=', date('Y-m-d H:i:s',strtotime(Carbon::now()))]
            ])
            ->get(array(
                'currencies.id',
                'currencies.code',
                'currencies.name',
                'currency_conversions.conversion_rate',
        ));

        return $conversions;
    }

    public function updateCurrencies()
    {
        // get xml data
        $json = $this->getXMLData();

        // get db data, if any
        $currencies  = DB::table('currencies')->get();

        if (count($currencies) < 1) {
            // populate empty table
            foreach ($json['item'] as $data) {
                $id = DB::table('currencies')->insertGetId([
                    'code'       => $data['targetCurrency'],
                    'name'       => $data['targetName'],
                    'created_at' => NOW(),
                    'updated_at' => NOW()
                ]);

                // update conversion rate
                $this->updateConversionRates($id, $data);
            }

        } else {
            foreach ($currencies as $currency) {
                foreach ($json['item'] as $data) {
                    if ($currency->code == $data['targetCurrency']) {
                        // update conversion rate
                        $this->updateConversionRates($currency->id, $data);
                    } else {
                        continue;
                    }
                }
            }            
        }
    }

    public function updateConversionRates($id, $data)
    {        
        $rate = DB::table('currency_conversions')->where('curr_id', $id)->latest()->first();

        // no existing rate yet
        if (count($rate) < 1) {
            $this->insertConversionRate($id, $data);
        }
        // exchange rate is new 
        elseif (date('Y-m-d H:i:s',strtotime($data['pubDate'])) > $rate->created_at
            && date('Y-m-d H:i:s',strtotime($data['pubDate'])) > $rate->created_at) {
            $this->insertConversionRate($id, $data);
        }
        // updated on the same day 
        else { 
            DB::table('currency_conversions')->where([
                ['id', '=', $rate->id],
                ['curr_id', '=', $id]
            ])->update(['updated_at' => NOW()]);
        }

    }

    public function insertConversionRate($id, $data)
    {
        DB::table('currency_conversions')->insert([
            'curr_id'         => $id,
            'conversion_rate' => floatval(preg_replace('/[^\d.]/', '', $data['exchangeRate'])),
            'created_at'      => NOW(),
            'updated_at'      => NOW()
        ]);
    }

    /** AJAX REQUESTS **/

    public function ajaxUpdate(Request $request)
    {
        // update
        $this->updateCurrencies();
        
        // get conversion rates
        $conversions = $this->getConversionRates()->toArray();

        return $conversions;
    }

    public function ajaxConvert(Request $request)
    {
        $input = $request->all();
        print_r($input);
    }

}
