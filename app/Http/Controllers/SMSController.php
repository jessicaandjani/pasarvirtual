<?php

namespace App\Http\Controllers;

use App\SMS;
use App\User;
use App\Order;
use App\OrderLine;
use Textmagic\Services\TextmagicRestClient;
use Illuminate\Http\Request;
use Response;

class SMSController extends Controller
{
    public function sendMessage() {
        $client = new TextmagicRestClient('jessicaandjani', 'Z1HuSc1UIKQMgOfmGeFmtmAMMRH7GK');
        $result = ' ';
        try {
            $result = $client->messages->create(
                array(
                    'text' => 'Hello from TextMagic PHP',
                    'phones' => implode(', ', array('+6287822253153'))
                )
            );
        } catch (\Exception $e) {
            if ($e instanceof RestException) {
                print '[ERROR] ' . $e->getMessage() . "\n";
                foreach ($e->getErrors() as $key => $value) {
                    print '[' . $key . '] ' . implode(',', $value) . "\n";
                }
            } else {
                print '[ERROR] ' . $e->getMessage() . "\n";
            }
            return;
        }
        var_dump($result['id']);
        return $result['id'];
    }

    private function getAbbreviation() {
        $collection = SMS::all();
        $keyed = $collection->mapWithKeys(function ($item) {
            return [$item['abbreviation'] => $item['word']];
        });
        return $keyed->all();
    }

    public function receive() {
        var_dump('expression');
    }

    private function receiveMessage() {
        // var_dump("receiveMessage");
        // $message = \Nexmo\Message\InboundMessage::createFromGlobals();
        // Log::info('got text: ' . $message->getBody());
        $sentence = "Graciel, Jl Pajajaran No.12
        pesan
        1 kg aym
        kangkung 3 ikat 
        2 ikat kcng pnjng";
        // $sentence = $message->getBody();
        $sentence = strtolower($sentence);
        $replacements = self::getAbbreviation();
        $sentence = strtr($sentence, $replacements);
        return $sentence;
    }

    public function parseMessage() {
        $sentence = self::receiveMessage();
        $sentence = explode("pesan", $sentence);
        $sentence_arr = array(
            "user_section" => trim($sentence[0]),
            "order_section" => trim($sentence[1]),
            );
        
        return $sentence_arr;
    }

    private function addUser($name, $address, $phone) {
        $user = new User();
        $user->name = $name ;
        $user->address = $address;
        $user->phone = $phone;
        // $user->save();
        //get user id after insert the user to database
        $user_id = $user->id;
        $user_id = 2;
        return $user_id;
    }

    private function findUserId($phone) {
        $user_id = 1;
        // $user_id = DB::table('users')
        //              ->select('id')
        //              ->where('phone', $num_phone)
        //              ->get();
        // if($user_id == null) {
        //     $user_id = addCustomer();
        // } 
        return $user_id;
    }

    public function getUserId() {
        $sentence = self::parseMessage()["user_section"];
        $phone = "123456789";
        if($sentence != "") { // add new user to database
            $customer = explode(",", $sentence);
            $name = trim($customer[0]);
            $address = trim($customer[1]);
            $user_id = self::addUser($name, $address, $phone);
        } else {
            $user_id = self::findUserId($phone);
        }
        return $user_id;
    }

    private function orderLineParser() {
        //get sentence after "pesan" in sentence
        $sentence = self::parseMessage()["order_section"];
        // make array of orderline
        $orderline = explode("\n", $sentence);

        return $orderline;
    }

    public function getOrder() {
        $customer_id = self::getUserId();
        $total_product = count(self::orderLineParser());
        $order_type = 'SMS';

        return array(
            'customer_id' => $customer_id,
            'total_product'=>$total_product,
            'order_type' => $order_type
        );

    }

    public function getOrderLine() {
        $orderline = self::orderLineParser();
        $orderline_array = array();
        foreach ($orderline as $item) {
            //get quantity and unit
            $re = '/(\d+)(\s?\S+)/ix';
            preg_match($re, $item, $matches);
            $product = str_replace($matches[0], '', $item);
            $orderlines[] = array(
                    "productId" => trim($product, " "),
                    "quantity" => trim($matches[1], " "),
                    "unitId" => trim($matches[2], " "),
                    "isPriority" => false
                );
        }

        return array(
            'order_line'=>$orderlines
        );
    }

    public function addOrder() {
        //add order to database
        $arrayOrder = self::getOrder();
        $order = new Order();
        $order->customer_id = $arrayOrder["customer_id"];
        $order->total_product = $arrayOrder["total_product"];
        $order->order_type = $arrayOrder["order_type"];
        $order->save();
        //get order id after insert the order to database
        $order_id = $order->id;
        return (string)$order_id;
    }

    public function addOrderLine() {
        $order_id = self::addOrder();
        $order_line = self::getOrderLine()['order_line'];
        foreach ($order_line as $item) {
            $orderLine = new OrderLine();
            $orderLine->order_id = $order_id ;
            $orderLine->product_id = $item['productId'];
            $orderLine->quantity = $item['quantity'];
            $orderLine->unit_id = $item['unitId'];
            $orderLine->is_priority = $item['isPriority'];
            $orderLine->save();
        }
        return "Pesanan Anda berhasil diproses";
    }

}
