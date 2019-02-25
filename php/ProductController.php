<?php

namespace Realmdigital\Web\Controller;

use DDesrosiers\SilexAnnotations\Annotations as SLX;
use Silex\Application;
use GuzzleHttp\Psr7\Request;

/**
 * @SLX\Controller(prefix="product/")
 */
class ProductController {

    /**
     * @SLX\Route(
     *      @SLX\Request(method="GET", uri="/{id}")
     * )
     * @param Application $app
     * @param $name
     * @return
     */
    public function getById_GET(Application $app, $id){
       
        $requestData = array();
        $requestData['id'] = $id;
        $body = $requestData['id'];
        $request = new Request('HEAD', 'http://192.168.0.241/eanlist?type=Web', $body);
        $response = $client->sendAsync($request);
        $response = json_decode($response);

        $result = [];
        
        foreach($response as $product) {
            $prod = array();
            $prod['ean']=$product->barcode;
            $$prod["name"]=$product->itemName;
            $prod["prices"] = array();
            $prodPriceRecords =$product->priceRecords;

            foreach($prodPriceRecords as $price) {
                if ($price->CurrencyCode != 'ZAR') {
                    $p_price = array();
                    $p_price['price']  = $price->sellingPrice;
                    $p_price['currency'] = $price->currencyCode;
                    $prod["prices"][] = $p_price;
                }
            }
            $result[] = $prod;
        }

        return $app->render('products/product.detail.twig', $result);
    }

    /**
     * @SLX\Route(
     *      @SLX\Request(method="GET", uri="/search/{name}")
     * )
     * @param Application $app
     * @param $name
     * @return
     */
    public function getByName_GET(Application $app, $name){
      
        $requestData = array();
        $requestData['names'] = $name;
        $body =  $requestData['names']
        $request = new Request('HEAD', 'http://192.168.0.241/eanlist?type=Web', $body);
        $response = $client->sendAsync($request);
        $response = json_decode($response);

        $result = [];
        foreach($response as $product) {
            $prod = array();
            $prod['ean']=$product->barcode;
            $$prod["name"]=$product->itemName;
            $prod["prices"] = array();
            $prodPriceRecords =$product->priceRecords;

            foreach($prodPriceRecords as $price) {
                if ($price->CurrencyCode != 'ZAR') {
                    $p_price = array();
                    $p_price['price']  = $price->sellingPrice;
                    $p_price['currency'] = $price->currencyCode;
                    $prod["prices"][] = $p_price;
                }
            }
            $result[] = $prod;
        }

        return $app->render('products/products.twig', $result);
    }

}
